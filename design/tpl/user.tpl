<form action="/admin/users/<?php echo $user['user_id']?>" method="post">
  <div class="row">
    <div class="col-md-12">
      <p><a href="/admin/users" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> <?php echo $app->lang->get('Back')?></a></p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Firstname')?></label>
            <input type="text" name="user[firstname]" value="<?php echo $user['user_firstname']?>" class="form-control">
          </div>
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Lastname')?></label>
            <input type="text" name="user[lastname]" value="<?php echo $user['user_lastname']?>" class="form-control">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Email')?></label>
        <input type="text" name="user[email]" value="<?php echo $user['user_email']?>" class="form-control">
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Phone')?></label>
        <input type="text" name="user[phone]" value="<?php echo $user['user_phone']?>" class="form-control">
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Register date')?></label>
            <input type="text" value="<?php echo $user['user_reg_date']?>" readonly class="form-control">
          </div>
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Last visit')?></label>
            <input type="text" value="<?php echo $user['user_last_visit'] == '0000-00-00 00:00:00' ? $app->lang->get('Never') : $user['user_last_visit']?>" readonly class="form-control">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Active')?></label>
        <br>
        <input type="checkbox" name="user[active]" value="yes" <?php echo $user['user_active'] == 1 ? "checked" : ""?> class="make-switch">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Zip')?></label>
            <input type="text" name="user[addr][index]" value="<?php echo $addr['index']?>" class="form-control">
          </div>
          <div class="col-md-6">
            <label><?php echo $app->lang->get('City')?></label>
            <input type="text" name="user[addr][city]" value="<?php echo $addr['city']?>" class="form-control">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo $app->lang->get('Street')?></label>
        <input type="text" name="user[addr][ave]" value="<?php echo $addr['ave']?>" class="form-control">
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label><?php echo $app->lang->get('House')?></label>
            <input type="text" name="user[addr][house]" value="<?php echo $addr['house']?>" class="form-control">
          </div>
          <div class="col-md-6">
            <label><?php echo $app->lang->get('Room')?></label>
            <input type="text" name="user[addr][room]" value="<?php echo $addr['room']?>" class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
    <b><?php echo $app->lang->get('Save')?></b></button>
</form>