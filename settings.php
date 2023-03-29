<?php

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
