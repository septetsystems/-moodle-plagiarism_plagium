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
        global $PAGE, $cm;

        $cmid = $linkarray['cmid'];

        $context = context_module::instance($cmid);

        if (!get_config("plagiarism_plagium", 'plagium_status') || !has_capability('plagiarism/plagium:enable', $context)) {
            return '';
        }

        $instanceconfig = plagium_get_instance_config($cmid);
        if (empty($instanceconfig->coursemodule_status)) {
            return "";
        }

        $pageurl = $PAGE->url;
        $pagination = optional_param('page', -1, PARAM_INT);

        if ($pagination != -1) {
            $pageurl->param('page', $pagination);
        }

        try {
            $plagiumconnect = new plagium_connect();

            $analizy = null;
            if (!empty($linkarray["file"]) && $file = $linkarray["file"]) {
                $datafile = [
                    "module" => "file",
                    "module_id" => $file->get_id(),
                    "cm_id" => $cm->id,
                    "user_id" => $linkarray['userid']
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
        } catch (Exception $e) {
            return;
        }
    }
}

/**
 * plagium_get_instance_config
 *
 * @param  mixed $cmid
 * @param  mixed $defaultconfig
 * @return void
 */
function plagium_get_instance_config($cmid, $defaultconfig = true) {
    global $DB;

    if ($config = $DB->get_record('plagiarism_plagium_config', array('cm' => $cmid))) {
        return $config;
    }

    $default = new \stdClass();
    if ($defaultconfig) {
        $default->coursemodule_status = 1;
    }

    return $default;
}


/**
 * plagium_set_instance_config
 *
 * @param  mixed $cmid
 * @param  mixed $data
 * @return void
 */
function plagium_set_instance_config($cmid, $data) {
    global $DB;

    $current = $DB->get_record('plagiarism_plagium_config', array('cm' => $cmid));
    if ($current) {
        $data->id = $current->id;
        $DB->update_record('plagiarism_plagium_config', $data);
    } else {
        $data->cm = $cmid;
        $DB->insert_record('plagiarism_plagium_config', $data);
    }
}

/**
 * plagiarism_plagium_coursemodule_standard_elements
 *
 * @param  mixed $formwrapper
 * @param  mixed $mform
 * @return void
 */
function plagiarism_plagium_coursemodule_standard_elements($formwrapper, $mform) {
    $plagium = 'plagiarism_plagium';

    $context = context_course::instance($formwrapper->get_course()->id);

    if (!get_config($plagium, 'plagium_status') || !has_capability('plagiarism/plagium:enable', $context)) {
        return '';
    }

    $modulename = $formwrapper->get_current()->modulename;

    $status = array(
        1 => get_string('active', $plagium),
        0 => get_string('inactive', $plagium)
    );

    if ($modulename == 'assign') {
        $cmid = optional_param('update', 0, PARAM_INT);
        $mform->addElement('header', 'plagiumdesc', get_string('plagium', $plagium));

        $mform->addElement('select', 'coursemodule_status', get_string('coursemodule_status', $plagium), $status);
        $mform->addHelpButton('coursemodule_status', 'coursemodule_status', $plagium);
        $mform->setDefault('coursemodule_status', 0);

        $instanceconfig = plagium_get_instance_config($cmid);
        if (isset($instanceconfig->coursemodule_status)) {
            $mform->setDefault('coursemodule_status', $instanceconfig->coursemodule_status);
        }
    }

}

/**
 * plagiarism_plagium_coursemodule_edit_post_actions
 *
 * @param  mixed $data
 * @param  mixed $course
 * @return void
 */
function plagiarism_plagium_coursemodule_edit_post_actions($data, $course) {
    if ($data->modulename != "assign") {
        return $data;
    }

    $cmid = $data->coursemodule;

    $config = new \stdClass();

    $validationdefault = array('options' => array('default' => 0));
    $config->coursemodule_status = filter_var($data->coursemodule_status, FILTER_VALIDATE_INT, $validationdefault);

    plagium_set_instance_config($cmid, $config);

    return $data;
}
