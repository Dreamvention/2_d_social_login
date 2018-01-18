<?php

/*
 * 	d_social_login
 *	dreamvention.com || Live fix
 */
class ControllerExtensionDSocialLoginCallbackLive extends Controller {
	public function index (){
		$_REQUEST['hauth_done'] = 'Live';
		require_once("system/library/d_social_login/hybrid/auth.php");
		require_once("system/library/d_social_login/hybrid/endpoint.php");
		Hybrid_Endpoint::process();
	}
}