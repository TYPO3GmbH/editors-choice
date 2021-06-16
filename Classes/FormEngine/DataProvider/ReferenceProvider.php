<?php
declare(strict_types=1);

/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\EditorsChoice\FormEngine\DataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FormEngine Data Provider adding reference data as 'customData'
 */
class ReferenceProvider implements FormDataProviderInterface
{
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
        $result['customData']['editorsChoice']['references'] = $references;
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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_refindex');
        $rows = $queryBuilder
            ->select('*')
            ->from('sys_refindex')
            ->where(
                $queryBuilder->expr()->eq(
                    'ref_table',
                    $queryBuilder->createNamedParameter('tt_content')
                ),
                $queryBuilder->expr()->eq(
                    'ref_uid',
                    $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();

        $refLines = [];
        foreach ($rows as $row) {
            if ($row['tablename'] === 'sys_file_reference') {
                return [];
            }
            $record = BackendUtility::getRecord($row['tablename'], $row['recuid']);
            if ($record) {
                $line = $this->transformRecordToLine($row, $record);

                if ($row['field'] === 'l18n_parent') {
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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_refindex');
        $rows = $queryBuilder
            ->select('*')
            ->from('sys_refindex')
            ->where(
                $queryBuilder->expr()->eq(
                    'ref_table',
                    $queryBuilder->createNamedParameter('pages')
                ),
                $queryBuilder->expr()->eq(
                    'tablename',
                    $queryBuilder->createNamedParameter('pages')
                ),
                $queryBuilder->expr()->eq(
                    'ref_uid',
                    $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'deleted',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();

        $refLines = [];
        foreach ($rows as $row) {
            $record = BackendUtility::getRecord($row['tablename'], $row['recuid']);
            if ($record) {
                $line = $this->transformRecordToLine($row, $record);

                if ($row['field'] === 'shortcut') {
                    $refLines['shortcuts'][] = $line;
                } elseif ($row['field'] === 'content_from_pid') {
                    $refLines['content_from_pid'][] = $line;
                }
            }
        }
        return $refLines;
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
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $url = $uriBuilder->buildUriFromRoute('record_edit', $urlParameters);
        $line['url'] = $url;
        $line['row'] = $row;
        $line['record'] = $record;
        $line['recordTitle'] = BackendUtility::getRecordTitle($row['tablename'], $record, true);
        return $line;
    }
}
