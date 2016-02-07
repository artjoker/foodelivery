<form action="/admin/orders" method="get">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <select name="status" data-active="<?php echo $app->request->get('status')?>" class="form-control nc">
          <option value="-1" selected><?php echo $app->lang->get('All')?></option>
          <option value="0" class="bg-default"><?php echo $app->lang->get('Deleted')?></option>
          <option value="1" class="bg-danger"><?php echo $app->lang->get('New')?></option>
          <option value="2" class="bg-warning"><?php echo $app->lang->get('Sent')?></option>
          <option value="3" class="bg-success"><?php echo $app->lang->get('Delivered')?></option>
        </select>
      </div>
    </div>
    <div class="col-md-2 col-xs-6">
      <div class="form-group">
        <div class="input-group dt">
          <input type="text" name="from" placeholder="<?php echo $app->lang->get('Date from')?>"
                 value="<?php echo $app->request->get('from')?>" class="form-control dt">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-2 col-xs-6">
      <div class="form-group">
        <div class="input-group dt">
          <input type="text" name="to" placeholder="<?php echo $app->lang->get('Date to')?>"
                 value="<?php echo $app->request->get('to')?>" class="form-control dt">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-2">
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
    <th rowspan="2" width="50px"><?php echo $app->lang->get('ID')?></th>
    <th rowspan="2" width="130px"><?php echo $app->lang->get('Date')?></th>
    <th rowspan="2"><?php echo $app->lang->get('Client name')?></th>
    <th rowspan="2"><?php echo $app->lang->get('Client email')?></th>
    <th rowspan="2" width="150px"><?php echo $app->lang->get('Cost')?></th>
    <th colspan="2"><?php echo $app->lang->get('Status')?></th>
    <th rowspan="2" width="90px"></th>
  </tr>
  <tr>
    <th width="50px"><?php echo $app->lang->get('Order')?></th>
    <th width="50px"><?php echo $app->lang->get('Payment')?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($orders as $order): ?>
  <tr>
    <td>
      <small class="text-muted"><?php echo $order['order_id'] ?></small>
    </td>
    <td>
      <small><?php echo $order['order_created'] ?></small>
    </td>
    <td><a href="/admin/client/<?php echo $order['internalKey']?>"><?php echo $order['fullname']?></a></td>
    <td class="text-right"><a href="/admin/client/<?php echo $order['internalKey']?>"><?php echo $order['email']?></a>
    </td>
    <td class="text-right"><?php echo $order['order_cost'] ?></td>
    <td class="text-center">
      <?php if ($order['order_status'] == 0): ?><span class="label label-default"><?php echo $app->lang->get('Deleted') ?></span><?php endif ?>
      <?php if ($order['order_status'] == 1): ?><span class="label label-danger"><?php echo $app->
        lang->get('New') ?></span><?php endif ?>
      <?php if ($order['order_status'] == 2): ?><span class="label label-warning"><?php echo $app->
        lang->get('Sent') ?></span><?php endif ?>
      <?php if ($order['order_status'] == 3): ?><span class="label label-success"><?php echo $app->
        lang->get('Delivered') ?></span><?php endif ?>
    </td>
    <td class="text-center">
      <?php if ($order['order_status_pay'] == 0): ?><span class="label label-danger"><?php echo $app->
        lang->get('No')?></span><?php endif ?>
      <?php if ($order['order_status_pay'] == 1): ?><span class="label label-success"><?php echo $app->
        lang->get('Yes')?></span><?php endif ?>
    </td>
    <td>
      <a href="/admin/order/<?php echo $order['order_id']?>" class="btn btn-primary btn-sm">
        <span class="glyphicon glyphicon-info-sign"></span>
        <b><?php echo $app->lang->get('Details')?></b>
      </a>
    </td>
  </tr>
  <?php endforeach ?>
  </tbody>
</table>
<nav>
  <ul class="pagination">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>