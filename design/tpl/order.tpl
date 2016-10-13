<form action="<?php echo URL_ROOT ?>admin/order/<?php echo $order['order_id']?>" method="post" xmlns="http://www.w3.org/1999/html">
  <div class="row">
    <div class="col-md-4">
      <h4 class="text-muted"><?php echo $app->lang->get("Client")?></h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th width="120px"><?php echo $app->lang->get("Client name")?></th>
          <td><?php echo $order['user_firstname']?> <?php echo $order['user_lastname']?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?php echo $order['user_email']?></td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Phone")?></th>
          <td><?php echo $order['user_phone']?></td>
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
            <select name="order[manager]" class="form-control" data-active="<?php echo $order['order_manager']?>">
              <?php foreach ($managers as $manager): ?>
              <option value="<?php echo $manager['manager_id']?>"><?php echo $manager['manager_name']?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Status")?></th>
          <td>
            <select name="order[status]" class="form-control" data-active="<?php echo $order['order_status']?>">
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
            <input type="checkbox" name="order[payment]"
                    value="yes" <?php echo $order['order_status_pay'] == 1 ? "checked" : "" ?> class="make-switch">
          </td>
        </tr>
        <tr>
          <th><?php echo $app->lang->get("Comment")?></th>
          <td>
            <textarea class="form-control" name="order[comment]"><?php echo $order['order_comment']?></textarea>
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
                      data-delivery="<?php echo $ship['delivery_cost']?>"><?php echo $ship['delivery_name']?>
                (<?php echo $ship['delivery_cost']?> <?php echo CURRENCY?>)
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
    <div class="col-md-8">
      <h4 class="text-muted"><?php echo $app->lang->get("Products")?> (<?php echo count($products)?>)</h4>
      <table class="table table-bordered table-condensed" id="items">
        <thead>
        <th><?php echo $app->lang->get("Product")?></th>
        <th width="120px"><?php echo $app->lang->get("Price")?></th>
        <th width="70px"><?php echo $app->lang->get("Count")?></th>
        <th width="120px"><?php echo $app->lang->get("Cost")?></th>
        <th width="50px"></th>
        </thead>
        <tbody>
        <?php foreach ($products as $item): ?>
        <tr>
          <td><img src="<?php echo URL_ROOT.
            $app->image->resize(
              IMAGE_STORAGE . DS .'products' . DS . $item['product_id'] . DS . $item['product_cover'],
              array(
              'w' => 64,
              'h' => 64,
              'far' => 1
              ),
              'backend'
              )
            ?>" alt="<?php echo $item['product_name'] ?>" class="img-thumbnail">
            <a href="<?php echo URL_ROOT ?>admin/product/<?php echo $item['product_id']?>"
                    target="_blank"><?php echo $item['product_name']?></a></td>
          <td>
            <div class="input-group">
              <input class="form-control" type="text" name="order[item][price][]"
                      value="<?php echo $item['product_price']?>"/>
              <span class="input-group-addon"><?php echo CURRENCY?></span>
            </div>
          </td>
          <td><input class="form-control text-center" type="text" name="order[item][count][]"
                    value="<?php echo $item['product_count']?>"/></td>
          <td>
            <div class="input-group">
              <input class="form-control" type="text" readonly
                      value="<?php echo str_replace(',', '.', $item['product_count'] * $item['product_price'])?>"/>
              <span class="input-group-addon"><?php echo CURRENCY?></span>
            </div>
          </td>
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
            <a href="#add_order_product" data-toggle="modal" class="btn btn-success">
              <span class="glyphicon glyphicon-plus-sign"></span>
              <?php echo $app->lang->get("Add another product to order")?>
            </a>
          </td>
        </tr>
        <tr>
          <th colspan="3"><?php echo $app->lang->get("Total")?></th>
          <th colspan="2"><span></span> <?php echo CURRENCY?></th>
        </tr>
        </tfoot>
      </table>
      <div class="panel panel-default bottom">
        <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> <?php echo $app->
          lang->get("Notification")?>
        </div>
        <div class="panel-body">
          <label><input type="checkbox" name="order[notify]" value="<?php echo $order['user_email']?>"/> <?php echo $app->lang->get("Send email notification to customer")?></label>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="order[delivery_cost]" id="delivery_cost">
  <input type="hidden" name="order[total]" id="total">
  <button class="btn btn-primary btn-lg" type="submit"><span
            class="glyphicon glyphicon-ok-sign"></span> <?php echo $app->lang->get("Update order")?>
  </button>
</form>

<div class="modal fade" id="add_order_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get("Add another product to order")?></h4>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <select name="" id="category" class="form-control">
                  <option value="0" selected><?php echo $app->lang->get('Choose category')?></option>
                  <? foreach ($categories as $category): ?>
                  <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
                  <? endforeach?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <select name="" id="products" class="hide form-control">
                </select>
              </div>
            </div>
          </div>
          <div class="panel panel-default hide" id="selected">
            <div class="panel-heading js_title"></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-2">
                  <img src="" alt="" class="thumbnail js_image">
                </div>
                <div class="col-md-9">

                  <p class="js_intro"></p>
                </div>
              </div>
            </div>
            <div class="panel-footer"><b class="js_price"></b> <?php echo CURRENCY?></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="push" class="btn btn-success pull-right hide"><b><?php echo $app->lang->get('Add')?></b> <span
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
    $("#delivery_cost").val($("#delivery [data-delivery]:selected").attr("data-delivery"));
    $("#total").val(_total);
    $("#items tfoot tr:last span").html(_total.toFixed(2));
  }

  $(document).ready(function () {
    calcOrder();
    var _product = {};

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
    $("#category").on("change", function(){
      $.ajax({
        url:'<?php echo URL_ROOT ?>ajax/get_category_products',
        data:{category_id: $(this).val()},
        success:function(_ajax){
          $("#products").html(_ajax);
          $("#products").removeClass("hide");
          $("#selected, #push").addClass("hide");
        }
      })
    })
    $("#products").on("change", function(){
      $.ajax({
        url:'<?php echo URL_ROOT ?>ajax/get_product',
        data:{product_id: $(this).val()},
        dataType:'json',
        success:function(_json){
          _product = _json;
          $("#selected .js_title").html(_json.product_name);
          $("#selected .js_intro").html(_json.product_intro);
          $("#selected .js_price").html(_json.product_price);
          $("#selected .js_image").attr("src", _json.product_image);
          $("#selected, #push").removeClass("hide");
        }
      })
    })
    $("#push").on("click", function(){
      if (_product != undefined) {
        row = $("#items tbody tr:first").clone();
        row.find("img").attr("src", _product.product_image);
        row.find("a").attr("href", "/admin/product/"+_product.product_id).html(_product.product_name);
        row.find("[name*='price'], [readonly]").val(_product.product_price);
        row.find("[name*='count']").val(1);
        row.find("[name*='id']").val(_product.product_id);

        $("#items tbody").append(row.clone());
        calcOrder();
        $("#add_order_product").modal('hide');
      }
      return false;
    })
  });
</script>
