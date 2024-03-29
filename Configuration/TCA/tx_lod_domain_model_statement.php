<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement',
        'label' => 'subject',
        'label_alt' => 'predicate,object',
        'label_alt_force' => 1,
        'default_sortby' => 'graph,subject',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'subject,predicate,object,name',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_statement.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, subject, subject_type, subject_uid, predicate, predicate_type, predicate_uid, object, object_type, object_uid, object_recursion, object_inversion, graph',
    ],
    'types' => [
        '1' => ['showitem' => '--palette--;;SubjectPredicateObject, reference_statements, graph, --palette--;;flags, sys_language_uid'],
    ],
    'palettes' => [
        'flags' => [
            'showitem' => 'hidden, object_inversion, object_recursion'
        ],
        'SubjectPredicateObject' => [
            'showitem' => 'subject, predicate, object'
        ],
        'PredicateObject' => [
            'showitem' => 'predicate, object'
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => -1,
                'readOnly' => 1,
            ]
        ],
        'graph' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.graph',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_graph',
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_graph',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'label',
                    ],
                ],
                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                    'enhancedTableList' => [
                        'renderType' => 'enhancedTableList',
                    ],
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],
                'fieldControl' => [
                    'elementBrowser' => [
                        'disabled' => true,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                        'options' => [
                            'table' => 'tx_lod_domain_model_graph',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ],
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'subject' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.subject',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_bnode,tx_lod_domain_model_statement',
                'filter' => [
                    [
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => [
                            'field' => 'subject',
                            'default' => '1,2'
                         ],
                    ],
                ],
                'prepend_tname' => 1,
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'value',
                    ],
                    'tx_lod_domain_model_iri' => [
// @TODO: make this configurable per TSConfig per page
/*
                        'searchCondition' => 'type = 1',
*/
                        'additionalSearchFields' => 'prefix_value,label,comment,value',
                    ],
                    'tx_lod_domain_model_bnode' => [
                        'additionalSearchFields' => 'label,comment,value',
                    ],
                    'tx_lod_domain_model_statement' => [
                        'additionalSearchFields' => '',
                    ],
                ],

                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                    'enhancedTableList' => [
                        'renderType' => 'enhancedTableList',
                    ],
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],

                'fieldControl' => [
                    'elementBrowser' => [
                        'disabled' => true,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    '01_addIri' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new IRI',
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_iri',
                        ],
                    ],
                    '02_addBnode' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new blank node',
                            'table' => 'tx_lod_domain_model_bnode',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_bnode',
                        ],
                    ],
                ],
            ],
        ],
        'predicate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.predicate',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_iri',
                'prepend_tname' => 1,
                'size' => 1,
/*
                'filter' => [
                    [
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => [
                            'field' => 'predicate',
                            'default' => 2
                         ],
                    ],
                ],
*/
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'prefix_value,label,comment,value',
                    ],
                    'tx_lod_domain_model_iri' => [
                        'searchCondition' => 'type = 2',
                    ],
                ],

                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                    'enhancedTableList' => [
                        'renderType' => 'enhancedTableList',
                    ],
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],

                'fieldControl' => [
                    'elementBrowser' => [
                        'disabled' => true,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    '01_addIri' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new IRI',
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_iri',
                        ],
                    ],
                ],
            ],
        ],
        'object' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.object',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_literal,tx_lod_domain_model_bnode,tx_lod_domain_model_statement',
                'filter' => [
                    [
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => [
                            'field' => 'object',
                            'default' => '1,2'
                         ],
                    ],
                ],
                'prepend_tname' => 1,
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'label',
                    ],
                    'tx_lod_domain_model_iri' => [
// @TODO: make this configurable per TSConfig per page
/*
                        'searchCondition' => 'type = 1',
*/
                        'additionalSearchFields' => 'prefix_value,label,comment,value',
                    ],
                    'tx_lod_domain_model_bnode' => [
                        'additionalSearchFields' => 'label,comment,value',
                    ],
                    'tx_lod_domain_model_literal' => [
                        'additionalSearchFields' => 'value',
                    ],
                    'tx_lod_domain_model_statement' => [
                        'additionalSearchFields' => '',
                    ],
                ],

                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                    'enhancedTableList' => [
                        'renderType' => 'enhancedTableList',
                    ],
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],

                'fieldControl' => [
                    'elementBrowser' => [
                        'disabled' => true,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    '01_addIri' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new IRI',
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_iri',
                        ],
                    ],
                    '02_addBnode' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new blank node',
                            'table' => 'tx_lod_domain_model_bnode',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_bnode',
                        ],
                    ],
                    '03_addLiteral' => [
                        'renderType' => 'enhancedAddRecord',
                        'options' => [
                            'title' => 'Create new literal',
                            'table' => 'tx_lod_domain_model_literal',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set',
                            'iconIdentifier' => 'tx_lod_actions_add_literal',
                        ],
                    ],
                ],
            ],
        ],
        'reference_statements' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.reference_statements',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_lod_domain_model_statement',
                'foreign_field' => 'subject_uid',
                'foreign_table_field' => 'subject_type',
                'foreign_sortby' => 'iri_sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'newRecordLinkAddTitle' => 1,
                    'useSortable' => 1,
                ],
                'behaviour' => [
                    'disableMovingChildrenWithParent' => 1,
                ],
                'overrideChildTca' => [
                    'types' => [
                        '1' => [
                            'showitem' => '--palette--;;PredicateObject, --palette--;;flags'
                        ],
                    ],
                ],
            ],
        ],
        'object_recursion' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.object_recursion',
            'config' => [
                'type' => 'check',
            ],
        ],
        'object_inversion' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.object_inversion',
            'config' => [
                'type' => 'check',
            ],
        ],
        'subject_type' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'subject_uid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'predicate_type' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'predicate_uid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'object_type' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'object_uid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
