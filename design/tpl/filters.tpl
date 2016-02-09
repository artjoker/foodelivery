<form action="/admin/filters" method="get">
  <div class="row">
    <div class="col-md-4">
      <a href="/admin/filter/add" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>
        <b><?php echo $app->lang->get('Add new filter')?></b>
      </a>
    </div>
    <div class="col-md-3 col-md-offset-3">
      <div class="form-group">
        <select name="category" data-active="<?php echo $app->request->get('category')?>" class="form-control nc">
          <option value="0" selected><?php echo $app->lang->get('All categories')?></option>
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>

    <div class="col-md-2 text-right">
      <div class="form-group">
        <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-filter"></span> <b><?php echo $app->
            lang->get('Apply filter')?></b></button>
      </div>
    </div>
  </div>
</form>
<table class="table table-bordered table-responsive table-condensed table-striped table-hover">
  <thead>
  <tr>
    <th width="35px"><?php echo $app->lang->get('ID')?></th>
    <th><?php echo $app->lang->get('Title')?></th>
    <th><?php echo $app->lang->get('Linked categories')?></th>
    <th><?php echo $app->lang->get('Description')?></th>
    <th width="70px"><?php echo $app->lang->get('Type')?></th>
    <th width="30px"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($filters as $filter): ?>
  <tr>
    <td><?php echo $filter['filter_id']?></td>
    <td><?php echo $filter['filter_name']?></td>
    <td><?php echo $filter['category']?></td>
    <td><small class="text-muted"><?php echo $filter['filter_description']?></small></td>
    <td>
      <?php if ($filter['filter_type'] == 1): ?> <span class="label label-info"><?php echo $app->lang->get('Numeric')?></span><?php endif ?>
      <?php if ($filter['filter_type'] == 2): ?> <span class="label label-success"><?php echo $app->lang->get('OR')?></span><?php endif ?>
      <?php if ($filter['filter_type'] == 3): ?> <span class="label label-danger"><?php echo $app->lang->get('AND')?></span><?php endif ?>
    </td>
    <td><a href="/admin/filter/<?php echo $filter['filter_id']?>" class="btn btn-sm btn-primary" title="<?php echo $app->lang->get('Edit')?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
  </tr>
  <?php endforeach ?>
  </tbody>
 </table>