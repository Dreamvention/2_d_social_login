<?php
/*
 * 	d_social_login
 *	dreamvention.com
 */
class ControllerExtensionDSocialLoginCallback extends Controller {
	public function index (){
		require_once("system/library/hybrid/auth.php"); 
		require_once("system/library/hybrid/endpoint.php");
		if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
		{
		    Hybrid_Endpoint::process();
		}
	}
}