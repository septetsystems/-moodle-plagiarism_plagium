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
 * @package   plagium
 * @copyright 2023 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
