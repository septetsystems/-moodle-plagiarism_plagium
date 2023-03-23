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
 * lib.php - Contains Plagiarism plugin specific functions called by Modules.
 *
 * @since 2.0
 * @package    plagiarism_plagium
 * @subpackage plagiarism
 * @copyright  2010 Dan Marsden http://danmarsden.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use plagium\classes\plagium_connect;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

//get global class
global $CFG;
require_once($CFG->dirroot.'/plagiarism/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_connect.php');

///// Turnitin Class ////////////////////////////////////////////////////
class plagiarism_plugin_plagium extends plagiarism_plugin {
     /**
     * hook to allow plagiarism specific information to be displayed beside a submission 
     * @param array  $linkarraycontains all relevant information for the plugin to generate a link
     * @return string
     * 
     */
    public function get_links($linkarray)
    {
        //ini_set('memory_limit', '-1');

        global $CFG, $USER, $COURSE, $DB, $PAGE, $cm;

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
            $plagiumConnect = new plagium_connect(); 
            #$assignment = new assign($context, null, null);
        
            $analizy = null;    
            if (!empty($linkarray["file"]) && $file = $linkarray["file"]) {
                $dataFile = [
                    "module" => "file",
                    "module_id" => $file->get_id(),
                    "cm_id" => $cm->id,
                ];

            

                $analizy = $plagiumConnect->getAnalizyPlagium([], $dataFile);
            } else {
                if (empty($linkarray["content"])) return "";

                if (str_word_count(strip_tags($linkarray["content"])) <= 2) return;

                $dataAnalizy = (object) [
                    "context" => (object) $context,
                    "linkarray" => (object) $linkarray,
                    "cm" => (object) $cm,
                    "content" => $linkarray["content"]
                ];

                $analizy = $plagiumConnect->getAnalizyPlagium($dataAnalizy);
            }

            if (!$analizy) return;
            
            static $ajaxenabled;
            if (!isset($ajaxenabled[$cmid])) {
                $jsmodule = array(
                    'name' => 'plagiarism_plagium',
                    'fullpath' => '/plagiarism/plagium/ajax.js',
                    'requires' => array('json'),
                );
                $PAGE->requires->js_init_call('M.plagiarism_plagium.init', array(), true, $jsmodule);
                $ajaxenabled[$cmid] = true;
            }

            return $plagiumConnect->showIconTable($analizy, $context);
        } catch(Exception $e) {
            //plagium_connect::dump($e);
            //return $e->getMessage();
        }
    }

    public function event_handler($eventdata) {
        global $DB, $CFG;

    }

    /**
     * hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {
        //Add elements to form using standard mform like:
        //$mform->addElement('hidden', $element);
        //$mform->disabledIf('plagiarism_draft_submit', 'var4', 'eq', 0);
    }

    /**
     * hook to allow a disclosure to be printed notifying users what will happen with their submission
     * @param int $cmid - course module id
     * @return string
     */
    public function print_disclosure($cmid) {
        global $OUTPUT;
        $plagiarismsettings = (array)get_config('plagiarism');
        //TODO: check if this cmid has plagiarism enabled.
        echo $OUTPUT->box_start('generalbox boxaligncenter', 'intro');
        $formatoptions = new stdClass;
        $formatoptions->noclean = true;
        echo format_text($plagiarismsettings['new_student_disclosure'], FORMAT_MOODLE, $formatoptions);
        echo $OUTPUT->box_end();
    }

    /**
     * called by admin/cron.php 
     *
     */
    public function cron() {
        //do any scheduled task stuff
    }
}

function new_event_file_uploaded($eventdata) {
    $result = true;
        //a file has been uploaded - submit this to the plagiarism prevention service.

    return $result;
}
function new_event_files_done($eventdata) {
    $result = true;
        //mainly used by assignment finalize - used if you want to handle "submit for marking" events
        //a file has been uploaded/finalised - submit this to the plagiarism prevention service.

    return $result;
}

function new_event_mod_created($eventdata) {
    $result = true;
        //a new module has been created - this is a generic event that is called for all module types
        //make sure you check the type of module before handling if needed.

    return $result;
}

function new_event_mod_updated($eventdata) {
    $result = true;
        //a module has been updated - this is a generic event that is called for all module types
        //make sure you check the type of module before handling if needed.

    return $result;
}

function new_event_mod_deleted($eventdata) {
    $result = true;
        //a module has been deleted - this is a generic event that is called for all module types
        //make sure you check the type of module before handling if needed.

    return $result;
}