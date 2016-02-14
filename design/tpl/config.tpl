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
              <label class="btn btn-default active">
                <input type="radio" name="mail_secure" value="" autocomplete="off" checked> None
              </label>
              <label class="btn btn-default">
                <input type="radio" name="mail_secure" value="ssl" autocomplete="off"> SSL
              </label>
              <label class="btn btn-default">
                <input type="radio" name="mail_secure" value="tls" autocomplete="off"> TLS
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="email">
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
  <br>
  <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span>
    <b><?php echo $app->lang->get('Save')?></b></button>
</form>

<script src="<?php echo URL_JS; ?>tinymce/tinymce.min.js"></script>
<script>

  $(document).ready(function () {
    tinymce.init({
      selector: 'textarea',
      height: 500,
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