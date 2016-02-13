/*
    Всплывающее сообщение
*/
function alert(_text) {
    $("#alert").fadeIn(200);
    $("#alert p").html(_text);
    setTimeout(function(){$("#alert").fadeOut(2000);}, 5000);
}
/*
    Подсчет корзины
*/
function calcCart(){
    if ($("#Cart").size() == 0) return;
    var _total = 0;
    $("#Cart table tr:not(:last)").each(function(){
        var _item = $(this);
        _sum = parseFloat(_item.find("td:eq(2) span").html()) * parseInt(_item.find("input[type=text]").val());
        _item.find("td:last span").html(_sum.toFixed(2));
        _total += _sum;
    })
    $("#Cart table tr:last th:last span").html(_total.toFixed(2));
}
/*
    Просчет заказа
*/
function calcOrder() {
    var _total = 0;
    $(".js_order_cart .js_item").each(function(){
        _total += parseFloat($(this).html());
    });
    _delivery = $("[data-delivery]:selected").attr("data-delivery");
    _total += parseInt(_delivery);
    $(".js_delivery").html(_delivery);
    $(".js_order_cart .js_total").html(_total);
}
function getCart() {
    if ($("#Cart").size() > 0) $("#Cart").remove();
    $.ajax({
        data:"ajax=cart",
        success:function(json){
            if (json.alert != '' && json.alert != undefined)
                alert(json.alert);
            else {
                $("body").append(json.cart);
                calcCart();
                $("#Cart").modal("show");
            }
        }
    });
}
function getFilterType (_filterName) {
    if (_filterName.indexOf('min') > 0) _type = 1;
    else if (_filterName.indexOf('[]') > 0) _type = 3;
    else _type = 2;
    return _type;
}

