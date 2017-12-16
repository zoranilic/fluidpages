<?php
namespace FluidTYPO3\Fluidpages\Provider;

/*
 * This file is part of the FluidTYPO3/Fluidpages project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Provider\ProviderInterface;

/**
 * PageLanguageOverlay SubConfiguration Provider
 *
 * This Provider has a slightly lower priority
 * than the main PageProvider but will trigger
 * on any selection in the targeted field,
 * including when "parent decides" is selected.
 *
 * This lets the PageProvider act on records
 * that define a specific action to use and the
 * SubPageProvider act on all other page records.
 */
class SubPageLanguageOverlayProvider extends PageLanguageOverlayProvider implements ProviderInterface
{

    /**
     * @var string
     */
    protected $fieldName = self::FIELD_NAME_SUB;

    /**
     * @param array $row
     * @return string
     */
    public function getControllerActionReferenceFromRecord(array $row)
    {

        $pid = $row['pid'] ?? $row[0]['pid'];

        if(empty($pid) && isset($row['doktype'])) {
            return 'render';
        } elseif(empty($row)) {
            return 'error';
        }

        $pageRow = $this->recordService->getSingle('pages', '*', $pid);
        if (true === empty($pageRow[self::FIELD_ACTION_SUB])) {
            $pageRow = $this->pageService->getPageTemplateConfiguration($pageRow['uid']);
        }
        return $pageRow[self::FIELD_ACTION_SUB];
    }
}
