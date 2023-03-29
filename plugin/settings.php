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
 * settings.php - allows the admin to configure plagiarism stuff
 *
 * @package     plagiarism_plagium
 * @subpackage  plagiarism
 * @author      Jesús Prieto <jprieto@plagium.com> (Based on the work of Daniel Gockel  <dgockel@plagium.com>)
 * @copyright   2018 Plagium GmbH {@link https://www.plagium.com/}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use plagium\classes\plagium_connect;
use plagium\classes\plagium_setup_form;

require_once(dirname(dirname(__FILE__)) . '/../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/plagiarismlib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_settings_form.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_connect.php');

require_login();
admin_externalpage_setup('plagiarismplagium');

$context = context_system::instance();
require_capability('moodle/site:config', $context, $USER->id);

$connection = new plagium_connect();

//require form
$mform = new plagium_setup_form();
if ($mform->is_cancelled()) {
    $url = new moodle_url('/plagiarism/plagium/settings.php');
    redirect($url);
}


echo $OUTPUT->header();
if (($data = $mform->get_data()) && confirm_sesskey()) {

     //plagscan_use will not be send if it's false
     if (!isset($data->plagium_use)) {
        $data->plagium_use = 0;
    }
    if (!isset($data->plagscan_email_notification_account)) {
        $data->plagscan_email_notification_account = 0;
    }
    set_config('enabled', true, 'plagiarism_plagium');

    $connection->saveConfigs($data);
    //echo $OUTPUT->notification(get_string('savedapiconfigerror', 'plagiarism_plagium'), 'notifyerror');
    echo $OUTPUT->notification(get_string('savedconfigsuccess', 'plagiarism_plagium'), 'notifysuccess');
}

$mform->set_data($connection->allConfigs(true));

echo $OUTPUT->box_start('generalbox boxaligncenter', 'intro');
$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
