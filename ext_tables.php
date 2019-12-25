<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// TYPOSCRIPT

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'lod', 'Configuration/TypoScript', 'Linked Open Data for TYPO3'
);

// TSCONFIG

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:lod/Configuration/TSConfig/setup.txt">
');

// PLUGINS

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'Term',
    'LOD: Selected Terms'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'Vocabulary',
    'LOD: Selected Vocabulary'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'Api',
    'LOD: Restful API'
);

// FLEXFORMS

$TCA['tt_content']['types']['list']['subtypes_addlist']['lod_term'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_term', 'FILE:EXT:lod/Configuration/FlexForms/TermPlugin.xml');

$TCA['tt_content']['types']['list']['subtypes_addlist']['lod_vocabulary'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_vocabulary', 'FILE:EXT:lod/Configuration/FlexForms/VocabularyPlugin.xml');

// TABLES

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_namespace');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_iri');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_bnode');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_literal');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_statement');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_term');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_vocabulary');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_representation');

// ICONS

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
   \TYPO3\CMS\Core\Imaging\IconRegistry::class
);

$iconRegistry->registerIcon(
   'tx_lod_actions_add_iri',
   \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
   ['source' => 'EXT:lod/Resources/Public/Icons/tx_lod_actions_add_iri.svg']
);

$iconRegistry->registerIcon(
   'tx_lod_actions_add_bnode',
   \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
   ['source' => 'EXT:lod/Resources/Public/Icons/tx_lod_actions_add_bnode.svg']
);

$iconRegistry->registerIcon(
   'tx_lod_actions_add_literal',
   \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
   ['source' => 'EXT:lod/Resources/Public/Icons/tx_lod_actions_add_literal.svg']
);
