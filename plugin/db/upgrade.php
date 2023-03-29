<?php

function xmldb_plagiarism_plagium_upgrade($oldversion) 
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012031401) {
        // Rename 'plagium' table to 'plagiarism_plagium' (to meet Moodle guidelines)
        $table = new xmldb_table('plagium');
        $dbman->rename_table($table, 'plagiarism_plagium');

        // Rename 'plagium_config' table to 'plagiarism_plagium_config' (to meet Moodle guidelines)
        $table = new xmldb_table('plagium_config');
        $dbman->rename_table($table, 'plagiarism_plagium_config');

        // plagium savepoint reached
        upgrade_plugin_savepoint(true, 2012031401, 'plagiarism', 'plagium');
    }

    return true;
}
