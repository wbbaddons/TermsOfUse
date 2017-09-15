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

namespace wcf\data\termsofuse\revision;

use \wcf\system\WCF;

/**
 * Executes terms of use revision-related actions.
 */
class TermsofuseRevisionAction extends \wcf\data\AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
	protected $permissionsDelete = [ ];

	/**
	 * @inheritDoc
	 */
	protected $permissionsUpdate = [ ];
	
	/**
	 * @inheritDoc
	 */
	 public function create() {
		WCF::getDB()->beginTransaction();
		$result = parent::create();

		$sql = "INSERT INTO wcf".WCF_N."_termsofuse_revision_content
		                    (revisionID, languageID, content)
		        VALUES      (?, ?, ?)";
		$insertStatement = WCF::getDB()->prepareStatement($sql);
		$sql = "UPDATE wcf".WCF_N."_termsofuse_revision_content
		        SET    hasEmbeddedObjects = ?
			WHERE  contentID = ?";
		$updateStatement = WCF::getDB()->prepareStatement($sql);
		foreach ($this->parameters['content'] as $languageID => $processor) {
			$insertStatement->execute([ $result->revisionID, $languageID, $processor->getHtml() ]);
			$contentID = WCF::getDB()->getInsertID("wcf".WCF_N."_termsofuse_revision_content", "contentID");
			$processor->setObjectID($contentID);
			if (\wcf\system\message\embedded\object\MessageEmbeddedObjectManager::getInstance()->registerObjects($processor)) {
				$updateStatement->execute([ 1, $contentID ]);
			}
		}
		WCF::getDB()->commitTransaction();
		
		return $result;
	}
}
