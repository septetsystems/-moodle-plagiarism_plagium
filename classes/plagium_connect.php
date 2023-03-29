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

namespace plagium\classes;

use context_course;
use Exception;
use renderer_base;
use stdClass;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/plagiarism/plagium/classes/plagium_api.php');

/**
 * plagium_connect
 */
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
     * pluginname
     *
     * @var string
     */
    protected $pluginname = 'plagium';

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
    function __construct($notinstance = null) {
        $this->config = get_config('plagiarism_plagium');
        if ($notinstance) {
            $this->username = false;
        }

        $this->api = new plagium_api();
    }
    
    /**
     * get_setting_mappings
     *
     * @return void
     */
    public function get_setting_mappings() {
        return array(
            'api_key',
            'api_analyze',
            'api_visible',
            'api_seach_by_default',
            'api_seach_type_web',
            'api_seach_type_file'
        );
    }
    
    /**
     * save_configs
     *
     * @param  mixed $data
     * @return void
     */
    public function save_configs($data)
    {
        global $DB;
        foreach ($data as $field => $value) {
            if ($plagiumconfigfield = $DB->get_record('config_plugins', array('name' => $field, 'plugin' => $this->pluginname))) {
                $plagiumconfigfield->value = $value;
                if (!$DB->update_record('config_plugins', $plagiumconfigfield)) {
                    error("errorupdating");
                }
            } else {
                $plagiumconfigfield = new stdClass();
                $plagiumconfigfield->value = $value;
                $plagiumconfigfield->plugin = $this->pluginname;
                $plagiumconfigfield->name = $field;
                if (!$DB->insert_record('config_plugins', $plagiumconfigfield)) {
                    error("errorinserting");
                }
            }
            set_config($field, $value, $this->pluginname);
        }
    }
    
    /**
     * all_configs
     *
     * @param  mixed $format
     * @return void
     */
    public function all_configs($format = false)
    {
        $settings = [];
        foreach ($this->get_setting_mappings() as $key => $value) {
            $cacheexist = get_config($this->pluginname, $value);
            $settings[] = (object) ["name" => $value, "value" => $cacheexist];
        }

        if (!$format) {
            return $settings;
        }

        $configs = [];
        foreach ($settings as $key => $setting) {
            $configs[$setting->name] = $setting->value;
        }

        return $configs;
    }
    
    /**
     * get_analizy_id
     *
     * @param  mixed $id
     * @return void
     */
    public function get_analizy_id($id)
    {
        global $DB;
        $analizy = $DB->get_record("plagiarism_plagium", ["id" => $id]);
        $analizy->meta = json_decode($analizy->meta ?? "");

        $analizy = $this->prepare_result($analizy);

        return $analizy;
    }
    
    /**
     * prepare_result
     *
     * @param  mixed $analizy
     * @return void
     */
    public function prepare_result($analizy)
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
                } else if ($analizy->similarity_risk < 25.0) {
                    $analizy->similarity_risk_label = "badge-info";
                } else if ($analizy->similarity_risk < 50.0) {
                    $analizy->similarity_risk_label = "badge-primary";
                } else if ($analizy->similarity_risk < 75.0) {
                    $analizy->similarity_risk_label = "badge-warning";
                } else if ($analizy->similarity_risk <= 100.0) {
                    $analizy->similarity_risk_label = "badge-danger";
                }
            } else {
                $analizy->similarity_risk = 0;
                $analizy->similarity_risk_label = "badge-primary";
            }
        }
        return $analizy;
    }
    
    /**
     * get_analizy_plagium
     *
     * @param  mixed $data
     * @param  mixed $datareference
     * @return void
     */
    public function get_analizy_plagium($data, $datareference = null)
    {
        try {
            global $DB;

            if ($datareference == null) {
                $datareference = [
                    "cm_id" => $data->linkarray->cmid,
                    "module" => $data->linkarray->course,
                    "module_id" => $data->linkarray->assignment ?? "",
                    "user_id" => $data->linkarray->userid
                ];
            }

            if (!empty($datareference["module"]) && $datareference["module"] == "file") {
                $file = get_file_storage()->get_file_by_id($datareference["module_id"]);
                if (!in_array($file->get_mimetype(), plagium_connect::FILE_TYPES)) {
                    return;
                }
            }

            $analizy = $DB->get_record("plagiarism_plagium", $datareference);

            if (!$analizy) {
                $datareference["plagium_status"] = 0;
                $datareference["status"] = 0;
                if (!empty($data->content)) {
                    $datareference['content'] = $data->content;
                }

                $id = $DB->insert_record('plagiarism_plagium', $datareference);
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
            $analizy = $this->prepare_result($analizy);

            return $analizy;
        } catch (Exception $e) {
            throw new Exception("PLAGIUM ERROR");
        }
    }
    
    /**
     * get_plagium_record
     *
     * @param  mixed $analizyId
     * @param  mixed $refresh
     * @return void
     */
    public function get_plagium_record($analizyId, $refresh = false)
    {
        global $DB, $USER;

        $analizy = $this->get_analizy_id($analizyId);

        $body = [
            "data" => [
                "author" => $USER->firstname . " " . $USER->lastname . " " . $USER->email,
                "text" => $analizy->content,
                "import_id" => "text_" . $analizy->id,
                "source" => [],
                "read" => "public",
            ]
        ];

        $typeweb = get_config($this->pluginname, "api_seach_type_web");
        if ($typeweb) {
            $data["data"]["source"][] = "web";
        }

        $typefile = get_config($this->pluginname, "api_seach_type_file");
        if ($typefile) {
            $data["data"]["source"][] = "file";
        }

        $typevisible = get_config($this->pluginname, "api_visible");
        if ($typevisible) {
            $data["data"]["read"] = strtolower($typevisible->value);
        }

        $plagiumkey = get_config($this->pluginname, "api_key");
        if ($plagiumkey) {
            $body["key"] = $plagiumkey;
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
                $this->submit_single_file($file, $analizy->id);
            } else {
                $result = $this->api->request(
                    plagium_api::DOCUMENT,
                    "POST",
                    $body
                );

                if (
                    !empty($result->status) &&
                    $result->status == "ok" &&
                    !empty($result->result->obj)
                ) {
                    $DB->update_record('plagiarism_plagium', [
                        "id" => $analizy->id,
                        "meta" => json_encode($result->result->obj)
                    ]);
                }
            }
        }

        $analizy = $this->get_analizy_id($analizyId);

        if (!empty($analizy->meta->_id)) {
            $result2 = $this->api->request(
                plagium_api::RESULT,
                "POST",
                ["key" => $body["key"], "data" => ["id" => $analizy->meta->_id]]
            );

            if (
                !empty($result2->status) &&
                $result2->status == "ok" &&
                !empty($result2->result->results->objs)
            ) {

                $resultData = $result2->result->results->objs ?? [];

                foreach ($resultData as &$item) {
                    if (!empty($item->score)) {
                        $item->score = number_format($item->score, 1)."%";
                    }
                }

                $result2->result->obj->results = $resultData;
                $DB->update_record('plagiarism_plagium', [
                    "id" => $analizy->id,
                    "meta" => json_encode($result2->result->obj)
                ]);
            }
        }

        $analizy = $this->get_analizy_id($analizyId);
        return $analizy;
    }
    
    /**
     * show_icon_table
     *
     * @param  mixed $analizy
     * @param  mixed $context
     * @return void
     */
    public function show_icon_table($analizy, $context = null)
    {
        if (!$analizy) return;

        global $USER;
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

        $typeweb = get_config("plagium", "api_analyze");
        $plagium = "plagiarism_plagium";
        return $mustache->render('plagium.action', [
            "analizy" => $analizy,
            "typeWeb" => $typeweb,
            "action_analyze" => get_string('action_analyze', $plagium),
            "action_similarity" => get_string('action_similarity', $plagium),
            "action_risk" => get_string('action_risk', $plagium),
            "action_similarity_max" => get_string('action_similarity_max', $plagium),
            "action_report" => get_string('action_report', $plagium),
            "action_pdf" => get_string('action_pdf', $plagium),
            "action_full_report" => get_string('action_full_report', $plagium)
        ]);
    }
    
    /**
     * show_file_table
     *
     * @return void
     */
    public function show_file_table()
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
    
    /**
     * show_report
     *
     * @param  mixed $analizy
     * @return void
     */
    public function show_report($analizy)
    {
        $path = dirname(dirname(__FILE__));
        $actionPath = file_get_contents($path."/templates/report.mustache");

        $loader = new \Mustache_Loader_ArrayLoader([
            'plagium.report' => $actionPath
        ]);
        $mustache = new \core\output\mustache_engine(['loader' => $loader]);

        return $mustache->render('plagium.report', ["analizy" => $analizy]);
    }
    
    /**
     * submit_single_file
     *
     * @param  mixed $file
     * @param  mixed $analizyId
     * @return void
     */
    function submit_single_file($file, $analizyId)
    {
        global $DB, $USER;

        $data = [
            "data" => [
                "author" => $USER->firstname . " " . $USER->lastname . " " . $USER->email,
                "title" => $file->get_filename(),
                "import_id" => "file_" . $file->get_id(),
                "source" => [],
                "read" => "public",
            ]
        ];

        $typeweb = get_config($this->pluginname, "api_seach_type_web");
        if ($typeweb) {
            $data["data"]["source"][] = "web";
        }

        $typefile = get_config($this->pluginname, "api_seach_type_file");
        if ($typefile) {
            $data["data"]["source"][] = "file";
        }

        $typevisible = get_config($this->pluginname, "api_visible");
        if ($typevisible) {
            $data["data"]["read"] = strtolower($typevisible->value);
        }

        $plagiumkey = get_config($this->pluginname, "api_key");
        if ($plagiumkey) {
            $data["key"] = $plagiumkey;
        }

        $result = $this->api->request(
            plagium_api::UPLOAD,
            "POST",
            $data,
            $file
        );

        if (
            !empty($result->status) &&
            $result->status == "ok" &&
            !empty($result->result->obj)
        ) {
            $DB->update_record('plagiarism_plagium', [
                "id" => $analizyId,
                "meta" => json_encode($result->result->obj)
            ]);
        }

        return $result;
    }
}
