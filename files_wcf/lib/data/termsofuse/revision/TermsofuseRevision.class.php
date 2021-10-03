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

use wcf\data\DatabaseObject;
use wcf\data\language\Language;
use wcf\data\user\User;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\WCF;

/**
 * Represents a terms of use revision.
 */
final class TermsofuseRevision extends DatabaseObject
{
    /**
     * contents by language
     * @var string[]
     */
    protected $content;

    /**
     * output processor to use
     * @var HtmlOutputProcessor
     */
    protected $outputProcessor;

    /**
     * the revision that is currently active
     * @var \wcf\data\termsofuse\revision\TermsofuseRevision
     */
    protected static $activeRevision = false;

    /**
     * the latest draft
     * @var \wcf\data\termsofuse\revision\TermsofuseRevision
     */
    protected static $latestDraft = false;

    /**
     * Returns the revision most recently enabled, null
     * if there is no such revision.
     */
    public static function getActiveRevision(bool $skipCache = false): ?TermsofuseRevision
    {
        if (self::$activeRevision === false || $skipCache) {
            $sql = "SELECT      *
                    FROM        wcf" . WCF_N . "_termsofuse_revision
                    WHERE       enabledAt IS NOT NULL
                    ORDER BY    createdAt DESC";
            $statement = WCF::getDB()->prepareStatement($sql, 1);
            $statement->execute();
            $row = $statement->fetchArray();

            if ($row === false) {
                self::$activeRevision = null;
            } else {
                self::$activeRevision = new static(null, $row);
            }
        }

        return self::$activeRevision;
    }

    /**
     * Returns most recent draft newer than the active revision.
     *
     * @return \wcf\data\termsofuse\revision\TermsofuseRevision
     */
    public static function getLatestDraft($skipCache = false)
    {
        if (self::$latestDraft === false || $skipCache) {
            $sql = "SELECT      *
                    FROM        wcf" . WCF_N . "_termsofuse_revision
                    WHERE       enabledAt IS NULL
                    ORDER BY    createdAt DESC";
            $statement = WCF::getDB()->prepareStatement($sql, 1);
            $statement->execute();
            $row = $statement->fetchArray();

            if ($row === false) {
                self::$latestDraft = null;
            } else {
                self::$latestDraft = new static(null, $row);
            }
        }

        return self::$latestDraft;
    }

    /**
     * Returns whether this revision was enabled.
     */
    public function isActive(): bool
    {
        return $this->enabledAt !== null;
    }

    /**
     * Returns whether this revision is more recent than the active revision.
     */
    public function isNewerThanActive($skipCache = false): bool
    {
        $active = self::getActiveRevision($skipCache);
        if ($active === null) {
            return true;
        }

        return $this->createdAt > $active->createdAt;
    }

    /**
     * Returns whether this revision is outdated.
     * For drafts it returns whether it is the latest draft.
     * For non-drafts it returns whether it is the currently active revision.
     */
    public function isOutdated(): bool
    {
        if ($this->isActive()) {
            return $this->revisionID !== static::getActiveRevision()->revisionID;
        } else {
            return $this->revisionID !== static::getLatestDraft()->revisionID;
        }
    }

    /**
     * Returns whether the given user has accepted this revision.
     */
    public function hasAccepted(User $user): bool
    {
        $sql = "SELECT  acceptedAt
                FROM    wcf" . WCF_N . "_termsofuse_revision_to_user
                WHERE       revisionID = ?
                        AND userID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([ $this->revisionID, $user->userID ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Returns the content for the given Language or null
     * if there is no version for the given language.
     *
     * @param  boolean                     $raw
     * @return string[]
     */
    public function getContent(Language $language, $raw = false)
    {
        if ($this->content === null) {
            $sql = "SELECT  *
                    FROM    wcf" . WCF_N . "_termsofuse_revision_content
                    WHERE   revisionID = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([ $this->revisionID ]);
            $this->content = [ ];
            while (($row = $statement->fetchArray())) {
                $this->content[$row['languageID']] = $row;
            }

            $contentIDs = \array_map(static function (array $row) {
                return $row['contentID'];
            }, \array_filter($this->content, static function (array $row) {
                return $row['hasEmbeddedObjects'];
            }));

            if (!empty($contentIDs)) {
                MessageEmbeddedObjectManager::getInstance()->loadObjects('be.bastelstu.termsOfUse', $contentIDs);
            }
        }

        if (isset($this->content[$language->languageID])) {
            if ($raw) {
                return $this->content[$language->languageID]['content'];
            }
            $this->getOutputProcessor()->process(
                $this->content[$language->languageID]['content'],
                'be.bastelstu.termsOfUse',
                $this->content[$language->languageID]['contentID']
            );

            return $this->getOutputProcessor()->getHtml();
        }

        return null;
    }

    /**
     * Returns the output processor to use.
     */
    public function getOutputProcessor(): HtmlOutputProcessor
    {
        if ($this->outputProcessor === null) {
            $this->outputProcessor = new HtmlOutputProcessor();
        }

        return $this->outputProcessor;
    }
}
