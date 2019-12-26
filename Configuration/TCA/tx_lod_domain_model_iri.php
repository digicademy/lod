<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri',
        'label' => 'label',
        'label_alt' => 'value',
        'default_sortby' => 'type ASC, label ASC, value ASC',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'origUid' => 't3_origuid',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'type' => 'type',
        'typeicon_column' => 'type',
        'typeicon_classes' => [
            'default' => 'tx_lod_domain_model_iri',
            '1' => 'tx_lod_type_entity',
            '2' => 'tx_lod_type_property',
        ],
        'searchFields' => 'label,comment,value',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_iri.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => '
            hidden, 
            type, 
            label, 
            comment, 
            namespace,
            value, 
            record,
            representations,
            statements',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, value, type, namespace, label, comment, record, representations, statements, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
        '2' => array('showitem' => 'hidden, value, type, namespace, label, comment, record, representations, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
        'label' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.label',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'comment' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.comment',
            'config' => array(
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ),
        ),
        'type' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.type.I.1', 1, 'tx_lod_type_entity'),
                    array('LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.type.I.2', 2, 'tx_lod_type_property'),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'namespace' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.namespace',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_lod_domain_model_namespace',
                'foreign_table_where' => 'AND tx_lod_domain_model_namespace.pid IN (###PAGE_TSCONFIG_IDLIST###) ORDER BY tx_lod_domain_model_namespace.prefix',
                'items' => array(
                    array('', 0),
                ),
                'size' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    'add' => Array(
                        'type' => 'popup',
                        'title' => 'Create new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_namespace',
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
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                ),
            ),
        ),
        'value' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.value',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                // 'eval' => 'required,trim,unique' // for system wide unique IRIs
                // 'readOnly' => 1, // recommended if id generator is used
            ),
        ),
        'record' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.record',
            'config' => array(
                'type' => 'group',
                'allowed' => '*',
                'internal_type' => 'db',
                'prepend_tname' => true,
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'right',
                    'edit' => Array(
                        'type' => 'popup',
                        'title' => 'Edit',
                        'icon' => 'actions-open',
                        'JSopenParams' => 'height=550,width=900,status=0,menubar=0,scrollbars=1',
                        'popup_onlyOpenIfSelected' => 1,
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                ),
            )
        ),
        'representations' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.representations',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_lod_domain_model_representation',
                'foreign_field' => 'parent',
                'minitems' => 0,
                'maxitems' => 999,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'statements' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_iri.statements',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_lod_domain_model_statement',
                'foreign_field' => 'subject_uid',
                'foreign_table_field' => 'subject_type',
                'foreign_sortby' => 'iri_sorting',
                'maxitems' => 9999,
                'appearance' => array(
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'newRecordLinkAddTitle' => 1,
                    'useSortable' => 1,
                ),
                'behaviour' => array(
                    'disableMovingChildrenWithParent' => 1,
                ),
            ),
        ),
    ),
);
