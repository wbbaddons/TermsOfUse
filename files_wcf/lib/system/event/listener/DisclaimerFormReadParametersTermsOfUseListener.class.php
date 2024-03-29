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

use wcf\form\DisclaimerForm;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

/**
 * Intercepts requests to the DisclaimerForm.
 */
class DisclaimerFormReadParametersTermsOfUseListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param DisclaimerForm $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        WCF::getSession()->register(
            'termsOfUseRegister',
            1
        );

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('TermsOfUse'));

        exit;
    }
}
