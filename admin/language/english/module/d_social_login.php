<?php
// Heading
$_['heading_title']             = ' <span style="color:#449DD0; font-weight:bold">Social Login</span><span style="font-size:12px; color:#999"> by <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" style="font-size:1em; color:#999" target="_blank">Dreamvention</a></span>';
$_['heading_title_main']        = 'Social Login';
$_['text_edit']                 = 'Edit Social Login module';

// Tab
$_['text_module']               = 'Modules';
$_['text_setting']              = 'Settings';
$_['text_instruction']          = 'Instructions';

// Modules
$_['entry_size']                = 'Button Size:';
$_['entry_status']              = 'Status:';

$_['text_icons']                = 'Icons';
$_['text_small']                = 'Small';
$_['text_medium']               = 'Medium';
$_['text_large']                = 'Large';
$_['text_huge']                 = 'Huge';

// Settings
$_['text_setting_basic']        = 'Basic';
$_['text_setting_field']        = 'Social buttons & Fields';
$_['text_setting_provider']     = 'API settings';

$_['entry_name']                = 'Module name';
$_['entry_config_files']        = 'Config files';

$_['entry_version_check']       = 'You have version %s';
$_['text_no_update']            = 'Super! You have the latest version.';
$_['text_new_update']           = 'Wow! There is a new version available for download.';
$_['text_error_update']         = 'Sorry! Something went wrong. If this repeats, contact the support please.';
$_['text_error_failed']         = 'Oops! We could not connect to the server. Please try again later.';
$_['button_version_check']      = 'Check for update';

$_['entry_return_page']         = 'Select the page that will be used for a newly registered customer to return to<i class="icon-question" rel="tooltip" data-help="Viewed - is the page where the guest clicked the signin button. Address - this is the page where the first address can be edited. Home - the homepage. Account - the account page."></i>';
$_['entry_base_url_index']      = 'Activate index route to fix permission conflicts <i class="icon-question" rel="tooltip" data-help="Toggle this option to set the Callback url for social logins not to a absolute url, but through the opencart system. This may solve many issues with permissions such as the error: You cannot access this page directly. Read more in the instructions tab."></i>';
$_['entry_background_img']      = 'Set the background image of the last popup screen';

$_['entry_sort_order']          = 'Sort and activate social login buttons. You can also set the colors of the buttons and their color when the button is pushed. The color of the button will be used as the background color for the last popup screen:';
$_['text_google']               = 'Google+';
$_['text_facebook']             = 'Facebook';
$_['text_twitter']              = 'Twitter';
$_['text_live']                 = 'Live';
$_['text_linkedin']             = 'LinkedIn';
$_['text_vkontakte']            = 'Vkontakte';
$_['text_odnoklassniki']        = 'Odnoklassniki';
$_['text_mailru']               = 'Mail.ru';
$_['text_yandex']               = 'Yandex';
$_['text_instagram']            = 'Instagram';
$_['text_paypal']               = 'Paypal';
$_['text_vimeo']                = 'Vimeo';
$_['text_tumblr']               = 'Tumblr';
$_['text_yahoo']                = 'Yahoo';
$_['text_foursquare']           = 'Foursquare';

$_['text_background_color']     = 'Button color';
$_['text_background_color_active'] = 'on Push';


$_['entry_fields_sort_order']   = 'Sort and activate fields for the popup screen for the first social login action, which is the registration of a new customer. (email is not present because its activated by default) ';
$_['text_firstname']            = 'First name';
$_['text_lastname']             = 'Last name';
$_['text_phone']                = 'Phone';
$_['text_mask']                 = 'Mask<i class="icon-question" rel="tooltip" data-help="You can add a mask filter to the phone input field like this (999) 999-99-99. Leave empty is you do not want to use it."></i>';
$_['text_address_1']            = 'Address 1';
$_['text_address_2']            = 'Address 2';
$_['text_city']                 = 'City';
$_['text_postcode']             = 'Postcode';
$_['text_country_id']           = 'Country';
$_['text_zone_id']              = 'Region';
$_['text_password']             = 'Password';
$_['text_confirm']              = 'Confirm password';
$_['text_company']              = 'Company name';
$_['text_company_id']           = 'Company id';
$_['text_tax_id']               = 'Tax id';

