<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// PLUGINS

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Digicademy.lod',
    'Vocabulary',
    array(
        'Vocabulary' => 'show',
    ),
    array(
        'Vocabulary' => '',
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Digicademy.lod',
    'Api',
    array(
        'Api' => 'about',
    ),
    array(
        'Api' => 'about',
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Digicademy.lod',
    'Serializer',
    array(
        'Serializer' => 'iri',
    ),
    array(
        'Serializer' => '',
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

    // register tcemain hooks for bnode generation
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'Digicademy\Lod\Hooks\Backend\DataHandler';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'Digicademy\Lod\Hooks\Backend\DataHandler';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][138471234123] = [
       'nodeName' => 'addRecordWithIconIdentifier',
       'priority' => 30,
       'class' => Digicademy\Lod\Backend\Form\FieldControl\AddRecord::class
    ];
}

// register signal/slot for ItemMappingService
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
  TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper::class,
  'afterMappingSingleRow',
  Digicademy\Lod\Service\ItemMappingService::class,
  'mapGenericProperty'
);

// exclude extension parameters from cHash generation
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[iri]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[page]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[limit]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[query]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[subject]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[predicate]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[object]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_lod_api[sorting]';
