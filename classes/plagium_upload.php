<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains agreement class form.
 *
 * @package   plagiarism_plagium
 * @copyright 2023 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagium\classes;

/**
 * plagium_upload
 */
class plagium_upload {
    /**
     * file_uploaded
     *
     * @param  mixed $event
     * @return void
     */
    public static function file_uploaded($event) {
        global $cm, $USER;

        foreach ($event->other['pathnamehashes'] as $pathnamehash) {
            $file = get_file_storage()->get_file_by_hash($pathnamehash);

            if ($file && !$file->is_directory() && in_array($file->get_mimetype(), plagium_connect::FILE_TYPES)) {

                $plagiumconnect = new plagium_connect();

                $dataanalizy = [
                    "cm_id" => $cm->id,
                    "module" => "file",
                    "module_id" => $file->get_id(),
                    "user_id" => $USER->id
                ];

                $analizy = $plagiumconnect->get_analizy_plagium([], $dataanalizy);

                $typeweb = get_config("plagium", "api_analyze");
                if ($analizy && $typeweb) {
                    $plagiumconnect->submit_single_file($file, $analizy->id);
                }
            }
        }
    }
}