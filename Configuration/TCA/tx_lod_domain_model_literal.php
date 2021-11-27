<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal',
        'label' => 'value',
        'default_sortby' => 'value',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'type,value,lang,datatype,',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_literal.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, type, value, language, datatype',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, --palette--;;value'],
    ],
    'palettes' => [
        'value' => [
            'showitem' => 'value, language, datatype'
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
        'value' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal.value',
            'config' => [
                'type' => 'text',
                'cols' => '25',
                'rows' => '5',
                'eval' => 'required,trim'
            ],
        ],
        'language' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal.language',
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
        'datatype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal.datatype',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                    ['Core types', '--div--'],
                    ['string', 'http://www.w3.org/2001/XMLSchema#string'],
                    ['boolean', 'http://www.w3.org/2001/XMLSchema#boolean'],
                    ['decimal', 'http://www.w3.org/2001/XMLSchema#decimal'],
                    ['integer', 'http://www.w3.org/2001/XMLSchema#integer'],
                    ['IEEE floating-point numbers', '--div--'],
                    ['double', 'http://www.w3.org/2001/XMLSchema#double'],
                    ['float', 'http://www.w3.org/2001/XMLSchema#float'],
                    ['Time and date', '--div--'],
                    ['date', 'http://www.w3.org/2001/XMLSchema#date'],
                    ['time', 'http://www.w3.org/2001/XMLSchema#time'],
                    ['dateTime', 'http://www.w3.org/2001/XMLSchema#dateTime'],
                    ['dateTimeStamp', 'http://www.w3.org/2001/XMLSchema#dateTimeStamp'],
                    ['Recurring and partial dates', '--div--'],
                    ['gYear', 'http://www.w3.org/2001/XMLSchema#gYear'],
                    ['gMonth', 'http://www.w3.org/2001/XMLSchema#gYear'],
                    ['gDay', 'http://www.w3.org/2001/XMLSchema#gDay'],
                    ['gYearMonth', 'http://www.w3.org/2001/XMLSchema#gYearMonth'],
                    ['gMonthDay', 'http://www.w3.org/2001/XMLSchema#gMonthDay'],
                    ['duration', 'http://www.w3.org/2001/XMLSchema#duration'],
                    ['yearMonthDuration', 'http://www.w3.org/2001/XMLSchema#yearMonthDuration'],
                    ['dayTimeDuration', 'http://www.w3.org/2001/XMLSchema#dayTimeDuration'],
                    ['Limited-range integer numbers', '--div--'],
                    ['byte', 'http://www.w3.org/2001/XMLSchema#byte'],
                    ['short', 'http://www.w3.org/2001/XMLSchema#short'],
                    ['int', 'http://www.w3.org/2001/XMLSchema#int'],
                    ['long', 'http://www.w3.org/2001/XMLSchema#long'],
                    ['unsignedByte', 'http://www.w3.org/2001/XMLSchema#unsignedByte'],
                    ['unsignedShort', 'http://www.w3.org/2001/XMLSchema#unsignedShort'],
                    ['unsignedInt', 'http://www.w3.org/2001/XMLSchema#unsignedInt'],
                    ['unsignedLong', 'http://www.w3.org/2001/XMLSchema#unsignedLong'],
                    ['positiveInteger', 'http://www.w3.org/2001/XMLSchema#positiveInteger'],
                    ['nonNegativeInteger', 'http://www.w3.org/2001/XMLSchema#nonNegativeInteger'],
                    ['negativeInteger', 'http://www.w3.org/2001/XMLSchema#negativeInteger'],
                    ['nonPositiveInteger', 'http://www.w3.org/2001/XMLSchema#nonPositiveInteger'],
                    ['Encoded binary data', '--div--'],
                    ['hexBinary', 'http://www.w3.org/2001/XMLSchema#hexBinary'],
                    ['base64Binary', 'http://www.w3.org/2001/XMLSchema#base64Binary'],
                    ['Miscellaneous XSD types', '--div--'],
                    ['anyURI', 'http://www.w3.org/2001/XMLSchema#anyURI'],
                    ['language', 'http://www.w3.org/2001/XMLSchema#language'],
                    ['normalizedString', 'http://www.w3.org/2001/XMLSchema#normalizedString'],
                    ['token', 'http://www.w3.org/2001/XMLSchema#token'],
                    ['NMTOKEN', 'http://www.w3.org/2001/XMLSchema#NMTOKEN'],
                    ['Name', 'http://www.w3.org/2001/XMLSchema#Name'],
                    ['NCName', 'http://www.w3.org/2001/XMLSchema#NCName'],
                    ['HTML and XML', '--div--'],
                    ['html', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#HTML'],
                    ['xml', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#XMLLiteral'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
    ],
];
