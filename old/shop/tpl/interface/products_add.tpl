<form action="<?=$url?>b=items&c=save" method="post" enctype="multipart/form-data">
	<button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$_lang["shop_update_product"]?></button>
	<ul class="nav nav-tabs" id="myTab">
	  <li class="active"><a href="#product" data-toggle="tab"><b><?=$_lang["shop_product"]?></b></a></li>
	  <li><a href="#text" data-toggle="tab"><b><?=$_lang["shop_texts"]?></b></a></li>
	  <li><a href="#seo" data-toggle="tab"><b>SEO</b></a></li>
	  <li><a href="#filters" data-toggle="tab"><b><?=$_lang["shop_filters"]?></b></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="product">
			<br>
			<table class="table table-condensed table-bordered table-striped">
				<tr>
					<td><b><?=$_lang["shop_product_code"]?></b></td>
					<td><input type="text" name="add[code]" class="form-control" placeholder="<?=$_lang["shop_product_code_p"]?>" value="" /></td>
				</tr>
				<tr>
					<td><b>URL</b></td>
					<td>
						<div class="input-group">
						  <span class="input-group-addon"><?=$modx->makeUrl(5, null, null, 1)?></span>
						  <input type="text" class="form-control" name="add[url]" id="alias" placeholder="<?=$_lang["shop_url_product"]?>">
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_price"]?></b></td>
					<td>
						<div class="input-group">
						  <input type="text" class="form-control col-xs-2" name="add[price]" data-allow="[^0-9\.?]" placeholder="<?=$_lang["shop_price_p"]?>">
						  <span class="input-group-addon"><?=$modx->config['shop_curr_default_sign']?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_availability"]?></b></td>
					<td>
						<div class="make-switch" data-on="success" data-off="danger">
							<input type="checkbox" name="add[visible]" checked value="yes">
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_status"]?></b></td>
					<td>
						<label class="col-md-8"><input type="radio" name="add[available]" value="0"> <span class="text-muted"><?=$_lang["shop_not_available"]?></span></label>
						<label class="col-md-8"><input type="radio" name="add[available]" value="1" checked="checked"> <span class="text-success"><?=$_lang["shop_available"]?></span></label>
						<label class="col-md-8"><input type="radio" name="add[available]" value="2"> <span class="text-info"><?=$_lang["shop_new"]?></span></label>
						<label class="col-md-8"><input type="radio" name="add[available]" value="3"> <span class="text-warning"><?=$_lang["shop_bestseller"]?></span></label>
						<label class="col-md-8"><input type="radio" name="add[available]" value="4"> <span class="text-danger"><?=$_lang["shop_promotional"]?></span></label>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_categories"]?></b></td>
					<td>
						<select name="add[category][]" required multiple="multiple" class="form-control" data-placeholder="<?=$_lang["shop_categories_p"]?>" data-active="<?=$_GET['category']?>">
							<? while($cat = $modx->db->getRow($categories)):?>
								<option value="<?=$cat['id']?>"><?=$cat['pagetitle']?> (<?=$cat['id']?>)</option>
							<? endwhile ?>
						</select>
					</td>
				</tr>				
				<tr>
					<td><b><?=$_lang["shop_product_friends"]?></b></td>
					<td><input type="text" class="form-control col-xs-2" name="add[friends]" placeholder="<?=$_lang["shop_product_friends_p"]?>"></td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_images"]?></b></td>
					<td>
						<a href="#" id="uploader" class="btn btn-warning"><?=$_lang["shop_load_images"]?></a>
						<ul class="images"></ul>
						<input type="hidden" name="add[cover]" id="cover" />
					</td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="text">
			<br>
			<ul class="nav nav-tabs" id="langTab">
				<? foreach ($languages as $lang): ?>
			  	<li><a href="#<?=$lang?>" data-toggle="tab"><b><?=strtoupper($lang)?></b></a></li>
				<? endforeach; ?>
			</ul>
			<br>
			<div class="tab-content">
				<? foreach ($languages as $lang): ?>
					<div class="tab-pane" id="<?=$lang?>">
						<table class="table table-condensed table-bordered table-striped">
							<tr>
								<td width="100px"><b><?=$_lang["shop_name"]?></b></td>
								<td><input type="text" name="add[<?=$lang?>][name]" class="form-control" /></td>
							</tr>
							<tr>
								<td width="100px"><b><?=$_lang["shop_introtext"]?></b></td>
								<td><textarea name="add[<?=$lang?>][introtext]" id="introtext_<?=$lang?>"></textarea></td>
							</tr>
							<tr>
								<td><b><?=$_lang["shop_description"]?></b></td>
								<td><textarea name="add[<?=$lang?>][description]" id="description_<?=$lang?>"></textarea></td>
							</tr>
						</table>
					</div>
				<? endforeach ?>
			</div>
		</div>
		<div class="tab-pane" id="seo">
			<br>
			<table class="table table-condensed table-bordered table-striped">
				<tr>
					<th width="100px">Title</th>
					<td><input type="text" name="tv_seo_title" id="" class="form-control"></td>
				</tr>
				<tr>
					<th>Keywords</th>
					<td><input type="text" name="tv_seo_keywords" id="" class="form-control"></td>
				</tr>
				<tr>
					<th>Description</th>
					<td><textarea name="tv_seo_description" id="" cols="30" rows="10" class="form-control"></textarea></td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="filters">
			<br>
			<h3><?=$_lang["shop_filter_i"]?></h3>
		</div>
	</div>
	<br><br>
	<button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$_lang["shop_update_product"]?></button>
	<a href="" class="btn btn-warning"><span class="icon icon-white icon-hand-left"></span> <?=$_lang["shop_cancel"]?></a>
</form>
<script src="/assets/site/ajaxupload.js"></script>
<script>
	function updateImages(){
        $.ajax({url:"index.php",type:"GET", data:"a=255&b=get_images&folder=tmp", success:function(ajax){
            $(".images").html(ajax);
            $("[title]").tooltip();
            $(".images img[alt='"+$("#cover").val()+"']").addClass("cover");
        }})
    }
    $(document).on("click", ".images .btn-danger", function() {
        if (!confirm("<?=$_lang['shop_images_delete']?>")) return false; 
        t = $(this).prevAll("img");
        if (t.hasClass("cover")) {
            $("#cover").val('');
            t.removeClass("cover");
        }
        $.ajax({url:"index.php",type:"GET", data:"a=255&c=delete_image&folder=tmp&delete="+t.attr("alt"), success:function(ajax){
            t.parents("li").fadeOut().remove();
        }})
    })
    $(document).on("click", ".images .btn-primary, .image img", function(e) {
    		e.preventDefault();
    		_img = $(this).parents("li").find("img");
        $("#cover").val(_img.attr('alt'));
        $(".images .cover").removeClass("cover");
        _img.addClass("cover");
    })
    updateImages();

    $(document).ready(function(){
    	$("#langTab a:first").trigger("click");

	  	$("[data-allow]").on("keyup", function(){
	  			$(this).val($(this).val().replace(new RegExp($(this).attr("data-allow")), ''));
	  	});

			new AjaxUpload($("#uploader"), {
				action: "index.php?a=255&b=upload",
				multiple: true,
			  name: "uploader[]",
			  data: {
						"size"  : 2048576,
						"folder": 'tmp'
			  },
			  onSubmit: function(file, ext){
			      if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){ 
			          alert('<?=$_lang["shop_file_format"]?>');
			          return false;
			      }
			  },
			  onComplete: function(file, response){
			      updateImages();
			  }
			});
    })

</script>
<?=$tiny_mce?>