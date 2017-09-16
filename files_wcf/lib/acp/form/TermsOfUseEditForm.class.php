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

namespace wcf\acp\form;

use \wcf\data\termsofuse\revision\TermsofuseRevision;
use \wcf\data\termsofuse\revision\TermsofuseRevisionAction;
use \wcf\system\exception\UserInputException;
use \wcf\system\html\input\HtmlInputProcessor;
use \wcf\system\language\LanguageFactory;
use \wcf\system\WCF;

/**
 * Shows the terms of use edit form.
 */
class TermsOfUseEditForm extends \wcf\form\AbstractForm {
	/**
	 * @inheritDoc
	 */
	public $templateName = 'termsOfUseEdit';

	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.termsOfUse.edit';

	/**
	 * @inheritDoc
	 */
	public $neededPermissions = [ ]; // TODO

	/**
	 * list of available languages
	 * @var \wcf\data\language\Language[]
	 */
	public $availableLanguages = [ ];

	/**
	 * array of texts for the different languages
	 * @var string[]
	 */
	public $content = [ ];

	/**
	 * @var \wcf\system\html\input\HtmlInputProcessor[]
	 */
	public $htmlInputProcessors = [ ];
	
	/**
	 * revision being preloaded into the form
	 * @var \wcf\data\termsofuse\revision\TermsofuseRevision
	 */
	public $revision = null;

	/**
	 * @inheritDoc
	 */
	public function readData() {
		$this->availableLanguages = LanguageFactory::getInstance()->getLanguages();

		parent::readData();

		$this->revision = $draft = TermsofuseRevision::getLatestDraft(true);
		$active = TermsofuseRevision::getActiveRevision(true);
		if ($active && (!$draft || $draft->createdAt < $active->createdAt)) {
			$this->revision = $active;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['content']) && is_array($_POST['content'])) $this->content = \wcf\util\ArrayUtil::trim($_POST['content']);

		foreach (LanguageFactory::getInstance()->getLanguages() as $language) {
			$this->htmlInputProcessors[$language->languageID] = new HtmlInputProcessor();
			$this->htmlInputProcessors[$language->languageID]->process((!empty($this->content[$language->languageID]) ? $this->content[$language->languageID] : ''), 'be.bastelstu.termsOfUse');
		}
	}

	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();

		foreach ($this->htmlInputProcessors as $languageID => $processor) {
			$processor->validate();
			if ($processor->appearsToBeEmpty()) {
				throw new UserInputException('content'.$languageID);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();

		$data = [ 'createdAt' => TIME_NOW ];
		$this->objectAction = new TermsofuseRevisionAction([ ], 'create', [ 'data' => array_merge($this->additionalFields, $data)
		                                                                  , 'content' => $this->htmlInputProcessors
		                                                                  ]);
		$returnValues = $this->objectAction->executeAction();
		
		$this->saved();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign([ 'availableLanguages' => $this->availableLanguages
		                      , 'revision'           => $this->revision
		                      ]);
	}
}

