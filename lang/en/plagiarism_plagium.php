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

$string['pluginname'] = "Plagium";
$string['api_username'] = "Username";
$string['api_password'] = "Password";
$string['api_key'] = "Api Key";
$string['api_server'] = 'Api Url';
$string['api_server_help'] = 'Plagium server';

$string['api_analyze'] = 'Analyze';
$string['api_analyze_automatic'] = 'Auto';
$string['api_analyze_manual'] = 'Manual';

$string['api_seach_by_default'] = 'Search Type';
$string['api_seach_by_default_quick'] = 'Quick Search';
$string['api_seach_by_default_search'] = 'Deep Search';

$string['api_seach_type'] = 'Source';
$string['api_seach_type_web'] = 'Web';
$string['api_seach_type_file'] = 'File';

$string['api_visible'] = 'Report Access';
$string['api_visible_public'] = 'Public';
$string['api_visible_private'] = 'Private';

$string['config_info1'] = '<b class="plagium-badge">Plagium</b> is a paid plagiarism detection software that is designed to help individuals and organizations detect instances of plagiarism in written content. It uses advanced algorithms and technologies to compare submitted content against a vast database of sources, including academic journals, websites, and other online sources.';
$string['config_info2'] = 'You can find more information at <a href="https://www.plagium.com/en/moodle">https://www.plagium.com/en/moodle</a>.';
$string['config_info3'] = 'General information can be found at <a href="https://www.plagium.com">https://www.plagium.com</a>.';

$string['action_analyze'] = 'Analyze';
$string['action_similarity'] = 'Similarity';
$string['action_risk'] = 'Risk';
$string['action_similarity_max'] = 'Similarity Max.';
$string['action_report'] = 'Report';
$string['action_pdf'] = 'PDF';
$string['action_full_report'] = 'Full Report';

$string['save'] = 'Save';
$string['savedapiconfigerror'] = 'An error occurred updating your Plagium settings';
$string['savedconfigsuccess'] = 'Plagium settings saved successfully';

$string['privacy:metadata'] = "<h3>Privacy Policy for Moodle Plugin using Plagium API</h3>
<p>Our Moodle plugin uses the Plagium API to check student assignments for plagiarism. When you use the plugin, we will send your user email, assignment ID, and assignment files or text to the Plagium service for analysis.</p>
<p>The purpose of sending this information to Plagium is to check for any instances of plagiarism in the assignment. This will help maintain academic integrity and ensure that students are submitting original work.</p>
<p>We will only send the minimum amount of information necessary to the Plagium service for analysis. Your personal information, including your name, will not be sent to Plagium.</p>
<p>Please note that Plagium has its own privacy policy that governs its use of your information. We encourage you to review Plagium's privacy policy before using our plugin.</p>
<p>By using our Moodle plugin, you consent to the use of the Plagium API and the sending of your user email, assignment ID, and assignment files or text to Plagium for analysis.</p>
<p>We are committed to protecting your privacy and will only use your information for the purpose of checking for plagiarism in your assignment. If you have any questions or concerns about our privacy policy, please contact us at support@plagium.com.</p>";

$string['privacy:metadata:core_files'] = 'Files and online text that has been submitted using Plagium plugin.';
$string['privacy:metadata:plagiarism_plagium'] = 'Stores Plagium data.';
$string['privacy:metadata:plagiarism_plagium:id'] = 'ID for each entry in the plagiarism_plagium table.';
$string['privacy:metadata:plagiarism_plagium:content'] = 'The content assessment.';
$string['privacy:metadata:plagiarism_plagium:user_id'] = 'The Moodle system user id.';
$string['privacy:metadata:plagiarism_plagium:plagium_status'] = 'The Status of the register in the Plagium system.';
$string['privacy:metadata:plagiarism_plagium:status'] = 'The Status of the register in the plagiarism_plagium table.';
$string['privacy:metadata:plagiarism_plagium:cm_id'] = 'The ID of the context where the assignment was created.';
$string['privacy:metadata:plagiarism_plagium:module'] = 'File or Assignment';
$string['privacy:metadata:plagiarism_plagium:module_id'] = 'FileId or Assignment Id.';
$string['privacy:metadata:plagiarism_plagium:meta'] = 'Meta data the file ou text';

$string['privacy:metadata:plagiarism_plagium:firstname'] = 'The firstname of the user.';
$string['privacy:metadata:plagiarism_plagium:lastname'] = 'The lastname of the user.';
$string['privacy:metadata:plagiarism_plagium:email'] = 'The email of the user.';
$string['privacy:metadata:plagiarism_plagium:filename'] = 'The data from the file.';
$string['privacy:metadata:plagiarism_plagium:file'] = 'The name of the file.';