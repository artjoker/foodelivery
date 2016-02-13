<form action="<?=$url?>b=banners" method="post">
<p>
	<button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-save"></span> <b>Update data</b></button>
	<a href="#" id="uploader" class="btn btn-success"><span class="glyphicon glyphicon-upload"></span> <b><?=$_lang["shop_load_images"]?></b></a>
</p>
<table id="banners" class="table table-condensed table-bordered">
	<thead>
		<th width="480px">Image</th>
		<th width="380px">Link</th>
		<th>Position</th>
		<th>Current status</th>
		<th>Toggle active</th>
		<th>Remove</th>
	</thead>
	<tbody>
		<? foreach($banners as $banner) include TPLS . "banner_one.tpl"; ?>
	</tbody>
</table>
</form>
<script src="/assets/site/ajaxupload.js"></script>
<script>
	$(document).ready(function(){
		new AjaxUpload($("#uploader"), {
			action: "index.php?a=255&b=banners&c=upload",
			multiple: true,
		  name: "uploader[]",
		  data: {
					"size"  : 2048576
		  },
		  onSubmit: function(file, ext){

		      if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){ 
		          alert('<?=$_lang["shop_file_format"]?>');
		          return false;
		      } else {
		      	$("#banners").html('<tr><td class="text-center"><img src="/assets/site/preloader.gif" alt="" /></td></tr>');
		      }
		  },
		  onComplete: function(file, response){
		    window.location.reload();
		  }
		});
  })
</script>