<?php
defined('TYPO3_MODE') or die();

$tca = [
    'iri' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_lod_domain_model_iri',
            'foreign_field' => 'record_uid',
            'foreign_table_field' => 'record_tablename',
            'maxitems' => 1,
            'appearance' => [
                'collapseAll' => 1,
                'expandSingle' => 1,
                'levelLinksPosition' => 'bottom',
                'newRecordLinkAddTitle' => 1,
            ],
            'behaviour' => [
                'disableMovingChildrenWithParent' => 1,
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tca);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'iri',
    '',
    ''
);
