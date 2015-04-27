<?php
class ModelDSocialLoginHybridauthModel extends Model {

	function process(){
		require_once( "catalog/model/d_social_login/Hybrid/Auth.php" );
		require_once( "catalog/model/d_social_login/Hybrid/Endpoint.php" ); 

		Hybrid_Endpoint::process();
	}
}
?>