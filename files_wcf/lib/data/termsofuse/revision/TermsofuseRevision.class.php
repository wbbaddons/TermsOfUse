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
 * Represents a terms of use revision.
 */
class TermsofuseRevision extends \wcf\data\DatabaseObject {
	/**
	 * contents by language
	 * @var string[]
	 */
	protected $content = null;
	
	/**
	 * output processor to use
	 * @var \wcf\system\html\output\HtmlOutputProcessor
	 */
	protected $outputProcessor = null;
	
	/**
	 * Returns the revision most recently enabled, null
	 * if there is no such revision.
	 *
	 * @return \wcf\data\termsofuse\revision\TermsofuseRevision
	 */
	public static function getMostRecentRevision() {
		$sql = "SELECT   *
		        FROM     wcf".WCF_N."_termsofuse_revision
		        WHERE    enabledAt IS NOT NULL
		        ORDER BY enabledAt DESC";
		$statement = WCF::getDB()->prepareStatement($sql, 1);
		$statement->execute();
		$row = $statement->fetchArray();
		
		if ($row === false) return null;
		
		return new static(null, $row);
	}
	
	/**
	 * Returns the content for the given Language or null
	 * if there is no version for the given language.
	 *
	 * @param  \wcf\data\language\Language $language
	 * @return string[]
	 */
	public function getContent(\wcf\data\language\Language $language) {
		if ($this->content === null) {
			$sql = "SELECT *
			        FROM   wcf".WCF_N."_termsofuse_revision_content
			        WHERE  revisionID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute([ $this->revisionID ]);
			$this->content = [ ];
			while (($row = $statement->fetchArray())) {
				$this->content[$row['languageID']] = $row;
			}
			
			$contentIDs = array_map(function (array $row) {
				return $row['contentID'];
			}, array_filter($this->content, function (array $row) {
				return $row['hasEmbeddedObjects'];
			}));
			
			if (!empty($contentIDs)) {
				\wcf\system\message\embedded\object\MessageEmbeddedObjectManager::getInstance()->loadObjects('be.bastelstu.termsOfUse', $contentIDs);
			}
		}
		
		if (isset($this->content[$language->languageID])) {
			$this->getOutputProcessor()->process($this->content[$language->languageID]['content'], 'be.bastelstu.termsOfUse', $this->content[$language->languageID]['contentID']);
			return $this->getOutputProcessor()->getHtml();
		}
		
		return null;
	}

	/**
	 * Returns the output processor to use.
	 *
	 * @return \wcf\system\html\output\HtmlOutputProcessor
	 */
	public function getOutputProcessor() {
		if ($this->outputProcessor === null) {
			$this->outputProcessor = new \wcf\system\html\output\HtmlOutputProcessor();
		}
		
		return $this->outputProcessor;
	}
}
