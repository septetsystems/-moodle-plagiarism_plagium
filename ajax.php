<?php

use plagium\classes\plagium_connect;

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');

$data = optional_param('data', array(), PARAM_RAW);

require_login();

$data = json_decode($data);

if ($data == null) {
    die('No data sent');
}

$connection = new plagium_connect();
$analizy = $connection->getPlagiumRecord($data->id, ($data->refresh ?? false));

echo json_encode([
    "analizy" => $analizy,
    "report" => $connection->showReport($analizy)
], true);
die;
