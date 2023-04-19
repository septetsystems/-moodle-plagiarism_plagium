<?php
// This file is part of the Plagium Plugin for Moodle - https://www.plagium.com
//
// The Plagium Plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// The Plagium Plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with the Plagium Plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains agreement class form.
 *
 * @package   plagiarism_plagium
 * @copyright 2023 Septet Systems
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_upload.php');

use plagium\classes\plagium_connect;
use plagium\classes\plagium_upload;

 /**
  * plagiarism_plagium_observer
  */
class plagiarism_plagium_observer {
    /**
     * assignsubmission_file_uploaded
     *
     * @param  mixed $event
     * @return void
     */
    public static function assignsubmission_file_uploaded($event) {
        $cmid = $event['contextinstanceid'];
        $context = context_module::instance($cmid);
        if (get_config("plagiarism_plagium", 'plagium_status') && has_capability('plagiarism/plagium:enable', $context)) {
            return '';
            plagium_upload::file_uploaded($event);
        }
    }
    /**
     * assignsubmission_onlinetext_uploaded
     *
     * @param  mixed $event
     * @return void
     */
    public static function assignsubmission_onlinetext_uploaded($event) {
        $cmid = $event['contextinstanceid'];
        $context = context_module::instance($cmid);
        if (get_config("plagiarism_plagium", 'plagium_status') && has_capability('plagiarism/plagium:enable', $context)) {
            $result = $event->get_data();

            $analizydata = new stdClass();
            $analizydata->linkarray = new stdClass();
            $analizydata->linkarray->cmid = $result['objectid'];
            $analizydata->linkarray->course = $result['courseid'];
            $analizydata->linkarray->assignment = $result['contextinstanceid'];
            $analizydata->linkarray->userid = $result['userid'];
            $analizydata->linkarray->content = $result['other']["content"] ?? "";

            $connection = new plagium_connect();
            $connection->get_analizy_plagium($analizydata);
        }
    }
}
