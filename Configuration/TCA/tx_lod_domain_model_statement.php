<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement',
        'label' => 'subject',
        'label_alt' => 'predicate,object',
        'label_alt_force' => 1,
        'default_sortby' => 'graph',
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
        'searchFields' => 'subject,predicate,object,name',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_statement.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, subject, subject_type, subject_uid, predicate, predicate_type, predicate_uid, object, object_type, object_uid, graph',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, --palette--;;triple, graph, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
    ),
    'palettes' => array(
        'triple' => array(
            'showitem' => 'subject, predicate, object'
        ),
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
        'graph' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.graph',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_graph',
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_graph',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'right',
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label',
                        ],
                    ),
                    'add' => Array(
                        'type' => 'popup',
                        'title' => 'Create new graph',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_graph',
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
        'subject' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.subject',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_bnode',
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '1',
                         ),
                    ),
                ),
                'prepend_tname' => 1,
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'top',
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
                    'add_subject_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new IRI',
                        'icon' => 'tx_lod_actions_add_iri',
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
                    'add_subject_bnode' => Array(
                        'type' => 'popup',
                        'title' => 'Create new blank node',
                        'icon' => 'tx_lod_actions_add_bnode',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_bnode',
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
        'predicate' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.predicate',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_iri',
                'prepend_tname' => 1,
                'size' => 1,
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '2',
                         ),
                    ),
                ),
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'top',
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 2',
                        ],
                    ),
                    'add_predicate_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new',
                        'icon' => 'tx_lod_actions_add_iri',
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
        'object' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.object',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_literal,tx_lod_domain_model_bnode',
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '1',
                         ),
                    ),
                ),
                'prepend_tname' => 1,
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    '_POSITION' => 'top',
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_literal' => [
                            'additionalSearchFields' => 'value,datatype,language',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 1',
                        ],
                    ),
                    'add_object_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new IRI',
                        'icon' => 'tx_lod_actions_add_iri',
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
                    'add_object_literal' => Array(
                        'type' => 'popup',
                        'title' => 'Create new literal',
                        'icon' => 'tx_lod_actions_add_literal',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_literal',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'add_object_bnode' => Array(
                        'type' => 'popup',
                        'title' => 'Create new blank node',
                        'icon' => 'tx_lod_actions_add_bnode',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_bnode',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'edit' => Array(
                        'type' => 'popup',
                        'title' => 'Edit record',
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
        'subject_type' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'subject_uid' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'predicate_type' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'predicate_uid' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'object_type' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'object_uid' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
    ),
);
