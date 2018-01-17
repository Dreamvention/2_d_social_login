<?php

/* !
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

/**
 * Yahoo OAuth Class
 * 
 * @package             HybridAuth providers package 
 * @author              Lukasz Koprowski <azram19@gmail.com>
 * @version             0.2
 * @license             BSD License
 */

/**
 * Hybrid_Providers_Yahoo - Yahoo provider adapter based on OAuth1 protocol
 */
class Hybrid_Providers_Yahoo extends Hybrid_Provider_Model_OpenID {

	var $openidIdentifier = 'https://open.login.yahooapis.com/openid20/www.yahoo.com/xrds';

}