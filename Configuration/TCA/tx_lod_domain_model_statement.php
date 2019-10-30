<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement',
        'label' => 'subject',
        'label_alt' => 'predicate,object',
        'label_alt_force' => 1,
        'default_sortby' => 'name',
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
        'searchFields' => 'subject,predicate,object,name',
        'iconfile' => 'EXT:lod/Resources/Public/Icons/tx_lod_domain_model_statement.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, subject, predicate, object, name',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, subject, predicate, object, name, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
        'name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.name',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_namespace',
                'prepend_tname' => false,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_namespace',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'prefix,iri',
                        ],
                    ),
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'Edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                    'add' => Array(
                        'type' => 'popup',
                        'title' => 'Create new namespace',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_namespace',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                ),
            ),
        ),
        'subject' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.subject',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_bnode',
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '1',
                         ),
                    ),
                ),
                'prepend_tname' => 1,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                // 'foreign_table' => 'tx_lod_domain_model_iri',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 1',
                        ],
                    ),
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'Edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                    'add_subject_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new IRI',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'add_subject_bnode' => Array(
                        'type' => 'popup',
                        'title' => 'Create new blank node',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_bnode',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                ),
            ),
        ),
        'predicate' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.predicate',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri',
                'prepend_tname' => 1,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                'foreign_table' => 'tx_lod_domain_model_iri',
                'size' => 1,
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '2',
                         ),
                    ),
                ),
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 2',
                        ],
                    ),
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'Edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                    'add_predicate_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                ),
            ),
        ),
        'object' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lod/Resources/Private/Language/locallang_db.xlf:tx_lod_domain_model_statement.object',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_lod_domain_model_iri,tx_lod_domain_model_literal,tx_lod_domain_model_bnode',
                'filter' => array (
                    array(
                        'userFunc' => 'Digicademy\Lod\Utility\Backend\IriUtility->filterByType',
                        'parameters' => array(
                            'type' => '1',
                         ),
                    ),
                ),
                'prepend_tname' => 1,
                // prevent http://wiki.typo3.org/Exception/CMS/1353170925
                // 'foreign_table' => 'tx_lod_domain_model_iri',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => Array(
                        'type' => 'suggest',
                        'title' => 'Find records',
                        'default' => [
                            'additionalSearchFields' => 'label,comment,value',
                        ],
                        'tx_lod_domain_model_literal' => [
                            'additionalSearchFields' => 'value,datatype,language',
                        ],
                        'tx_lod_domain_model_iri' => [
                            'searchCondition' => 'type = 1',
                        ],
                    ),
                    'edit' => Array(
                        'type' => 'popup',
                        'title' => 'Edit record',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'JSopenParams' => 'height=550,width=900,status=0,menubar=0,scrollbars=1',
                        'popup_onlyOpenIfSelected' => 1,
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                    ),
                    'add_object_iri' => Array(
                        'type' => 'popup',
                        'title' => 'Create new IRI',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_iri',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'add_object_literal' => Array(
                        'type' => 'popup',
                        'title' => 'Create new literal',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_literal',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                    'add_object_bnode' => Array(
                        'type' => 'popup',
                        'title' => 'Create new blank node',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_lod_domain_model_bnode',
                            'pid' => '###PAGE_TSCONFIG_ID###',
                            'setValue' => 'set'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => array(
                            'name' => 'wizard_add',
                        ),
                    ),
                ),
            ),
        ),
        'term' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
    ),
);
