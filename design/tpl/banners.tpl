<form action="/admin/banners" method="post">
  <p>
    <a href="#" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <b><?php echo $app->
        lang->get('Upload new banner')?></b></a>
  </p>
  <table class="table table-bordered table-condensed">
    <thead>
    <tr>
      <th width="32px"><?php echo $app->lang->get('ID')?></th>
      <th width="256px"><?php echo $app->lang->get('Image')?></th>
      <th><?php echo $app->lang->get('Attached')?></th>
      <th width="100px"><?php echo $app->lang->get('Position')?></th>
      <th width="50px"><?php echo $app->lang->get('Status')?></th>
      <th width="100px"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($banners as $banner): ?>
    <tr>
      <td>
        <small class="text-muted"><?php echo $banner['banner_id']?></small>
      </td>
      <td>
        <img src="<?php echo $app->image->resize(
        IMAGE_STORAGE . DS . "banners" . DS . $banner['banner_image'],
        array( 'w' => 256, 'h' => 128, 'zc' => 1 ),
        'backend'
        ) ?>" alt="" class="thumbnail">
      </td>
      <td>
        <div class="form-group">
          <label><input type="radio" <?php echo $banner['banner_link_type'] == 1 ? "checked" : "" ?>
            name="type[<?php echo $banner['banner_id']?>]" value="product"> <?php echo $app->
            lang->get('Product')?></label>
          <input type="text" name="" value="<?php echo $banner['banner_link_id']?>"
                 placeholder="<?php echo $app->lang->get('Type single ProductID here')?>" class="form-control">
        </div>
        <div class="form-group">
          <label><input type="radio" <?php echo $banner['banner_link_type'] == 2 ? "checked" : "" ?>
            name="type[<?php echo $banner['banner_id']?>]" value="category"> <?php echo $app->
            lang->get('Category')?></label>
          <select name="category" data-active="<?php echo $banner['banner_link_id']?>" class="form-control nc">
            <option value="0" selected><?php echo $app->lang->get('Not chosen')?></option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
            <?php endforeach ?>
          </select>
        </div>
      </td>
      <td><input type="text" name="" value="<?php echo $banner['banner_position']?>" class="form-control text-center"></td>
      <td>
        <?php if ($banner['banner_active'] == 0): ?><span class="label label-danger"><?php echo $app->lang->get('No') ?></span><?php endif ?>
        <?php if ($banner['banner_active'] == 1): ?><span class="label label-success"><?php echo $app->lang->get('Yes') ?></span><?php endif ?>
      </td>
      <td>
        <a href="#" class="btn btn-success btn-block"><span class="glyphicon glyphicon-eye-open"></span> <b><?php echo $app->lang->get('Activate') ?></b></a>
        <a href="#" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-eye-close"></span> <b><?php echo $app->lang->get('Deactivate') ?></b></a>
      </td>
    </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>