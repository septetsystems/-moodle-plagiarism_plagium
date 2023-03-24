<?php
namespace plagium\classes;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

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
     * @param array $filedata
     * @param bool $urlencodeddata
     *
     * @return array
     */
    public function request($endPoint, $requestType, $data, $filedata = null, $urlencodeddata = false)
    {
        $ch = curl_init();
        $url = self::API_DEFAULT_URL . $endPoint;

        if ($urlencodeddata) {
            foreach ($data as $param => $value) {
                $url .="&$param=" . urlencode($value);
            }
        }

        if ($requestType == "POST" && $filedata != null) {
            $boundary = uniqid();
            $delimiter = '-------------' . $boundary;

            $data = $this->build_data_file($boundary, $data, $filedata);

            curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                "Content-Type: multipart/form-data; boundary=" . $delimiter,
                "Content-Length: " . strlen($data)
            ]);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        } else {
            $payload = json_encode( $data );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                'Content-Type:application/json'
            ]);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        }

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $pslog = array(
                'other' => [
                    'errormsg' => curl_error($ch)
                ]
            );
            //error_happened::create($pslog)->trigger();
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response_handled = $this->handle_response($response, $httpcode);

        if ($httpcode >= 400 && isset($response_handled["response"]["error"]))
        {
            $pslog = array(
                'other' => [
                    'errormsg' => $response_handled["response"]["error"]["code"]." - ".$response_handled["response"]["error"]["message"]
                ]
            );
            //error_happened::create($pslog)->trigger();
        }

        return $response_handled;
    }

    /**
     * Returns the reponse within in array with response json decoded and http code
     *
     * @param string $response
     * @param int $httpcode
     * @return array
     */
    private function _handle_response($response, $httpcode) {
        $response = json_decode($response, true);

        return array("response" => $response, "httpcode" => $httpcode);
    }

    /**
     * Helps to build a HTTP content with a given files data
     *
     * @param string $boundary
     * @param array $fields
     * @param array $files
     * @return string
     */
    private function build_data_files($boundary, $fields, $files)
    {
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                    . $content . $eol;
        }


        foreach ($files as $file) {
            $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="fileUpload"; filename="' . $file->get_filename() . '"' . $eol
                    . 'Content-Type: ' . $file->get_mimetype() . '' . $eol
                    . 'Content-Transfer-Encoding: binary' . $eol
            ;

            $data .= $eol;
            $data .= $file->get_content() . $eol;
        }
        $data .= "--" . $delimiter . "--" . $eol;


        return $data;
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
