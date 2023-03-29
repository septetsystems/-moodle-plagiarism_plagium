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

use plagium\classes\plagium_connect;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/plagiarism/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_connect.php');


/**
 * plagiarism_plugin_plagium
 */
class plagiarism_plugin_plagium extends plagiarism_plugin {
    
    /**
     * get_links
     *
     * @param  mixed $linkarray
     * @return void
     */
    public function get_links($linkarray) {
        global $CFG, $PAGE, $cm;

        $cmid = $linkarray['cmid'];

        $pageurl = $PAGE->url;
        $pagination = optional_param('page', -1, PARAM_INT);

        if($pagination != -1){
            $pageurl->param('page', $pagination);
        }

        if ($CFG->version < 2011120100) {
            $context = get_context_instance(CONTEXT_MODULE, $cmid);
        } else {
            $context = context_module::instance($cmid);
        }

        try {
            $plagiumconnect = new plagium_connect();

            $analizy = null;
            if (!empty($linkarray["file"]) && $file = $linkarray["file"]) {
                $datafile = [
                    "module" => "file",
                    "module_id" => $file->get_id(),
                    "cm_id" => $cm->id,
                ];



                $analizy = $plagiumconnect->get_analizy_plagium([], $datafile);
            } else {
                if (empty($linkarray["content"])) {
                    return "";
                }

                if (str_word_count(strip_tags($linkarray["content"])) <= 2) {
                    return;
                }

                $dataanalizy = (object) [
                    "context" => (object) $context,
                    "linkarray" => (object) $linkarray,
                    "cm" => (object) $cm,
                    "content" => $linkarray["content"]
                ];

                $analizy = $plagiumconnect->get_analizy_plagium($dataanalizy);
            }

            if (!$analizy) {
                return;
            }

            static $ajaxenabled;
            if (!isset($ajaxenabled[$cmid])) {
                $jsmodule = array(
                    'name' => 'plagiarism_plagium',
                    'fullpath' => '/plagiarism/plagium/ajax.js',
                    'requires' => array('json'),
                );
                $PAGE->requires->js_init_call('M.plagiarism_plagium.init', array($cmid), true, $jsmodule);
                $ajaxenabled[$cmid] = true;
            }

            return $plagiumconnect->show_icon_table($analizy, $context);
        } catch(Exception $e) {
            return;
        }
    }
}