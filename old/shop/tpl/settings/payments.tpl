<form action="<?=$url?>b=payments" method="post">
	<div class="panel-group" id="accordion">

	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#liqpay">
	          <img src="/assets/shop/img/liqpay.png" alt="LiqPay" title="LiqPay" />
	        </a>
	      </h4>
	    </div>
	    <div id="liqpay" class="panel-collapse collapse">
	      <div class="panel-body">
	        <table class="table table-condensed table-bordered">
	        	<tr>
	        		<th width="150px">MerchantID</th>
	        		<td><input type="text" name="shop_liqpay_mid" value="<?=$modx->config['shop_liqpay_mid']?>" class="form-control"></td>
	        	</tr>
	        	<tr>
	        		<th>Signature</th>
	        		<td><input type="text" name="shop_liqpay_sig" value="<?=$modx->config['shop_liqpay_sig']?>" class="form-control"></td>
	        	</tr>
	        	<tr>
	        		<th>% <?=$_lang["shop_payments"]?></th>
	        		<td>
	        			<label><input type="radio" name="shop_liqpay_percent" <?=($modx->config['shop_liqpay_percent'] == "shop" ? "checked" : "")?> value="shop"> За счет магазина</label> <small>при оплате заказа к сумме заказов не будет прибавляться процент за обслуживание платежной системой (3%)</small><br>
	        			<label><input type="radio" name="shop_liqpay_percent" <?=($modx->config['shop_liqpay_percent'] == "user" ? "checked" : "")?> value="user"> За счет покупателя</label> <small>при оплате заказа на 100 грн к выставляемому счету автоматически прибавится 3 грн (3 %)</small>
	        		</td>
	        	</tr>
	        </table>
	        <a href="https://www.liqpay.com/admin/business" target="_blank" class="btn btn-success"><?=$_lang["shop_get_keys"]?></a>
	      </div>
	    </div>
	  </div>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#privat24">
	          <img src="/assets/shop/img/privat24.png" alt="Приват24" title="Приват24" />
	        </a>
	      </h4>
	    </div>
	    <div id="privat24" class="panel-collapse collapse">
	      <div class="panel-body">
	        <table class="table table-condensed table-bordered">
	        	<tr>
	        		<th width="150px">MerchantID</th>
	        		<td><input type="text" name="shop_privat24_mid" value="<?=$modx->config['shop_privat24_mid']?>" class="form-control"></td>
	        	</tr>
	        	<tr>
	        		<th>Signature</th>
	        		<td><input type="text" name="shop_privat24_sig" value="<?=$modx->config['shop_privat24_sig']?>" class="form-control"></td>
	        	</tr>
	        </table>
	      </div>
	    </div>
	  </div>
	</div>
	<br>
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>