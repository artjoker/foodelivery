<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<title><?=$_lang["shop_import"]?></title>
<meta http-equiv="refresh" content="5; url=<?=$url?>b=import&submit=import_progress&limit=<?=$_GET['limit']?>&counter=<?=($_GET['counter']+1)?>">
    <script src="/assets/site/jquery.min.js"></script>
    <link href="/assets/site/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/site/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="/assets/site/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container col-xs-12">
	<div class="content">
		<div class="panel panel-default">	
			<p><i><?=$_lang["shop_import_progress"]?></i></p>
			<?if(isset($_GET['counter']) AND isset($_GET['limit'])){ $progress = ($_GET['counter']*100) / $_GET['limit'];?>
				<p><div class="progress progress-striped">
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?=$_GET['counter']?>" aria-valuemin="0" aria-valuemax="<?=$_GET['limit']?>" style="width: <?=$progress?>%"><b><?=$_lang["shop_finish"]?>: <?=$progress?>% [<?=$_GET['counter']?>/<?=$_GET['limit']?>] </b>
						<span class="sr-only"><?=$progress?>% Complete (danger)</span>
					</div>
				</div></p>
			<? } ?>
		</div>
	</div>
</div>
</body>
</html>