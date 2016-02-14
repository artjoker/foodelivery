<form action="/admin/config" method="post" autocomplete="off">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo $app->
        lang->get('General')?></a></li>
    <li role="presentation"><a href="#email" role="tab" data-toggle="tab"><?php echo $app->lang->get('Email templates')?></a></li>
  </ul>
  <br>
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label><?php echo $app->lang->get('Brand') ?></label>
            <input type="text" name="brand" value="<?php echo BRAND?>" class="form-control">
          </div>
          <div class="form-group">
            <a href="/design/lang.csv" target="_blank" class="btn btn-xs btn-info pull-right"><b><?php echo $app->
                lang->get('Modify translate')?></b></a>
            <label><?php echo $app->lang->get('Language') ?></label>
            <input type="text" name="lang" value="<?php echo LANG?>" class="form-control">
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Page limit in backend') ?></label>
            <input type="number" name="limit" value="<?php echo LIMIT?>" class="form-control">
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Google Map API') ?></label>
            <input type="text" name="gmap_key" value="<?php echo GMAP_KEY?>" class="form-control">
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Currency') ?></label>
            <input type="text" name="currency" value="<?php echo CURRENCY ?>" class="form-control">
          </div>
        </div>
        <div class="col-md-6">
          <h4><?php echo $app->lang->get('SMTP configuration')?></h4>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label><?php echo $app->lang->get('Host')?></label>
                <input type="text" name="mail_host" value="<?php echo MAIL_HOST?>" class="form-control">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo $app->lang->get('Port')?></label>
                <input type="number" name="mail_port" value="<?php echo MAIL_PORT?>" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('User')?></label>
                <input type="text" name="mail_user" value="<?php echo MAIL_USER?>" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $app->lang->get('Password')?></label>
                <input type="password" name="mail_pass" value="<?php echo MAIL_PASS?>" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label><?php echo $app->lang->get('Secure')?></label>
            <br>
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-default  <?php echo MAIL_SECURE == '' ? "active" : "" ?>">
                <input type="radio" name="mail_secure" value="" autocomplete="off" <?php echo MAIL_SECURE == '' ? "checked" : "" ?>> None
              </label>
              <label class="btn btn-default  <?php echo MAIL_SECURE == 'ssl' ? "active" : "" ?>">
                <input type="radio" name="mail_secure" value="ssl" autocomplete="off" <?php echo MAIL_SECURE == 'ssl' ? "checked" : "" ?>> SSL
              </label>
              <label class="btn btn-default  <?php echo MAIL_SECURE == 'tls' ? "active" : "" ?>">
                <input type="radio" name="mail_secure" value="tls" autocomplete="off" <?php echo MAIL_SECURE == 'tls' ? "checked" : "" ?>> TLS
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="email">
      <div class="row">
        <div class="col-md-12">
          <p>
            <a href="#template_help" data-toggle="modal" class="btn btn-info pull-right"><span class="glyphicon glyphicon-info-sign"></span> <b><?php echo $app->lang->get('Email templates placeholders')?></b></a>
          </p>
        </div>
      </div>
      <div class="clearfix"><br></div>
      <div class="row">
        <div class="col-md-12">
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                     aria-controls="collapseOne">
                    <?php echo $app->lang->get('Registration template') ?>
                  </a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Subject')?></label>
                    <input type="text" name="email_subject_reg" value="<?php echo EMAIL_SUBJECT_REG?>" class="form-control">
                  </div>
                  <div class="form-group">
                    <label><?php echo $app->lang->get('HTML body')?></label>
                    <textarea name="email_body_reg" rows="20"><?php echo EMAIL_BODY_REG?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true">
                    <?php echo $app->lang->get('Recovery template') ?>
                  </a>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse " role="tabpanel">
                <div class="panel-body">
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Subject')?></label>
                    <input type="text" name="email_subject_recovery" value="<?php echo EMAIL_SUBJECT_RECOVERY?>"
                           class="form-control">
                  </div>
                  <div class="form-group">
                    <label><?php echo $app->lang->get('HTML body')?></label>
                    <textarea name="email_body_recovery" rows="30"><?php echo EMAIL_BODY_RECOVERY?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"
                     aria-expanded="true">
                    <?php echo $app->lang->get('Order template') ?>
                  </a>
                </h4>
              </div>
              <div id="collapseThree" class="panel-collapse collapse " role="tabpanel">
                <div class="panel-body">
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Subject')?></label>
                    <input type="text" name="email_subject_order" value="<?php echo EMAIL_SUBJECT_ORDER?>"
                           class="form-control">
                  </div>
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Order HTML body')?></label>
                    <textarea name="email_body_order" rows="20"><?php echo EMAIL_BODY_ORDER?></textarea>
                  </div>
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Item HTML')?></label>
                    <textarea name="email_body_order_item" rows="20"><?php echo EMAIL_BODY_ORDER_ITEM?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour"
                     aria-expanded="true">
                    <?php echo $app->lang->get('Order change template') ?>
                  </a>
                </h4>
              </div>
              <div id="collapseFour" class="panel-collapse collapse " role="tabpanel">
                <div class="panel-body">
                  <div class="form-group">
                    <label><?php echo $app->lang->get('Subject')?></label>
                    <input type="text" name="email_subject_order_change" value="<?php echo EMAIL_SUBJECT_ORDER_CHANGE?>"
                           class="form-control">
                  </div>
                  <div class="form-group">
                    <label><?php echo $app->lang->get('HTML body')?></label>
                    <textarea name="email_body_order_change" rows="20"><?php echo EMAIL_BODY_ORDER_CHANGE?></textarea>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
    <b><?php echo $app->lang->get('Save')?></b></button>
