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
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
            ],
        ],
        'parent' => [
            'config' => [
                'type' => 'passthrough'
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
                'type' => 'input',
                'size' => 50,
                'eval' => 'required,trim'
            ],
        ],
        'query' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.query',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ],
        ],
        'fragment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.fragment',
            'config' => [
                'type' => 'input',
                'size' => 20,
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
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_language.language_isocode',
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
