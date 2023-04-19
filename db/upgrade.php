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

/**
 * xmldb_plagiarism_plagium_upgrade
 *
 * @param  mixed $oldversion
 * @return void
 */
function xmldb_plagiarism_plagium_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012031401) {
        $table = new xmldb_table('plagium');
        $dbman->rename_table($table, 'plagiarism_plagium');

        $table = new xmldb_table('plagium_config');
        $dbman->rename_table($table, 'plagiarism_plagium_config');

        upgrade_plugin_savepoint(true, 2012031401, 'plagiarism', 'plagium');
    }

    return true;
}
