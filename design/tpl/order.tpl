<form action="/admin/order/<?php echo $order['order_id']?>" method="post">
  <div class="row">
    <h3 class="col-md-12"></h3>
    <div class="col-md-6 rel">
      <div id="ac_result"></div>
      <div class="alert alert-warning hide">
        <?php echo $app->lang->get("shop_on_request")?>
        <?php echo $app->lang->get("shop_not_found")?>
      </div>
      <h4 class="text-muted"><?php echo $app->lang->get("Client")?></h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th width="120px"><?php echo $app->lang->get("Client name")?></th>
          <td><?php echo $order['fullname']?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?php echo $order['email']?></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Phone")?></th>
          <td><?php echo $order['phone']?></td>
        </tr>
      </table>
      <h4 class="text-muted"><?php echo $app->lang->get("Order")?></h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th><?php echo $app->lang->get("Date")?></th>
          <td><?php echo $order['order_created']?></td>
        </tr>
        <tr>
          <th width="120px"><?php echo $app->lang->get("Manager")?></th>
          <td>
            <select name="order[order_manager]" class="form-control" data-active="<?php echo $order['order_manager']?>">
              <?php foreach ($managers as $manager): ?>
              <option value="<?php echo $manager['id']?>"><?php echo $manager['username']?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Status")?></th>
          <td>
            <select name="order[order_status]" class="form-control" data-active="<?php echo $order['order_status']?>">
              <option value="0"><?php echo $app->lang->get("Deleted")?></option>
              <option value="1"><?php echo $app->lang->get("New")?></option>
              <option value="2"><?php echo $app->lang->get("Sent")?></option>
              <option value="3"><?php echo $app->lang->get("Delivered")?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Payment")?></th>
          <td>
            <select name="order[order_status_pay]" class="form-control"
                    data-active="<?php echo $order['order_status_pay']?>">
              <option value="0"><?php echo $app->lang->get("Not paid")?></option>
              <option value="1"><?php echo $app->lang->get("Paid")?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Comment")?></th>
          <td>
            <textarea class="form-control" name="order[order_comment]"><?php echo $order['order_comment']?></textarea>
          </td>
        </tr>
      </table>
      <h4 class="text-muted"><?php echo $app->lang->get("Shipping")?></h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th width="120px"><?php echo $app->lang->get("Delivery type")?></th>
          <td>
            <select class="form-control nc" name="order[delivery][type]" id="delivery"
                    data-active="<?php if (isset($delivery['type'])) echo $delivery['type']?>">
              <?php foreach ($shipping as $ship): ?>
              <option value="<?php echo $ship['delivery_id']?>"
                      data-delivery="<?php echo $ship['delivery_cost']?>"><?php echo $ship['delivery_title']?>
                (<?php echo $ship['delivery_cost']?>)
              </option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Zip")?></th>
          <td><input class="form-control" type="text" name="order[delivery][index]"
                     value="<?php echo $delivery['index']?>"/></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("City")?></th>
          <td><input class="form-control" type="text" name="order[delivery][city]"
                     value="<?php echo $delivery['city']?>"/></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Street")?></th>
          <td><input class="form-control" type="text" name="order[delivery][ave]"
                     value="<?php echo $delivery['ave']?>"/></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("House")?></th>
          <td><input class="form-control" type="text" name="order[delivery][house]"
                     value="<?php echo $delivery['house']?>"/></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Apartment")?></th>
          <td><input class="form-control" type="text" name="order[delivery][room]"
                     value="<?php echo $delivery['room']?>"/></td>
        </tr>
      </table>
    </div>
    <div class="col-md-6 rel">
      <h4 class="text-muted"><?php echo $app->lang->get("Products")?> (<?php echo count($products)?>)</h4>
      <table class="table table-bordered table-condensed" id="items">
        <thead>
        <th><?php echo $app->lang->get("Product")?></th>
        <th width="100px"><?php echo $app->lang->get("Price")?></th>
        <th width="100px"><?php echo $app->lang->get("Count")?></th>
        <th width="100px"><?php echo $app->lang->get("Cost")?></th>
        <th width="50px"></th>
        </thead>
        <tbody>
        <?php foreach ($products as $item): ?>
        <tr>
          <td><a href="/admin/product/<?php echo $item['product_id']?>"
                 target="_blank"><?php echo $item['product_name']?></a></td>
          <td><input class="form-control" type="text" name="order[item][price][]"
                     value="<?php echo $item['product_price']?>"/></td>
          <td><input class="form-control" type="text" name="order[item][count][]"
                     value="<?php echo $item['product_count']?>"/></td>
          <td><input class="form-control" type="text" readonly
                     value="<?php echo str_replace(',', '.', $item['product_count'] * $item['product_price'])?>"/></td>
          <td>
            <span class="btn btn-danger js_remove"><span class="glyphicon glyphicon-remove-sign"></span></span>
            <input name="order[item][id][]" value="<?php echo $item['product_id']?>" type="hidden"/>
          </td>
        </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
        <tr>
          <td align="center" colspan="5">
            <span class="btn btn-success" id="item_add">
              <span class="glyphicon glyphicon-plus-sign"></span>
              <?php echo $app->lang->get("Add another product to order")?>
            </span>
          </td>
        </tr>
        <tr>
          <th colspan="3"><?php echo $app->lang->get("Total")?></th>
          <th colspan="2"><span></span> <?php //echo $modx->config['shop_curr_default_sign']?></th>
        </tr>
        </tfoot>
      </table>
      <div class="panel panel-default bottom">
        <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> <?php echo $app->
          lang->get("Notification")?>
        </div>
        <div class="panel-body">
          <label><input type="checkbox" name="order[notify][status]" value="yes"/> <?php echo $app->lang->get("Send
            email notification to customer")?></label>
        </div>
      </div>
    </div>
  </div>
  <input name="order[order_id]" value="<?php echo $order['order_id']?>" type="hidden"/>
  <button class="btn btn-primary btn-lg" type="submit"><span
            class="glyphicon glyphicon-ok-sign"></span> <?php echo $app->lang->get("Update order")?>
  </button>
</form>

<script>
  // Calculate order total cost (products + delivery)
  function calcOrder() {
    _total = 0;
    $("#items tbody tr").each(function () {
      _price = parseFloat($(this).find("td:eq(1) input").val());
      _count = parseInt($(this).find("td:eq(2) input").val());
      _cost = _price * _count;
      if (!isNaN(_cost)) {
        $(this).find("td:eq(3) input").val(_cost.toFixed(2));
        _total += parseFloat(_cost);
      }
    })
    _total += parseInt($("#delivery [data-delivery]:selected").attr("data-delivery"));
    $("#items tfoot tr:last span").html(_total.toFixed(2));
  }

  $(document).ready(function () {
    calcOrder();

    // recalculate order cost on ant changes (delivery or product price/count)
    $("#delivery").on("change", function (){calcOrder()});
    $("#items").on("keyup", "input", function () {
      calcOrder();
    });

    // remove any product from order without saving
    $("#items").on("click", ".js_remove", function () {
      if (confirm("<?php echo $app->lang->get('You really want to remove product from order?') ?>")) {
        $(this).closest("tr").remove();
        calcOrder();
      }
    })
  });

</script>