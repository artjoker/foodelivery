<form action="<?php echo URL_ROOT ?>admin/manager/<?php echo $manager['manager_id']?>" method="post">
  <div class="row">
    <div class="col-md-12">
      <p><a href="<?php echo URL_ROOT ?>admin/managers" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> <?php echo $app->lang->get('Back')?></a></p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label><?php echo $app->lang->get('Name')?></label>
        <input type="text" name="manager[name]" value="<?php echo $manager['manager_name']?>" class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Email')?></label>
        <input type="text" name="manager[email]" value="<?php echo $manager['manager_email']?>" class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Shop')?></label>
        <select name="manager[shop]" data-active="<?php echo $manager['shop_id']?>" class="form-control">
          <option value="0" selected></option>
          <?php foreach($shops as $shop): ?>
           <option value="<?php echo $shop['shop_id']?>"><?php echo $shop['shop_name']?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Active')?></label>
        <br>
        <input type="checkbox" name="manager[active]" value="yes" <?php echo $manager['manager_active'] == 1 ? "checked" : "" ?> class="make-switch">
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label><?php echo $app->lang->get('New password')?></label>
            <input type="password" name="manager[pass]" value="" class="form-control">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label><?php echo $app->lang->get('Confirm new password')?></label>
            <input type="password" name="manager[cfm]" value="" class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
    <b><?php echo $app->lang->get('Save')?></b></button>
</form>