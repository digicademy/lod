<?php

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['lod_vocabulary'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_vocabulary', 'FILE:EXT:lod/Configuration/FlexForms/VocabularyPlugin.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['lod_serializer'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('lod_serializer', 'FILE:EXT:lod/Configuration/FlexForms/SerializerPlugin.xml');
