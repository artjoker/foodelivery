<div class="row">
  <div class="col-md-4">
    <p>
      <a href="#add_category" data-toggle="modal" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>
        <b><?php echo $app->lang->get('Add new category')?></b>
      </a>
    </p>
  </div>
</div>
<table class="table table-bordered table-responsive table-condensed table-striped table-hover">
  <thead>
  <tr>
    <th width="35px"><?php echo $app->lang->get('ID')?></th>
    <th><?php echo $app->lang->get('Title')?></th>
    <th width="70px"><?php echo $app->lang->get('Active')?></th>
    <th width="100px"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($categories as $category): ?>
  <tr>
    <td><?php echo $category['category_id']?></td>
    <td><?php echo $category['category_name']?></td>
    <td class="text-center">
      <?php if ($category['category_active'] == 1): ?>
        <span class="label label-success"><?php echo $app->lang->get('Yes')?></span>
      <?php else: ?>
        <span class="label label-danger"><?php echo $app->lang->get('No')?></span>
      <?php endif ?>
    </td>
    <td class="text-center">
      <a href="#edit_category" data-edit-category="<?php echo $category['category_id']?>" data-toggle="modal" class="btn btn-sm btn-primary" title="<?php echo $app->lang->get('Edit')?>"><span class="glyphicon glyphicon-pencil"></span></a>
      <a href="<?php echo URL_ROOT ?>admin/products?category=<?php echo $category['category_id']?>" class="btn btn-sm btn-info" title="<?php echo $app->lang->get('Browse products in category')?>"><span class="glyphicon glyphicon-briefcase"></span></a>
    </td>
  </tr>
  <?php endforeach ?>
  </tbody>
</table>

<div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('Add new category')?></h4>
      </div>
      <form action="<?php echo URL_ROOT ?>admin/catalog" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label><?php echo $app->lang->get('Name')?></label>
            <input type="text" name="add[name]" id="" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success pull-right"><b><?php echo $app->lang->get('Save')?></b> <span class="glyphicon glyphicon-ok-sign"></span></button>
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span> <?php echo $app->lang->get('Cancel')?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="edit_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('Edit category')?></h4>
      </div>
      <form action="<?php echo URL_ROOT ?>admin/catalog" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label><?php echo $app->lang->get('Name')?></label>
            <input type="text" name="edit[name]" id="" class="form-control">
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Active')?></label>
            <br>
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-success">
                <input type="radio" name="edit[active]" value="1" autocomplete="off" > <b><?php echo $app->lang->get('Yes')?></b>
              </label>
              <label class="btn btn-danger">
                <input type="radio" name="edit[active]" value="0" autocomplete="off" > <b><?php echo $app->lang->get('No')?></b>
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="edit[id]">
          <button type="submit" class="btn btn-success pull-right"><b><?php echo $app->lang->get('Save')?></b> <span class="glyphicon glyphicon-ok-sign"></span></button>
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span> <?php echo $app->lang->get('Cancel')?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $('#edit_category').on('show.bs.modal', function (e) {
      $.ajax({
        url:'<?php echo URL_ROOT ?>ajax/category',
        dataType:'json',
        data: {'category_id' : $(e.relatedTarget).attr("data-edit-category")},
        success:function(_json){
          $("#edit_category [name*='[id]']").val(_json.category_id);
          $("#edit_category [name*='[name]']").val(_json.category_name);
          $("#edit_category .active").removeClass("active");
          $("#edit_category [name*='[active]'][value='"+_json.category_active+"']").prop("checked", true).parent().addClass('active');
        }
      })

    })
  })
</script>