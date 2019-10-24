<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal',
        'label' => 'value',
        'default_sortby' => 'value',
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
        'searchFields' => 'type,value,lang,datatype,',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_literal.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, type, value, language, datatype',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, value, language, datatype, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
        'value' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal.value',
            'config' => array(
                'type' => 'text',
                'cols' => '25',
                'rows' => '5',
                'eval' => 'required,trim'
            ),
        ),
        'language' => array(
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
        'datatype' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_literal.datatype',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', ''),
                    array('boolean', 'http://www.w3.org/2000/01/rdf-schema#boolean'),
                    array('decimal', 'http://www.w3.org/2001/XMLSchema#decimal'),
                    array('date', 'http://www.w3.org/2000/01/rdf-schema#date'),
                    array('real', 'http://www.w3.org/2000/01/rdf-schema#real'),
                    array('string', 'http://www.w3.org/2000/01/rdf-schema#string'),
                ),
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ),
        ),
    ),
);
