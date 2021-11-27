<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_graph',
        'label' => 'label',
        'label_alt' => 'iri',
        'label_alt_force' => 1,
        'default_sortby' => 'label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'label,comment',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_graph.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, iri, label, comment, statements',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, iri, label, comment, statements'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'iri' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_graph.iri',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                'filter' => [
                    [
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => [
                            'field' => 'iri',
                            'default' => 1
                         ],
                    ],
                ],
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                // 'foreign_table' => 'tx_lod_domain_model_iri',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'label,comment,value',
                    ],
                    'tx_lod_domain_model_iri' => [
                        'searchCondition' => 'type = 1',
                    ],
                ],
                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                ],
                'fieldControl' => [
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
                    'editPopup' => [
                        'disabled' => false,
                    ]
                ],
            ],
        ],
        'label' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_graph.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'comment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_graph.comment',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ],
        ],
        'statements' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_graph.statements',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_lod_domain_model_statement',
                'foreign_field' => 'graph',
                'foreign_sortby' => 'graph_sorting',
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
            ],
        ],
    ],
];
