<?php
declare(strict_types=1);

/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\EditorsChoice\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Copy a referenced element and remove it from the reference element
 */
class DereferenceElementAjaxController
{
    /**
     * Create a new inline child via AJAX.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function dereferenceAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $queryParameters = $request->getParsedBody();
        $tableName = $queryParameters['tableName'];

        if ($tableName !== 'tt_content') {
            $response->getBody()->write(json_encode([ 'success' => false ]));
            return $response;
        }

        $contentElementUid = (int)$queryParameters['contentElementUid'];
        $referenceElementUid = (int)$queryParameters['referenceElementUid'];

        $referenceRecord = BackendUtility::getRecord('tt_content', $referenceElementUid);

        // Find content element in reference element
        $currentReferencedElements = $referenceRecord['records'];
        $currentReferencedElementsArray = GeneralUtility::trimExplode(',', $currentReferencedElements, true);
        $foundContentElementInReferenceElement = false;
        $newReferenceElements = [];
        foreach ($currentReferencedElementsArray as $currentReferencedElement) {
            $split = BackendUtility::splitTable_Uid($currentReferencedElement);
            $tableName = empty($split[0]) ? 'tt_content' : $split[0];
            if ($tableName === 'tt_content' && (int)$split[1] === $contentElementUid) {
                $foundContentElementInReferenceElement = true;
            } else {
                $newReferenceElements[] = $currentReferencedElement;
            }
        }

        // If the content element was found in the list of referenced elements, copy the element below the
        // reference element via dataHandler and update the reference element to no longer contain the
        // referenced element
        if ($foundContentElementInReferenceElement) {
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $data = [
                'tt_content' => [
                    $contentElementUid => [
                        'copy' => ($referenceElementUid * -1),
                    ],
                ],
            ];
            $dataHandler->start([], $data);
            $dataHandler->process_cmdmap();

            $data = [
                'tt_content' => [
                    $referenceElementUid => [
                        'records' => implode(',', $newReferenceElements),
                    ],
                ],
            ];
            $dataHandler->start($data, []);
            $dataHandler->process_datamap();
        }

        $response->getBody()->write(json_encode([ 'success' => true ]));
        return $response;
    }
}