$_['text_app_settings']         = 'App settings';
$_['text_app_id']               = 'App id:';
$_['text_app_key']              = 'App key:';
$_['text_app_secret']           = 'App secret:';

$_['text_success']              = 'Success: You have modified module Social Login!';
$_['button_save_and_stay']      = 'Save and Stay';

// Error
$_['error_permission']          = 'Warning: You do not have permission to modify module  Social Login!';
$_['warning_app_settings']      = 'Creating an App can be tricky';
$_['warning_app_settings_full'] = 'Be ready that it may not work from the first time. To get it working from the first try you must follow the <a href="#instruction" data-toggle="tab">instructions</a> exactly. If you face some issues, you can read more in our <a href="https://dreamvention.zendesk.com/hc/en-us/sections/201049012-Social-Login" target="_blank">Knowledgebase</a> or ask us to help you out via <a href="http://www.dreamvention.com/support" target="_blank">support</a>. But please remember that creating an app requires access to your Social network personal page, which usually carries personal data and should not be shared, so we can only guide you through the installation that much. You can also find alot of tutorials on the internet on how to create social apps.';

$_['text_instructions_full']    ='
<div class="row">
  <div class="col-md-6">
    <div class="wrap-5">
      <h1>Introduction</h1>
      <p>Social login lets you implement login buttons of the most popular social networks in the world. </p>
      <h2>Set up buttons</h2>
      <ol>
        <li>You will need to go to settings and set up the buttons App id and secrets for the social logins you want to use.</li>
        <li>Sort them and check the checkbox for those that should be shown</li>
        <li>Once you have edited the settings, use the module tab to place the buttons on the shop like any other module.</li>
      </ol>

      <h2>Why do some social logins require an email?</h2>
      <p>Some social networks do not provide the email from the profile, like twitter and vkontakte. Since opencart requires an account to have at least have an email, when the client uses one of such login buttons, he will be redirected to a page where he will be asked to add an email.</p>

      <h2>What is the last Popup screen?</h2>
      <p>
      Signing in with social networks is cool, yet it provides very little information about the customer. You mainly get the name and the email. And some networks do not even provide the email. For these cases you have an extra step at the end of the registration. It only pops up when the customer logins for the first time. You can customize it to fit your design - change the background image, add the fields you really need (all of them will be required, so think twice before activating them.)
      <img src="view/image/d_social_login/extra_step.png" class="img-thumbnail img-responsive"/>
      </p>
      <h2>Troubleshooting</h2>
      <p> If you have issues with setting up the module, try these steps:
        <ol>
          <li>Check that your newly created App is activated. i.e. Facebook keeps new apps deactivated and Twitter needs extra checkbox to allow social logins.</li>
          <li>If you set everything right on the Social network page, but get error messages like: You cannot access this page directly. Try the Settings option: Activate index route to fix permission conflicts. It will replace the direct path to dynamic path of opencart.</li>

          <div class="bs-callout bs-callout-warning">
            <h4>Please remember!</h4>
            <p>If you have the option Activate index route to fix permission conflicts ON - your paths will look like this <br/><br/>'.HTTPS_CATALOG.'<strong>index.php?route=module/d_social_login/hybridauth&</strong>hauth.done=Google<br/><br/> But if you switch to OFF - you must change the paths to <br/><br/>'.HTTPS_CATALOG.'<strong>catalog/model/d_social_login/hybridauth.php?</strong>hauth.done=Google</p>
          </div>

          <li>Check that you have access to your callback path - visit <a href="'.HTTPS_CATALOG.'catalog/model/d_social_login/hybridauth.php">'.HTTPS_CATALOG.'catalog/model/d_social_login/hybridauth.php</a>. You should see this text HybridAuth Open Source Social Sign On PHP Library. hybridauth.sourceforge.net/. If you do not see it - try adding permissions 755 or 777 to your folder '.HTTPS_CATALOG.'catalog/model/d_social_login/ </li>
          <li>Give it 1 hour to refresh the cache on the social network side and try again.</li>
          <li>Send us a support ticket at <a href="http://dreamvention.com/support">dreamvention.com/support</a></li>
        </ol>
      </p>
      <h2>My social logins stopped working after update</h2>
      <p>Do not panic - its all good. We have implemented a new flow for the callback url process that goes directly through opencart index.php. In other words, we made it better.</p>
      <p>But we also kept the old version. You can turn off the new flow by going to the settings tab and <strong>unchecking</strong> the option: "Activate index route to fix permission conflicts" OR simply setup the new callback urls in your Social apps (see "How to set up social apps?".)</p>
    </div>
  </div>
  <div class="col-md-6">
    <div class="wrap-5">
      <h1>How to set up social apps?</h1>

      <ul class="nav nav-tabs">
        <li class="active"><a href="#google_plus"  data-toggle="tab"><i class="icon-google-plus"></i> Google+</a></li>
        <li><a href="#facebook"  data-toggle="tab"><i class="icon-facebook"></i> Facebook</a></li>
        <li><a href="#twitter"  data-toggle="tab"><i class="icon-twitter-bird"></i> Twitter</a></li>
        <li><a href="#windows_live"  data-toggle="tab"><i class="icon-windows-live"></i> Live</a></li>
        <li><a href="#linkedin"  data-toggle="tab"><i class="icon-linkedin"></i> Linkedin</a></li>
        <li><a href="#vkontakte"  data-toggle="tab"><i class="icon-vkontakte"></i> vkontakte</a></li>
        <li><a href="#yandex"  data-toggle="tab"><i class="icon-yandex"></i> Yandex</a></li>
        <li><a href="#paypal"  data-toggle="tab"><i class="icon-paypal"></i> Paypal</a></li>
        <li><a href="#instagram"  data-toggle="tab"><i class="icon-instagrem-square"></i> Instagram</a></li>
        <li><a href="#tumblr"  data-toggle="tab"><i class="icon-tumblr"></i> Tumbler</a></li>
      </ul>
      <div class="tab-content">
        <div id="facebook" class="tab-pane active">
          <div class="tab-title"><i class="icon-facebook"></i> Setup Facebook login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit facebook developers page <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a></li>
            <li>In menu Apps – select create new app</li>
            <img src="view/image/d_social_login/facebook/01.png" class="img-thumbnail img-responsive" />
            <li>In the popup window fill in the Display Name and Choose category</li>
            <img src="view/image/d_social_login/facebook/02.png" class="img-thumbnail img-responsive" />
            <li>After the app is created, go to settings in the left menu</li>
            <li>Fill in Namespace and Contact Email</li>
            <li>Click Add platform and select Website</li>
            <li>Fill in the Site url and mobile site url and save</li>
            <div class="bs-callout bs-callout-warning"><h4>Your Site URL</h4><p>'.HTTPS_CATALOG.'</p></div>
            <img src="view/image/d_social_login/facebook/03.png" class="img-thumbnail img-responsive" />
            <li>In the same page ask to show the App Secret</li>
            <li>Do not forget to activate the APP in the left manu - Status & Review and turn on the APP by sliding the bar to the right</li>
            <li>Fill in the App ID and App Secret in the Social Login settings tab for Facebook</li>
            </ol>
          </div>
        </div>
        <div id="twitter" class="tab-pane">
          <div class="tab-title"><i class="icon-twitter-bird"></i> Setup Twitter login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit twitter developers page <a href="https://dev.twitter.com/" target="_blank">https://dev.twitter.com/</a></li>
            <li>In menu near your icon – select my applications</li>
            <img src="view/image/d_social_login/twitter/01.png" class="img-thumbnail img-responsive" />
            <li>Create new app</li>
            <img src="view/image/d_social_login/twitter/02.png" class="img-thumbnail img-responsive" />
            <li>Fill in all the fields and click save</li>
            <img src="view/image/d_social_login/twitter/03.png" class="img-thumbnail img-responsive" />
            <div class="bs-callout bs-callout-warning"><h4>Your Website and Callback url</h4><p>'.HTTPS_CATALOG.'</p></div>
            <li>Then select your newly created app and go to tab Settings</li>
            <li>Check the checkbox “Allow this application to be used to Sign in with Twitter" and click Save</li>
            <img src="view/image/d_social_login/twitter/05.png" class="img-thumbnail img-responsive" />
            <li>Then go to tab API Keys and click "Generate my access token</li>
            <li>Fill in the App Key and App Secret in the Social Login settings tab for Twitter</li>
            <img src="view/image/d_social_login/twitter/04.png" class="img-thumbnail img-responsive" />
            </ol> 
          </div>
        </div>
        <div id="google_plus" class="tab-pane">
          <div class="tab-title"><i class="icon-google-plus"></i> Setup Google+ login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit the Google Developers console <a href="https://cloud.google.com/console" target="_blank"> https://cloud.google.com/console</a></li>
            <li>Create a new project</li>
            <img src="view/image/d_social_login/google/01.png" class="img-thumbnail img-responsive" />
            <li>Fill in the project name and click save – wait for several seconds for the project to be created. </li>
            <img src="view/image/d_social_login/google/02.png" class="img-thumbnail img-responsive" />
            <li>Select the newly created project. Go to APIs & auth on the left menu and then to Credentials</li>
            <img src="view/image/d_social_login/google/03.png" class="img-thumbnail img-responsive" />
            <li>Click button - create new client id</li>
            <li>In the popup select web applications and fill in the urls. Please fill in the Redirect URIs with the correct url</li>
            <div class="bs-callout bs-callout-warning"><h4>Your Redirect URL</h4><p>'.HTTPS_CATALOG.'index.php?route=module/d_social_login/hybridauth&hauth.done=Google</p></div>
            <img src="view/image/d_social_login/google/04.png" class="img-thumbnail img-responsive" />
            <li>Fill in the Client Id and Client Secret in the Social Login settings tab for Google+</li>
            <img src="view/image/d_social_login/google/05.png" class="img-thumbnail img-responsive" />
            </ol>
            <div class="bs-callout bs-callout-warning"><h4>Attention!</h4><p>If you get an error from google, saying the the app needs to be provided with a name, please follow the following steps:</p></div>
            <ol>
              <li>Go to App settings in google dev console.</li>
              <li>Go to tab APIs & auth  and then to Consent Screen</li>
              <li>Fill the required fields as shown on the image below</li>
              <img src="view/image/d_social_login/google/06.png" class="img-thumbnail img-responsive" />
              <li>Save and wait for several minutes for the Google api to refresh its data then test the login.</li>
            </ol>
          </div>
        </div>
        <div id="linkedin" class="tab-pane">
          <div class="tab-title"><i class="icon-linkedin"></i> Setup Likedin login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Linkedin developers page at <a href="http://developer.linkedin.com/" target="_blank">http://developer.linkedin.com/</a></li>
            <li>Sign in and in the top right menu select API keys</li>
            <li>Click Add new application</li>
            <img src="view/image/d_social_login/linkedin/01.png" class="img-thumbnail img-responsive" />
            <li>Fill in the data according to the screen shot</li>
            <img src="view/image/d_social_login/linkedin/02.png" class="img-thumbnail img-responsive" />
            <div class="bs-callout bs-callout-warning"><h4>Your OAuth 2.0 Redirect urls URL</h4><p>'.HTTPS_CATALOG.'</p></div>
            <li>Be sure to fill in OAuth 2.0 Redirect URLs and select  r_fullprofile, r_emailaddress, r_network and  r_contactinfo. Click save</li>
            <li>Fill in the API key and API Secret in the Social Login settings tab for Linkedin</li>
            <img src="view/image/d_social_login/linkedin/03.png" class="img-thumbnail img-responsive" />
            </ol>
          </div>
        </div>
        <div id="windows_live" class="tab-pane">
          <div class="tab-title"><i class="icon-windows-live"></i> Setup Live login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit live app developers page <a href="https://account.live.com/developers/applications/index" target="_blank"> https://account.live.com/developers/applications/index</a></li>
            <img src="view/image/d_social_login/live/01.png" class="img-thumbnail img-responsive" />
            <li>Click Create application</li>
            <img src="view/image/d_social_login/live/02.png" class="img-thumbnail img-responsive" />
            <li>Fill in the name and select the language</li>
            <img src="view/image/d_social_login/live/03.png" class="img-thumbnail img-responsive" />
            <li>After the app is created, select API parameters from menu on the left</li>
            <li>Fill in the redirect url (if you shop is in a subfolder, specify the subfolder) and save</li>
            <img src="view/image/d_social_login/live/04.png" class="img-thumbnail img-responsive" />
            <div class="bs-callout bs-callout-warning"><h4>Your URL-Forwarding address</h4><p>'.HTTPS_CATALOG.'</p></div>
            <li>Go to Application settings from the menu on the left</li>
            <li>Fill in the Client Id and Client Secret in the Social Login settings tab for Live</li>
            <img src="view/image/d_social_login/live/05.png" class="img-thumbnail img-responsive" />
            </ol>
          </div>
        </div>
        <div id="vkontakte" class="tab-pane">
          <div class="tab-title"><i class="icon-vkontakte"></i> Setup Vkontakte login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Vkontakte app developers page <a href="http://vk.com/dev" target="_blank"> http://vk.com/dev</a></li>
            <li>Click create application</li>
            <img src="view/image/d_social_login/vkontakte/01.png" class="img-thumbnail img-responsive" />
            <li>Fill in name and select web-site. Follow the instructions. Enter the url of the website and the base domains. </li>
            <img src="view/image/d_social_login/vkontakte/02.png" class="img-thumbnail img-responsive" />
            <div class="bs-callout bs-callout-warning"><h4>Your site address</h4><p>'.HTTPS_CATALOG.'</p></div>
            <li>Ask for a code to be sent to your phone number as sms and fill it in as instructed</li>
            <img src="view/image/d_social_login/vkontakte/03.png" class="img-thumbnail img-responsive" />
            <li>Fill in the App Id and App Secret in the Social Login settings tab for Vkontakte</li>
            <img src="view/image/d_social_login/vkontakte/04.png" class="img-thumbnail img-responsive" />
            </ol>
          </div>
        </div>
        <div id="yandex" class="tab-pane">
          <div class="tab-title"><i class="icon-yandex"></i> Setup Yandex login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Yandex app developers page <a href="https://oauth.yandex.ru/client/new" target="_blank"> https://oauth.yandex.ru/client/new</a></li>
            <li>Fill in the name, rights (yandex.login – check all the 3 checkboxs), callback url (important to input the correct url - 
            http://mywebstite.com/catalog/model/d_social_login/hybridauth.php?hauth.done=Yandex) and save</li>
            <img src="view/image/d_social_login/yandex/01.png" class="img-thumbnail img-responsive" />
            <li>Fill in the App Id and App Secret in the Social Login settings tab for Yandex</li>
            <div class="bs-callout bs-callout-warning"><h4>Your CallBack Url</h4><p>'.HTTPS_CATALOG.'index.php?route=module/d_social_login/hybridauth&hauth.done=Yandex</p></div>
            <img src="view/image/d_social_login/yandex/02.png" class="img-thumbnail img-responsive" />
            </ol>
          </div>
        </div>
        <div id="paypal" class="tab-pane">
          <div class="tab-title"><i class="icon-paypal"></i> Setup Paypal login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Paypal app developers page <a href="https://developer.paypal.com/webapps/developer/applications" target="_blank"> https://developer.paypal.com/webapps/developer/applications</a></li>
            <li>Login with your paypal account.</li>
            <li>Click Create App</li>
            <img src="view/image/d_social_login/paypal/01.png" class="img-thumbnail img-responsive" />
            <li>Fill in the name. You should also have a sandbox developer account. If you do not have one, create one here <a href="https://developer.paypal.com/webapps/developer/applications/accounts">Sandbox accounts</a></li>
            <li>Once the app is created, you will have a page with all the credentials. Checkbox the Login with paypal.</li>
            <img src="view/image/d_social_login/paypal/02.png" class="img-thumbnail img-responsive" />
            <li>Edit App redirect URLs: you must fill in App return URL (live) with the following data:</li>
            <div class="bs-callout bs-callout-warning"><h4>Your App return Url</h4><p>'.HTTPS_CATALOG.'index.php?route=module/d_social_login/hybridauth&hauth.done=Paypal</p></div>
            <li>Very important - click advance options and check all the checkbox (Personal Information, Address Information, Account Information), fill in the required urls and click SAVE</li>
            <img src="view/image/d_social_login/paypal/03.png" class="img-thumbnail img-responsive" />
            <li>Fill in the Client Id and Secret (LIVE only, NOT Test) in the Social Login settings tab for Paypal. You are ready to go.</li>
            </ol>
          </div>
        </div>
        <div id="instagram" class="tab-pane">
          <div class="tab-title"><i class="icon-instagrem-square"></i> Setup Instagram login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Instagram app developers page <a href="http://instagram.com/developer/clients/manage/" target="_blank"> http://instagram.com/developer/clients/manage/</a></li>
            <li>Click Create App</li>
            <li>Fill in the Application Name, description, website (your domain name) and OAuth redirect_uri like so:</li>
            <div class="bs-callout bs-callout-warning"><h4>Your OAuth redirect_uri</h4><p>'.HTTPS_CATALOG.'index.php?route=module/d_social_login/hybridauth&hauth.done=Instagram</p></div>
            <img src="view/image/d_social_login/instagram/01.png" class="img-thumbnail img-responsive" />
            <li>Once the app is created, you will have a page with all the credentials.</li>
            <img src="view/image/d_social_login/instagram/02.png" class="img-thumbnail img-responsive" />
            <li>Fill in the Client Id and Client Secret in the Social Login settings tab for Instagram. You are ready to go.</li>
            </ol>
          </div>
        </div>
        <div id="tumblr" class="tab-pane">
          <div class="tab-title"><i class="icon-tumblr"></i> Setup Tumblr login button</div>
          <div class="tab-body">
            <ol>
            <li>Visit Tumblr app developers page <a href="https://www.tumblr.com/oauth/apps" target="_blank"> https://www.tumblr.com/oauth/apps</a></li>
            <li>Click Create App</li>
            <li>Fill in the Application Name, Application website, Application description, Administrative contact email and Default callback URL:</li>
            <div class="bs-callout bs-callout-warning"><h4>Your Default callback URL</h4><p>'.HTTPS_CATALOG.'index.php?route=module/d_social_login/hybridauth&hauth.done=Tumblr</p></div>
            <img src="view/image/d_social_login/tumblr/01.png" class="img-thumbnail img-responsive" />
            <li>Once the app is created, you will have a page with all the credentials on the right side.</li>
            <img src="view/image/d_social_login/tumblr/02.png" class="img-thumbnail img-responsive" />
            <li>Fill in the OAuth consumer key and OAuth consumer secret in the Social Login settings tab for Tumblr. You are ready to go.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
';
?>