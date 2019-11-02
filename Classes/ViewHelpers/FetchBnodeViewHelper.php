<?php

namespace Digicademy\Lod\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) Torsten Schrade <Torsten.Schrade@adwmainz.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use Digicademy\Lod\Domain\Model\Bnode;
use Digicademy\Lod\Domain\Repository\StatementRepository;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class FetchBnodeViewHelper extends AbstractViewHelper
{

    /**
     * @var \Digicademy\Lod\Domain\Repository\StatementRepository
     */
    protected $statementRepository = null;

    /**
     * @param \Digicademy\Lod\Domain\Repository\StatementRepository $statementRepository
     */
    public function injectStatementRepository(StatementRepository $statementRepository)
        {
        $this->statementRepository = $statementRepository;
    }

    /**
     * @param \Digicademy\Lod\Domain\Model\Bnode $bnode
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Exception
     */
    public function render(Bnode $bnode)
    {
        return $this->statementRepository->findEntityByPosition('subject', $bnode);
    }

}
