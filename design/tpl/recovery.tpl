<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo $app->lang->get('Password recovery')?></title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo URL_CSS; ?>bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>bootstrap-theme.min.css" rel="stylesheet">
  <link href="<?php echo URL_CSS; ?>auth.css" rel="stylesheet">


</head>

<body>

<div class="container">
  <form class="form-signin" autocomplete="off" action="<?php echo URL_ROOT ?>recovery" method="post">
    <?php if ('' != $flash['error']): ?>
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong><?php echo $flash['error']?></strong>
    </div>
    <?php endif ?>
    <h2><?php echo $app->lang->get('Password recovery')?></h2>
    <p><?php echo $app->lang->get('Use master key to recover your password')?></p>
    <input type="password" id="inputEmail" name="recovery[key]" value="<?php echo $flash['email']?>" class="form-control"
            placeholder="<?php echo $app->lang->get('Master key')?>" required autofocus>
    <input type="password" id="inputPassword" name="recovery[pass]" class="form-control"
            placeholder="<?php echo $app->lang->get('New password')?>" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $app->lang->get('Send')?></button>
  </form>

</div> <!-- /container -->
<script src="<?php echo URL_JS; ?>jquery-2.2.0.min.js"></script>
<script src="<?php echo URL_JS; ?>bootstrap.min.js"></script>
</body>
</html>
