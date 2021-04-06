<?php
declare(strict_types=1);
namespace Digicademy\Lod\Backend\Form\FieldWizard;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Copy of the core table list wizard with the modification that it decouples record browser display
 * from table lists; search for @metacontext to find changes
 */
class EnhancedTableList extends AbstractNode
{
    /**
     * Render table buttons
     *
     * @return array
     */
    public function render(): array
    {
        $languageService = $this->getLanguageService();
        $result = $this->initializeResultArray();

        $parameterArray = $this->data['parameterArray'];
        $config = $parameterArray['fieldConf']['config'];
        $itemName = $parameterArray['itemFormElName'];

        if (empty($config['allowed']) || !is_string($config['allowed']) || !isset($config['internal_type']) || $config['internal_type'] !== 'db') {
            // No handling if the field has no, or funny "allowed" setting and if internal_type is not "db"
            return $result;
        }

        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $allowed = GeneralUtility::trimExplode(',', $config['allowed'], true);
        $allowedTablesHtml = [];
        foreach ($allowed as $tableName) {
            if ($tableName === '*') {
                $label = $languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.allTables');
                $allowedTablesHtml[] = '<span>';
                $allowedTablesHtml[] =  htmlspecialchars($label);
                $allowedTablesHtml[] = '</span>';
            } else {
                $label = $languageService->sL($GLOBALS['TCA'][$tableName]['ctrl']['title']);
                $icon = $iconFactory->getIconForRecord($tableName, [], Icon::SIZE_SMALL)->render();
                // @metacontext: decouple element browser from linked record lists
                /*
                if ((bool)($config['fieldControl']['elementBrowser']['disabled'] ?? false)) {
                    $allowedTablesHtml[] = '<span class="tablelist-item-nolink">';
                    $allowedTablesHtml[] =  $icon;
                    $allowedTablesHtml[] =  htmlspecialchars($label);
                    $allowedTablesHtml[] = '</span>';
                } else {
                */
                    $allowedTablesHtml[] = '<a href="#" class="btn btn-default t3js-element-browser" data-mode="db" data-params="' . htmlspecialchars($itemName . '|||' . $tableName) . '">';
                    $allowedTablesHtml[] =  $icon;
                    $allowedTablesHtml[] =  htmlspecialchars($label);
                    $allowedTablesHtml[] = '</a>';
                //}
            }
        }

        $html = [];
        $html[] = '<div class="help-block">';
        $html[] =   implode(LF, $allowedTablesHtml);
        $html[] = '</div>';

        $result['html'] = implode(LF, $html);
        return $result;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
