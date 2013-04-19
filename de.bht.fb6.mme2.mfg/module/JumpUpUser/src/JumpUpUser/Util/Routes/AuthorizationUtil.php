<?php
namespace JumpUpUser\Util\Routes;

use JumpUpUser\Util\StringUtil;

use Application\Util\String_Util;

use Zend\Authentication\AuthenticationService;

class AuthorizationUtil {
    /**
     * Check if a the user is within an anonymous page or if he has an idendity by the AuthenticationService yet.
     * @param AuthenticationService $as
     * @param String $requestUrl
     */
    static public function isAuthorized(AuthenticationService $as, $requestUrl) {
        /*
         * Constraints: which actions may be visited by anonymous 
         */
        if(StringUtil::endsWith($requestUrl, '/')
          || StringUtil::endsWith($requestUrl, 'register') 
          || StringUtil::endsWith($requestUrl, 'auth')
          || StringUtil::endsWith($requestUrl, 'auth/authenticate')
          || StringUtil::endsWith($requestUrl, 'auth/logout')
          || StringUtil::endsWith($requestUrl, 'register/confirm'  )) {
              return true;
          }
        /*
         * check authentication from service
         */
        else {
            return $as->hasIdentity();
        }
    }    
}