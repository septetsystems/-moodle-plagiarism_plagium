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
 * @package   plagiarism_plagium
 * @copyright 2023 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagium\privacy;

use core_privacy\local\metadata\collection;

/**
 * provider
 */
class provider implements
    // This plugin does store personal user data.
    \core_privacy\local\metadata\provider,
    \core_plagiarism\privacy\plagiarism_provider {

    // This trait must be included to provide the relevant polyfill for the metadata provider.
    use \core_privacy\local\legacy_polyfill;

    // This trait must be included to provide the relevant polyfill for the plagirism provider.
    use \core_plagiarism\privacy\legacy_polyfill;
    
    /**
     * get_metadata
     *
     * @param  mixed $collection
     * @return void
     */
    public static function get_metadata($collection) {

        $collection->link_subsystem(
            'core_files',
            'privacy:metadata:core_files'
        );

        $collection->add_database_table(
            'plagiarism_plagium',
            [
                'user_id' => 'privacy:metadata:plagiarism_plagium:user_id',
                'plagium_status' => 'privacy:metadata:plagiarism_plagium:plagium_status',
                'status' => 'privacy:metadata:plagiarism_plagium:status',
                'cm_id' => 'privacy:metadata:plagiarism_plagium:cm_id',
                'module' => 'privacy:metadata:plagiarism_plagium:module',
                'module_id' => 'privacy:metadata:plagiarism_plagium:module_id',
                'content' => 'privacy:metadata:plagiarism_plagium:content',
                'meta' => 'privacy:metadata:plagiarism_plagium:meta',
            ],
            'privacy:metadata:plagiarism_plagium'
        );
        return $collection;
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_plagiarism_for_context(\context $context) {
        global $DB;

        if (empty($context)) {
            return;
        }

        if (!$context instanceof \context_module) {
            return;
        }

        // Delete all submissions.
        $DB->delete_records('plagiarism_plagium', ['cm_id' => $context->instanceid]);

    }

    /**
     * Delete all user information for the provided user and context.
     *
     * @param  int      $userid    The user to delete
     * @param  \context $context   The context to refine the deletion.
     */
    public static function delete_plagiarism_for_user($userid, \context $context) {
        global $DB;

        if (!$context instanceof \context_module) {
            return;
        }

        $DB->delete_records('plagiarism_plagium', ['userid' => $userid, 'cm_id' => $context->instanceid]);
    }
}
