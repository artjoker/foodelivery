<form action="<?php echo URL_ROOT ?>admin/product/<?php echo $product['product_id']?>" autocomplete="off" id="js_frm_product" method="post">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#tab_general" aria-controls="home" role="tab" data-toggle="tab"><?php echo $app->lang->get('General') ?></a>
    </li>
    <li role="presentation">
      <a href="#tab_filters" aria-controls="home" role="tab" data-toggle="tab"><?php echo $app->lang->get('Filters') ?></a>
    </li>
  </ul>
  <br>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tab_general">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label><?php echo $app->lang->get('Name')?></label>
            <input type="text" name="product[name]" value="<?php echo $product['product_name']?>" required
                    class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Code')?>
                  <span class="label label-danger"><?php echo $app->lang->get('Must be unique!')?></span></label>
                <input type="text" id="js_unique" name="product[code]" value="<?php echo $product['product_code']?>" required class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Price')?></label>
                <div class="input-group">
                  <input type="text" name="product[price]" value="<?php echo $product['product_price']?>" required class="form-control">
                  <span class="input-group-addon"><?php echo CURRENCY?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Available')?></label>
                <br>
                <input type="checkbox" name="product[available]"
                        value="yes" <?php if ($product['product_visible']) echo "checked" ?> class="make-switch">
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Status')?></label>
                <select name="product[status]" data-active="<?php echo $product['product_available']?>"
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
            <select name="product[categories][]" multiple data-active="<?php echo $product['category']?>" class="form-control">
              <?php foreach ($categories as $category): ?>
              <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
              <?php endforeach ?>
            </select>
          </div>
          <!-- <div class="form-group">
            <label><?php echo $app->lang->get('Intro')?></label>
        <textarea name="product[intro]" id="intro" class="form-control"
                rows="3"><?php echo $product['product_intro']?></textarea>
          </div> -->
        </div>
        <div class="col-md-8">
          <label><?php echo $app->lang->get('Images')?></label>
          <a href="#" class="btn btn-xs pull-right btn-success" id="uploader"><span class="glyphicon glyphicon-picture"></span>
            <b><?php echo $app->lang->get('Upload images')?></b></a>
          <div class="clearfix"></div>
          <ul id="product_images" class="gallery">

          </ul>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label><?php echo $app->lang->get('Description')?></label>
        <textarea name="product[description]" id="description" class="form-control"
                rows="20"><?php echo $product['product_description']?></textarea>
          </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_filters">
      <table class="table table-condensed table-bordered">
        <thead>
        <tr>
          <th width="200px"><?php echo $app->lang->get('Name')?></th>
          <th width="450px"><?php echo $app->lang->get('Value')?></th>
          <th><?php echo $app->lang->get('Description')?></th>
          <th width="50px"><?php echo $app->lang->get('Type')?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach($filters as $filter): ?>
          <tr>
            <th><?php echo $filter['filter_name']?></th>
            <td>
              <?php if ($filter['filter_type'] == 1): ?>
              <input type="number" name="filter[<?php echo $filter['filter_type']."|".$filter['filter_id']?>]" value="<?php echo $filter['value']?>" class="form-control">
              <?php endif?>
              <?php if ($filter['filter_type'] == 2): ?>
              <input type="checkbox" <?php echo ($filter['value'] == 1 ? "checked" : "") ?> value="yes" class="make-switch js_linked">
              <input type="hidden" name="filter[<?php echo $filter['filter_type']."|".$filter['filter_id']?>]" value="<?php echo ($filter['value'] == 1 ? "yes" : "no") ?>" class="js_with">
              <?php endif?>
              <?php if ($filter['filter_type'] == 3): ?>
                <input type="text" name="filter[<?php echo $filter['filter_type']."|".$filter['filter_id']?>]" value="<?php echo $filter['value']?>" class="form-control">
              <?php endif?>
            </td>
            <td><small><?php echo $filter['filter_description']?></small></td>
            <td class="text-center">
              <?php if ($filter['filter_type'] == 1): ?><span class="label label-info"><?php echo $app->lang->get('Numeric')?></span><?php endif?>
              <?php if ($filter['filter_type'] == 2): ?><span class="label label-success"><?php echo $app->lang->get('OR')?></span><?php endif?>
              <?php if ($filter['filter_type'] == 3): ?><span class="label label-danger"><?php echo $app->lang->get('AND')?></span><?php endif?>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
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
  var _is_valid_form = true;
  function getProductImages() {
    $.ajax({
      url: '<?php echo URL_ROOT ?>ajax/product_images',
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
      height: 500,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      language: '<?php echo LANG?>',
      a_plugin_option: true,
      a_configuration_option: 400
    });
    getProductImages();

    // check product code unique
    $("#js_unique").on("keyup", function () {
      var _base = <?=$existcodes?>;
      if (_base.indexOf($(this).val()) < 0)
        $(this).closest(".form-group").addClass("has-success").removeClass("has-error");
      else
        $(this).closest(".form-group").removeClass("has-success").addClass("has-error");
      _is_valid_form = (_base.indexOf($(this).val()) < 0);
    })

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
        url: '<?php echo URL_ROOT ?>ajax/delete_image',
        data:{'product_id': <?php echo $product['product_id']?>, 'image': _this.find("img").attr("alt")},
        success: function (_ajax) {
          _this.fadeOut().remove();
        }
      })
    })

    // OR filter
    $(".js_linked").on("switchChange.bootstrapSwitch", function(e,s){
      $(this).parents("td").find(".js_with").val(s ? "yes" : "no");
    })

    // file uploader
    new AjaxUpload($("#uploader"), {
      action: "<?php echo URL_ROOT ?>ajax/upload",
      multiple: true,
      name: "uploader[]",
      data: {
        "size": 2048576,
        "folder": '<?php echo (int)$product['product_id'] ?>'
      },
      onSubmit: function (file, ext) {
        if (!(ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))) {
          alert('<?php echo $app->lang->get(' Invalid format ') ?>' ) ;
          return false;
        }
      },
      onComplete: function (file, response) {
        getProductImages();
      }
    });

    $("#js_frm_product").on("submit",function () {
      return _is_valid_form;
    })
  })
</script>