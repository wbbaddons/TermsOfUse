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

namespace wcf\page;

use \wcf\data\termsofuse\revision\TermsofuseRevision;
use \wcf\system\exception\IllegalLinkException;
use \wcf\system\WCF;

/**
 * Show the Terms Of Use.
 */
class TermsOfUsePage extends \wcf\page\AbstractPage {
	/**
	 * requested revision
	 * @var int
	 */
	public $revisionID = 0;
	
	/**
	 * revision
	 * @var \wcf\data\termsofuse\revision\TermsofuseRevision
	 */
	public $revision = null;
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['id'])) {
			$this->revisionID = intval($_GET['id']);
			$this->revision = new TermsofuseRevision($this->revisionID);
			if (!$this->revision->revisionID) throw new IllegalLinkException();
		}
		else {
			$this->revision = TermsofuseRevision::getMostRecentRevision();
		}
		
		if ($this->revision === null) throw new IllegalLinkException();
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([ 'revisionID' => $this->revisionID
		                      , 'revision' => $this->revision
		                      ]);
	}
}
