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

use \wcf\data\termsofuse\revision\TermsofuseRevision;
use \wcf\system\exception\AJAXException;
use \wcf\system\request\LinkHandler;
use \wcf\system\request\RequestHandler;
use \wcf\system\WCF;
use \wcf\util\HeaderUtil;

/**
 * Forces re-acceptance of newer terms of use.
 */
class ControllerTermsOfUseListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!WCF::getUser()->userID) return;
		$active = TermsofuseRevision::getActiveRevision();
		if ($active === null) return;
		if ($active->hasAccepted(WCF::getUser())) return;

		if (RequestHandler::getInstance()->getActiveRequest()->isAvailableDuringOfflineMode()) return;
		
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
			throw new AJAXException(WCF::getLanguage()->getDynamicVariable('wcf.ajax.error.permissionDenied'), AJAXException::INSUFFICIENT_PERMISSIONS);
		}
		else {
			HeaderUtil::redirect(LinkHandler::getInstance()->getLink('TermsOfUse'));
			exit;
		}
	}
}
