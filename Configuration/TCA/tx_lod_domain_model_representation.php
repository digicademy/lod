<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation',
        'label' => 'content_type',
        'default_sortby' => 'ORDER BY parent',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
        ),
        'searchFields' => 'content_type, content_language, parameters',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_representation.svg'
    ),
    'interface' => array(
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
    ),
    'types' => array(
        '1' => array(
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
        ),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'parent' => array(
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'scheme' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.scheme',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'required,trim'
            ),
        ),
        'authority' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.authority',
            'config' => array(
                'type' => 'input',
                'size' => 50,
                'eval' => 'required,trim'
            ),
        ),
        'path' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.path',
            'config' => array(
                'type' => 'input',
                'size' => 50,
                'eval' => 'required,trim'
            ),
        ),
        'query' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.query',
            'config' => array(
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ),
        ),
        'fragment' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.fragment',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim'
            ),
        ),
        'content_type' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_representation.content_type',
            'config' => array(
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ),
        ),
        'content_language' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_language.language_isocode',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'items' => [
                    ['', '']
                ],
                'itemsProcFunc' => \TYPO3\CMS\Core\Service\IsoCodeService::class . '->renderIsoCodeSelectDropdown',
            ),
        ),
    ),
);
