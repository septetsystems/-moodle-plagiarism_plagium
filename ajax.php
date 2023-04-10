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

use plagium\classes\plagium_connect;

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');

$data = optional_param('data', array(), PARAM_RAW);
require_login();

$data = json_decode($data);
if (!$data) {
    throw new moodle_exception('Permission denied!', 'plagium');
}

if (empty($data->id)) {
    throw new moodle_exception('Permission denied!', 'plagium');
}

if (empty($data->cmid)) {
    throw new moodle_exception('Permission denied!', 'plagium');
}

$coursecontext = context_course::instance($data->cmid);
require_capability('plagiarism/plagium:enable', $coursecontext);
require_sesskey();

$connection = new plagium_connect();
$analizy = $connection->get_plagium_record($data->id, ($data->refresh ?? false));

echo json_encode([
    "analizy" => $analizy,
    "report" => $connection->show_report($analizy)
], true);
die;
