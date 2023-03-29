<?php

namespace plagium\classes;
use curl;
use stored_file;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->libdir . '/filelib.php');
class plagium_api
{

    /**
     * DOCUMENT
     */
    const DOCUMENT = "/document/create";

    /**
     * UPLOAD
     */
    const UPLOAD = "/document/upload";

    /**
     * RESULT
     */
    const RESULT = "/document/results";

    /**
     * API_DEFAULT_URL
     */
    const API_DEFAULT_URL = "https://api.plagium.com/300";

    /**
     * Make a HTTP request to the API
     *
     * @param string $endPoint
     * @param string $requestType
     * @param array $data
     * @param stored_file $filedata
     * @param bool $urlencodeddata
     *
     * @return object
     */
    public function request($endPoint, $requestType, $data, $filedata = null, $urlencodeddata = null)
    {
        $curl = new curl();
        $url = self::API_DEFAULT_URL . $endPoint;

        if ($urlencodeddata) {
            foreach ($data as $param => $value) {
                $url .="&$param=" . urlencode($value);
            }
        }
        
        $response = null;
        if ($requestType == "POST" && $filedata != null) {

            $boundary = uniqid();
            $delimiter = '-------------' . $boundary;
            $data = $this->build_data_file($boundary, $data, $filedata);

            $curl->setHeader(array(
                "Content-Type: multipart/form-data; boundary=" . $delimiter,
                "Content-Length: " . strlen($data)
            ));
            
            $response = $curl->post($url, $data);
        } else {
            $curl->setHeader('Content-Type:application/json');
            
            $payload = json_encode( $data );
            $response = $curl->post($url, $payload);
        }

        return json_decode($response);
    }

    private function build_data_file($boundary, $fields, $file)
    {
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;


        foreach ($fields as $name => $content) {
            if (is_array($content)) {
                $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                    . json_encode($content) . $eol;
            } else {
                $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                    . $content . $eol;
            }
        }

        $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="file"; filename="' . $file->get_filename() . '"' . $eol
                . 'Content-Type: ' . $file->get_mimetype() . '' . $eol
                . 'Content-Transfer-Encoding: binary' . $eol
        ;

        $data .= $eol;
        $data .= $file->get_content() . $eol;
        $data .= "--" . $delimiter . "--" . $eol;

        return $data;
    }
}
