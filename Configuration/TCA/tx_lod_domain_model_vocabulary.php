<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary',
        'label' => 'title',
        'default_sortby' => 'title',
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
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_vocabulary.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, title, description, terms',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, title, description, terms, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
        'title' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ),
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.description',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_vocabulary.terms',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_lod_domain_model_term',
                'foreign_table_where' => 'AND tx_lod_domain_model_term.pid IN (###PAGE_TSCONFIG_IDLIST###) ORDER BY tx_lod_domain_model_term.title',
                'MM' => 'tx_lod_vocabulary_term_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
            ),
        ),
    ),
);
