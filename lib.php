<?php
use plagium\classes\plagium_connect;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

//get global class
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
    public function get_links($linkarray)
    {
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
                $PAGE->requires->js_init_call('M.plagiarism_plagium.init', array($cmid), true, $jsmodule);
                $ajaxenabled[$cmid] = true;
            }

            return $plagiumConnect->showIconTable($analizy, $context);
        } catch(Exception $e) {
            //plagium_connect::dump($e);
            //return $e->getMessage();
        }
    }

    
    /**
     * print_disclosure
     *
     * @param  mixed $cmid
     * @return void
     */
    public function print_disclosure($cmid) {
        global $OUTPUT;
        $plagiarismsettings = (array)get_config('plagiarism');
        echo $OUTPUT->box_start('generalbox boxaligncenter', 'intro');
        $formatoptions = new stdClass;
        $formatoptions->noclean = true;
        echo format_text($plagiarismsettings['new_student_disclosure'], FORMAT_MOODLE, $formatoptions);
        echo $OUTPUT->box_end();
    }
}