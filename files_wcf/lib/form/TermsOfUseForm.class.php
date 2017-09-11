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

namespace wcf\form;

use \wcf\data\termsofuse\revision\TermsofuseRevision;
use \wcf\system\exception\IllegalLinkException;
use \wcf\system\exception\UserInputException;
use \wcf\system\WCF;

/**
 * Show the Terms Of Use.
 */
class TermsOfUseForm extends AbstractForm {
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
	 * revision to accept
	 * @var int
	 */
	public $accept = null;
	
	/**
	 * revision to reject
	 * @var int
	 */
	public $reject = null;
	
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
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['accept'])) $this->accept = intval($_POST['accept']);
		if (isset($_POST['reject'])) $this->reject = intval($_POST['reject']);
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();
		
		$mostRecent = TermsofuseRevision::getMostRecentRevision();
		if ($this->accept !== null && $this->reject !== null) {
			throw new UserInputException('accept', 'conflict');
		}
		if ($this->accept !== null) {
			if ($mostRecent->revisionID === WCF::getUser()->termsOfUseRevision) {
				throw new UserInputException('accept', 'alreadyAccepted');
			}
			if ($this->accept !== $mostRecent->revisionID) {
				throw new UserInputException('accept', 'outdated');
			}
		}
		if ($this->reject !== null) {
			if ($mostRecent->revisionID === WCF::getUser()->termsOfUseRevision) {
				throw new UserInputException('reject', 'alreadyAccepted');
			}
			if ($this->reject !== $mostRecent->revisionID) {
				throw new UserInputException('reject', 'outdated');
			}
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();
		
		if ($this->accept !== null) {
			$data = [ 'data' => [ 'termsOfUseRevision' => $this->accept ] ];
			$this->objectAction = new \wcf\data\user\UserAction([ WCF::getUser() ], 'update', $data);
			$this->objectAction->executeAction();

			WCF::getTPL()->assign('success', 'wcf.termsOfUse.accept.success');
			$this->saved();
		}
		else if ($this->reject !== null) {
			
		}
		else {
			throw new \LogicException('Unreachable');
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$mostRecent = TermsofuseRevision::getMostRecentRevision();
		WCF::getTPL()->assign([ 'revisionID'  => $this->revisionID
		                      , 'revision'    => $this->revision
		                      , 'isCurrent'   => $mostRecent->revisionID === $this->revision->revisionID
		                      , 'hasAccepted' => $mostRecent->revisionID === WCF::getUser()->termsOfUseRevision
		                      ]);
	}
}
