<?php

use plagium\classes\plagium_connect;

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');

$data = optional_param('data', array(), PARAM_RAW);

// Get URL parameters.
$systemcontext = context_system::instance();
$contextid = optional_param('context', $systemcontext->id, PARAM_INT);

// Check permissions.
list($context, $course, $cm) = get_context_info_array($contextid);
require_login($course, false, $cm);

$data = json_decode($data);

if ($data == null) {
    die('No data sent');
}

$connection = new plagium_connect();
$analizy = $connection->get_plagium_record($data->id, ($data->refresh ?? false));

echo json_encode([
    "analizy" => $analizy,
    "report" => $connection->show_report($analizy)
], true);
die;
