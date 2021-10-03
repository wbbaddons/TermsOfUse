<?php

/*
 * Copyright (c) 2018, Tim Düsterhus
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

use wcf\acp\action\UserExportGdprAction;
use wcf\system\WCF;

/**
 * Exports dates of term acceptance.
 */
class UserExportGdprActionTermsOfUseListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param UserExportGdprAction $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        $sql = "SELECT     revisionID, r.enabledAt, r2u.acceptedAt, rc.content, l.languageCode
                FROM       wcf1_termsofuse_revision_to_user r2u
                INNER JOIN wcf1_termsofuse_revision_content rc
                USING      (revisionID)
                INNER JOIN wcf1_termsofuse_revision r
                USING      (revisionID)
                INNER JOIN wcf1_language l
                USING      (languageID)
                WHERE      userID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([ $eventObj->user->userID ]);
        $data = [ 'acceptedTerms' => [ ] ];
        while (($row = $statement->fetchArray())) {
            if (!isset($data['acceptedTerms'][$row['revisionID']])) {
                $data['acceptedTerms'][$row['revisionID']] = [
                    'acceptedAt' => $row['acceptedAt'],
                    'publishedAt' => $row['enabledAt'],
                    'languages' => [ ],
                ];
            }
            if ($data['acceptedTerms'][$row['revisionID']]['acceptedAt'] !== $row['acceptedAt']) {
                throw new \LogicException('Unreachable');
            }
            if ($data['acceptedTerms'][$row['revisionID']]['publishedAt'] !== $row['enabledAt']) {
                throw new \LogicException('Unreachable');
            }
            $data['acceptedTerms'][$row['revisionID']]['languages'][$row['languageCode']] = $row['content'];
        }
        $eventObj->data['be.bastelstu.termsOfUse'] = $data;
    }
}
