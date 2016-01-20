<html>
<head>
  <title>Login</title>
  <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript"  src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript"  src="catalog/view/javascript/d_social_login/jquery.maskedinput.min.js"></script></head>
<body id="<?php echo $provider ?>">
<style>
body{
  background: <?php echo $background_color; ?>;
  background-image: url('image/<?php echo $background_img; ?>');
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  margin: 0px;
}
#iframe { 
  position: absolute; 
  top:0;
  float: left; 
  clear: both; 
  width: 100%; 
  height: 100%; 
  z-index: 0; 
  left no-repeat; }
#dsl_email{
  width: 400px;
  margin: 0 auto;
  text-align: center;
  z-index: 1000;
  position: relative;
}
#dsl_email_rwapper{
  z-index: 999;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.2);

}
#dsl_email_form{
  padding: 20px;
  background: #fff;
  border: 1px solid #ccc;
  margin-top: 20px;
  border-radius: 6px;
  box-shadow: 0px 3px 8px 0px rgba(0,0,0,0.4);
}
#dsl_email_form > div{
  margin-bottom: 10px;
}
input, select{
  display: inline-block;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  vertical-align: middle;
  width:212px
}
input.error, select.error{
  background: #f2dede;
  border: 1px solid #ebccd1}
.button{
  display: inline-block;
  margin-bottom: 0;
  font-weight: 400;
  text-align: center;
  vertical-align: middle;
  cursor: pointer;
  background-image: none;
  border: 1px solid transparent;
  white-space: nowrap;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  border-radius: 4px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: #fff;
  background-color: #428bca;
  border-color: #357ebd;
  width: 150px;
}
.wait{
  position: absolute;
  margin: 5px;
}
#dsl_email_intro{
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
  box-sizing: border-box;
  background-color: #dff0d8;
  border-color: #d6e9c6;
  color: #333;
  text-align: justify;
  margin-bottom: 20px;
  line-height: 1.4;
}
#dsl_error{
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
  box-sizing: border-box;
  background-color: #f2dede;
  border-color: #ebccd1;
  color: #a94442;
}
#dsl_profile_image{
  height: 32px;
  vertical-align: middle;
  border: 1px solid #ccc;
  margin-right: 4px;
  border-radius: 5px;
}
</style>

<div id="dsl_email">
  <div class="holder">
    <div class="popup">
      <form id="dsl_email_form">
        <div id="dsl_email_intro"><?php echo $text_email_intro; ?></div>
         <?php if(!$customer_data['email']){ ?>
         <div>
          <input type="text" id="email" name="email" value="<?php echo $customer_data['email']; ?>" placeholder="<?php echo $text_email; ?>"/>
        </div>
        <?php } ?>
          <?php foreach($fields as $field){ ?>
            <?php if($field['enabled']) { ?> 
            <div>
            <?php if($field['type'] == "select"){ ?>
              <select id="<?php echo $field['id']; ?>"  name="<?php echo $field['id']; ?>">
                <?php if($field['id']=='country_id') { ?>
                  <?php foreach($countries as $country) {?>
                  <option value="<?php echo $country['country_id']; ?>" <?php echo ($country['country_id'] == $customer_data[$field['id']]) ? 'selected="selected"': ''; ?>><?php echo $country['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            <?php }else{ ?>
              <input type="<?php echo $field['type']; ?>" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" value="<?php echo (isset($customer_data[$field['id']])) ? $customer_data[$field['id']] : ''; ?>" placeholder="<?php echo ${'text_'.$field['id']}; ?>" autocomplete="off"/>
             <?php } ?>
           </div>
             <?php if(isset($field['mask']) && $field['mask']){ ?>
               <script>
               $(document).ready(function() {
                   $("#<?php echo $field['id']; ?>").mask("<?php echo $field['mask']; ?>");
                })
               </script>
             <?php } ?> 
          <?php } ?> 
        <?php } ?> 
        <div>
          <?php if($authentication_data['photo_url']){ ?><img src="<?php echo $authentication_data['photo_url']; ?>" id="dsl_profile_image" /><?php } ?>
          <a id="dsl_email_submit" class="button"><?php echo $button_sign_in_mail; ?></a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if($iframe){ ?>
<div id="dsl_email_rwapper"></div>
<iframe id="iframe" src="<?php echo $iframe; ?>"></iframe>
<?php } ?>
<script>
$(document).ready(function() {

    $('#dsl_email_submit').on('click', function(){
      console.log($( '#dsl_email_form' ).serialize());
      $.ajax({
          url: 'index.php?route=module/d_social_login/register&provider=<?php echo $provider; ?>',
          type: 'post',
          data:    $( '#dsl_email_form' ).serialize(),
          dataType: 'json',
          beforeSend: function() {
            $('#dsl_email_submit').prop('disabled', true);
            $('#dsl_email_submit').after('<span class="wait fa fa-refresh fa-spin"></span>');
          },
          complete: function() {
            $('#dsl_email_submit').prop('disabled', false);
            $('.wait').remove();
          },
          success: function(json) {
            console.log(json)
            $('#dsl_error').remove()
            $('.error').removeClass('error')

            
            if(json['error']){
              var error = '';
              $.each( json['error'], function( i, l ){
                $( "#" + i ).addClass('error');
                error = l;
              });

              $('#dsl_email_intro').after('<div id="dsl_error">'+error+'</div>')
            }
            if(json['redirect']){
              window.location.replace(json['redirect']);
            }
            
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
    })
  })
</script>
<script type="text/javascript"><!--
$('#dsl_email_form select[name=\'country_id\']').bind('change', function() {
  if (this.value == '') return;
  $.ajax({
    url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function() {
      $('#dsl_email_form select[name=\'country_id\']').after('<span class="wait fa fa-refresh fa-spin"></span>');
    },
    complete: function() {
      $('.wait').remove();
    },      
    success: function(json) {
      
      html = '<option value=""><?php echo $text_select; ?></option>';
      
      if (json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
            html += '<option value="' + json['zone'][i]['zone_id'] + '"';
            html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
      }
      
      $('#dsl_email_form select[name=\'zone_id\']').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#dsl_email_form select[name=\'country_id\']').trigger('change');
//--></script>
</body>
</html>