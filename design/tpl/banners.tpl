<form action="<?php echo URL_ROOT ?>admin/banners" method="post">
  <p>
    <a href="#add_banner" data-toggle="modal" class="btn btn-success">
      <span class="glyphicon glyphicon-plus-sign"></span>
      <b><?php echo $app->lang->get('Add new banner')?></b>
    </a>
  </p>
  <table class="table table-bordered table-condensed">
    <thead>
    <tr>
      <th width="256px"><?php echo $app->lang->get('Image')?></th>
      <th><?php echo $app->lang->get('Attached')?></th>
      <th width="100px"><?php echo $app->lang->get('Position')?></th>
      <th width="50px"><?php echo $app->lang->get('Active')?></th>
      <th width="100px"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($banners as $banner): ?>
    <tr>
      <td>
        <img src="<?php echo URL_ROOT . $app->image->resize(
        IMAGE_STORAGE . DS . 'banners' . DS . $banner['banner_image'],
        array( 'w' => 256, 'h' => 128, 'zc' => 1 ),
        'backend'
        ) ?>" alt="<?php echo IMAGE_STORAGE . DS . 'banners' . DS . $banner['banner_image'];?>" class="thumbnail">
      </td>
      <td>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><input type="radio" <?php echo $banner['banner_link_type'] == 1 ? "checked" : "" ?>
                name="banner[<?php echo $banner['banner_id']?>][type]" value="product"> <?php echo $app->
                lang->get('Product')?></label>
              <input type="text" name="banner[<?php echo $banner['banner_id']?>][product]" value="<?php echo $banner['banner_link_id']?>"
                     placeholder="<?php echo $app->lang->get('Type single ProductID here')?>" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><input type="radio" <?php echo $banner['banner_link_type'] == 2 ? "checked" : "" ?>
                name="banner[<?php echo $banner['banner_id']?>][type]" value="category"> <?php echo $app->
                lang->get('Category')?></label>
              <select name="banner[<?php echo $banner['banner_id']?>][category]" data-active="<?php echo $banner['banner_link_id']?>" class="form-control nc">
                <option value="0" selected><?php echo $app->lang->get('Not chosen')?></option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>
      </td>
      <td><input type="text" name="banner[<?php echo $banner['banner_id']?>][position]" value="<?php echo $banner['banner_position']?>" class="form-control text-center">
      </td>
      <td>
        <input type="checkbox" value="yes" name="banner[<?php echo $banner['banner_id']?>][active]" <?php echo $banner['banner_active'] == 1 ? "checked" : "" ?>>
      </td>
      <td>
        <a href="#" class="btn btn-primary btn-block js_banner_update"><span class="glyphicon glyphicon-ok-sign"></span>
          <b><?php echo $app->lang->get('Update') ?></b></a>
      </td>
    </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>
<div class="modal fade" id="add_banner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('Add new banner')?></h4>
      </div>
      <form action="<?php echo URL_ROOT ?>admin/banners" enctype="multipart/form-data" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label><?php echo $app->lang->get('Image')?></label>
            <input type="file" name="banner" allow="image/png,image/jpeg">
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Attachment')?></label>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><input type="radio" checked name="banner[type]" value="product"> <?php echo $app->
                    lang->get('Product')?></label>
                  <input type="text" name="banner[product]" value=""
                         placeholder="<?php echo $app->lang->get('Type single ProductID here')?>" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><input type="radio" name="banner[type]" value="category"> <?php echo $app->
                    lang->get('Category')?></label>
                  <select name="banner[category]" class="form-control nc">
                    <option value="0" selected><?php echo $app->lang->get('Not chosen')?></option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Position')?></label>
                <input type="number" name="banner[position]" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Active')?></label>
                <br>
                <input type="checkbox" checked name="banner[active]" value="yes" class="make-switch">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success pull-right"><b><?php echo $app->lang->get('Save')?></b> <span
                    class="glyphicon glyphicon-ok-sign"></span></button>
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span
                    class="glyphicon glyphicon-remove-sign"></span> <?php echo $app->lang->get('Cancel')?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $(".js_banner_update").on("click", function(){
      _this = $(this);
      $.ajax({
        url:'<?php echo URL_ROOT ?>ajax/update_banner',
        data: _this.closest("tr").find("input,select").serialize(),
        success:function(){
          _this.removeClass('btn-primary').addClass('btn-success');
          setTimeout(function(){_this.removeClass('btn-success').addClass('btn-primary')}, 1000);
        }
      });
      return false;
    })
  })
</script>