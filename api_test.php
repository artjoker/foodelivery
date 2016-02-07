<?php
  error_reporting(0);
  $tests = array('get_catalog', 'get_catalog_upd', 'banner', 'shoplist', 'reg', 'auth', 'forgot', 'update_user', 'order',/* 'change_password',  'product_info',*/
    'history');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>API test</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">

  <form action="" method="post">
    <h3>foodeliveryapp api test</h3>
    <p>
      <input type="text" required name="host"
             value="<?= ($_POST['host'] != "" ? $_POST['host'] : "foodeliveryapp.com") ?>"
             placeholder="Domain - host without http" class="form-control">
    </p>
    <p>
      <input type="email" required name="email"
             value="<?= ($_POST['email'] != "" ? $_POST['email'] : "kharkiv.adminko@gmail.com") ?>"
             placeholder="Email to registration and login" class="form-control">
    </p>
    <p>
      <input type="text" name="pass" value="" placeholder="Password login" class="form-control">
    </p>
    <p>
      <input type="text" name="user" value="<?= ($_POST['user'] != "" ? $_POST['user'] : 0) ?>" placeholder="User ID"
             class="form-control">
    </p>
    <ul>
      <? foreach ($tests as $key): ?>
        <li><label><input type="checkbox" name="test_me[]" <?= (in_array($key, $_POST['test_me']) ? "checked" : "") ?>
                          value="<?= $key ?>"> <?= $key ?></label></li>
      <? endforeach ?>
    </ul>
    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-fire"></span> <b>test</b></button>
  </form>
  <hr>
  <?php

    /*
      Foodelivery api tester

     */
    if ($_POST['host'] != "") {
      $host  = $_POST['host'];
      $time  = 1433348040;//time();
      $skey  = md5($host . "Artjoker");
      $tests = array(
        'get_catalog'     => '{"data":{},"function":"get_catalog","signature":"' . md5("{}" . $skey) . '"}',
        'get_catalog_upd' => '{"data":{"date":' . $time . '},"function":"get_catalog","signature":"' . md5('"{"date":' . $time . '}"' . $skey) . '"}',
        'banner'          => '{"data":{"height":0,"width":480},"function":"banners","signature":"' . md5('{"height":0,"width":480}' . $skey) . '"}',
        'shoplist'        => '{"data":"shoplist","function":"shoplist","signature":"' . md5('"shoplist"' . $skey) . '"}',
        'reg'             => '{"data":{"city":"TEST_CITY","email":"' . $_POST['email'] . '","surname":"SURNAME","house_number":"HOUSE","street":"STREET","name":"NAME","phone":"0123456789","room_number":"FLAT","groups":1},"function":"reg","signature":"' . md5('{"city":"TEST_CITY","email":"' . $_POST['email'] . '","surname":"SURNAME","house_number":"HOUSE","street":"STREET","name":"NAME","phone":"0123456789","room_number":"FLAT","groups":1}' . $skey) . '"}',
        'auth'            => '{"data":{"email":"' . $_POST['email'] . '","pass":"' . md5($_POST['pass']) . '"},"function":"auth","signature":"' . md5('{"email":"' . $_POST['email'] . '","pass":"' . md5($_POST['pass']) . '"}' . $skey) . '"}',
        'update_user'     => '{"data":{"user_id":' . $_POST['user'] . ',"email":"' . $_POST['email'] . '","phone":"9876543210","name":"UPDATED_NAME","surname":"UPDATED_SURNAME","city":"UPDATED_CITY","house_number":"UPDATED_HOUSE","street":"UPDATED_STREET"},"function":"update_user","signature":"' . md5('{"user_id":' . $_POST['user'] . ',"email":"' . $_POST['email'] . '","phone":"9876543210","name":"UPDATED_NAME","surname":"UPDATED_SURNAME","city":"UPDATED_CITY","house_number":"UPDATED_HOUSE","street":"UPDATED_STREET"}' . $skey) . '"}',
        'forgot'          => '{"data":{"email":"' . $_POST['email'] . '"},"function":"forgot","signature":"' . md5('{"email":"' . $_POST['email'] . '"}' . $skey) . '"}',
        'change_password' => '',
        'order'           => '',
        'product_info'    => '',
        'history'         => '{"data":{"user_id":100},"function":"history","signature":"' . md5('{"user_id":100}' . $skey) . '"}',
      );


      foreach ($tests as $key => $test)
        if (in_array($key, $_POST['test_me'])) {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'http://' . $host . '/api/');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          // curl_setopt ($ch, CURLOPT_HEADER, 1);
          // curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
          // curl_setopt ($ch, CURLOPT_ENCODING, '');
          // curl_setopt ($ch, CURLOPT_AUTOREFERER, 1);
          // curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 120);
          // curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
          // curl_setopt ($ch, CURLOPT_MAXREDIRS, 10);
          curl_setopt($ch, CURLOPT_USERAGENT, "API Tester " . date("Ymd His"));
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $test);
          $response = curl_exec($ch);
          $code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);
          ob_start();
          echo '
			<div id="' . $key . '" class="panel panel-' . ($code == 200 ? "success" : ($code == 500 ? "danger" : "warning")) . '">
			  <div class="panel-heading"><b class="pull-right">' . $code . '</b> ' . $key . '</div>
			  <div class="panel-body">
			    <pre>';
          var_dump(json_decode($response, true));
          echo '</pre>
			  </div>
			  <div class="panel-footer">
			  ' . $test . '
			  </div>
			</div>';
          $content = ob_get_contents();
          ob_end_clean();
          echo $content;
        }
    }
  ?>
</div>
</body>
</html>