<?php
defined('TYPO3_MODE') or die();

$tca = array(
    'statements' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement',
        'config' => array(
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'tx_lod_domain_model_statement',
            'foreign_table' => 'tx_lod_domain_model_statement',
            'MM' => 'tx_lod_statement_record_mm',
            'MM_match_fields' => array(
                // ident field as workaround for possible bug in line 544 of \TYPO3\CMS\Core\Database\RelationHandler
                // tablenames must be name of foreign table (statements) whereas ident is needed to distinguish between records from different tables
                // otherwise relations are not kept/displayed correctly in this field
                'ident' => 'tt_content',
                'tablenames' => 'tx_lod_domain_model_statement',
                'fieldname' => 'statements',
            ),
            'size' => 10,
            'minitems' => 0,
            'maxitems' => 9999,
            'wizards' => array(
                'add' => Array(
                    'type' => 'popup',
                    'title' => 'Create new',
                    'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                    'params' => array(
                        'table' => 'tx_lod_domain_model_statement',
                        'pid' => '###PAGE_TSCONFIG_ID###',
                        'setValue' => 'prepend'
                    ),
                    'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                    'module' => array(
                        'name' => 'wizard_add',
                    ),
                ),
                'edit' => Array(
                    'type' => 'popup',
                    'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:wizard_edit',
                    'icon' => 'actions-open',
                    'JSopenParams' => 'height=550,width=900,status=0,menubar=0,scrollbars=1',
                    'popup_onlyOpenIfSelected' => 1,
                    'module' => array(
                        'name' => 'wizard_edit',
                    ),
                ),
            ),
        ),
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tca);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'statements',
    '',
    ''
);
