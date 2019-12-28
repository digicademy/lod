<?php
namespace Digicademy\Lod\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Torsten Schrade <Torsten.Schrade@adwmainz.de>, Academy of Sciences and Literature | Mainz
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Digicademy\Lod\Domain\Repository\IriNamespaceRepository;
use Digicademy\Lod\Domain\Repository\VocabularyRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VocabularyController extends ActionController
{
    /**
     * @var \Digicademy\Lod\Domain\Repository\IriNamespaceRepository
     */
    protected $iriNamespaceRepository;

    /**
     * @var \Digicademy\Lod\Domain\Repository\VocabularyRepository
     */
    protected $vocabularyRepository = null;

    /**
     * Initializes the controller and dependencies
     *
     * @param \Digicademy\Lod\Domain\Repository\IriNamespaceRepository      $iriNamespaceRepository
     * @param \Digicademy\Lod\Domain\Repository\VocabularyRepository        $vocabularyRepository
     */
    public function __construct(
        IriNamespaceRepository $iriNamespaceRepository,
        VocabularyRepository $vocabularyRepository
    ) {
        $this->iriNamespaceRepository = $iriNamespaceRepository;
        $this->vocabularyRepository = $vocabularyRepository;
    }

    /**
     * show selected vocabulary
     *
     * @return void
     */
    public function showAction()
    {
        // assign selected vocabulary
        if ((int)$selectedVocabulary = $this->settings['general']['selectedVocabulary']) {
            $this->view->assign(
                'vocabulary',
                $this->vocabularyRepository->findByUid($selectedVocabulary)
            );
        }

        // assign existing namespaces
        $this->view->assign('namespaces', $this->iriNamespaceRepository->findAll());

        // assign current arguments
        $this->view->assign('arguments', $this->request->getArguments());

        // provide environment vars
        $environment = [
            'TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'),
            'TYPO3_REQUEST_URL' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')
        ];
        $this->view->assign('environment', $environment);
    }

}
