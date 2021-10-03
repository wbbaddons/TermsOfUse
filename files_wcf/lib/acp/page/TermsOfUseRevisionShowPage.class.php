<?php

/*
 * Copyright (c) 2017, Tim Düsterhus
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace wcf\acp\page;

use wcf\data\language\Language;
use wcf\data\termsofuse\revision\TermsofuseRevision;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Shows a specific revision.
 */
class TermsOfUseRevisionShowPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.termsOfUse.revision.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = [ 'admin.content.canManageTermsOfUse' ];

    /**
     * @var int
     */
    public $revisionID = 0;

    /**
     * @var TermsofuseRevision
     */
    public $revision;

    /**
     * list of available languages
     * @var Language[]
     */
    public $availableLanguages = [ ];

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_GET['id'])) {
            $this->revisionID = \intval($_GET['id']);
        }
        $this->revision = new TermsofuseRevision($this->revisionID);
        if (!$this->revision->revisionID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData()
    {
        parent::readData();

        $this->availableLanguages = LanguageFactory::getInstance()->getLanguages();
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'revisionID' => $this->revisionID,
            'revision' => $this->revision,
            'availableLanguages' => $this->availableLanguages,
        ]);
    }
}
