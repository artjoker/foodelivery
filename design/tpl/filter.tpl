<form action="<?php echo URL_ROOT ?>admin/filter/<?php echo $filter['filter_id'] == "" ? 0 : $filter['filter_id']?>" method="post">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="form-group">
        <label><?php echo $app->lang->get('Name')?></label>
        <input type="text" name="filter[name]" value="<?php echo $filter['filter_name']?>" class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Description')?></label>
        <textarea name="filter[description]" rows="3" class="form-control"><?php echo $filter['filter_description']?></textarea>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Type')?></label>
        <br>
        <div class="btn-group" data-toggle="buttons">
          <label class="btn btn-default <?php if ($filter['filter_type'] == 1): ?>active<?php endif ?>">
            <input type="radio" name="filter[type]" value="1" autocomplete="off" <?php if ($filter['filter_type'] == 1): ?>checked<?php endif ?>> <?php echo $app->lang->get('Numeric')?>
          </label>
          <label class="btn btn-default <?php if ($filter['filter_type'] == 2): ?>active<?php endif ?>">
            <input type="radio" name="filter[type]" value="2" autocomplete="off" <?php if ($filter['filter_type'] == 2): ?>checked<?php endif ?>> <?php echo $app->lang->get('OR')?>
          </label>
          <label class="btn btn-default <?php if ($filter['filter_type'] == 3): ?>active<?php endif ?>">
            <input type="radio" name="filter[type]" value="3" autocomplete="off" <?php if ($filter['filter_type'] == 3): ?>checked<?php endif ?>> <?php echo $app->lang->get('AND')?>
          </label>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Attached to next categories')?></label>
        <select name="filter[category][]" multiple data-active="<?php echo $filter['category']?>" class="form-control">
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
          <b><?php echo $app->lang->get('Save')?></b></button>
      </div>
    </div>
  </div>
</form>