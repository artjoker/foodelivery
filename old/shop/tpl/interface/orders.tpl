<table class="table table-condensed table-bordered table-striped">
  <thead>
    <tr>
      <th rowspan="2" width="50px">ID</th>
      <th rowspan="2"><?=$_lang["shop_date"];?></th>
      <th rowspan="2"><?=$_lang["shop_client"];?></th>
      <th rowspan="2"><?=$_lang["shop_cost"];?></th>
      <th colspan="2"><?=$_lang["shop_status"];?></th>
      <th rowspan="2" width="80px"></th>
    </tr>
    <tr>
      <th><?=$_lang["shop_order_r"];?></th>
      <th><?=$_lang["shop_payment"];?></th>
    </tr>
  </thead>
  <tbody>
    <? while ($o = $modx->db->getRow($orders)): ?>
    <tr>
      <td><?=str_pad($o['order_id'], 6, 0, STR_PAD_LEFT)?></td>
      <td><?=$shop->formatDate($o['order_created'])?></td>
      <td><a href="<?=$url?>b=user&user=<?=$o['order_client']?>" target="_blank"> <?=$o['fullname']?> (<?=$o['email']?>)</a></td>
      <td><?=$o['order_cost']?></td>
      <td>
        <? if ($o['order_status'] == 0): ?><span class="label label-default"><?=$_lang["shop_deleted"];?></span><? endif ?>
        <? if ($o['order_status'] == 1): ?><span class="label label-danger"><?=$_lang["shop_new"];?></span><? endif ?>
        <? if ($o['order_status'] == 2): ?><span class="label label-warning"><?=$_lang["shop_sent"];?></span><? endif ?>
        <? if ($o['order_status'] == 3): ?><span class="label label-success"><?=$_lang["shop_delivered"];?></span><? endif ?>
      </td>
      <td>
        <? if ($o['order_status_pay'] == 0): ?><span class="label label-danger"><?=$_lang["shop_not_paid"];?></span><? endif ?>
        <? if ($o['order_status_pay'] == 1): ?><span class="label label-success"><?=$_lang["shop_paid"];?></span><? endif ?>
      </td>
      <td><a href="<?=$url?>b=orders&order=<?=$o['order_id']?>" class="btn btn-mini btn-primary"><span class="glyphicon glyphicon-info-sign"></span> <?=$_lang["shop_details"];?></a></td>
    </tr>
    <? endwhile; ?>
  </tbody>
</table>
<ul class="pagination"><?=$pagin;?></ul>