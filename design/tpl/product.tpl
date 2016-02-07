<form action="/admin/product/<?php echo $product['product_id']?>" method="post">
  <div class="row">
    <div class="col-md-5">
      <div class="form-group">
        <label><?php echo $app->lang->get('Name')?></label>
        <input type="text" name="product[name]" value="<?php echo $product['product_name']?>" required class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Code')?> <span class="label label-danger"><?php echo $app->lang->get('Must be unique!')?></span></label>
        <input type="text" name="product[code]" value="<?php echo $product['product_code']?>" required class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Price')?></label>
        <input type="text" name="product[price]" value="<?php echo $product['product_price']?>" required class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Intro')?></label>
        <textarea name="product[intro]" id="intro" class="form-control" rows="3"><?php echo $product['product_intro']?></textarea>
      </div>
    </div>
    <div class="col-md-7">
      <label><?php echo $app->lang->get('Images')?></label>
      <ul id="images">

      </ul>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label><?php echo $app->lang->get('Description')?></label>
        <textarea name="product[description]" id="description" class="form-control" rows="20"><?php echo $product['product_name']?></textarea>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span> <b><?php echo $app->lang->get('Save')?></b></button>
</form>
<script src="<?php echo URL_JS; ?>tinymce/tinymce.min.js"></script>
<script>
  $(document).ready(function(){
    tinymce.init({
      selector: 'textarea#description',
      plugin: 'advlist',
      a_plugin_option: true,
      a_configuration_option: 400
    });

  })
</script>