function restoreHash() {
    hash = window.location.hash.substr(2);
    if (hash != "" && hash != undefined) {
        values = hash.split("&");
        for (i in values) {
            value = values[i].split("=");
            switch($("[name='"+value[0]+"']").attr("type")) {
                case "text": case "hidden": 
                    $("[name='"+value[0]+"']").val(value[1]); 
                    break;
                case "radio": case "checkbox": 
                    $("[name='"+value[0]+"'][value='"+value[1].replace("+", " ")+"']").prop("checked", true); 
                    break;
            }
        }
    }
}
$(document).ready(function(){
    /*
        Инициализация
    */
    var _url = window.location.href.split("/");
    $.ajaxSetup({url:"/"+_url[3]+"/",type:"GET",dataType:"json",cache:false});
    $("#sliderPrice").slider().on("slide", function(ev){
        $(this).closest(".row").find("[name*='min']").val(ev.value[0]);
        $(this).closest(".row").find("[name*='max']").val(ev.value[1]);
    })
    // $("[title]").tooltip();

    $("[data-remember]").each(function(){
        $(this).val(localStorage[$(this).attr("name")]);
    })
    $("[data-active]").each(function(){
        $(this).find("[value='"+$(this).attr("data-active")+"']").prop("selected", true);
    })
    /*
        Купить товар
    */
    $("[data-buy]").on("click", function(e){
        e.preventDefault();
        var _this = $(this);
        $.ajax({
            data:"ajax=buy&buy="+_this.attr("data-buy"),
            success:function(json){
                $(".cart_title").html(json.cart_title);
                getCart();
            }
        });
        return false;
    })
    /*
        Корзина
    */
    $("[data-ajax]").on("click", function(e){
        e.preventDefault();
        var _this = $(this);
        switch(_this.attr("data-ajax")) {
            case 'cart': // корзина
                getCart();
            break;
        }
        // return false;
    })
    /*
        Изменение количества товара в корзине
    */
    $(document).on("blur", "#Cart input[type=text]", function(){
        $.ajax({data:"ajax=cartPriceUpdate&pid="+$(this).attr("name")+"&qnt="+$(this).val() });
        calcCart();
    })
    /*
        Удаление товара из корзины
    */
    $(document).on("click", "#Cart [data-cart-remove]", function(){
        var _this = $(this);
        $.ajax({data:"ajax=cartRemove&pid="+_this.attr("data-cart-remove"), success:function(){
            if ($("#Cart table tr").size() == 1) {
                $("#Cart").modal("hide").remove();
                $(".cart_title").html('Корзина пуста');
            } else {
                _this.parents("tr").slideUp().remove();
                $(".cart_title").html('Корзина ('+($("#Cart table tr").size() - 1)+')');
            }
            calcCart();
        }});
    })
    /*
        Закрытие всплывающего сообщения
    */
    $("#alert .close").on("click", function(){
        $("#alert").fadeOut(1000);
        return false;
    })
    /*
        Фильтрация полей по data-filter
    */
    $(document).on("keyup", "[data-filter]", function(){
        var _val = $(this).val().replace(new RegExp("[^"+$(this).attr("data-filter")+"]"), '');
        $(this).val(parseInt(_val) == 0 ? 1 : _val);
    })
    $("[data-remember]").on("keyup", function(){
        localStorage[$(this).attr("name")] = $(this).val();
    })

    /*
        Смена способа доставки
    */
    $("[data-delivery]").parent("select").on("change", function(){
        calcOrder();
    });
    /*
        Просмотр деталей заказа
    */
    $("[data-order-details]").on("click", function(){
        var _this = $(this);
        $.ajax({data:"ajax=getOrderDetails&order="+_this.attr("data-order-details"), success:function(_json){
            $(_json.order).insertAfter(_this.closest("tr"));
            _this.closest("tr").next(".js_order_detail").hide().removeClass("hide").slideDown(1000);
            _this.fadeOut();
        }})
        return false;
    })
    /*
    * Фильтрация
    * */
    $("#filters button").on("click", function(){
        _data = decodeURIComponent($("#filters form").serialize());
        window.location.hash = '#!'+_data;
        $.ajax({url:window.location.href, type:'POST', cache:false, data:"ajax=filter&"+_data, success:function(_json){
            $(".js_catalog").html(_json.catalog);
            $(".js_paginate").html(_json.paginate);
            $(".js_reset_filters").attr('disabled',false);
        }})
        return false;
    })
    $('.js_reset_filters').on('click', function(){
        window.location.hash = '';
        window.location.reload();
        return false;
    });
    /*
    * Пагинация
    */
    $(document).on("click", ".js_change_page", function(){
        $("#filters form input[name='p']").val($(this).attr('href'));
        _data = decodeURIComponent($("#filters form").serialize());
        window.location.hash = '#!'+_data;
        $.ajax({url:window.location.href, type:'POST', cache:false, data:"ajax=filter&"+_data, success:function(_json){
            $(".js_catalog").html(_json.catalog);
            $(".js_paginate").html(_json.paginate);
        }})
        return false;
    })
    /*
        Переключатель языка
     */
    $("#js_switch_lang").on("change", function(){
        _url[3] = $(this).val();
        window.location.href = _url.join("/");
    })
    /*
        Переключатель валюты
     */
    $("#js_switch_curr").on("change", function(){ 
        $.ajax({data:"ajax=setCurrency&currency="+$(this).val(),success:function(){
            window.location.reload(); 
        }})
    })
    /*
        Новая Почта
    */
    $("[name='order[delivery]']").on("change", function (){
        if ($(this).val() == 2) {
            $("#newpost_city").trigger("change");
            $(".newpost").removeClass("hide");
        } else
            $(".newpost").addClass("hide");
    })
    $("#newpost_city").on("change", function (){
        $.ajax({url:"/ua/",data:"ajax=newpost&city="+$(this).val(),success:function(_json){
            $("#newpost_warehouse").html(_json.content)
        }})
    })

    if (window.location.hash != "" && $("[data-toggle='tab'][href='"+window.location.hash+"']").size() > 0)
        $("[data-toggle='tab'][href='"+window.location.hash+"']").trigger("click");
});