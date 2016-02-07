<li>
  <img src="<?php echo $app->image->resize($image, array("w" => 128, "h" => 128, "far"=> 1), "backend"); ?>" alt="<?php echo basename($image)?>" class="img-thumbnail" />
  <a href="#" title="<?php echo $app->lang->get('Set as cover'); ?>" class="btn btn-xs btn-primary js_product_set_cover">
    <span class="glyphicon glyphicon-camera"></span>
  </a>
  <a href="#" title="<?php echo $app->lang->get('Delete'); ?>" class="btn btn-xs btn-danger js_product_image_delete">
    <span class="glyphicon glyphicon-remove-sign"></span>
  </a>
</li>