<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title><?=$_lang["shop_management"];?></title>
    <meta charset="UTF-8">
    <script src="/assets/site/jquery.min.js"></script>
    <link href="/assets/site/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/site/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/assets/site/bootstrap/css/datetime.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/site/bootstrap/css/switcher.min.css">
    <link rel="stylesheet" href="/assets/site/interface.css" />
    <link rel="stylesheet" href="/assets/site/chosen/chosen.min.css" />
    <script src="/assets/site/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/site/bootstrap/js/switcher.min.js"></script>
    <script src="/assets/site/bootstrap/js/datetime.min.js"></script>
    <script src="/assets/site/chosen/chosen.jquery.min.js"></script>
    <script src="/assets/site/typeahead.min.js"></script>
    <script type="text/javascript">
        String.prototype.translit = (function(){
                var L = {
                    'А' : 'A','а' : 'a','Б' : 'B','б' : 'b','В' : 'V','в' : 'v','Г' : 'G','г' : 'g','Д' : 'D','д' : 'd','Е' : 'E','е' : 'e','Ё' : 'Yo','ё' : 'yo','Ж' : 'Zh','ж' : 'zh','З' : 'Z','з' : 'z','И' : 'I','и' : 'i','Й' : 'Y','й' : 'y','К' : 'K','к' : 'k','Л' : 'L','л' : 'l','М' : 'M','м' : 'm','Н' : 'N','н' : 'n','О' : 'O','о' : 'o','П' : 'P','п' : 'p','Р' : 'R','р' : 'r','С' : 'S','с' : 's','Т' : 'T','т' : 't','У' : 'U','у' : 'u','Ф' : 'F','ф' : 'f','Х' : 'Kh','х' : 'kh','Ц' : 'Ts','ц' : 'ts','Ч' : 'Ch','ч' : 'ch','Ш' : 'Sh','ш' : 'sh','Щ' : 'Sch','щ' : 'sch','Ъ' : '','ъ' : '','Ы' : 'Y','ы' : 'y','Ь' : "",'ь' : "",'Э' : 'E','э' : 'e','Ю' : 'Yu','ю' : 'yu','Я' : 'Ya','я' : 'ya',' ' : '-','&' : '','"' : '',"'" : '','%' : '',',' : '','.' : '','!' : '','І' : 'I','і' : 'i','Є' : 'E','є' : 'e','Ґ' : 'G','ґ' : 'g','Ї' : 'i','ї' : 'i','~' : '','`' : '',';' : '',':' : '',')' : '','(' : '','*' : '','@' : '','#' : '','$' : '','^' : '','+' : '','=' : '','?' : '', "_":"","/":''
                    },
                    r = '',
                    k;
                for (k in L) r += k;
                r = new RegExp('[' + r + ']', 'g');
                k = function(a){
                    return a in L ? L[a] : '';
                };
                return function(){
                    return this.replace(r, k);
                };
            })();
        $(document).ready(function(){
            $(function() {
                $('#datetimepicker').datetimepicker({language: 'ru',pickTime: false});
                $('#datetimepicker2').datetimepicker({language: 'ru',pickTime: false});
              });

            $("[data-active]").each(function(){
              _ids = $(this).attr("data-active").split(",");
              for (_i in _ids) {
                 _option = $(this).find("option[value='"+_ids[_i]+"']");
                 _option.prop("selected", true);
              }
              $(this).trigger("liszt:updated");
            });
            $("[data-allow]").on("keyup", function(e){
                var _key = e.keyCode;
                if (_key != 36 && _key != 37 && _key != 39 && _key != 65 && _key != 35)
                    $(this).val($(this).val().replace(new RegExp($(this).attr("data-allow")), ''));
            })

            $("[data-product-price]").on("blur", function(){
                _this = $(this);
                $.ajax({url:"index.php",data:"a=255&b=update_price&pid="+_this.attr("data-product-price")+"&price="+_this.val(), success:function(ajax){
                    _this.parents(".control-group").addClass("success");
                }})
            })

            $("#pagetitle").on("keyup", function (){
                $("#alias").val($(this).val().translit().toLowerCase()+'/');
            });
            $("[title]").tooltip({placement:'bottom'});
            $("select").not(".nc").chosen();
            $('.make-switch').bootstrapSwitch('setOnLabel', '<?=$_lang["yes"];?>');
            $('.make-switch').bootstrapSwitch('setOffLabel', '<?=$_lang["no"];?>');
            $('.make-switch').bootstrapSwitch('setSizeClass', 'switch-small');
        });
    </script>
</head>
<body>
  <div class="container col-xs-12">
    <div class="masthead">
    <br>
      <ul class="nav nav-justified">
        <li <?=($_GET['b'] == "" ? 'class="active"' : '')?>><a href="<?=$url?>"><?=$_lang["shop_orders"];?></a></li>
        <li <?=($_GET['b'] == "items" ? 'class="active"' : '')?>><a href="<?=$url?>b=items"><?=$_lang["shop_products"];?></a></li>
        <li <?=($_GET['b'] == "filters" ? 'class="active"' : '')?>><a href="<?=$url?>b=filters"><?=$_lang["shop_filters"];?></a></li>
        <li <?=($_GET['b'] == "recalls" ? 'class="active"' : '')?>><a href="<?=$url?>b=recalls"><?=$_lang["shop_recalls"];?></a></li>
        <li <?=($_GET['b'] == "banners" ? 'class="active"' : '')?>><a href="<?=$url?>b=banners"><?=$_lang["shop_banners"];?></a></li>
      </ul>
    </div>
    <? if (isset($_GET['w'])): ?>
      <br>
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?=$messages[$_GET['w']]?></strong>
      </div>
    <? endif ?>
    <div class="content">
      <?=$res['content']?>
    </div>
  </div>
</body>
</html>