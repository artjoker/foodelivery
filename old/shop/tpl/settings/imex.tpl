<form action="<?=$url?>b=imex" method="post">
	<div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title"><span class="glyphicon glyphicon-import"></span> <b><?=$_lang["shop_import_xml"]?></b></h3>
		</div>
		<div class="panel-body">
			<table class="table table-bordered table condensed">
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_import_dir"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_import_dir_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_import_dir" id="" class="form-control span6" value="<?=$modx->config['shop_import_dir']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_import_filename"]?> 
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_import_filename_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_import_filename" id="" class="form-control span6" value="<?=$modx->config['shop_import_filename']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_img_dir_from"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_img_dir_from_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_import_img_dir_from" id="" class="form-control span6" value="<?=$modx->config['shop_import_img_dir_from']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_img_dir_to"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_img_dir_to_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_import_img_dir_to" id="" class="form-control span6" value="<?=$modx->config['shop_import_img_dir_to']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_import_step"]?> 
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_import_step_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_import_step" id="" class="form-control span6" value="<?=$modx->config['shop_import_step']?>" /></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <h3 class="panel-title"><span class="glyphicon glyphicon-export"></span> <b><?=$_lang["shop_export_orders"]?></b></h3>
	    </div>
	    <div class="panel-body">
			<table class="table table-bordered">
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_export_dir"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_export_dir_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_export_dir" id="" class="form-control span6" value="<?=$modx->config['shop_export_dir']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_export_filename"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_export_filename_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_export_filename" id="" class="form-control span6" value="<?=$modx->config['shop_export_filename']?>" /></td>
				</tr>
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_export_period"]?>  
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_export_period_i"]?>"></span>
						</b>
					</td>
					<td colspan="3">
						<select name="shop_export_period" class="nc form-control">								
							<option value="1" <? if($modx->config['shop_export_period'] == 1) echo 'selected';?> ><?=$_lang["shop_period_m"]?></option>	
							<option value="2" <? if($modx->config['shop_export_period'] == 2) echo 'selected';?> ><?=$_lang["shop_period_w"]?></option>			
							<option value="3" <? if($modx->config['shop_export_period'] == 3) echo 'selected';?> ><?=$_lang["shop_period_d"]?></option>
			            	<option value="4" <? if($modx->config['shop_export_period'] == 4) echo 'selected';?> ><?=$_lang["shop_period_all"]?></option>
						</select>
					</td>
				</tr>				
			</table>
		</div>
	</div>
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <h3 class="panel-title"><span class="glyphicon glyphicon-export"></span> <b><?=$_lang["shop_price_hotline"]?></b></h3>
	    </div>
	    <div class="panel-body">
			<table class="table table-bordered">
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_price_filename"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_hotline_filename_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_hotline_filename" id="" class="form-control span6" value="<?=$modx->config['shop_hotline_filename']?>" /></td>
				</tr>				
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_lang"]?>  
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_lang_i"]?>"></span>
						</b>
					</td>
					<td colspan="3">
						<select name="shop_hotline_lang" class="nc form-control">
						<? foreach ($langs as $lang): ?>								
							<option value="<?=$lang?>" <? if($modx->config['shop_hotline_lang'] == $lang) echo 'selected';?> ><?=$lang?></option>
						<? endforeach; ?>
						</select>
					</td>
				</tr>				
			</table>
		</div>
	</div>
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <h3 class="panel-title"><span class="glyphicon glyphicon-export"></span> <b><?=$_lang["shop_price_pn"]?></b></h3>
	    </div>
	    <div class="panel-body">
			<table class="table table-bordered">
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_price_filename"]?>
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_pn_filename_i"]?>"></span>
						</b>
					</td>
					<td><input type="text" name="shop_pn_filename" id="" class="form-control span6" value="<?=$modx->config['shop_pn_filename']?>" /></td>
				</tr>				
				<tr>
					<td width="250px">
						<b>
							<?=$_lang["shop_lang"]?>  
							<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_lang_i"]?>"></span>
						</b>
					</td>
					<td colspan="3">
						<select name="shop_pn_lang" class="nc form-control">
						<? foreach ($langs as $lang): ?>								
							<option value="<?=$lang?>" <? if($modx->config['shop_pn_lang'] == $lang) echo 'selected';?> ><?=$lang?></option>
						<? endforeach; ?>
						</select>
					</td>
				</tr>				
			</table>
		</div>
	</div>	
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>