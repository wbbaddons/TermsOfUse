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
	 * @var	Language[]
	 */
	public $availableLanguages = [];

	/**
	 * @inheritDoc
	 */
	public function readData() {
		$this->availableLanguages = LanguageFactory::getInstance()->getLanguages();

		parent::readData();
	}

	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();

	}

	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();

	}

	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();

	}

	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();


		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign([ 'availableLanguages' => $this->availableLanguages ]);
	}
}

