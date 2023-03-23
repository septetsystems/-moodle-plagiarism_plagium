<?php

namespace plagium\classes;

use moodleform;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/plagiarism/plagium/lib.php');

class plagium_setup_form extends moodleform {

/// Define the form
    function definition () {
        global $CFG;

        $mform =& $this->_form;
        $plagium = "plagiarism_plagium";

        $mform->addElement('html', "<link rel='stylesheet' href='style.css' />");

        $mform->addElement('html', "<section class='section-plagium-page'>");
        
        $actionPath = file_get_contents(dirname(dirname(__FILE__))."/templates/info.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.info' => $actionPath
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

