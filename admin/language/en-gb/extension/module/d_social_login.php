<?php
// Heading
$_['heading_title']                 = ' <span style="color:#449DD0; font-weight:bold">Social Login</span><span style="font-size:12px; color:#999"> by <a href="https://dreamvention.ee/" style="font-size:1em; color:#999" target="_blank">Dreamvention</a></span>';
$_['heading_title_main']            = 'Social Login';
$_['text_edit']                     = 'Edit Social Login module';
$_['text_module']                   = 'Modules';

$_['text_setting']                  = 'Settings';
$_['text_social_login']             = 'Social logins';
$_['text_registration_field']       = 'Registration form';
$_['text_debug']                    = 'Debug';
$_['text_instruction']              = 'Instructions';
$_['text_sign_in']                  = 'Sign in';

$_['entry_status']                  = 'Status:';
$_['entry_support']                 = 'Support<br/><small>If you need help setting up a social login APP, send us a ticket.</small>';
$_['text_support']                  = 'Open Ticket';
$_['text_expend_to_edit']           = "Expend to edit API & Design";

$_['text_api']                      = 'API added';
$_['text_no_api']                   = 'No API data';
$_['entry_title']                   = 'Label';
$_['help_title']                    = 'Set the storefront label of the buttons. (Ex. Sign in)';
$_['text_icons']                    = 'Icons';
$_['text_small']                    = 'Small';
$_['text_medium']                   = 'Medium';
$_['text_large']                    = 'Large';
$_['text_huge']                     = 'Huge';
$_['text_display']                  = 'display';
$_['text_required']                 = 'required';
$_['entry_size']                    = 'Button Size:';
$_['entry_customer_group']          = '<span data-toggle="tooltip" title="" data-original-title="You can define the customer group for customers, who sign in with the social login option.">Customer group</span>';
$_['entry_newsletter']              = '<span data-toggle="tooltip" title="" data-original-title="You can make every person that registers with social login accept newsletters by default.">Newsletter</span>';
$_['entry_header']                  = '<span data-toggle="tooltip" title="" data-original-title="You can place the social login inside your header. To do this, position {{d_social_login}} in your header.twig file">Header position</span>';
$_['text_header_placeholder_2']     = 'Add this to your header.tpl file';
$_['text_header_placeholder_3']     = 'Add this to your header.twig file';
$_['entry_return_page_url']         = 'Return page.';
$_['help_return_page_url']          = 'Enter the page url that will be used for a newly registered customer to return to (leave empty and the user will return to the previous page).';
$_['placeholder_return_page_url']   = 'ex. '. HTTPS_CATALOG ;
$_['entry_background_img']          = 'Set the background image of the registration popup screen';
$_['entry_debug_mode']              = 'Debug mode';
$_['entry_iframe']                  = 'Set store as background for registration screen';
    
$_['entry_sort_order']              = '
    <h4>Set up Social Login App</h4>
    <p>Before you can use Social logins, you need to set up a Social Network APP. For example, if you want Google+ login to work, you need to create a developers account with Google Developers console, set up an APP and receive API key and secret. Luckily, we have prepared a detailed instructions manual for you. Dive in by clicking "Expend to Edit API and Design" on one of the Social Logins below and click on the Question mark for more instructions. Follow the instructions step-by-step to get the App working. It can be tricky with some. If you face any issues, you can read how to <a href="https://dreamvention.ee/social-login-troubleshooting" target="_blank">troubleshoot</a> or ask us to help you set it up via <a href="http://www.dreamvention.com/support" target="_blank">support</a>.</p><br/>
    <h4>Sort and activate social login buttons</h4><p>You can also set the colors of the buttons and their color when the button is pushed.</p>';

$_['text_background_color']         = 'Button color';
$_['text_background_color_active']  = 'on Push';
$_['text_background_color_hover']   = 'on Hover   ';


$_['entry_fields_sort_order']       = '<h4>Modify Registration form</h4><p>You can sort and activate fields for the registration popup screen. This screen is shown to new customers. After that the customer will use it to only login. (Email is not present because its activated by default) </p>';

$_['text_email']                    = 'Email';
$_['text_firstname']                = 'First name';
$_['text_lastname']                 = 'Last name';
$_['text_telephone']                = 'Telephone';
$_['text_mask']                     = '<span data-toggle="tooltip" title="" data-original-title="You can add a mask filter to the phone input field like this (999) 999-99  -99. Leave empty is you do not want to use it.">Mask</span>';
$_['text_address_1']                = 'Address 1';
$_['text_address_2']                = 'Address 2';
$_['text_city']                     = 'City';
$_['text_postcode']                 = 'Postcode';
$_['text_country_id']               = 'Country';
$_['text_zone_id']                  = 'Region';
$_['text_password']                 = 'Password';
$_['text_confirm']                  = 'Confirm password';
$_['text_company']                  = 'Company name';
$_['text_company_id']               = 'Company id';
$_['text_tax_id']                   = 'Tax id';
    
$_['text_app_settings']             = 'App settings';
$_['text_app_id']                   = 'App id:';
$_['text_app_key']                  = 'App key:';
$_['text_app_scope']                = 'App scope:';
$_['text_app_secret']               = 'App secret:';
    
$_['text_success']                  = 'Success: You have modified module Social Login!';
$_['button_save_and_stay']          = 'Save and Stay';
$_['button_clear_debug_file']       = 'Clear';
$_['text_instruction_social_login'] = 'How to set up Social Logins:';

