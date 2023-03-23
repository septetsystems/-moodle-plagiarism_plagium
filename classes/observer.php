<?php

// This file is part of the plagium plugin for Moodle - http://moodle.org/
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
 * plagiarism_plagium_observer.php - Class for the observer event of Moodle
 *
 * @package      plagiarism_plagium
 * @subpackage   plagiarism
 * @author       Jesús Prieto <jprieto@plagium.com>
 * @copyright    2018 plagium GmbH {@link https://www.plagium.com/}
 * @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_upload.php');

use plagium\classes\plagium_connect;
use plagium\classes\plagium_upload;

class plagiarism_plagium_observer {

    /**
     * Controls the file upload event
     * 
     * @param \assignsubmission_file\event\assessable_uploaded $event
     */
    public static function assignsubmission_file_uploaded(
    \assignsubmission_file\event\assessable_uploaded $event) {
        plagium_upload::file_uploaded($event);
    }

    /**
     * Controls the assessable submitted event
     * 
     * @param \mod_assign\event\assessable_submitted $event
     */
    public static function mod_assign_assessable_submitted(
        \mod_assign\event\assessable_submitted $event) {
            //plagium_connect::dump($event);
        }

    /**
     * Controls the onlinetext upload event
     * 
     * @param \assignsubmission_onlinetext\event\assessable_uploaded $event
     */
    public static function assignsubmission_onlinetext_uploaded(
    \assignsubmission_onlinetext\event\assessable_uploaded $event) {
        $result = $event->get_data();
        
        $analizyData = new stdClass();
        $analizyData->linkarray = new stdClass();
        $analizyData->linkarray->cmid = $result['objectid'];
        $analizyData->linkarray->course = $result['courseid'];
        $analizyData->linkarray->assignment = $result['contextinstanceid'];
        $analizyData->linkarray->userid = $result['userid'];
        $analizyData->linkarray->content = $result['other']["content"] ?? "";

        $connection = new plagium_connect();
        $connection->getAnalizyPlagium($analizyData);
        
    }

}
