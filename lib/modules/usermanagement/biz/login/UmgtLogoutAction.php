<?php
namespace APF\modules\usermanagement\biz\login;

/**
 * <!--
 * This file is part of the adventure php framework (APF) published under
 * http://adventure-php-framework.org.
 *
 * The APF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The APF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with the APF. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
 * -->
 */
use APF\core\frontcontroller\AbstractFrontcontrollerAction;
use APF\core\service\APFService;
use APF\modules\usermanagement\biz\UmgtUserSessionStore;
use APF\tools\http\HeaderManager;
use APF\tools\link\LinkGenerator;
use APF\tools\cookie\Cookie;
use APF\modules\usermanagement\biz\login\UmgtAutoLoginAction;
use APF\tools\link\Url;

/**
 * @package APF\modules\usermanagement\biz\login
 * @class UmgtLogoutAction
 *
 * This front controller action logs out the user and initializes a redirect
 * to the desired logout page. The url is generated by the logout url provider.
 *
 * @author Christian Achatz
 * @version
 * Version 0.1, 03.06.2011<br />
 */
class UmgtLogoutAction extends AbstractFrontcontrollerAction {

   public function run() {
      $logout = $this->getInput()->getAttribute('logout', 'false');
      if ($logout === 'true') {
         $sessionStore = & $this->getServiceObject('APF\modules\usermanagement\biz\UmgtUserSessionStore', APFService::SERVICE_TYPE_SESSION_SINGLETON);
         /* @var $sessionStore UmgtUserSessionStore */
         $sessionStore->logout($this->getContext());

         // delete cookie to avoid re-log-in effects after clicking on log-out
         $cookie = new Cookie(UmgtAutoLoginAction::AUTO_LOGIN_COOKIE_NAME);
         $cookie->delete();

         // redirect to target page
         $urlProvider = & $this->getDIServiceObject('APF\modules\usermanagement\biz', 'LogoutRedirectUrlProvider');
         /* @var $urlProvider UmgtRedirectUrlProvider */
         HeaderManager::forward(LinkGenerator::generateUrl(Url::fromString($urlProvider->getRedirectUrl())));
      }
   }

}
