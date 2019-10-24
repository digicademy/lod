<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// TYPOSCRIPT

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'lod', 'Configuration/TypoScript', 'LOD'
);

// TSCONFIG

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:lod/Configuration/TSConfig/setup.txt">
');

// PLUGINS

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'Terms',
    'LOD: Selected Terms'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'Vocab',
    'LOD: Selected Vocabulary'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Digicademy.lod',
    'About',
    'LOD: About API'
);

// FLEXFORMS

$TCA['tt_content']['types']['list']['subtypes_addlist']['lod_term'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_term', 'FILE:EXT:lod/Configuration/FlexForms/TermsPlugin.xml');

$TCA['tt_content']['types']['list']['subtypes_addlist']['lod_vocab'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_vocab', 'FILE:EXT:lod/Configuration/FlexForms/VocabPlugin.xml');

// TABLES

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_namespace');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_iri');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_bnode');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_literal');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_statement');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_term');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_vocabulary');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lod_domain_model_representation');
