<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Linked Open Data',
    'description' => 'LOD layer for TYPO3',
    'category' => 'fe',
    'author' => 'Torsten Schrade',
    'author_email' => 'Torsten.Schrade@adwmainz.de',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '7.6.0-9.5.99'
        ),
        'conflicts' => array(),
        'suggests' => array(
            'typo3db_legacy' => ''
        ),
    ),
);
