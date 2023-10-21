<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

// PLUGINS

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Lod',
    'Vocabulary',
    array(
        \Digicademy\Lod\Controller\VocabularyController::class => 'show',
    ),
    array(
        \Digicademy\Lod\Controller\VocabularyController::class => '',
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Lod',
    'Api',
    array(
        \Digicademy\Lod\Controller\ApiController::class => 'about',
    ),
    array(
        \Digicademy\Lod\Controller\ApiController::class => 'about',
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Lod',
    'Serializer',
    array(
        \Digicademy\Lod\Controller\SerializerController::class => 'iri',
    ),
    array(
        \Digicademy\Lod\Controller\SerializerController::class => '',
    )
);

// REGISTERES URI RESOLVER

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lod']['resolver'] = [
    't3' => Digicademy\Lod\Resolver\T3Resolver::class,
    'http' => Digicademy\Lod\Resolver\HttpResolver::class,
    'https' => Digicademy\Lod\Resolver\HttpsResolver::class,
];

// TYPE ICONS

if (TYPO3_MODE === 'BE') {

    // register icons for IRIs
    $icons = [
        'ext-lod-type-default' => 'tx_lod_domain_model_iri.svg',
        'ext-lod-type-class' => 'tx_lod_domain_model_iri.svg',
        'ext-lod-type-property' => 'tx_lod_domain_model_property.svg',
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($icons as $identifier => $path) {
        if (!$iconRegistry->isRegistered($identifier)) {
            $iconRegistry->registerIcon(
                $identifier,
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:lod/Resources/Public/Icons/' . $path]
            );
        }
    }

    // register tcemain hooks for table tracking, statement synchronization and identifier generation
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'Digicademy\Lod\Hooks\Backend\DataHandler';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'Digicademy\Lod\Hooks\Backend\DataHandler';

    // add modified addRecord fieldControl (make it reusable for different types of records)
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][138471234123] = [
       'nodeName' => 'enhancedAddRecord',
       'priority' => 30,
       'class' => Digicademy\Lod\Backend\Form\FieldControl\EnhancedAddRecord::class
    ];

    // add modified tableList fieldWizard (take out hard coded connection to elementBrowser)
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1617617718] = [
       'nodeName' => 'enhancedTableList',
       'priority' => 30,
       'class' => Digicademy\Lod\Backend\Form\FieldWizard\EnhancedTableList::class
    ];

    // XCLASS group field to change hardcoded HTML arrangement of fieldControl
    // we don't register a new formEngine node and use XCLASS since has problems in data handling (tested)
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Backend\Form\Element\GroupElement::class] = [
       'className' => Digicademy\Lod\Backend\Form\Element\EnhancedGroupElement::class
    ];
}

// exclude extension parameters from cHash generation
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[iri]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[page]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[limit]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[query]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[subject]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[predicate]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[object]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[sorting]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[apiDocumentation]';
