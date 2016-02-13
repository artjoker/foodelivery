<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <title><?php echo $title;?></title>
  <meta charset="UTF-8">


  <link href="<?php echo URL_CSS; ?>bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>bootstrap-theme.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>bootstrap-multiselect.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>datetime.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>bootstrap-switch.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>styles.css" rel="stylesheet">


  <script src="<?php echo URL_JS; ?>jquery-2.2.0.min.js"></script>
  <script src="<?php echo URL_JS; ?>bootstrap.min.js"></script>
  <script src="<?php echo URL_JS; ?>bootstrap-multiselect.min.js"></script>
  <script src="<?php echo URL_JS; ?>bootstrap-switch.min.js"></script>
  <script src="<?php echo URL_JS; ?>moment-with-locales.min.js"></script>
  <script src="<?php echo URL_JS; ?>datetime.min.js"></script>

  <script type="text/javascript">

    $(document).ready(function () {
      $.ajaxSetup({type:'POST', cache: false});
      $('.dt').datetimepicker({locale: '<?php echo LANG; ?>',format: 'DD.MM.YYYY'});
      $('.dt_').datetimepicker({locale: '<?php echo LANG; ?>',pickTime: true});

      $("[data-active]").each(function () {
        _ids = $(this).attr("data-active").split(",");
        for (_i in _ids) {
          _option = $(this).find("option[value='" + _ids[_i] + "']");
          _option.prop("selected", true);
        }
      });
      $("[title]").tooltip({placement:'bottom'});
      $('.make-switch').bootstrapSwitch({
        onColor: 'success',
        offColor: 'danger',
        onText: '<?php echo $app->lang->get("Yes")?>',
        offText: '<?php echo $app->lang->get("No")?>',
        size: 'small'
      });
      $('[multiple]').multiselect({
        nonSelectedText: '<?php echo $app->lang->get('Nothing selected')?>',
        numberDisplayed: 7,
        buttonWidth: '100%'
      });
    });
  </script>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
              data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo BRAND ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li class="<?php if ($menu == 'order') echo " active
        ";?>">
        <a href="<?php echo URL_ADMIN ?>orders"><span
                  class="glyphicon glyphicon-shopping-cart"></span> <?php echo $app->lang->get('Orders')?></a>
        </li>
        <li class="<?php if ($menu == 'product') echo " active
        ";?>">
        <a href="<?php echo URL_ADMIN ?>products"><span class="glyphicon glyphicon-briefcase"></span> <?php echo $app->
          lang->get('Products')?></a></li>
        <li class="<?php if ($menu == 'filter') echo " active
        ";?>">
        <a href="<?php echo URL_ADMIN ?>filters"><span class="glyphicon glyphicon-filter"></span> <?php echo $app->
          lang->get('Filters')?></a></li>
        <li class="dropdown <?php if ($menu == 'content') echo " active
        ";?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
             aria-expanded="false"><span class="glyphicon glyphicon-file"></span> <?php echo $app->
            lang->get('Content')?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo URL_ADMIN ?>catalog"><?php echo $app->lang->get('Catalog')?></a></li>
            <li><a href="<?php echo URL_ADMIN ?>shops"><?php echo $app->lang->get('Shops')?></a></li>
            <li><a href="<?php echo URL_ADMIN ?>banners"><?php echo $app->lang->get('Banners')?></a></li>
          </ul>
        </li>
        <li class="dropdown <?php if ($menu == 'users') echo " active ";?>">
        <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true"
             aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <?php echo $app->lang->get('Users')?>
            <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo URL_ADMIN ?>managers"><span
                        class="glyphicon glyphicon-star"></span> <?php echo $app->lang->get('Managers')?></a></li>
            <li><a href="<?php echo URL_ADMIN ?>users"><span
                        class="glyphicon glyphicon-star-empty"></span> <?php echo $app->lang->get('Users')?></a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="<?php if ($menu == 'config') echo " active
        ";?>"><a href="<?php echo URL_ADMIN ?>config"><span class="glyphicon glyphicon-wrench"></span> <?php echo $app->
          lang->get('Config')?></a></li>
        <li><a href="<?php echo URL_ADMIN ?>exit"><span class="glyphicon glyphicon-off"></span> <?php echo $app->
            lang->get('Exit')?></a></li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
  <?php if ('' != $flash['success']): ?>
  <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><?php echo $flash['success']?></strong>
  </div>
  <?php endif ?>
  <?php if ('' != $flash['error']): ?>
  <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><?php echo $flash['error']?></strong>
  </div>
  <?php endif ?>
  <?php echo $content?>
</div>
</body>
</html>