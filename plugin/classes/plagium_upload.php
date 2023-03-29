<?php

namespace plagium\classes;

use Exception;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

class plagium_upload
{
    static public function file_uploaded($event)
    {
        global $cm, $USER;

        foreach ($event->other['pathnamehashes'] as $pathnamehash) {
            $file = get_file_storage()->get_file_by_hash($pathnamehash);

            if ($file && !$file->is_directory() && in_array($file->get_mimetype(), plagium_connect::FILE_TYPES)) {

                $plagiumConnect = new plagium_connect();

                $dataAnalizy = [
                    "cm_id" => $cm->id,
                    "module" => "file",
                    "module_id" => $file->get_id(),
                    "user_id" => $USER->id
                ];

                $analizy = $plagiumConnect->getAnalizyPlagium([], $dataAnalizy);

                $typeWeb = get_config("plagium", "api_analyze");
                if ($analizy && $typeWeb) {
                    $plagiumConnect->submitSingleFile($file, $analizy->id);
                }
            }
        }
    }
}