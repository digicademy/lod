<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_bnode',
        'label' => 'label',
        'label_alt' => 'value',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'origUid' => 't3_origuid',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'label,comment,value',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_bnode.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, value, label, comment, statements',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, value, label, comment, statements'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
            ],
        ],
        'label' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_bnode.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'comment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_bnode.comment',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ],
        ],
        'value' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_bnode.value',
            'config' => [
                'type' => 'input',
                'readOnly' => 1,
                'size' => 30,
                'eval' => 'required,trim,unique'
            ],
        ],
        'statements' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_bnode.statements',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_lod_domain_model_statement',
                'foreign_field' => 'subject_uid',
                'foreign_table_field' => 'subject_type',
                'foreign_sortby' => 'bnode_sorting',
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
                            'showitem' => '--palette--;;recursion, --palette--;;PredicateObject, graph'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
