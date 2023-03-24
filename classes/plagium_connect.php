<?php

namespace plagium\classes;

use context_course;
use Exception;
use renderer_base;
use stdClass;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_api.php');

class plagium_connect
{
    /**
     *  Defines the configuration of the module or assignment
     *
     * @var array
     */
    protected $config;

    /**
     * Username of the user using the plugin
     *
     * @var string
     */
    protected $username = -1;

    /**
     *
     * @var bool
     */
    protected $nondisclosure = false;

    /**
     * pluginName
     *
     * @var string
     */
    protected $pluginName = 'plagium';

    /**
     * api
     *
     * @var plagium_api
     */
    private $api;


    const FILE_TYPES = [
        "application/pdf",
        "text/plain",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ];


    /**
     * Constructor of the plagium_connection class
     *
     * @param bool $notinstance
     */
    function __construct($notinstance = false) {
        $this->config = get_config('plagiarism_plagium');
        if ($notinstance) {
            $this->username = false;
        }

        $this->api = new plagium_api();
    }

    static function dump($data, $exit = false)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        if ($exit) exit;
    }

    static function log($data, $exit = false)
    {
        $log = new Logger("log.txt");
        $log->setTimestamp("D M d 'y h.i A");

        $data = print_r($data, true);
        $log->putLog($data);

        if ($exit) exit;
    }


    public function get_setting_mappings() {
        return array(
            'api_key',
            'client_id',
            'api_server'
        );
    }

    public function saveConfigs($data)
    {
        global $DB;
        foreach ($data as $field => $value) {
            if ($plagiumConfigField = $DB->get_record('config_plugins', array('name' => $field, 'plugin' => $this->pluginName))) {
                $plagiumConfigField->value = $value;
                if (!$DB->update_record('config_plugins', $plagiumConfigField)) {
                    error("errorupdating");
                }
            } else {
                $plagiumConfigField = new stdClass();
                $plagiumConfigField->value = $value;
                $plagiumConfigField->plugin = $this->pluginName;
                $plagiumConfigField->name = $field;
                if (!$DB->insert_record('config_plugins', $plagiumConfigField)) {
                    error("errorinserting");
                }
            }
        }
    }

    public function allConfigs($formatForm = false)
    {
        global $DB;
        $settings = [];
        foreach ($this->get_setting_mappings() as $key => $value) {
            $settings[] = $DB->get_record("config_plugins", array("name" => $value, "plugin" => $this->pluginName));
        }

        if (!$formatForm) {
            return $settings;
        }

        $configs = [];
        foreach ($settings as $key => $setting) {
            $configs[$setting->name] = $setting->value;
        }

        return $configs;
    }

    public function getAnalizyId($id)
    {
        global $DB;
        $analizy = $DB->get_record("plagiarism_plagium", ["id" => $id]);
        $analizy->meta = json_decode($analizy->meta ?? "");

        $analizy = $this->prepareResult($analizy);

        return $analizy;
    }

    public function prepareResult($analizy)
    {
        if (!empty($analizy->meta->obj->data) && $data = $analizy->meta->obj->data) {
            if (!empty($data->stats->proximity->score->document) && $document = $data->stats->proximity->score->document) {
                $analizy->similarity = number_format($document, 1);;
                $analizy->similarity_label = "badge-success";
            } else {
                $analizy->similarity = 0;
                $analizy->similarity_label = "badge-primary";
            }

            if (!empty($data->stats->proximity->score->paragraph) && $paragraph = $data->stats->proximity->score->paragraph) {
                $analizy->similarity_max = number_format($paragraph, 1);
                $analizy->similarity_max_label = "badge-primary";
            } else {
                $analizy->similarity_max = 0;
                $analizy->similarity_max_label = "badge-primary";
            }

            if (!empty($data->stats->proximity->score->page) && $page = $data->stats->proximity->score->page) {
                $analizy->similarity_risk = number_format(($page + $document + $paragraph) / 3.0, 1);
                $analizy->similarity_risk_label = "badge-primary";

                if ($analizy->similarity_risk < 5.0) {
                    $analizy->similarity_risk_label = "badge-success";
                }
                else if ($analizy->similarity_risk < 25.0) {
                    $analizy->similarity_risk_label = "badge-info";
                }
                else if ($analizy->similarity_risk < 50.0) {
                    $analizy->similarity_risk_label = "badge-primary";
                }
                else if ($analizy->similarity_risk < 75.0) {
                    $analizy->similarity_risk_label = "badge-warning";
                }
                else if ($analizy->similarity_risk <= 100.0) {
                    $analizy->similarity_risk_label = "badge-danger";
                }
            } else {
                $analizy->similarity_risk = 0;
                $analizy->similarity_risk_label = "badge-primary";
            }
        }
        return $analizy;
    }

    public function getAnalizyPlagium($data, $dataReference = null)
    {
        try {
            global $DB;

            if ($dataReference == null) {
                $dataReference = [
                    "cm_id" => $data->linkarray->cmid,
                    "module" => $data->linkarray->course,
                    "module_id" => $data->linkarray->assignment ?? "",
                    "user_id" => $data->linkarray->userid
                ];
            }

            if (!empty($dataReference["module"]) && $dataReference["module"] == "file") {
                $file = get_file_storage()->get_file_by_id($dataReference["module_id"]);
                if (!in_array($file->get_mimetype(), plagium_connect::FILE_TYPES)) {
                    return;
                }
            }

            $analizy = $DB->get_record("plagiarism_plagium", $dataReference);

            if (!$analizy) {
                $dataReference["plagium_status"] = 0;
                $dataReference["status"] = 0;
                if (!empty($data->content)) {
                    $dataReference['content'] = $data->content;
                }

                $id = $DB->insert_record('plagiarism_plagium', $dataReference);
                $analizy = $DB->get_record("plagiarism_plagium", ["id" => $id]);
            } else {
                if (!empty($data->content)) {
                    $analizy->content = $data->content;
                }

                $DB->update_record('plagiarism_plagium', [
                    "id" =>  $analizy->id,
                    "content" => $analizy->content ?? ""
                ]);
            }

            $analizy->meta = json_decode($analizy->meta ?? "");
            $analizy = $this->prepareResult($analizy);

            return $analizy;
        } catch (Exception $e) {
            throw new Exception("PLAGIUM ERROR");
        }
    }

    public function getPlagiumRecord($analizyId, $refresh = false)
    {
        global $DB, $USER;

        $analizy = $this->getAnalizyId($analizyId);

        $body = [
            "data" => [
                "author" => $USER->firstname . " " . $USER->lastname . " " . $USER->email,
                "text" => $analizy->content,
                "import_id" => "text_" . $analizy->id,
                "source" => [],
                "read" => "public",
            ]
        ];

        $typeWeb = $DB->get_record('config_plugins', array('name' => "api_seach_type_web", 'plugin' => $this->pluginName));
        if ($typeWeb && $typeWeb->value) {
            $data["data"]["source"][] = "web";
        }

        $typeFile = $DB->get_record('config_plugins', array('name' => "api_seach_type_file", 'plugin' => $this->pluginName));
        if ($typeFile && $typeFile->value) {
            $data["data"]["source"][] = "file";
        }

        $typeVisible = $DB->get_record('config_plugins', array('name' => "api_visible", 'plugin' => $this->pluginName));
        if ($typeVisible && $typeVisible->value) {
            $data["data"]["read"] = strtolower($typeVisible->value);
        }

        $plagiumKey = $DB->get_record('config_plugins', array('name' => "api_key", 'plugin' => $this->pluginName));
        if ($plagiumKey) {
            $body["key"] = $plagiumKey->value;
        }

        if (
            empty($analizy->meta->_id)
            || $refresh
            || (
                empty($analizy->meta->_id)
                && empty($analizy->meta->_id)
            )
        ) {

            if ($analizy->module == "file") {
                $file = get_file_storage()->get_file_by_id($analizy->module_id);
                $this->submitSingleFile($file, $analizy->id);
            } else {
                $result = $this->api->request(
                    plagium_api::DOCUMENT,
                    "POST",
                    $body
                );

                if (
                    !empty($result["httpcode"]) &&
                    $result["httpcode"] == 200 &&
                    !empty($result["response"]["result"]["obj"])
                ) {
                    $DB->update_record('plagiarism_plagium', [
                        "id" => $analizy->id,
                        "meta" => json_encode($result["response"]["result"]["obj"])
                    ]);
                }
            }
        }

        $analizy = $this->getAnalizyId($analizyId);

        if (!empty($analizy->meta->_id)) {
            $result2 = $this->api->request(
                plagium_api::RESULT,
                "POST",
                ["key" => $body["key"], "data" => ["id" => $analizy->meta->_id]]
            );

            if (
                !empty($result2["httpcode"]) &&
                $result2["httpcode"] == 200 &&
                !empty($result2["response"]["result"]['results']['objs'])
            ) {

                $resultData = $result2["response"]["result"]['results']['objs'] ?? [];

                foreach ($resultData as $key => &$item) {
                    if (!empty($item["score"])) {
                        $item["score"] = number_format($item["score"], 1)."%";
                    }
                }

                $result2["response"]["result"]["obj"]["results"] = $resultData;
                $DB->update_record('plagiarism_plagium', [
                    "id" => $analizy->id,
                    "meta" => json_encode($result2["response"]["result"]["obj"])
                ]);
            }
        }

        $analizy = $this->getAnalizyId($analizyId);
        return $analizy;
    }

    public function showIconTable($analizy, $context = null)
    {
        if (!$analizy) return;

        global $USER, $DB;
        $path = dirname(dirname(__FILE__));
        $actionPath = file_get_contents($path."/templates/action.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.action' => $actionPath
        ]);
        $mustache = new \core\output\mustache_engine(['loader' => $loader]);

        if (!$context) {
            $context = context_course::instance($analizy->module);
        }
        $roles = get_user_roles($context, $USER->id, true);
        $role = key($roles);
        $rolename = $roles[$role]->shortname;

        if ($rolename === "student") return "";

        if (is_string($analizy)) {
            return $analizy;
        }

        $typeWeb = $DB->get_record('config_plugins', array('name' => "api_analyze", 'plugin' => "plagium"));

        $plagium = "plagiarism_plagium";
        return $mustache->render('plagium.action', [
            "analizy" => $analizy,
            "typeWeb" => $typeWeb ? $typeWeb->value : "",
            "action_analyze" => get_string('action_analyze', $plagium),
            "action_similarity" => get_string('action_similarity', $plagium),
            "action_risk" => get_string('action_risk', $plagium),
            "action_similarity_max" => get_string('action_similarity_max', $plagium),
            "action_report" => get_string('action_report', $plagium),
            "action_pdf" => get_string('action_pdf', $plagium),
            "action_full_report" => get_string('action_full_report', $plagium)
        ]);
    }

    public function showFileTable()
    {
        $path = dirname(dirname(__FILE__));
        $actionPath = file_get_contents($path."/templates/file.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.file' => $actionPath
        ]);
        $mustache = new \core\output\mustache_engine(['loader' => $loader]);

        $plagium = "plagiarism_plagium";
        return $mustache->render('plagium.file', [
            "action_pdf" => get_string('action_pdf', $plagium),
            "action_full_report" => get_string('action_full_report', $plagium)
        ]);
    }

    public function showReport($analizy)
    {
        $path = dirname(dirname(__FILE__));
        $actionPath = file_get_contents($path."/templates/report.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.report' => $actionPath
        ]);
        $mustache = new \core\output\mustache_engine(['loader' => $loader]);

        return $mustache->render('plagium.report', ["analizy" => $analizy]);
    }

    function submitSingleFile($file, $analizyId)
    {
        global $DB, $USER;

        $data = [
            "data" => [
                "author" => $USER->firstname . " " . $USER->lastname . " " . $USER->email,
                "title" => $file->get_filename(),
                "import_id" => "file_" . $file->get_id(),

                //Pegar da configuração
                "source" => [],

                //Pegar da configuração
                "read" => "public",
            ]
        ];

        $typeWeb = $DB->get_record('config_plugins', array('name' => "api_seach_type_web", 'plugin' => $this->pluginName));
        if ($typeWeb && $typeWeb->value) {
            $data["data"]["source"][] = "web";
        }

        $typeFile = $DB->get_record('config_plugins', array('name' => "api_seach_type_file", 'plugin' => $this->pluginName));
        if ($typeFile && $typeFile->value) {
            $data["data"]["source"][] = "file";
        }

        $typeVisible = $DB->get_record('config_plugins', array('name' => "api_visible", 'plugin' => $this->pluginName));
        if ($typeVisible && $typeVisible->value) {
            $data["data"]["read"] = strtolower($typeVisible->value);
        }

        $plagiumKey = $DB->get_record('config_plugins', array('name' => "api_key", 'plugin' => $this->pluginName));
        if ($plagiumKey) {
            $data["key"] = $plagiumKey->value;
        }

        $result = $this->api->request(
            plagium_api::UPLOAD,
            "POST",
            $data,
            $file
        );

        if (
            !empty($result["httpcode"]) &&
            $result["httpcode"] == 200 &&
            !empty($result["response"]["result"]["obj"])
        ) {
            $DB->update_record('plagiarism_plagium', [
                "id" => $analizyId,
                "meta" => json_encode($result["response"]["result"]["obj"])
            ]);
        }

        return $result;
    }

}



class Logger {

    private
        $file,
        $timestamp;

    public function __construct($filename) {
        $this->file = $filename;
    }

    public function setTimestamp($format) {
        $this->timestamp = date($format)." &raquo; ";
    }

    public function putLog($insert) {
        if (isset($this->timestamp)) {
            file_put_contents($this->file, $this->timestamp." ---  ".$insert, FILE_APPEND);
        } else {
            trigger_error("Timestamp not set", E_USER_ERROR);
        }
    }

    public function getLog() {
        $content = @file_get_contents($this->file);
        return $content;
    }

}