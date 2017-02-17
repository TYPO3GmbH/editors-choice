<?php
declare(strict_types=1);

namespace T3G\AgencyPack\EditorsChoice\FormEngine\DataProvider;

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

use T3G\AgencyPack\EditorsChoice\Repository\ReferenceRepository;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;


/**
 * Class ReferenceProvider
 * FormEngine Data Provider adding reference data as 'customData'
 *
 * @package T3G\AgencyPack\EditorsChoice\FormEngine\DataProvider
 */
class ReferenceProvider implements FormDataProviderInterface
{
    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * Available system languages
     *
     * @var array
     */
    protected $systemLanguages;

    /**
     * Add form data to result array
     *
     * @param array $result Initialized result array
     * @return array Result filled with more data
     */
    public function addData(array $result): array
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $this->systemLanguages = $result['systemLanguageRows'];
        if ($result['command'] !== 'edit') {
            return $result;
        }
        switch ($result['tableName']) {
            case 'tt_content':
                $references = $this->getContentReferences($result['vanillaUid']);
                break;
            case 'pages':
                $references = $this->getPageReferences($result['vanillaUid']);
                break;
            default:
                $references = [];
        }
        $result['customData']['EditorsChoice']['References'] = $references;
        return $result;
    }

    /**
     * Get preprocessed array with reference data for content elements
     *
     * @param int $uid uid
     * @return array
     */
    protected function getContentReferences(int $uid): array
    {
        $referenceRepository = GeneralUtility::makeInstance(ReferenceRepository::class);
        $rows = $referenceRepository->getContentReferences($uid);

        $refLines = [];
        foreach ($rows as $row) {
            if ($row['tablename'] === 'sys_file_reference') {
                return [];
            }
            $record = BackendUtility::getRecord($row['tablename'], $row['recuid']);
            if ($record) {
                $line = $this->transformRecordToLine($row, $record);

                if ($row['field'] == 'l18n_parent') {
                    $line['language'] = $this->systemLanguages[$record['sys_language_uid']]['title'];
                    $refLines['translation'][] = $line;
                } else {
                    $refLines['content'][] = $line;
                }
            }
        }
        return $refLines;
    }

    /**
     * Get preprocessed array with reference data for pages
     *
     * @param int $uid
     * @return array
     */
    protected function getPageReferences(int $uid): array
    {
        $referenceRepository = GeneralUtility::makeInstance(ReferenceRepository::class);
        $rows = $referenceRepository->getPageReferences($uid);
        $refLines = [];
        foreach ($rows as $row) {
            $record = BackendUtility::getRecord($row['tablename'], $row['recuid']);
            if ($record) {
                $line = $this->transformRecordToLine($row, $record);

                if ($row['field'] == 'shortcut') {
                    $refLines['shortcuts'][] = $line;
                } elseif ($row['field'] == 'content_from_pid') {
                    $refLines['content_from_pid'][] = $line;
                }
            }
        }
        return $refLines;
    }


    /**
     * Returns LanguageService
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Returns the current BE user.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @param $row
     * @param $record
     * @return array
     */
    protected function transformRecordToLine($row, $record): array
    {
        $line = [];
        $urlParameters = [
            'edit' => [
                $row['tablename'] => [
                    $row['recuid'] => 'edit',
                ],
            ],
            'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
        ];
        $url = BackendUtility::getModuleUrl('record_edit', $urlParameters);
        $line['url'] = $url;
        $line['icon'] = $this->iconFactory->getIconForRecord($row['tablename'], $record, Icon::SIZE_SMALL)->render();
        $line['row'] = $row;
        $line['record'] = $record;
        $line['recordTitle'] = BackendUtility::getRecordTitle($row['tablename'], $record, true);
        $line['title'] = $this->getLanguageService()->sL($GLOBALS['TCA'][$row['tablename']]['ctrl']['title']);
        return $line;
    }
}