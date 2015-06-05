
<?php if (!$islogged) { ?>
<style>
<?php foreach($providers as $provider){ ?>
#dsl_<?php echo $provider['id']; ?>_button{
  background:  <?php echo $provider['background_color']; ?>
}
#dsl_google_button:active{
  background: <?php echo $provider['background_color_active']; ?>;
}
<?php } ?>

#d_social_login .dsl-button{
  font-size: 16px;
  text-decoration: none;
  color: #fff;
  display: inline-block;
  box-sizing: border-box;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  box-shadow: 0 1px 0 rgba(0,0,0,0.10);
  margin: 2px;
  margin-bottom: 10px;
  padding:0px;
}
#d_social_login  .dsl-button:hover{
  text-decoration: none;
  box-shadow: inset 0 -2px 0 rgba(0,0,0,0.20);
}
#d_social_login .dsl-button:active{
  box-shadow: inset 0 2px 0 rgba(0,0,0,0.20);
}
#d_social_login .dsl-button.dsl-button-medium .l-side,
#d_social_login .dsl-button .l-side{
  padding: 5px 5px 0px 5px;
  border-right: 1px solid rgba(255,255,255,0.3);
  color: #fff;
  display: inline-block;
  font-size: 17px;
  vertical-align: middle;
  box-sizing: border-box;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
  position: relative;
}
#d_social_login .dsl-button.dsl-button-medium .r-side,
#d_social_login .dsl-button .r-side{
  padding: 6px 10px 6px 10px;
  color: #fff;
  display: inline-block;
  font-size: 12px;
  vertical-align: middle;
  box-sizing: border-box;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
}
#d_social_login .dsl-button.dsl-button-huge .l-side {
  font-size: 26px;
  padding: 8px 10px 8px 8px;
}
#d_social_login .dsl-button.dsl-button-huge .r-side {
  font-size: 15px;
  padding: 12px 20px 11px 20px;
}
#d_social_login .dsl-button.dsl-button-large .l-side {
  font-size: 20px;
  padding: 6px 6px 0px 6px;
}
#d_social_login .dsl-button.dsl-button-large .r-side {
  font-size: 13px;
  padding: 8px 16px 7px 16px;
}
#d_social_login .dsl-button.dsl-button-small .l-side {
  font-size: 14px;
  padding: 4px 4px 0px 4px;
}
#d_social_login .dsl-button.dsl-button-small .r-side {
  font-size: 10px;
  padding: 4px 5px 3px 5px;
}

#d_social_login .dsl-button.dsl-button-icons .l-side {
  font-size: 17px;
  padding: 5px 5px 0px 5px;
  border: none;
}
#d_social_login .dsl-button.dsl-button-icons .r-side {
  display: none;
}
a [class*="dsl-icon-"],
[class*="dsl-icon-"],
[class*="dsl-icon-"]:before{
  margin:0px !important;
  background-image:none !important;
}
.dsl-label {
  display: none;
  vertical-align: middle;
}
.dsl-label-icons{
  display: inline-block;
}
.dsl-hide-icon{
  opacity: 0
}
</style>
<div id="d_social_login">
  <?php if($error){ ?>
  <div class="alert alert-warning"><?php echo $error; ?></div>
  <?php } ?>
  <span class="dsl-label dsl-label-<?php echo $size; ?>"><?php echo $button_sign_in; ?></span>
  <?php foreach($providers as $key => $provider){ ?><?php if ($provider['enabled']) { ?><a id="dsl_<?php echo $provider['id']; ?>_button" class="dsl-button dsl-button-<?php echo $size; ?>" href="index.php?route=module/d_social_login/login&provider=<?php echo $key; ?>"><span class="l-side"><span class="dsl-icon <?php echo $provider['icon']; ?>"></span></span><span class="r-side"><?php echo $provider['heading']; ?></span></a><?php }  ?><?php } ?>
</div>
<script>
$( document ).ready(function() {
  $('.dsl-button').on('click', function(){
    $('.dsl-button').find('.l-side').spin(false);
    $(this).find('.l-side').spin('<?php echo $size; ?>', '#fff');
    
    $('.dsl-button').find('.dsl-icon').removeClass('dsl-hide-icon');
    $(this).find('.dsl-icon').addClass('dsl-hide-icon');
  })
})
</script>
<?php } ?>