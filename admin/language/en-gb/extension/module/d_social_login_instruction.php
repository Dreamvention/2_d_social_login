<?php
$_['text_instruction']          = 'Instructions';
$_['text_instructions_full']    = '
    <p>Social login lets you implement login buttons of the most popular social networks in the world. With the new version you get more options. When updating, check the instructions manual.</p>
    <h2>Set up buttons</h2>
    <ol>
        <li>You will need to go to settings and set up the buttons App id and secrets for the social logins you want to use.</li>
        <li>Sort them and check the checkbox for those that should be shown</li>
        <li>Once you have edited the settings, use the module tab to place the buttons on the shop like any other module.</li>
    </ol>

    <h2>How to edit language</h2>
    <p>Our module uses the basic language process. There is a language file in catalog/language/english/module/d_social_login.php. You can create your own languag file and update the languages.</p>

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
            <li></li>

            <div class="bs-callout bs-callout-warning">
                <h4>Important if Updating from older versions!</h4>
                <p>We have rethought the process of logging. This is why we have changed the callback urls which solved alot of issues. But now you must reset the callback urls for some of the social logins. If you social login is not working - its probably the callback url.<br/><br/>'.HTTPS_CATALOG.'<strong>d_social_login.php?</strong>hauth.done=Google<br/><br/> </p>
            </div>

            <li>Check that your newly created App is activated. i.e. Facebook keeps new apps deactivated and Twitter needs extra checkbox to allow social logins.</li>


            <li>Check that you have access to your callback path - visit <a href="'.HTTPS_CATALOG.'d_social_login.php">'.HTTPS_CATALOG.'d_social_login.php</a>. You should see this text HybridAuth Open Source Social Sign On PHP Library. hybridauth.sourceforge.net/. If you do not see it - try adding permissions 755 or 777 to your folder '.HTTPS_CATALOG.'catalog/model/d_social_login/ </li>
            <li>Give it 1 hour to refresh the cache on the social network side and try again.</li>
            <li>Send us a support ticket at <a href="http://dreamvention.com/support">dreamvention.com/support</a></li>
        </ol>
    </p>
    <h2>My social logins stopped working after update</h2>
    <p>Thats ok. We have changed the process to add more social logins. You apps will start working in no-time. Simply follow the updated instructions on how to set up social logins.</p>';

