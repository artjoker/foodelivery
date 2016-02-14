<form action="/admin/products" method="get">
  <div class="row">
    <div class="col-md-4">
      <a href="/admin/product/add" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>
        <b><?php echo $app->lang->get('Add new product')?></b></a>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <select name="category" data-active="<?php echo $app->request->get('category')?>" class="form-control nc">
          <option value="0" selected><?php echo $app->lang->get('All categories')?></option>
          <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>

    <div class="col-md-3 col-xs-12">
      <div class="form-group">
        <input type="text" name="search" placeholder="<?php echo $app->lang->get('Find by ID or Name')?>"
               value="<?php echo $app->request->get('search')?>" class="form-control">
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
    <th><?php echo $app->lang->get('Product name')?></th>
    <th><?php echo $app->lang->get('Category')?></th>
    <th width="70px"><?php echo $app->lang->get('Price')?></th>
    <th width="70px"><?php echo $app->lang->get('Available')?></th>
    <th width="70px"><?php echo $app->lang->get('Status')?></th>
    <th width="100px"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($products as $product): ?>
  <tr>
    <td>
      <small class="text-muted"><?php echo $product['product_id']?></small>
    </td>
    <td>
      <img src="<?php echo
      $app->image->resize(
        IMAGE_STORAGE . DS ."products" . DS . $product['product_id'] . DS . $product['product_cover'],
        array(
          'w'   => 32,
          'h'   => 32,
          'far' => 1
          ),
        'backend'
      )
      ?>" alt="<?php echo $product['product_name'] ?>" class="img-thumbnail">
      <a href="/admin/product/<?php echo $product['product_id'] ?>" target="_blank"><?php echo $product['product_name'] ?></a>

    </td>
    <td><?php echo $product['category']?></td>
    <td class="text-right"><b><?php echo $product['product_price']?></b> <small class="text-muted"><?php echo CURRENCY?></small></td>
    <td class="text-center">
      <?php if ($product['product_visible'] == 0): ?><span class="label label-danger"><?php echo $app->lang->get('No')?></span><?php endif ?>
      <?php if ($product['product_visible'] == 1): ?><span class="label label-success"><?php echo $app->lang->get('Yes')?></span><?php endif ?>
    </td>
    <td class="text-center">
      <?php if ($product['product_available'] == 0): ?><span class="label label-muted"><?php echo $app->lang->get('Out of stock')?></span><?php endif ?>
      <?php if ($product['product_available'] == 1): ?><span class="label label-success"><?php echo $app->lang->get('In stock')?></span><?php endif ?>
      <?php if ($product['product_available'] == 2): ?><span class="label label-danger"><?php echo $app->lang->get('New')?></span><?php endif ?>
      <?php if ($product['product_available'] == 3): ?><span class="label label-warning"><?php echo $app->lang->get('Sale')?></span><?php endif ?>
      <?php if ($product['product_available'] == 4): ?><span class="label label-info"><?php echo $app->lang->get('Action')?></span><?php endif ?>
    </td>
    <td>
      <a href="/admin/product/<?php echo $product['product_id']?>" class="btn btn-primary" title="<?php echo $app->lang->get('Edit')?>">
        <span class="glyphicon glyphicon-pencil"></span>
      </a>
      <a href="/admin/product/<?php echo $product['product_id']?>" title="<?php echo $app->lang->get('Remove')?>" class="btn btn-danger">
        <span class="glyphicon glyphicon-remove-sign"></span>
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