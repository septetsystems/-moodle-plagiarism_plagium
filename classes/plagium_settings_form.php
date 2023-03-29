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

namespace plagium\classes;

use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');


/**
 * plagium_setup_form
 */
class plagium_setup_form extends moodleform {
    /**
     * definition
     *
     * @return void
     */
    public function definition() {
        $mform =& $this->_form;
        $plagium = "plagiarism_plagium";

        $mform->addElement('html', "<link rel='stylesheet' href='style.css' />");

        $mform->addElement('html', "<section class='section-plagium-page'>");

        $actionpath = file_get_contents(dirname(dirname(__FILE__))."/templates/info.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.info' => $actionpath
        ]);
        $mustache = new \core\output\mustache_engine(['loader' => $loader]);

        $mform->addElement('html', $mustache->render('plagium.info', [
            "config_info1" => get_string('config_info1', $plagium),
            "config_info2" => get_string('config_info2', $plagium),
            "config_info3" => get_string('config_info3', $plagium),
        ]));

        $mform->addElement('text', 'api_key', get_string('api_key', $plagium));
        $mform->addRule('api_key', null, 'required', null, 'client');
        $mform->setType('api_key', PARAM_TEXT);

        $analyze = array(
            "Auto" => get_string('api_analyze_automatic', $plagium),
            "Manual" => get_string('api_analyze_manual', $plagium)
        );
        $mform->addElement('select', 'api_analyze', get_string('api_analyze', $plagium), $analyze);
        $mform->setDefault('api_analyze', "Auto");

        $analyze = array(
            "Public" => get_string('api_visible_public', $plagium),
            "Private" => get_string('api_visible_private', $plagium)
        );
        $mform->addElement('select', 'api_visible', get_string('api_visible', $plagium), $analyze);
        $mform->setDefault('api_visible', "Public");

        $analyze = array(
            "QUICK" => get_string('api_seach_by_default_quick', $plagium),
            "DEEP" => get_string('api_seach_by_default_search', $plagium)
        );
        $mform->addElement('select', 'api_seach_by_default', get_string('api_seach_by_default', $plagium), $analyze);
        $mform->setDefault('api_seach_by_default', "QUICK");

        $mform->addElement('html', get_string('api_seach_type', $plagium));
        $mform->addElement('checkbox', 'api_seach_type_web', get_string('api_seach_type_web', $plagium));
        $mform->addElement('checkbox', 'api_seach_type_file', get_string('api_seach_type_file', $plagium));

        $mform->addElement('html', "</section>");

        $this->add_action_buttons(true);
    }
}