$_['text_instructions_tabs'] = array(
    'google-plus' =>
        '<h3 class="tab-title"><i class="fa fa-google-plus"></i> Setup Google+ login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit the Google Developers console <a href="https://cloud.google.com/console" target="_blank"> https://cloud.google.com/console</a></li>
                <li>Select "Enable and manage APIs"</li>
                <img src="view/image/d_social_login/google/01.png" class="img-thumbnail img-responsive" />
                <li>Select "Library"</li>
                <img src="view/image/d_social_login/google/02.png" class="img-thumbnail img-responsive" />
                <li>Click on the "Google+ API"</li>
                <img src="view/image/d_social_login/google/03.png" class="img-thumbnail img-responsive" />
                <li>Click "Enable" on the top</li>
                <img src="view/image/d_social_login/google/04.png" class="img-thumbnail img-responsive" />
                <li>Then on the top right corner click button "Go to Credentials"</li>
                <img src="view/image/d_social_login/google/05.png" class="img-thumbnail img-responsive" />
                <li>Now select "Web browser(Javascript), User data and click button "What credentials do i need?"</li>
                <img src="view/image/d_social_login/google/06.png" class="img-thumbnail img-responsive" />                
                <li>Input the Authorized JavaScript origins and Authorized redirect URIs, then click button "Create client ID"</li>
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Redirect URL</h4><p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Google</p><h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Google</p>
                </div>
                <img src="view/image/d_social_login/google/07.png" class="img-thumbnail img-responsive" />                 
                <li>Select your email, input the project name and click button "Continue"</li>
                <img src="view/image/d_social_login/google/08.png" class="img-thumbnail img-responsive" />   
                <li>Click button "Done"</li>
                <img src="view/image/d_social_login/google/09.png" class="img-thumbnail img-responsive" />                   
                <li>Click on the project</li>
                <img src="view/image/d_social_login/google/10.png" class="img-thumbnail img-responsive" />                  
                <li>Copy Client ID and Client secret and fill in the Client Id and Client Secret in the Social Login settings tab for Google+</li>
                <img src="view/image/d_social_login/google/11.png" class="img-thumbnail img-responsive" />  
            </ol>
            <div class="bs-callout bs-callout-warning">
                <h4>Attention!</h4>
                <p>If you get an error from google, or a white screen- please check that you compleated all the steps.</p>
            </div>
            <ol>
                <li>Go to App settings in google dev console.</li>
                <li>Go to tab APIs & auth  and then to Consent Screen</li>
                <li>Fill the required fields as shown on the image below</li>
                <img src="view/image/d_social_login/google/12.png" class="img-thumbnail img-responsive" />
                <li>Save and wait for several minutes for the Google api to refresh its data then test the login.</li>
            </ol>
        </div>',

    'facebook' =>
        '<h3 class="tab-title"><i class="fa fa-facebook"></i> Setup Facebook login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit facebook developers page <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a></li>
                <li>In menu My Apps – select create new app</li>
                <img src="view/image/d_social_login/facebook/01.jpg" class="img-thumbnail img-responsive" />
                <img src="view/image/d_social_login/facebook/02.jpg" class="img-thumbnail img-responsive" />
                <li>Select Facebook Login</li>
                <img src="view/image/d_social_login/facebook//03.jpg" class="img-thumbnail img-responsive" />
                <li>Fill in Valid OAuth redirect URIs</li>
                <img src="view/image/d_social_login/facebook/04.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning"><h4>Your Site URL</h4>
                  <p>' . HTTP_CATALOG . '</p>
                  <h4>Your Redirect URL for HTTPS still the same http (facebook issue)</h4>
                  <p>' . HTTP_CATALOG . '</p>
                </div>
                <img src="view/image/d_social_login/facebook/05.jpg" class="img-thumbnail img-responsive" />
                <li>Click Add platform and select Website</li>
                <img src="view/image/d_social_login/facebook/06.jpg" class="img-thumbnail img-responsive" />
                <li>Fill in Namespace, Contact Email and site URL</li>
                <img src="view/image/d_social_login/facebook/07.png" class="img-thumbnail img-responsive" />
                <li>Activate api in App Review</li>
                <img src="view/image/d_social_login/facebook/08.jpg" class="img-thumbnail img-responsive" />
                <img src="view/image/d_social_login/facebook/09.png" class="img-thumbnail img-responsive" /> 
                <li>Fill in the App ID and App Secret in the Social Login settings tab for Facebook</li>
            </ol>
        </div>',

    'twitter' =>
        '<h3 class="tab-title"><i class="fa fa-twitter"></i> Setup Twitter login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit twitter developers page <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a></li>
                <li>Click "Create New App"</li>
                <img src="view/image/d_social_login/twitter/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in all the fields and click save</li>
                <img src="view/image/d_social_login/twitter/02.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Website and Callback url</h4>
                    <p>' . HTTP_CATALOG . '</p>
                    <h4>Your Redirect URL for HTTPS</h4><p>' . HTTPS_CATALOG . '</p>
                </div>
                <li>Then select your newly created app and go to tab Settings</li>
                <li>Check the checkbox “Allow this application to be used to Sign in with Twitter" and click Save</li>
                <img src="view/image/d_social_login/twitter/03.png" class="img-thumbnail img-responsive" />
                <li>Then go to tab "Keys and Access Tokens" and click "Generate my access token</li>
                <li>Fill in the App Key and App Secret in the Social Login settings tab for Twitter</li>
                <img src="view/image/d_social_login/twitter/04.png" class="img-thumbnail img-responsive" />
            </ol> 
        </div>',

    'linkedin' =>
        '<h3 class="tab-title"><i class="fa fa-linkedin"></i> Setup Likedin login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Linkedin developers page at <a href="http://developer.linkedin.com/" target="_blank">http://developer.linkedin.com/</a></li>
                <li>Sign in and in the top  menu select My Apps</li>
                <img src="view/image/d_social_login/linkedin/01.png" class="img-thumbnail img-responsive" />
                <li>Click Create  Application</li>
                <img src="view/image/d_social_login/linkedin/02.png" class="img-thumbnail img-responsive" />
                <li>Fill in the data according to the screen shot</li>
                <img src="view/image/d_social_login/linkedin/03.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your OAuth 2.0 Redirect urls URL</h4>
                    <p>' . HTTP_CATALOG . '</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . '</p>
                </div>
                <li>Be sure to fill in OAuth 2.0 Redirect URLs and select  r_fullprofile, r_emailaddress, r_network and  r_contactinfo. Click save</li>
                <li>Fill in the API key and API Secret in the Social Login settings tab for Linkedin</li>
                <img src="view/image/d_social_login/linkedin/04.png" class="img-thumbnail img-responsive" />
            </ol>
        </div>',

    'windows' =>
        '<h3 class="tab-title"><i class="fa fa-windows"></i> Setup Live login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit live app developers page <a href="https://account.live.com/developers/applications/create" target="_blank"> https://account.live.com/developers/applications/create</a></li>
                <img src="view/image/d_social_login/live/01.png" class="img-thumbnail img-responsive" />
                <li>Click Add platform</li>
                <img src="view/image/d_social_login/live/02.png" class="img-thumbnail img-responsive" />
                <li>Choose Web</li>
                <img src="view/image/d_social_login/live/03.png" class="img-thumbnail img-responsive" />
                <li>Fill in the redirect url and domain and save</li>
                <img src="view/image/d_social_login/live/04.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>IMPORTANT: Live Fix</h4>
                    <p>Windows live has blocked the ability to add parems to the url for the callback. this is why this is the only social login that requires its own callback url.</p>
                    <h4>Your URL-Forwarding address</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback_live</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback_live</p>
                </div>
                <li>Fill in the Client Id and Client Secret in the Social Login settings tab for Live</li>
                <img src="view/image/d_social_login/live/05.png" class="img-thumbnail img-responsive" />
            </ol>
        </div>',

    'vk' =>
        '<h3 class="tab-title"><i class="fa fa-vk"></i> Setup Vkontakte login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Vkontakte app developers page <a href="https://vk.com/apps?act=manage" target="_blank"> https://vk.com/apps?act=manage</a></li>
                <li>Click create application</li>
                <img src="view/image/d_social_login/vkontakte/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in name and select web-site. Follow the instructions. Enter the url of the website and the base domains. </li>
                <img src="view/image/d_social_login/vkontakte/02.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your site address</h4><p>' . HTTP_CATALOG . '</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . '</p>
                </div>
                <li>Ask for a code to be sent to your phone number as sms and fill it in as instructed</li>
                <img src="view/image/d_social_login/vkontakte/03.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App Id and App Secret in the Social Login settings tab for Vkontakte</li>
                <img src="view/image/d_social_login/vkontakte/04.png" class="img-thumbnail img-responsive" />
            </ol>
        </div>',

    'yandex' =>
        '<h3 class="tab-title"><i class="fa fa-yandex"></i> Setup Yandex login button</h3>
        <div class="tab-body">
          <ol>
              <li>Visit Yandex app developers page <a href="https://oauth.yandex.ru/client/new" target="_blank"> https://oauth.yandex.ru/client/new</a></li>
              <li>Fill in the name, rights (yandex.login – check all the 3 checkboxs), callback url (important to input the correct url - 
              http://example.com/index.php?route=extension/d_social_login/callback&hauth.done=Yandex) and save</li>
              <img src="view/image/d_social_login/yandex/01.png" class="img-thumbnail img-responsive" />
              <li>Fill in the App Id and App Secret in the Social Login settings tab for Yandex</li>
              <div class="bs-callout bs-callout-warning">
                  <h4>Your CallBack Url</h4>
                  <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Yandex</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Yandex</p>
              </div>
              <img src="view/image/d_social_login/yandex/02.png" class="img-thumbnail img-responsive" />
          </ol>
        </div>',

    'paypal' =>
        '<h3 class="tab-title"><i class="fa fa-paypal"></i> Setup Paypal login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Paypal app developers page <a href="https://developer.paypal.com/developer/applications/" target="_blank"> https://developer.paypal.com/developer/applications/</a></li>
                <li>Login with your paypal account.</li>
                <li>Click Create App</li>
                <img src="view/image/d_social_login/paypal/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in the name. You should also have a sandbox developer account. If you do not have one, create one here <a href="https://developer.paypal.com/developer/accounts/">Sandbox accounts</a></li>
                 <img src="view/image/d_social_login/paypal/02.png" class="img-thumbnail img-responsive" />
                <li>Once the app is created, you will have a page with all the credentials. Checkbox the Login with paypal.</li>
                <img src="view/image/d_social_login/paypal/03.png" class="img-thumbnail img-responsive" />
                <li>Edit App redirect URLs: you must fill in App return URL (live) with the following data:</li>
                <img src="view/image/d_social_login/paypal/04.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your App return Url</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Paypal</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Paypal</p>
                </div>
                <li>Very important - click advance options and check all the checkbox (Personal Information, Address Information, Account Information), fill in the required urls and click SAVE</li>
                <img src="view/image/d_social_login/paypal/05.png" class="img-thumbnail img-responsive" />
                <li>Fill in the Client Id and Secret (LIVE only, NOT Test) in the Social Login settings tab for Paypal. You are ready to go.</li>
            </ol>
        </div>',

    'instagram' =>
        '<h3 class="tab-title"><i class="fa fa-instagram"></i> Setup Instagram login button</h3>
        <div class="tab-body">
          <ol>
              <li>Visit Instagram app developers page <a href="http://instagram.com/developer/clients/manage/" target="_blank"> http://instagram.com/developer/clients/manage/</a></li>
              <li>Click "Register a New Client"</li>
              <img src="view/image/d_social_login/instagram/01.png" class="img-thumbnail img-responsive" />
              <li>Fill in the Application Name, description, website (your domain name) and OAuth redirect_uri like so:</li>
              <div class="bs-callout bs-callout-warning">
                  <h4>Your OAuth redirect_uri</h4>
                  <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Instagram</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Instagram</p>
              </div>
              <img src="view/image/d_social_login/instagram/02.png" class="img-thumbnail img-responsive" />
              <li>Once the app is created, you will have a page with all the credentials.</li>
              <img src="view/image/d_social_login/instagram/03.png" class="img-thumbnail img-responsive" />
              <li>Fill in the Client Id and Client Secret in the Social Login settings tab for Instagram. You are ready to go.</li>
          </ol>
        </div>',

    'tumblr' =>
        '<h3 class="tab-title"><i class="fa fa-tumblr"></i> Setup Tumblr login button</h3>
        <div class="tab-body">
          <ol>
              <li>Visit Tumblr app developers page <a href="https://www.tumblr.com/oauth/apps" target="_blank"> https://www.tumblr.com/oauth/apps</a></li>
              <li>Click Create App</li>
              <li>Fill in the Application Name, Application website, Application description, Administrative contact email and Default callback URL:</li>
              <div class="bs-callout bs-callout-warning">
                  <h4>Your Default callback URL</h4>
                  <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Tumblr</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Tumblr</p>
              </div>
              <img src="view/image/d_social_login/tumblr/01.png" class="img-thumbnail img-responsive" />
              <li>Once the app is created, you will have a page with all the credentials on the right side.</li>
              <img src="view/image/d_social_login/tumblr/02.png" class="img-thumbnail img-responsive" />
              <li>Fill in the OAuth consumer key and OAuth consumer secret in the Social Login settings tab for Tumblr. You are ready to go.</li>
          </ol>
        </div>',

    'yahoo' =>
        '<h3 class="tab-title"><i class="fa fa-yahoo"></i> Setup Yahoo login button</h3>
        <div class="tab-body">
          <ol>
              <li>Visit Yahoo app developers page <a href="https://developer.yahoo.com/apps/create/" target="_blank"> https://developer.yahoo.com/apps/create/</a></li>
              <li>Click Create App</li>
              <img src="view/image/d_social_login/yahoo/1-create-app.png" class="img-thumbnail img-responsive" />
              <li>Fill in the Application Name, Application website, Application description, callback URL and permissions Social Directory (Profiles) - Read/Write Public and Private:</li>
              <div class="bs-callout bs-callout-warning">
                  <h4>Your Default callback URL</h4>
                  <p>' . HTTP_CATALOG . '</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . '</p>
              </div>
              <img src="view/image/d_social_login/yahoo/2-fill-data.png" class="img-thumbnail img-responsive" />
              <li>Once the app is created, you will have a page with all the credentials on the right side.</li>
              <img src="view/image/d_social_login/yahoo/3-keys.png" class="img-thumbnail img-responsive" />
              <li>Fill in the OAuth consumer key and OAuth consumer secret in the Social Login settings tab for Yahoo. You are ready to go.</li>
          </ol>
        </div>',

    'foursquare' =>
        '<h3 class="tab-title"><i class="fa fa-foursquare"></i> Setup Foursquare login button</h3>
        <div class="tab-body">
          <ol>
              <li>Visit Foursquare app developers page <a href="https://foursquare.com/developers/register" target="_blank"> https://foursquare.com/developers/register</a></li>
              <li>Click Create App</li>
              <img src="view/image/d_social_login/foursquare/1-create.png" class="img-thumbnail img-responsive" />
              <li>Fill in the Application Name, Download / welcome page url, Your privacy policy url, Redirect URI(s), Short tagline, Detailed description:</li>
              <div class="bs-callout bs-callout-warning">
                  <h4>Your Default callback URL</h4>
                  <p>' . HTTP_CATALOG . '</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . '</p>
              </div>
              <li>Once the app is created, you will have a page with all the credentials on the right side.</li>
              <img src="view/image/d_social_login/foursquare/2-keys.png" class="img-thumbnail img-responsive" />
              <li>Fill in the OAuth consumer key and OAuth consumer secret in the Social Login settings tab for Foursquare. You are ready to go.</li>
          </ol>
        </div>',

    'odnoklassniki' =>
        '<h3 class="tab-title"><i class="fa fa-odnoklassniki"></i> Setup Odnoklassniki login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Odnoklassniki app developers page <a href="http://ok.ru/dk?st.cmd=appEdit&st._aid=Apps_Info_MyDev_AddApp" target="_blank"> http://ok.ru/dk?st.cmd=appEdit&st._aid=Apps_Info_MyDev_AddApp</a></li>
                <li>Fill in the Application Name, Shortname, Description, Select External, Input the app images with the correct sizes, Redirect URI(s), allowed domains and click create:</li>
                <img src="view/image/d_social_login/odnoklassniki/01.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Default callback URL</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Odnoklassniki</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Odnoklassniki</p>
                </div>
                <li>Once the app is created, you will recieve the Api keys to your account email.</li>
                <li>Fill in the OAuth consumer key and OAuth consumer secret in the Social Login settings tab for Odnoklassniki. You are ready to go.</li>
            </ol>
        </div>',

    'github' =>
        '<h3 class="tab-title"><i class="fa fa-github"></i> Setup Github login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Github app developers page <a href="https://github.com/settings/developers" target="_blank"> https://github.com/settings/developers</a></li>
                <li>Click Register a new application</li>
                <img src="view/image/d_social_login/github/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in the Application Name, Homepage URL, Authorization callback URL</li>
                <img src="view/image/d_social_login/github/02.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Homepage URL</h4>
                    <p>' . HTTP_CATALOG . '</p>
                    <h4>Your Authorization callback URL</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Github</p>
                    <h4>Your Redirect URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Github</p>
                </div>
                <li>Once the app is created, you will have a page with all the credentials.</li>
                <img src="view/image/d_social_login/github/03.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App id and App secret in the Social Login settings tab for Github. You are ready to go.</li>
            </ol>
        </div>',

    'dribbble' =>
        '<h3 class="tab-title"><i class="fa fa-dribbble"></i> Setup Dribbble login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Dribbble app developers page <a href="https://dribbble.com/account/applications" target="_blank"> https://dribbble.com/account/applications</a></li>
                <li>Click Register a new application</li>
                <img src="view/image/d_social_login/dribbble/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in the Name, Description, Website URL, Callback URL</li>
                <img src="view/image/d_social_login/dribbble/02.png" class="img-thumbnail img-responsive" />
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Website URL</h4>
                    <p>' . HTTP_CATALOG . '</p>
                    <h4>Your Callback URL</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Dribbble</p>
                    <h4>Your Callback URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Dribbble</p>
                </div>
                <li>Once the app is created, you will have a page with all the credentials.</li>
                <img src="view/image/d_social_login/dribbble/03.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App id and App secret in the Social Login settings tab for Dribbble. You are ready to go.</li>
            </ol>
        </div>',

    'steam' =>
        '<h3 class="tab-title"><i class="fa fa-steam"></i> Setup Steam login button</h3>
        <div class="tab-body">
        <h4>You do not need API</h4>
        </div>',

    '500px' =>
        '<h3 class="tab-title"><i class="fa fa-500px"></i> Setup 500px login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit 500px app developers page <a href="https://500px.com/settings/applications" target="_blank"> https://500px.com/settings/applications</a></li>
                <li>Click "Register your application"</li>
                <img src="view/image/d_social_login/500px/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in the Name, Description, Application URL, Callback URL, Email and click "Register"</li>
                <div class="bs-callout bs-callout-warning">
                  <h4>Your Default callback URL</h4>
                  <p>' . HTTP_CATALOG . '</p>
                  <h4>Your Redirect URL for HTTPS</h4>
                  <p>' . HTTPS_CATALOG . '</p>
                </div>
                <img src="view/image/d_social_login/500px/02.png" class="img-thumbnail img-responsive" />
                <li>Once the app is created, you will have a page with all the credentials.</li>
                <img src="view/image/d_social_login/500px/03.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App key and App secret in the Social Login settings tab for 500px. You are ready to go.</li>
            </ol>
        </div>',

    'lastfm' =>
        '<h3 class="tab-title"><i class="fa fa-lastfm"></i> Setup LastFM login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit LastFM app developers page <a href="http://www.last.fm/api/account/create" target="_blank"> http://www.last.fm/api/account/create</a></li>
                <li>Fill in the Email, Name, Description, Callback URL, homepage and click "Submit"</li>
                <div class="bs-callout bs-callout-warning">
                    <h4>Your Website URL</h4>
                    <p>' . HTTP_CATALOG . '</p>
                    <h4>Your Callback URL</h4>
                    <p>' . HTTP_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=LastFM</p>
                    <h4>Your Callback URL for HTTPS</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=LastFM</p>
                </div>
                <img src="view/image/d_social_login/lastfm/01.png" class="img-thumbnail img-responsive" />
                <li>Once the app is created, you will have a page with all the credentials.</li>
                <img src="view/image/d_social_login/lastfm/02.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App key and App secret in the Social Login settings tab for LastFM. You are ready to go.</li>
            </ol>
        </div>',

    'amazon' =>
        '<h3 class="tab-title"><i class="fa fa-amazon"></i> Setup Amazon login button</h3>
        <div class="tab-body">
            <ol>
                <li>Visit Amazon app developers page <a href="https://login.amazon.com/manageApps" target="_blank"> https://login.amazon.com/manageApps</a> and then either sign in or create a new account.</li>
                <li>Open your <a href="https://sellercentral.amazon.com/gp/homepage.html" target="_blank"> Applications</a> and click on the Register new application button.</li>
                <li>Select Web settings and Fill in the Name, Description, Privacy Notice URL and click "Save"</li>
                <div class="bs-callout bs-callout-warning">
                    <h4>Privacy Notice URL</h4>
                    <p>' . HTTPS_CATALOG . '</p>
                    <h4>Allowed Return URLs</h4>
                    <p>' . HTTPS_CATALOG . 'index.php?route=extension/d_social_login/callback&hauth.done=Amazon</p>
                </div>
                <img src="view/image/d_social_login/amazon/01.png" class="img-thumbnail img-responsive" />
                <li>Fill in the App id and App secret in the Social Login settings tab for Amazon. You are ready to go.</li>
            </ol>
        </div>',
);