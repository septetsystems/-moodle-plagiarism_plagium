<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_upload.php');

use plagium\classes\plagium_connect;
use plagium\classes\plagium_upload;

/**
 * plagiarism_plagium_observer
 */
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
