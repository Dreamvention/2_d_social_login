<?php
/*
 *  location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="form-inline pull-right">
        <?php if($stores){ ?>
        <select onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()" class="form-control">
          <?php foreach($stores as $store){ ?>
          <?php if($store['store_id'] ==  $store_id){ ?>
          <option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
          <?php }else{ ?>
          <option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select> 
        <?php } ?>
        <!-- <button onClick="saveAndStay()" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button> -->
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

          <ul  class="nav nav-tabs">
            <li class="active" data-toggle="tooltip" data-original-title="<?php echo $text_setting; ?>"><a href="#setting" data-toggle="tab">
              <span class="fa fa-cog"></span> 
            </a></li>
            <li data-toggle="tooltip" data-original-title="<?php echo $text_instruction; ?>"><a href="#instruction" data-toggle="tab">
              <span class="fa fa-graduation-cap"></span> 
            </a></li>
          </ul>


          <div class="tab-content">
          
          <div class="tab-pane active" id="setting" >
            <div class="tab-body">

               <ul  class="nav nav-tabs">
                <li class="active"><a href="#setting_basic" data-toggle="tab">
                  <?php echo $text_setting_basic; ?>
                </a></li>
                <li ><a href="#setting_field" data-toggle="tab">
                  <?php echo $text_setting_field; ?>
                </a></li>
                <li ><a href="#setting_provider" data-toggle="tab">
                  <?php echo $text_setting_provider; ?>
                </a></li>
              </ul>

              <div class="tab-content">
                <div id="setting_basic" class="tab-pane active">

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="input_status"><?php echo $entry_name; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="<?php echo $id;?>_module[setting][name]" id="input_name" class="form-control" value="<?php echo  $setting['name']; ?>"/>
                          
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
                      <div class="col-sm-10">
                        <select name="d_social_login_status" id="input_status" class="form-control">
                          <?php if ($d_social_login_status) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                  </div> 

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="select_size"><?php echo $entry_size; ?></label>
                      <div class="col-sm-10">
                        <select name="<?php echo $id;?>_module[setting][size]" class="form-control">
                          <?php if ($setting['size'] == 'icons') { ?>
                          <option value="icons" selected="selected"><?php echo $text_icons; ?></option>
                          <?php } else { ?>
                          <option value="icons"><?php echo $text_icons; ?></option>
                          <?php } ?>
                          <?php if ($setting['size'] == 'small') { ?>
                          <option value="small" selected="selected"><?php echo $text_small; ?></option>
                          <?php } else { ?>
                          <option value="small"><?php echo $text_small; ?></option>
                          <?php } ?>
                          <?php if ($setting['size'] == 'medium') { ?>
                          <option value="medium" selected="selected"><?php echo $text_medium; ?></option>
                          <?php } else { ?>
                          <option value="medium"><?php echo $text_medium; ?></option>
                          <?php } ?>
                          <?php if ($setting['size'] == 'large') { ?>
                          <option value="large" selected="selected"><?php echo $text_large; ?></option>
                          <?php } else { ?>
                          <option value="large"><?php echo $text_large; ?></option>
                          <?php } ?>
                          <?php if ($setting['size'] == 'huge') { ?>
                          <option value="huge" selected="selected"><?php echo $text_huge; ?></option>
                          <?php } else { ?>
                          <option value="huge"><?php echo $text_huge; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                  </div> 

                  

                  <?php if ($config_files) { ?>
                 <!--  <div class="form-group">
                      <label class="col-sm-2 control-label" for="input_config_file"><?php echo $entry_config_files; ?></label>
                      <div class="col-sm-10">
                        <select name="<?php echo $id;?>_module[setting][config]" id="input_config_file" class="form-control">
                          <?php foreach ($config_files as $config_file) { ?>
                          <option value="<?php echo $config_file; ?>"><?php echo $config_file; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                  </div> -->
                  <?php } ?>

                  <!-- <div class="form-group">
                      <label class="col-sm-2 control-label" for="button_version_check"><?php echo $entry_version_check; ?></label>
                      <div class="col-sm-5">
                        <a onClick="versionCheck('<?php echo $route; ?>', '#alert_version_check', '<?php echo $token; ?>')" class="btn btn-primary"><?php echo $button_version_check; ?></a>
                      </div>
                      <div class="col-sm-5">
                        <div id="alert_version_check"></div>
                      </div>
                  </div> -->

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="input_base_url_index"><?php echo $entry_base_url_index; ?></label>
                      <div class="col-sm-10">
                        <input type="hidden" name="<?php echo $id;?>_module[setting][base_url_index]" value="0" />
                      <input type="checkbox" name="<?php echo $id;?>_module[setting][base_url_index]" <?php echo ($setting['base_url_index'])? 'checked="checked"':'';?> value="1" id="input_base_url_index"/>
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="select_return_page"><?php echo $entry_return_page; ?></label>
                      <div class="col-sm-10">
                        <select name="<?php echo $id;?>_module[setting][return_page]" id="select_return_page" class="form-control">
                      <?php foreach($return_pages as $return_page) {?>
                        <option value="<?php echo $return_page; ?>" <?php echo ($setting['return_page'] == $return_page) ? 'selected="selected"' : ''; ?> ><?php echo $return_page; ?></option>
                      <?php } ?>
                    </select>
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="select_return_url"><?php echo $entry_background_img; ?></label>
                      <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $background_img_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                      <input type="hidden" name="<?php echo $id;?>_module[setting][background_img]" value="<?php echo $setting['background_img']; ?>" id="input-image" />
                      </div>
                  </div>

                </div> <!--  //setting_basic -->
                <div id="setting_field" class="tab-pane">

                  <div class="form-group">
                      <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                      <div class="col-sm-10">
                        <div class="sortable">
                        <?php foreach($providers as $key => $provider) { ?>
                        <div class="well well-sm clearfix sort-item">
                            <div class="row">
                              <div class="col-sm-3">
                              <span for="d_social_login_modules_providers_<?php echo $key; ?>_enabled">
                                <input type="hidden" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][enabled]" value="0" />
                                <input type="checkbox" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][enabled]" <?php echo ($provider['enabled'])? 'checked="checked"':'';?> value="1" id="<?php echo $id;?>_module[setting]_providers_<?php echo $key; ?>_enabled"/>
                                <i class="<?php echo $provider['icon']; ?>"></i> 
                                <?php echo ${'text_'.$provider['id']}; ?>
                              </span>
                              <input type="hidden" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][sort_order]" class="sort-value" value="<?php echo $provider['sort_order']; ?>" /><span class="dsl-icon-<?php echo $provider['id']; ?>"></span>
                              </div>
                              <div class="col-sm-3">
                                <label ><?php echo $text_background_color; ?></label>
                               <div class="input-group color-picker">
                                  <input type="text" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][background_color]" class=" form-control" value="<?php echo $provider['background_color']; ?>" />
                                  <span class="input-group-addon"><i></i></span>
                                </div>
                                
                                
                              </div>
                              <div class="col-sm-3">
                                <label><?php echo $text_background_color_active; ?></label>
                                <div class="input-group color-picker">
                                  <input  type="text" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][background_color_active]" class="form-control" value="<?php echo $provider['background_color_active']; ?>" />
                                  <span class="input-group-addon"><i></i></span>
                                </div>
                              </div>
                              <span class="icon-drag"></span>
                          </div>
                        </div>
                      <?php } ?>

                        <script>
                        $(function(){
                            $('.color-picker').colorpicker();
                        });
                        </script>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" ><?php echo $entry_fields_sort_order; ?></label>
                      <div class="col-sm-10">
                        <div class="sortable ">
                          <?php foreach($fields as $field) { ?>
                          <div class="well well-sm clearfix sort-item">
                            <div class="row">
                            <div class="col-sm-5">
                            <input type="hidden" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][enabled]" value="0" />
                            <input type="checkbox" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][enabled]" <?php echo ($field['enabled'])? 'checked="checked"':'';?> value="1" id="<?php echo $id;?>_module_fields_<?php echo $field['id']; ?>_enabled" />
                            <label for="<?php echo $id;?>_module_fields_<?php echo $field['id']; ?>_enabled"><?php echo ${'text_'.$field['id']}; ?></label>
                            <input type="hidden" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][sort_order]" class="sort-value" value="<?php echo $field['sort_order']; ?>" />
                            <input type="hidden" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][type]" value="<?php echo $field['type']; ?>" />
                            <input type="hidden" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][id]" value="<?php echo $field['id']; ?>" />
                            </div>
                            <?php if(isset($field['mask'])) {?>
                              <label class="col-sm-2">
                                <?php echo $text_mask; ?>
                              </label>
                              <div class="col-sm-3"><input type="text" name="<?php echo $id;?>_module[setting][fields][<?php echo $field['id']; ?>][mask]" value="<?php echo $field['mask']; ?>" class="form-control"/>
                              </div>
                            <?php } ?>
                            <span class="icon-drag"></span>
                            </div>
                          </div>
                         <?php } ?>
                       </div>
                      </div>
                  </div>

                </div> <!--  //setting_field -->
                <div id="setting_provider" class="tab-pane">

                  <div class="bs-callout bs-callout-warning"><h4><?php echo $warning_app_settings; ?></h4><p><?php echo $warning_app_settings_full; ?></p></div>

                  <?php foreach($providers as $key => $provider) { ?>
                  <div class="form-group">
                      <h4 class="col-sm-12" for="select_return_url"><i class="<?php echo $provider['icon']; ?>"></i> <?php echo ${'text_'.$provider['id']}. ' '.$text_app_settings ?><input type="hidden" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][id]" value="<?php echo $provider['id']; ?>" /></h4>
                      <div class="col-sm-12">
                        
                        <?php foreach($provider['keys'] as $k => $v){ ?>
                          <div class="row">
                            <label class="col-sm-2 control-label">
                              <?php echo ${'text_app_'.$k}; ?>
                            </label>
                            <div class="col-sm-10">
                             
                                <input type="text" name="<?php echo $id;?>_module[setting][providers][<?php echo $key; ?>][keys][<?php echo $k; ?>]" value="<?php echo $v; ?>" class="form-control" />          
                              
                            </div>
                          </div>
                          <?php } ?>

                      </div>
                  </div>
                  <?php } ?> 


                </div> <!--  //setting_provider -->
              </div>
            </div>
          </div>
          <div class="tab-pane" id="instruction" >
            <div class="tab-body"><?php echo $text_instructions_full; ?></div>
          </div>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<style>
.sortable {
  position: relative;
}
.sortable .well{
  margin-bottom: 2px; 
}
.sortable .dragged {
    position: absolute;
  z-index: 2000;
  width: 100%;
  display: table;
  background: #fff
}
.sortable .placeholder{
  opacity: 0.8;
  border: dotted 1px #999;
  background: #fff;
  min-height: 42px;

}
</style>
<script type="text/javascript"><!--

// sorting fields
$('.sortable > tr').tsort({attr:'sort-data'});
$(function () {
  

  $(".sortable").sortable({
    //containerSelector: '.sortable',
    itemPath: '',
    itemSelector: '.sort-item',
    distance: '10',
    pullPlaceholder: false,
    placeholder: '<div class="well well-sm clearfix placeholder"> </div>',
    onDragStart: function (item, container, _super) {
      var offset = item.offset(),
      pointer = container.rootGroup.pointer

      adjustment = {
        left: pointer.left - offset.left,
        top: pointer.top - offset.top
      }

      _super(item, container)
    },
    onDrag: function (item, position) {
      item.css({
        left: position.left - adjustment.left,
        top: position.top - adjustment.top
      })
    },
    onDrop: function  (item, container, _super) {
      item.closest('.sortable').find('.row').each(function (i, row) {
        console.log(i)
        $(row).find('.sort-value').val(i)
        
      })
   
      _super(item)
    }
  })
})
//--></script> 
<?php echo $footer; ?>