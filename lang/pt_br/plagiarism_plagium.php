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
$string['api_username'] = "Nome de usuário";
$string['api_password'] = "Senha";
$string['api_key'] = "Chave API";
$string['api_server'] = 'URL da API';
$string['api_server_help'] = 'Plagium server';

$string['api_analyze'] = 'Analisar';
$string['api_analyze_automatic'] = 'Auto';
$string['api_analyze_manual'] = 'Manual';

$string['api_seach_by_default'] = 'Tipo de busca';
$string['api_seach_by_default_quick'] = 'Busca rápida';
$string['api_seach_by_default_search'] = 'Busca avançada';

$string['api_seach_type'] = 'Fonte';
$string['api_seach_type_web'] = 'Web';
$string['api_seach_type_file'] = 'Arquivo';

$string['api_visible'] = 'Acesso ao relatório';
$string['api_visible_public'] = 'Público';
$string['api_visible_private'] = 'Privado';

$string['config_info1'] = '<b class="plagium-badge">Plagium</b> é um software pago de detecção de plágio desenvolvido para ajudar indivíduos e organizações a detectar casos de plágio em conteúdo escrito. Ele usa algoritmos e tecnologias avançadas para comparar o conteúdo enviado com um vasto banco de dados de fontes, incluindo revistas acadêmicas, sites e outras fontes online.';
$string['config_info2'] = 'Você pode encontrar mais informações em  <a href="https://www.plagium.com/pt/moodle">https://www.plagium.com/pt/moodle</a>.';
$string['config_info3'] = 'Informações gerais podem ser encontradas em <a href="https://www.plagium.com">https://www.plagium.com</a>.';

$string['action_analyze'] = 'Analisar';
$string['action_similarity'] = 'Semelhança';
$string['action_risk'] = 'Risco';
$string['action_similarity_max'] = 'Semelhança Max.';
$string['action_report'] = 'Relatório';
$string['action_pdf'] = 'PDF';
$string['action_full_report'] = 'Relatório completo';

$string['save'] = 'Salvar';
$string['savedapiconfigerror'] = 'Ocorreu um erro ao atualizar as configurações do Plagium';
$string['savedconfigsuccess'] = 'Configurações do Plagium salvas com sucesso';


$string['privacy:metadata'] = "<h3>Privacy Policy for Moodle Plugin using Plagium API</h3>
<p>Our Moodle plugin uses the Plagium API to check student assignments for plagiarism. When you use the plugin, we will send your user email, assignment ID, and assignment files or text to the Plagium service for analysis.</p>
<p>The purpose of sending this information to Plagium is to check for any instances of plagiarism in the assignment. This will help maintain academic integrity and ensure that students are submitting original work.</p>
<p>We will only send the minimum amount of information necessary to the Plagium service for analysis. Your personal information, including your name, will not be sent to Plagium.</p>
<p>Please note that Plagium has its own privacy policy that governs its use of your information. We encourage you to review Plagium's privacy policy before using our plugin.</p>
<p>By using our Moodle plugin, you consent to the use of the Plagium API and the sending of your user email, assignment ID, and assignment files or text to Plagium for analysis.</p>
<p>We are committed to protecting your privacy and will only use your information for the purpose of checking for plagiarism in your assignment. If you have any questions or concerns about our privacy policy, please contact us at support@plagium.com.</p>";

$string['privacy:metadata:core_files'] = 'Arquivos e texto online que foram enviados usando o plug-in Plagium.';
$string['privacy:metadata:plagiarism_plagium'] = 'Armazena dados do Plagium.';
$string['privacy:metadata:plagiarism_plagium:id'] = 'ID para cada entrada na tabela plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:content'] = 'A avaliação de conteúdo.';
$string['privacy:metadata:plagiarism_plagium:user_id'] = 'ID do usuário do sistema Moodle.';
$string['privacy:metadata:plagiarism_plagium:plagium_status'] = 'Status do cadastro no sistema Plagium.';
$string['privacy:metadata:plagiarism_plagium:status'] = 'O status do registro na tabela plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:cm_id'] = 'O ID do contexto onde a atribuição foi criada.';
$string['privacy:metadata:plagiarism_plagium:module'] = 'Arquivo ou Atribuição';
$string['privacy:metadata:plagiarism_plagium:module_id'] = 'FileId ou Assignment Id.';
$string['privacy:metadata:plagiarism_plagium:meta'] = 'Meta dados do arquivo ou texto';

$string['privacy:metadata:plagiarism_plagium:firstname'] = 'O nome do usuário.';
$string['privacy:metadata:plagiarism_plagium:lastname'] = 'O sobrenome do usuário.';
$string['privacy:metadata:plagiarism_plagium:email'] = 'O e-mail do usuário.';
$string['privacy:metadata:plagiarism_plagium:filename'] = 'Os dados do arquivo.';
$string['privacy:metadata:plagiarism_plagium:file'] = 'O nome do arquivo.';

$string['plagium_status'] = "Habilitar plagium";
$string['plagium_status_help'] = "Habilitar plagium";

$string['coursemodule_status'] = "Ativar plagium";
$string['coursemodule_status_help'] = "Ativar plagium";
$string['active'] = "Ativo";
$string['inactive'] = "Inativo";

$string['privacy:metadata:plagiarism_plagium_config'] = 'Armazena configuração do Plagium para dados do curso.';
$string['privacy:metadata:plagiarism_plagium_config:id'] = 'ID para cada entrada na tabela plagiarism_plagium_config.';
$string['privacy:metadata:plagiarism_plagium_config:cm'] = 'O ID da avaliação.';
$string['privacy:metadata:plagiarism_plagium_config:user_id'] = 'Status ativo ou inativo.';
