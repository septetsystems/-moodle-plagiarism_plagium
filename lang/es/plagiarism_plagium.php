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
$string['api_username'] = "Nombre de usuario";
$string['api_password'] = "Contraseña";
$string['api_key'] = "Clave API";
$string['api_server'] = 'Api URL';
$string['api_server_help'] = 'Servidor Plagium';

$string['api_analyze'] = 'Analizar';
$string['api_analyze_automatic'] = 'Auto';
$string['api_analyze_manual'] = 'Manual';

$string['api_seach_by_default'] = 'Tipo de búsqueda';
$string['api_seach_by_default_quick'] = 'Búsqueda rápida';
$string['api_seach_by_default_search'] = 'Búsqueda profunda';

$string['api_seach_type'] = 'Fuente';
$string['api_seach_type_web'] = 'Web';
$string['api_seach_type_file'] = 'Archivo';

$string['api_visible'] = 'Acceso al informe';
$string['api_visible_public'] = 'Público';
$string['api_visible_private'] = 'Privado';

$string['config_info1'] = '<b class="plagium-badge">Plagium</b> es un software de detección de plagio de pago que está diseñado para ayudar a las personas y organizaciones a detectar instancias de plagio en el contenido escrito. Utiliza algoritmos y tecnologías avanzados para comparar el contenido enviado con una amplia base de datos de fuentes, incluidas revistas académicas, sitios web y otras fuentes en línea.';
$string['config_info2'] = 'Puede encontrar más información en <a href="https://www.plagium.com/en/moodle">https://www.plagium.com/en/moodle</ a>.';
$string['config_info3'] = 'La información general se puede encontrar en <a href="https://www.plagium.com">https://www.plagium.com</a>.';

$string['action_analyze'] = 'Analizar';
$string['action_similarity'] = 'Similitud';
$string['action_risk'] = 'Riesgo';
$string['action_similarity_max'] = 'Similitud Máx.';
$string['action_report'] = 'Informe';
$string['action_pdf'] = 'PDF';
$string['action_full_report'] = 'Informe completo';

$string['guardar'] = 'Guardar';
$string['savedapiconfigerror'] = 'Ocurrió un error al actualizar la configuración de Plagium';
$string['savedconfigsuccess'] = 'La configuración de Plagium se guardó correctamente';


$string['privacy:metadata'] = "<h3>Privacy Policy for Moodle Plugin using Plagium API</h3>
<p>Our Moodle plugin uses the Plagium API to check student assignments for plagiarism. When you use the plugin, we will send your user email, assignment ID, and assignment files or text to the Plagium service for analysis.</p>
<p>The purpose of sending this information to Plagium is to check for any instances of plagiarism in the assignment. This will help maintain academic integrity and ensure that students are submitting original work.</p>
<p>We will only send the minimum amount of information necessary to the Plagium service for analysis. Your personal information, including your name, will not be sent to Plagium.</p>
<p>Please note that Plagium has its own privacy policy that governs its use of your information. We encourage you to review Plagium's privacy policy before using our plugin.</p>
<p>By using our Moodle plugin, you consent to the use of the Plagium API and the sending of your user email, assignment ID, and assignment files or text to Plagium for analysis.</p>
<p>We are committed to protecting your privacy and will only use your information for the purpose of checking for plagiarism in your assignment. If you have any questions or concerns about our privacy policy, please contact us at support@plagium.com.</p>";

$string['privacy:metadata:core_files'] = 'Archivos y texto en línea que se ha enviado usando el complemento Plagium.';
$string['privacy:metadata:plagiarism_plagium'] = 'Almacena datos de Plagium.';
$string['privacy:metadata:plagiarism_plagium:id'] = 'ID para cada entrada en la tabla plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:content'] = 'La evaluación del contenido.';
$string['privacy:metadata:plagiarism_plagium:user_id'] = 'La identificación de usuario del sistema Moodle.';
$string['privacy:metadata:plagiarism_plagium:plagium_status'] = 'Estado del registro en el sistema Plagium.';
$string['privacy:metadata:plagiarism_plagium:status'] = 'Estado del registro en la tabla plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:cm_id'] = 'El ID del contexto donde se creó la tarea.';
$string['privacy:metadata:plagiarism_plagium:module'] = 'Archivo o Asignación';
$string['privacy:metadata:plagiarism_plagium:module_id'] = 'FileId o ID de asignación';
$string['privacy:metadata:plagiarism_plagium:meta'] = 'Metadatos del archivo o texto';

$string['privacy:metadata:plagiarism_plagium:firstname'] = 'El nombre del usuario.';
$string['privacy:metadata:plagiarism_plagium:lastname'] = 'El apellido del usuario.';
$string['privacy:metadata:plagiarism_plagium:email'] = 'El correo electrónico del usuario.';
$string['privacy:metadata:plagiarism_plagium:filename'] = 'Los datos del archivo.';
$string['privacy:metadata:plagiarism_plagium:file'] = 'El nombre del archivo.';