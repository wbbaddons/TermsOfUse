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

/**
 * Shows the room list.
 */
class TermsOfUseRevisionListPage extends \wcf\page\SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.termsOfUse.revision.list';

	/**
	 * @inheritDoc
	 */
	public $neededPermissions = [ ]; // TODO

	/**
	 * @inheritDoc
	 */
	public $objectListClassName = \wcf\data\termsofuse\revision\TermsofuseRevisionList::class;

	/**
	 * @inheritDoc
	 */
	public $validSortFields = [ 'revisionID', 'enabledAt' ];

	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'revisionID';

	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
}