$_['text_tour_title_1']             = 'Welcome to Social login!';
$_['text_tour_content_1']           = 'This tour will guide you through the Social Login admin panel. Click start.';
$_['text_end']                      = 'End';
$_['text_start']                    = 'Start';
$_['text_tour_title_2']             = 'Activate Status';
$_['text_tour_content_2']           = 'We have activated Social Login for you and displyed it on the customer account page. To edit this, go to Design / Layouts and edit the Account layout.';
$_['text_tour_title_3']             = 'Activate Social login provider';
$_['text_tour_content_3']           = 'Before you can activate a Social Provider, you need to add API key and secret. Expend to see what is inside a Social Provider.';
$_['text_tour_title_4']             = 'Edit design';
$_['text_tour_content_4']           = 'Here you can change the colors of the Social Login provider button.';
$_['text_tour_title_5']             = 'API key and secret';
$_['text_tour_content_5']           = 'Here you need to input the API Key and Secret. To get one, you need to follow the instructions. Click the <i class=\"fa fa-question\"></i> and follow the step-by-step instructions.';
$_['text_tour_title_6']             = 'Settings tab';
$_['text_tour_content_6']           = 'Change the size of the buttons, Select the customer group, that will be attached to the newly created user. Learn more about it by hovering over the question mark.';
$_['text_tour_title_7']             = 'Registration form tab';
$_['text_tour_content_7']           = 'For newly registered customers we show a reigistration form. You can modify it here: sort, activate/deactivate and add a mask to the phone field. You can also modify the background of the registration form popup.';
$_['text_tour_title_8']             = 'Instructions tab';
$_['text_tour_content_8']           = 'If you need more information about how to setup your Social login providers or troubleshoot the module, you can read about in instructions tab.';
$_['text_tour_title_9']             = 'Thank you';
$_['text_tour_content_9']           = 'We hope you\'ll enjoy Social Login. If you need any help, please, feel free to contact us via <a href=\"https://dreamvention.ee/support\" target=\"_blank\">support</a>. Enjoy!';

// // Error
$_['error_permission']              = 'Warning: You do not have permission to modify module  Social Login!';
$_['error_get_file_contents']       = 'Warning: Your debug log file %s is %s!';
$_['success_clear_debug_file']      = 'Success: You have cleared the debug log file';
$_['text_success_setup']            = 'Success: Social Login has been activated and placed in design / layout / account. Please fill out the social login provider api key and secret. ';
$_['entry_debug_file']              = 'Debug file';
$_['text_debug_file_into']          = '<h4>Debug Mode</h4><p>When the debug mode is on, the system will log the process here.</p>';
$_['text_powered_by']               = 'Tested with <a href="https://shopunity.net/extension/social-login-pro" target="_blank">Shopunity.net</a> <br> Find more amazing extensions at <a href="https://dreamvention.ee/" target="_blank">Dreamvention.ee</a>';
$_['text_pro']                      = '<a href="https://dreamvention.ee/social-login-paypal-facebook-instagram-tumbler-etc" target="_blank">52% of all social logins is done with Facebook. Get Facebook, Paypal, Amazon and more with Social Login Pro now!</a>';


$_['text_setup']                    = '<style>
                .welcome {
                    background: url("view/image/d_social_login/bg.svg");
                    background-position: bottom;
                    background-repeat: repeat-x;
                    background-size: 50%;
                    min-height: 700px;
                    padding-top: 50px;
                    padding-bottom: 200px;
                }

                .welcome-into {
                    text-align: center;
                    max-width: 500px;
                    margin: auto;
                }

                .welcome-into-logo {
                    padding-bottom: 15px;
                }

                .welcome-into-heading {
                    font-size: 30px;
                    font-weight: bold;
                    padding-bottom: 15px;
                }

                .welcome-into-text {
                    font-size: 18px;
                    padding-bottom: 30px;
                }

                .welcome-into-start {
                    padding-bottom: 30px;
                }

                .welcome-features {
                    padding: 30px;
                }

                .welcome-features-icon{
                    padding: 20px;
                    height: 130px;
                    text-align: center;
                }

                .welcome-features-icon img{
                    height: 90px;
                }


                .welcome-features-text {
                    text-align: center;
                    font-weight: bold;
                    font-size: 16px;
                }

                .panel .panel-body {
                    padding: 0px;
                }
            </style>
            <div class="welcome" style="
    padding-bottom: 250px;
">
                <div class="welcome-into">
                    <div class="welcome-into-logo"><img src="view/image/d_social_login/logo.svg"/></div>
                    <div class="welcome-into-heading">Social Login</div>
                    <div class="welcome-into-text">40% of customers prefer social login over creating a new account. Yet implementing Social login is not easy. Thanks to this module, you can setup and customize all your favorite social network logins and start converting visitors from Facebook into real customers. 
                    </div>

                    <div class="welcome-into-start">
                        <a class="btn btn-primary btn-lg setup">Setup</a>
                    </div>
                </div>
                <div class="welcome-features">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="welcome-features-icon"><img src="view/image/d_social_login/icons/install.svg" /></div>
                            <div class="welcome-features-text">Step by step<br/> documentation</div>
                        </div>
                        <div class="col-md-3">
                            <div class="welcome-features-icon"><img src="view/image/d_social_login/icons/login.svg" /></div>
                            <div class="welcome-features-text">Complete <br/>Social login</div>
                        </div>
                        <div class="col-md-3">
                            <div class="welcome-features-icon"><img src="view/image/d_social_login/icons/register_field.svg" /></div>
                            <div class="welcome-features-text">Customizable <br/>registation pages</div>
                        </div>
                        <div class="col-md-3">
                            <div class="welcome-features-icon"><img src="view/image/d_social_login/icons/gdpr_compilant.svg" /></div>
                            <div class="welcome-features-text">GDPR <br/>compilant</div>
                        </div>
                        
                    </div>
                </div>
                
            </div>';
?>