</form>

<div class="modal fade" id="template_help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('Email templates placeholders')?></h4>
      </div>
      <div class="modal-body">
        <h5><?php echo $app->lang->get('Customer placeholders')?></h5>
        <dl class="dl-horizontal">
          <dt>{user_firstname}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('customer firstname')?> <i>John</i></dd>
          <dt>{user_lastname}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('customer lastname')?> <i>Doe</i></dd>
          <dt>{user_email}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('customer email')?> <i>john.doe@example.com</i></dd>
          <dt>{user_password}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('customer password')?> <i><?php echo uniqid()?></i></dd>
        </dl>
        <h5><?php echo $app->lang->get('Order placeholders')?></h5>
        <dl class="dl-horizontal">
          <dt>{brand}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <i><?php echo BRAND?></i> </dd>
          <dt>{order_id}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('order id')?> <i>1488</i></dd>
          <dt>{order_cost}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('total order cost')?> <i>17.95</i></dd>
          <dt>{order_delivery}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('order delivery type')?> <i>DHL</i></dd>
          <dt>{order_delivery_cost}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('order delivery cost')?> <i>15.00</i></dd>
          <dt>{currency}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <i><?php echo CURRENCY?></i></dd>
          <dt>{order_status}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('new order status')?> <i><?php echo "Done"?></i></dd>
          <dt>{productlist}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('ordered product list')?></dd>
        </dl>
        <h5><?php echo $app->lang->get('Product placeholders')?></h5>
        <dl class="dl-horizontal">
          <dt>{product_image}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('resized product image url')?> </dd>
          <dt>{product_name}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('product name')?> <i>Pizza Peperoni</i></dd>
          <dt>{product_code}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('product code')?> <i>PRD00001234</i></dd>
          <dt>{product_count}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('ordered product quantity')?> <i>4</i></dd>
          <dt>{product_cost}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('ordered product cost')?> <i>product_count * product_price</i></dd>
          <dt>{product_price}</dt>
          <dd><?php echo $app->lang->get('Will be replaced to ')?> <?php echo $app->lang->get('product price')?> <i>99.99</i></dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $app->lang->get('Understand')?></button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo URL_JS; ?>tinymce/tinymce.min.js"></script>
<script>

  $(document).ready(function () {
    tinymce.init({
      selector: 'textarea',
      height: 200,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      language: '<?php echo LANG?>',
      a_plugin_option: true,
      a_configuration_option: 400
    });
  });
</script>