<form action="/admin/product/<?php echo $product['product_id']?>" method="post">
  <div class="row">
    <div class="col-md-5">
      <div class="form-group">
        <label><?php echo $app->lang->get('Name')?></label>
        <input type="text" name="product[name]" value="<?php echo $product['product_name']?>" required
               class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Code')?> <span class="label label-danger"><?php echo $app->lang->get('Must be unique!')?></span></label>
        <input type="text" name="product[code]" value="<?php echo $product['product_code']?>" required
               class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Price')?></label>
        <input type="text" name="product[price]" value="<?php echo $product['product_price']?>" required
               class="form-control">
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label><?php echo $app->lang->get('Available')?></label>
            <br>
            <input type="checkbox" name="product[available]"
                   value="yes" <?php if ($product['product_visible']) echo "checked" ?> class="make-switch">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label><?php echo $app->lang->get('Status')?></label>
            <select name="product[available]" data-active="<?php echo $product['product_available']?>"
                    class="form-control">
              <option value="0"><?php echo $app->lang->get('Out of stock')?></option>
              <option value="1"><?php echo $app->lang->get('In stock')?></option>
              <option value="2"><?php echo $app->lang->get('New')?></option>
              <option value="3"><?php echo $app->lang->get('Sale')?></option>
              <option value="4"><?php echo $app->lang->get('Action')?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Categories')?></label>
        <br>
        <select name="product[available]" multiple data-active="<?php echo $product['category']?>" class="form-control">
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Intro')?></label>
        <textarea name="product[intro]" id="intro" class="form-control"
                  rows="3"><?php echo $product['product_intro']?></textarea>
      </div>
    </div>
    <div class="col-md-7">
      <label><?php echo $app->lang->get('Images')?></label>
      <a href="#" class="btn btn-xs pull-right btn-success" id="uploader"><span class="glyphicon glyphicon-picture"></span> <b><?php echo $app->lang->get('Upload images')?></b></a>
      <div class="clearfix"></div>
      <ul id="product_images" class="gallery">

      </ul>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label><?php echo $app->lang->get('Description')?></label>
        <textarea name="product[description]" id="description" class="form-control"
                  rows="20"><?php echo $product['product_name']?></textarea>
      </div>
    </div>
  </div>
  <input type="hidden" name="product[id]" value="<?php echo $product['product_id']?>">
  <input type="hidden" name="product[cover]" id="cover" value="<?php echo $product['product_cover']?>">
  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
    <b><?php echo $app->lang->get('Save')?></b></button>
</form>
<script src="<?php echo URL_JS; ?>tinymce/tinymce.min.js"></script>
<script src="<?php echo URL_JS; ?>ajaxupload.js"></script>
<script>
  function getProductImages() {
    $.ajax({
      url: '/admin/ajax/product_images',
      data:{'product_id': <?php echo $product['product_id']?>},
      success: function (_ajax) {
        $("#product_images").html(_ajax);
        $("#product_images [alt='" + $("#cover").val() + "']").parent("li").addClass("cover");
        $("#product_images [title]").tooltip({placement:'bottom'});
      }
    })
  }
  $(document).ready(function () {
    tinymce.init({
      selector: 'textarea#description',
      plugin: 'advlist',
      a_plugin_option: true,
      a_configuration_option: 400
    });
    getProductImages();

    // set product cover
    $("#product_images").on("click", ".js_product_set_cover", function () {
      $(".cover").removeClass("cover");
      var _this = $(this).closest("li");
      _this.addClass("cover");
      $("#cover").val(_this.find("img").attr("alt"));
    })

    // delete image
    $("#product_images").on("click", ".js_product_image_delete", function () {
      if (!confirm("<?php echo $app->lang->get('Are you sure?')?>")) return false;
      var _this = $(this).closest("li");
      $.ajax({
        url: '/admin/ajax/delete_image',
        data:{'product_id': <?php echo $product['product_id']?>, 'image': _this.find("img").attr("alt")},
        success: function (_ajax) {
         _this.fadeOut().remove();
        }
      })
    })

    // file uploader
    new AjaxUpload($("#uploader"), {
      action: "/admin/ajax/upload",
      multiple: true,
      name: "uploader[]",
      data: {
        "size"  : 2048576,
        "folder": '<?php echo (int)$product['product_id'] == 0 ? 'tmp' : $product['product_id']?>'
      },
      onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){
          alert('<?php echo $app->lang->get('Invalid format') ?>');
          return false;
        }
      },
      onComplete: function(file, response){
        getProductImages();
      }
    });
  })
</script>