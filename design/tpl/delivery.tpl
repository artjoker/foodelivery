<form action="<?php echo URL_ROOT ?>admin/delivery" method="post">
  <p>
    <a href="#add_delivery" data-toggle="modal" class="btn btn-success"><span
              class="glyphicon glyphicon-plus-sign"></span> <b><?php echo $app->lang->get('Add new delivery')?></b></a>
  </p>
  <table class="table table-bordered table-condensed">
    <thead>
    <tr>
      <th><?php echo $app->lang->get('Name')?></th>
      <th width="150px"><?php echo $app->lang->get('Cost')?></th>
      <th width="50px"><?php echo $app->lang->get('Active')?></th>
      <th width="100px"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($delivery as $del): ?>
    <tr>
      <td>
        <input type="text" name="delivery[<?php echo $del['delivery_id']?>][name]"
               value="<?php echo $del['delivery_name']?>" class="form-control">
      </td>
      <td>
        <div class="input-group">
          <input type="text" name="delivery[<?php echo $del['delivery_id']?>][cost]"
                 value="<?php echo $del['delivery_cost']?>" class="form-control">

          <span class="input-group-addon"><?php echo CURRENCY?></span>
        </div>
      </td>
      <td>
        <input type="checkbox" value="yes"
               name="delivery[<?php echo $del['delivery_id']?>][active]" <?php echo $del['delivery_active'] == 1 ? "checked" : "" ?> class="make-switch" >
      </td>
      <td>
        <a href="#" class="btn btn-primary btn-block js_delivery_update"><span
                  class="glyphicon glyphicon-ok-sign"></span>
          <b><?php echo $app->lang->get('Update') ?></b></a>
      </td>
    </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>
<div class="modal fade" id="add_delivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('Add new delivery')?></h4>
      </div>
      <form action="<?php echo URL_ROOT ?>admin/delivery" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label><?php echo $app->lang->get('Name')?></label>
            <input type="text" name="delivery[name]" id="" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Cost')?></label>
                <div class="input-group">
                  <input type="text" name="delivery[cost]" value="" class="form-control">
                  <span class="input-group-addon"><?php echo CURRENCY?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Active')?></label>
                <br>
                <input type="checkbox" checked name="delivery[active]" value="yes" class="make-switch">
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
  $(document).ready(function () {

    $(".js_delivery_update").on("click", function () {
      _this = $(this);
      $.ajax({
        url: '<?php echo URL_ROOT ?>ajax/update_delivery',
        data: _this.closest("tr").find("input").serialize(),
        success: function () {
          _this.removeClass('btn-primary').addClass('btn-success');
          setTimeout(function (){_this.removeClass('btn-success').addClass('btn-primary')}, 1000);
        }
      });
      return false;
    })
  })
</script>