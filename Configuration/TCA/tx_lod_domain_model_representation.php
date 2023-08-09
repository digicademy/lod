<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation',
        'label' => 'content_type',
        'default_sortby' => 'ORDER BY parent',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'content_type, content_language, parameters',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_representation.svg'
    ],
    'interface' => [
        'showRecordFieldList' => '
            hidden,
            parent,
            scheme,
            authority,
            path,
            query,
            fragment,
            content_type,
            content_language
        ',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                hidden,
                parent,
                scheme,
                authority,
                path,
                query,
                fragment,
                content_type,
                content_language,
            '
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
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
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'parent' => [
            'config' => [
/*
                'type' => 'passthrough'
*/
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_iri',
                'prepend_tname' => 0,
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'scheme' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.scheme',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'required,trim'
            ],
        ],
        'authority' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.authority',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'required,trim'
            ],
        ],
        'path' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.path',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '5',
                'eval' => 'trim'
            ],
        ],
        'query' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.query',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '5',
                'eval' => 'trim'
            ],
        ],
        'fragment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.fragment',
            'config' => [
                'type' => 'text',
                'cols' => '50',
                'rows' => '5',
                'eval' => 'trim'
            ],
        ],
        'content_type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.content_type',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ],
        ],
        'content_language' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.content_language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'items' => [
                    ['', '']
                ],
                'itemsProcFunc' => \TYPO3\CMS\Core\Service\IsoCodeService::class . '->renderIsoCodeSelectDropdown',
            ],
        ],
    ],
];
