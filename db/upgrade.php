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

/**
 * xmldb_plagiarism_plagium_upgrade
 *
 * @param  mixed $oldversion
 * @return void
 */
function xmldb_plagiarism_plagium_upgrade($oldversion) 
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012031401) {
        // Rename 'plagium' table to 'plagiarism_plagium' (to meet Moodle guidelines)
        $table = new xmldb_table('plagium');
        $dbman->rename_table($table, 'plagiarism_plagium');

        // Rename 'plagium_config' table to 'plagiarism_plagium_config' (to meet Moodle guidelines)
        $table = new xmldb_table('plagium_config');
        $dbman->rename_table($table, 'plagiarism_plagium_config');

        // plagium savepoint reached
        upgrade_plugin_savepoint(true, 2012031401, 'plagiarism', 'plagium');
    }

    return true;
}
