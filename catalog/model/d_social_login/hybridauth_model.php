<?php
//DEPRECATED starting from 4.0.0. Please change callback urls to http://example.com/d_social_login.php

class ModelDSocialLoginHybridauthModel extends Model {

	function process(){
		require_once( "system/library/hybrid/auth.php" );
		require_once( "system/library/hybrid/endpoint.php" );

		Hybrid_Endpoint::process();
	}
}
?>