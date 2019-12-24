<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary',
        'label' => 'label',
        'default_sortby' => 'label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'label,comment',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_vocabulary.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, iri, label, comment, terms',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, iri, label, comment, terms, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'iri' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.iri',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '1',
                         ),
                    ),
                ),
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                // 'foreign_table' => 'tx_lod_domain_model_iri',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'right',
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 1',
                        ],
                    ),
                    'add_vocabulary_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new IRI',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'Edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                ),
            ),
        ),
        'label' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.label',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'comment' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.comment',
            'config' => array(
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ),
        ),
        'terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.terms',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_lod_domain_model_graph',
                'foreign_table_where' => 'AND tx_lod_domain_model_graph.pid IN (###PAGE_TSCONFIG_IDLIST###) ORDER BY tx_lod_domain_model_graph.label',
                'MM' => 'tx_lod_vocabulary_graph_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
            ),
        ),
    ),
);
