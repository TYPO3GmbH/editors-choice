<?php
declare(strict_types=1);

namespace T3G\AgencyPack\EditorsChoice\FormEngine;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class ReferencesToThisRecordWizard
 *
 * @package T3G\TemplateTypo3com\FormEngine
 */
class ReferencesToThisRecordWizard extends AbstractNode
{

    /**
     * Handler for single nodes
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render(): array
    {
        $result = $this->initializeResultArray();
        $references = $this->data['customData']['EditorsChoice']['References'];
        if (count($references) > 0) {
            $result['html'] = $this->getHtml($references);
        }
        return $result;
    }

    /**
     * Render HTML for the wizard via fluid
     *
     * @param array $references
     * @return string
     */
    protected function getHtml(array $references): string
    {
        /** @var StandaloneView $view */
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:editors_choice/Resources/Private/Templates/FormEngine/References.html');
        $view->assign('refLines', $references);
        return $view->render();
    }

}
