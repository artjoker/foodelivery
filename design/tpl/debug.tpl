<p>
  <a class="btn btn-sm btn-info" href="<?php echo URL_ROOT ?>debug/phpinfo">phpinfo</a>
  <a class="btn btn-sm btn-success" href="<?php echo URL_ROOT ?>debug/reset">reset admin</a>

</p>
<form action="<?php echo URL_ROOT ?>debug/get" method="post">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <input type="text" name="url" placeholder="URL" class="form-control">
      </div>
      <div class="form-group">
        <input type="text" name="name" placeholder="NAME" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary"><b>get</b></button>
    </div>
    <div class="col-md-6">
      Master key :
      <div class="form-group">
        <textarea readonly rows="3" class="form-control"><?php echo MASTER_KEY ?></textarea>
      </div>

    </div>
  </div>
</form>