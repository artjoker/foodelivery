<form action="/admin/products" method="get">
  <div class="row">
    <div class="col-md-4">
      <a href="/admin/product/" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>
        <b><?php echo $app->lang->get('Add new product')?></b></a>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <select name="category" data-active="<?php echo $app->request->get('category')?>" class="form-control nc">
        </select>
      </div>
    </div>

    <div class="col-md-3 col-xs-12">
      <div class="form-group">
        <input type="text" name="to" placeholder="<?php echo $app->lang->get('Find by ID or Name')?>"
               value="<?php echo $app->request->get('word')?>" class="form-control">
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
    <th width="50px"><?php echo $app->lang->get('ID')?></th>
    <th><?php echo $app->lang->get('Product name')?></th>
    <th><?php echo $app->lang->get('Category')?></th>
    <th width="70px"><?php echo $app->lang->get('Price')?></th>
    <th width="70px"><?php echo $app->lang->get('Status')?></th>
    <th width="70px"><?php echo $app->lang->get('Availability')?></th>
    <th width="120px"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($products as $product): ?>
  <tr>
    <td>
      <small class="text-muted"><?php echo $product['product_id']?></small>
    </td>
    <td>
      <img src="<?php echo $app->image->resize(IMAGE_STORAGE . '/'.$product['product_id'].'/'.$product['product_cover'], array('w'=>32, 'h'=>32,'far'=>1))?>"
           alt="" class="image-thumbnail"><?php echo $product['product_id']?></td>
    <td><?php echo $product['product_id']?></td>
    <td><?php echo $product['product_id']?></td>
    <td><?php echo $product['product_id']?></td>
    <td>
      <a href="/admin/product/<?php echo $product['product_id']?>" class="btn btn-success"><span
                class="glyphicon glyphicon-pencil"></span> <b><?php echo $app->lang->get('Edit product')?></b></a>
      <a href="/admin/product/<?php echo $product['product_id']?>" class="btn btn-danger"><span
                class="glyphicon glyphicon-pencil"></span> <b><?php echo $app->lang->get('Remove product')?></b></a>
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