<form action="<?=$url?>b=letters" method="post">
	<? switch($_GET['i']){ 
		case 10: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_meta">
	          <span class="glyphicon glyphicon-envelope"></span> <?=$_lang["shop_meta_tpl"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_meta" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_meta" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_meta']?></textarea>
	      </div>
	    </div>
	  </div>
	  <?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_meta"))));?>
	<? break;  ?>
	<? case 1: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_reg">
	          <span class="glyphicon glyphicon-user"></span> <?=$_lang["shop_registration"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_reg" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_reg" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_reg']?></textarea>
	      </div>
	    </div>
	  </div>
		<?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_reg"))));?>
	<? break;  ?>
	<? case 2: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_recovery">
	          <span class="glyphicon glyphicon-retweet"></span> <?=$_lang["shop_recover_pass"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_recovery" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_recovery" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_recovery']?></textarea>
	      </div>
	    </div>
	  </div>
	<?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_recovery"))));?>
	<? break;  ?>
	<? case 3: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_order_meta">
	          <span class="glyphicon glyphicon-shopping-cart"></span> <?=$_lang["shop_add_meta_tpl"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_order_meta" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_order_meta" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_order_meta']?></textarea>
	      </div>
	    </div>
	  </div>
		<?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_order_meta"))));?>
	<? break;  ?>
	<? case 4: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_order_item">
	          <span class="glyphicon glyphicon-gift"></span> <?=$_lang["shop_add_order_tpl"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_order_item" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_order_item" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_order_item']?></textarea>
	      </div>
	    </div>
	  </div>
		<?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_order_item"))));?>
	<? break;  ?>
	<? case 5: ?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#shop_mail_tpl_order_status">
	          <span class="glyphicon glyphicon-bullhorn"></span> <?=$_lang["shop_change_status"]?>
	        </a>
	      </h4>
	    </div>
	    <div id="shop_mail_tpl_order_status" class="panel-collapse collapse in">
	      <div class="panel-body">
	        <textarea name="shop_mail_tpl_order_status" rows="10" class="form-control"><?=$modx->config['shop_mail_tpl_order_status']?></textarea>
	      </div>
	    </div>
	  </div>
		<?=(reset($modx->invokeEvent("OnRichTextEditorInit", Array("ta" => "shop_mail_tpl_order_status"))));?>
	<? break;  ?>
	<? default:?>
		<ul class="row">
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-envelope"></span> <?=$_lang["shop_common_meta_tpl"]?> </a></li>
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-user"></span> <?=$_lang["shop_registration_tpl"]?></a></li>
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-retweet"></span> <?=$_lang["shop_recover_pass_tpl"]?></a></li>
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-shopping-cart"></span> <?=$_lang["shop_order_meta_tpl"]?></a></li>
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-gift"></span> <?=$_lang["shop_order_product_tpl"]?></a></li>
			<li><a href="<?=$url?>b=letters&i="><span class="glyphicon glyphicon-bullhorn"></span> <?=$_lang["shop_change_status_tpl"]?></a></li>
		</ul>
	<? break; 
	} ?>
	<br>
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>