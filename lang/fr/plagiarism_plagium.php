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
$string['api_username'] = "Nom d'utilisateur";
$string['api_password'] = "Mot de passe";
$string['api_key'] = "Clé API";
$string['api_server'] = "URL de l'API";
$string['api_server_help'] = 'Serveur Plagium';

$string['api_analyze'] = 'Analyser';
$string['api_analyze_automatic'] = 'Auto';
$string['api_analyze_manual'] = 'Manuel';

$string['api_seach_by_default'] = 'Type de recherche';
$string['api_seach_by_default_quick'] = 'Recherche rapide';
$string['api_seach_by_default_search'] = 'Recherche approfondie';

$string['api_seach_type'] = 'Source';
$string['api_seach_type_web'] = 'Web';
$string['api_seach_type_file'] = 'Fichier';

$string['api_visible'] = 'Accès au rapport';
$string['api_visible_public'] = 'Public';
$string['api_visible_private'] = 'Privé';

$string['config_info1'] = "<b class=\"plagium-badge\">Plagium</b> est un logiciel de détection de plagiat payant conçu pour aider les individus et les organisations à détecter les cas de plagiat dans le contenu écrit. Il utilise des algorithmes et des technologies avancés pour comparer le contenu soumis à une vaste base de données de sources, y compris des revues universitaires, des sites Web et d'autres sources en ligne.";
$string['config_info2'] = "Vous pouvez trouver plus d'informations sur <a href=\"https://www.plagium.com/fr/moodle\">https://www.plagium.com/fr/moodle</a>.";
$string['config_info3'] = 'Des informations générales sont disponibles sur <a href="https://www.plagium.com">https://www.plagium.com</a>.';

$string['action_analyze'] = 'Analyser';
$string['action_similarity'] = 'Similarity';
$string['action_risk'] = 'Risque';
$string['action_similarity_max'] = 'Similarité Max.';
$string['action_report'] = 'Rapport';
$string['action_pdf'] = 'PDF';
$string['action_full_report'] = 'Rapport complet';

$string['save'] = 'Enregistrer';
$string['savedapiconfigerror'] = 'Une erreur s\'est produite lors de la mise à jour de vos paramètres Plagium';
$string['savedconfigsuccess'] = 'Paramètres Plagium enregistrés avec succès';


$string['privacy:metadata'] = "<h3>Privacy Policy for Moodle Plugin using Plagium API</h3>
<p>Our Moodle plugin uses the Plagium API to check student assignments for plagiarism. When you use the plugin, we will send your user email, assignment ID, and assignment files or text to the Plagium service for analysis.</p>
<p>The purpose of sending this information to Plagium is to check for any instances of plagiarism in the assignment. This will help maintain academic integrity and ensure that students are submitting original work.</p>
<p>We will only send the minimum amount of information necessary to the Plagium service for analysis. Your personal information, including your name, will not be sent to Plagium.</p>
<p>Please note that Plagium has its own privacy policy that governs its use of your information. We encourage you to review Plagium's privacy policy before using our plugin.</p>
<p>By using our Moodle plugin, you consent to the use of the Plagium API and the sending of your user email, assignment ID, and assignment files or text to Plagium for analysis.</p>
<p>We are committed to protecting your privacy and will only use your information for the purpose of checking for plagiarism in your assignment. If you have any questions or concerns about our privacy policy, please contact us at support@plagium.com.</p>";

$string['privacy:metadata:core_files'] = 'Fichiers et texte en ligne qui ont été soumis à l\'aide du plugin Plagium.';
$string['privacy:metadata:plagiarism_plagium'] = 'Stocke les données Plagium.';
$string['privacy:metadata:plagiarism_plagium:id'] = 'ID pour chaque entrée dans la table plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:content'] = 'L\'évaluation du contenu.';
$string['privacy:metadata:plagiarism_plagium:user_id'] = 'L\'identifiant de l\'utilisateur du système Moodle.';
$string['privacy:metadata:plagiarism_plagium:plagium_status'] = 'Le statut du registre dans le système Plagium.';
$string['privacy:metadata:plagiarism_plagium:status'] = 'Le statut du registre dans la table plagiarism_plagium.';
$string['privacy:metadata:plagiarism_plagium:cm_id'] = 'L\'ID du contexte où le devoir a été créé.';
$string['privacy:metadata:plagiarism_plagium:module'] = 'Fichier ou devoir';
$string['privacy:metadata:plagiarism_plagium:module_id'] = 'FileId ou Assignment Id.';
$string['privacy:metadata:plagiarism_plagium:meta'] = 'Méta données du fichier ou texte';

$string['privacy:metadata:plagiarism_plagium:firstname'] = 'Le prénom de l\'utilisateur.';
$string['privacy:metadata:plagiarism_plagium:lastname'] = 'Le nom de famille de l\'utilisateur.';
$string['privacy:metadata:plagiarism_plagium:email'] = 'L\'email de l\'utilisateur.';
$string['privacy:metadata:plagiarism_plagium:filename'] = 'Les données du fichier.';
$string['privacy:metadata:plagiarism_plagium:file'] = 'Le nom du fichier.';

$string['plagium_status'] = "Activer le plagium";
$string['plagium_status_help'] = "Activer le plagium";

$string['coursemodule_status'] = "Activer le plagium";
$string['coursemodule_status_help'] = "Activer le plagium";
$string['active'] = "Actif";
$string['inactive'] = "Inactif";

$string['privacy:metadata:plagiarism_plagium_config'] = 'Stocke la configuration Plagium pour les données du cours.';
$string['privacy:metadata:plagiarism_plagium_config:id'] = 'ID pour chaque entrée dans la table plagiarism_plagium_config.';
$string['privacy:metadata:plagiarism_plagium_config:cm'] = 'L\'identifiant de l\'évaluation.';
$string['privacy:metadata:plagiarism_plagium_config:user_id'] = 'Statut actif ou inactif.';
