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

namespace wcf\system\event\listener;

use wcf\form\RegisterForm;
use wcf\system\WCF;

/**
 * Stores the accepted version of the terms of use after registration.
 */
class RegisterFormSavedTermsOfUseListener implements IParameterizedEventListener
{
    private $revisionID;

    /**
     * @inheritDoc
     * @param RegisterForm $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        switch ($eventName) {
            case 'save':
                $this->revisionID = WCF::getSession()->getVar('disclaimerAccepted');

                return;
            case 'saved':
                $sql = "INSERT INTO wcf" . WCF_N . "_termsofuse_revision_to_user
                                    (userID, revisionID, acceptedAt)
                        VALUES      (?, ?, ?)";
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute([
                    WCF::getUser()->userID,
                    $this->revisionID,
                    TIME_NOW,
                ]);

                return;
            default:
                throw new \LogicException('Unreachable');
        }
    }
}
