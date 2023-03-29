<?php

$capabilities = array(
    //capability to enable/disable plagium inside an activity
    'plagiarism/plagium:enable' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
        )
    ),
    // Ability to get all controller links to e.g. to submit/resubmit
    'plagiarism/plagium:control' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => array(
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'teacher' => CAP_ALLOW
        )
    ),
    //capability to view full reports
    'plagiarism/plagium:viewfullreport' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => array(
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'teacher' => CAP_ALLOW
        )
    ),
);
// Take into consideration to allow the capability to show the report link to the student