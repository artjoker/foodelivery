<table class="table table-bordered table-responsive table-condensed table-striped table-hover">
  <thead>
  <tr>
    <th width="35px"><?php echo $app->lang->get('ID')?></th>
    <th><?php echo $app->lang->get('Name')?></th>
    <th><?php echo $app->lang->get('Email')?></th>
    <th><?php echo $app->lang->get('Attached shop')?></th>
    <th width="90px"><?php echo $app->lang->get('Active')?></th>
    <th width="100px"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($managers as $user): ?>
  <tr>
    <td>
      <small class="text-muted"><?php echo $user['manager_id']?></small>
    </td>
    <td><?php echo $user['manager_name']?></td>
    <td><?php echo $user['manager_email']?></td>
    <td><?php echo $user['shop']?></td>
    <td class="text-center">
      <?php if ($user['manager_active'] == 1): ?>
      <span class="label label-success"><?php echo $app->lang->get('Yes')?></span>
      <?php else: ?>
      <span class="label label-danger"><?php echo $app->lang->get('No')?></span>
      <?php endif ?>
    </td>
    <td class="text-center">
      <a href="<?php echo URL_ROOT ?>admin/manager/<?php echo $user['manager_id']?>"
         class="btn btn-sm btn-primary" title="<?php echo $app->lang->get('Edit')?>"><span
                class="glyphicon glyphicon-pencil"></span></a>
      <a href="<?php echo URL_ROOT ?>admin/orders?manager=<?php echo $user['manager_id']?>" class="btn btn-sm btn-info"
         title="<?php echo $app->lang->get('Browse manager orders')?>"><span
                class="glyphicon glyphicon-shopping-cart"></span> </a>
    </td>
  </tr>
  <?php endforeach ?>
  </tbody>
</table>