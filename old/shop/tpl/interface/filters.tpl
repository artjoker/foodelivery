
<form action="<?=$url?>c=<?=$_GET['c']?>&b=filters" method="post">
    <a href="<?=$url?>&b=filters&d=add" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <?=$_lang["shop_new_filter"]?></a>
    <br><br>
    <table class="table table-bordered table-striped table-condensed table-hover">
        <thead>
            <tr>
                <th><?=$_lang["shop_name"]?></th>
                <th width="50px"><?=$_lang["shop_type"]?></th>
                <th width="270px"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach($filters as $f): ?>
                <tr>
                    <td> 
                        <i><?=$f['filter_name']?></i> 
                        <? if ($f['filter_desc'] != ""): ?>
                            <div class="icon icon-question-sign text-muted" title="<?=$f['filter_desc']?>"></div>
                        <? endif ?>
                    </td>
                    <td>
                        <?php 
                            switch ($f['filter_type']){
                                case 1: echo $_lang["shop_numerical"]; break;
                                case 2: echo $_lang["shop_or"]; break;
                                case 3: echo $_lang["shop_and"]; break;
                            }
                        ?>
                    </td>
                    <td>
                        <a href="<?=$url?>b=filters&d=edit&edit=<?=$f['filter_id']?>" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> <?=$_lang["shop_edit"]?></a>
                        <a href="<?=$url?>b=filters&d=delete&delete=<?=$f['filter_id']?>" onclick="return confirm('<?=$_lang["shop_filter_ask"]?>')" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_delete"]?></a>
                    </td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>

</form>
