<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary',
        'label' => 'label',
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
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_vocabulary.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, iri, label, comment, terms',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, iri, label, comment, terms'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
            ],
        ],
        'iri' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.iri',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                'filter' => [
                    [
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => [
                            'type' => '1',
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
                    'addRecord' => [
                        'disabled' => false,
                        'options' => [
                            'table' => 'tx_lod_domain_model_iri',
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
        'label' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'comment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.comment',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ],
        ],
        'terms' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.terms',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_iri',
                'MM' => 'tx_lod_vocabulary_iri_mm',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'label,comment,value',
                    ],
                ],
                'fieldWizard' => [
                    'tableList' => [
                        'disabled' => true,
                    ],
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                        'options' => [
                            'table' => 'tx_lod_domain_model_iri',
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
    ],
];
