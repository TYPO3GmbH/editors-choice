<?php
declare(strict_types=1);

/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\EditorsChoice\FormEngine\FieldWizard;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Render a table showing and linking elements that reference this record
 * or are translations of this record.
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
        $references = $this->data['customData']['editorsChoice']['references'] ?? [];
        if (\count($references) > 0) {
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
