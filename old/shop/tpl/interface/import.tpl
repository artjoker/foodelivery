<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-import"></span> <b><?=$_lang["shop_import_csv"]?></b></h3>
  </div>
  <div class="panel-body">
  	<p><i><?=$_lang["shop_import_to_site"]?></i></p>
		<p><?=$_lang["shop_load_csv"]?></p>
		<form action="" method="post" enctype="multipart/form-data">
			<input name="file" type="file"/><br>
			<button name="submit" value="import_csv" class="btn btn-primary"><span class="glyphicon glyphicon-import"></span> <?=$_lang["shop_import_r"]?></button>
		</form>		
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-import"></span> <b><?=$_lang["shop_import_xml"]?></b></h3>
  </div>
  <div class="panel-body">
  	<p><i><?=$_lang["shop_import_to_site"]?></i></p>
		<p><?=$_lang["shop_load_xml"]?></p>
		<form action="" method="post" enctype="multipart/form-data">
			<input name="file" type="file"/><br>
			<button name="submit" value="import" class="btn btn-primary"><span class="glyphicon glyphicon-import"></span> <?=$_lang["shop_import_r"]?></button>
		</form>		
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-export"></span> <b><?=$_lang["shop_export_orders"]?></b></h3>
  </div>
  <div class="panel-body">
  	<p><i><?=$_lang["shop_export_i"]?></i></p>
		<form action="" method="post" enctype="multipart/form-data">
			<table class="table-condensed">
				<tr>
					<td class="text-right"><?=$_lang["shop_from"]?></td>
					<td>
						<div id="datetimepicker" class="input-append date">
						  <input data-format="yyyy-MM-dd" type="text" name="date_from" id="datetimepicker" class="form-control add-on" style="width: 100px;" value="<?=$date_begin?>"></input>
						</div>
					</td>
					<td><?=$_lang["shop_to"]?></td>
					<td>
						<div id="datetimepicker2" class="input-append date">
						  <input data-format="yyyy-MM-dd" type="text" name="date_to" id="datetimepicker2" class="form-control add-on" style="width: 100px;" value="<?=date("Y-m-d")?>"></input>
						</div>
					</td>
				</tr>
				<tr>
					<td><?=$_lang["shop_status"]?></td>
					<td colspan="3">
						<select name="status" class="nc form-control">
							<option value="-1" selected><?=$_lang["shop_all"]?></option>
							<option value="0"><?=$_lang["shop_deleted"]?></option>
				            <option value="1"><?=$_lang["shop_new"]?></option>
				            <option value="2"><?=$_lang["shop_sent"]?></option>
				            <option value="3"><?=$_lang["shop_delivered"]?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<button name="submit" value="export" class="btn btn-primary"><span class="glyphicon glyphicon-export"></span> <?=$_lang["shop_export_r"]?></button>
					</td>
				</tr>
			</table>
		</form>
  </div>
</div>