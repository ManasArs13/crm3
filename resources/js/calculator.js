import Inputmask from "inputmask";

$(document).ready(function(){
    Inputmask({"mask": "+79999999999"}).mask(".phone");

    $('.select').on('click', '.select__head', function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $(this).next().fadeOut();
        } else {
            $('.select__head').removeClass('open');
            $('.select__list').fadeOut();
            $(this).addClass('open');
            $(this).next().fadeIn();
        }
    });

    $('.select').on('click', '.CEB__select_color_js .select__item', function () {
        $('.select__head').removeClass('open');
        $(this).parent().find('.selected').removeClass("selected");

        $(this).addClass("selected");
        $(this).parent().fadeOut();
        $(this).parent().prev().html($(this).text()).css({
            "backgroundColor": $(this).attr("data-codecolor"),
            "color": $(this).attr("data-codecolortext"),
        });

        $(this).parent().next().val($(this).attr("data-value"))
        let group=$(this).parent().attr("data-id");
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let quantity=$(formClass+'[name="positions['+group+'][quantity]"]').val();

        let price=$(this).attr("data-price");
        let weight=$(this).attr("data-weight");

        $(formClass+"#price_client_"+group).text(price);
        $(formClass+'[name="positions['+group+'][price]"]').val(price);
        $(formClass+"#weight_total_"+group).text(weight*quantity);
        $(formClass+"#price_total_"+group).text(price*quantity);
        $(formClass+".balance[data-id="+group+"]").text($(this).attr("data-balance"));

        calculation(formClass);
    });

    $(document).click(function (e) {
        if (!$(e.target).closest('.select').length) {
            $('.select__head').removeClass('open');
            $('.select__list').fadeOut();
        }
    });

    $('.quantity').each(function() {
      var spinner = $(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max'),
        step=parseFloat(input.attr("step"));

      btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + step;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - step;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });

    $("body").on("change", ".change_js", function() {
        let quantity=$(this).val();
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let group=$(this).attr("data-id");

        let price=0;
        let weight=0;
        let select=$(formClass+'.select_product');

        price=select.find('.selected').attr("data-price");
        weight=select.find('.selected').attr("data-weight");

        $(formClass+"#weight_total_"+group).text(weight*quantity);
        $(formClass+"#price_total_"+group).text(price*quantity);

        calculation(formClass);
    });


    $("body").on("click", ".CEB__select_beton_js  .select__item", function() {
        $('.select__head').removeClass('open');
        $(this).parent().find('.selected').removeClass("selected");
        $(this).addClass("selected");
        $(this).parent().fadeOut();
        $(this).parent().prev().html($(this).text());

        let select=$(this).parent();
        let oldGroup=select.attr("data-id");
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let price=$(this).attr("data-price");
        let weight=$(this).attr("data-weight");
        let group=$(this).attr("data-id");
        select.attr("data-id", group);

        $(this).parent().next().val($(this).attr("data-value")).attr("name", "positions["+group+"][product_id]");

        let quantityInput=$(formClass+'[name="positions['+oldGroup+'][quantity]"]');
        let quantity=quantityInput.val();
        quantityInput.removeAttr("name").attr("name","positions["+group+"][quantity]").attr("data-id",group);

        $(formClass+"#price_client_"+oldGroup).attr("id", "price_client_"+group).text(price);
        $(formClass+'[name="positions['+oldGroup+'][price]"]').attr("name", 'positions['+group+'][price]').attr("id", "price_client_"+group).val(price);

        $(formClass+"#weight_total_"+oldGroup).attr("id", "weight_total_"+group).text(weight*quantity);
        $(formClass+"#price_total_"+oldGroup).attr("id", "price_total_"+group).text(price*quantity);

        calculation(formClass);
    });

    function calculation(formClass){
        var weigth_total=0;
        var price_total=0;

        $(formClass+".weight").each(function() {
            weigth_total+=parseFloat($(this).text());
        });

        $(formClass+".price").each(function() {
            price_total+=parseFloat($(this).text());
        });

        $(formClass+"#weight_total").text(weigth_total);
        $(formClass+"#price_total").text(price_total);

        $(formClass+".CEB__select_color_js").each(function() {
            $(this).parent(".select").find(".select__head").css({
                    "backgroundColor": $(this).find(".selected").attr("data-codecolor"),
                    "color": $(this).find(".selected").attr("data-codecolortext"),
            }).text($(this).find(".selected").text());
        });

        calcDelivery(formClass);
    };

    function setPriceWeight(group, quantity, formClass){
        let select=$(formClass+'.CEB__select_color_js[data-id="'+group+'"');
        let price=select.find('.selected').attr("data-price");
        let weight=select.find('.selected').attr("data-weight");

        $(formClass+"#price_client_"+group).text(price);
        $(formClass+'[name="positions['+group+'][price]"]').val(price);
        $(formClass+"#weight_total_"+group).text(weight*quantity);
        $(formClass+"#price_total_"+group).text(price*quantity);
    }

    function calculation0(formClass){
        numberType = $(".CMR__input_typeZabor_js:checked").attr("data-numberType");
        lengthColumns = Length - post_quantity;
        lengthWalls = Length - (post_quantity * 0.28);

        if (numberType == 1) {
            rowsBlocks = wallHeight / 20;
        } else if (numberType == 2) {
            rowsBlocks = wallHeight / 20 - 1;
        } else if (numberType == 3) {
            rowsBlocks = wallHeight / 20 - 1;
        };

        WallSteps = lengthWalls / 0.4;
        WallSteps = Math.ceil(WallSteps);

        //Блоки
        let quantity=parseInt(WallSteps * rowsBlocks);
        $(formClass+"[name='positions[12][quantity]']").val(quantity);
        setPriceWeight(12, quantity, formClass);

        //Колонны
        quantity=parseInt(post_quantity * columnHeight / 20);
        $(formClass+"[name='positions[21][quantity]']").val(quantity);
        setPriceWeight(21, quantity, formClass);

        //Крышек
        $(formClass+"[name='positions[15][quantity]']").val(post_quantity);
        setPriceWeight(15, post_quantity, formClass);

        //Парапет
        if (numberType == 1) {
            quantity = +(WallSteps).toFixed(0);
        } else if (numberType == 2) {
            quantity = +(WallSteps).toFixed(0);
        } else if (numberType == 3) {
            quantity = +(WallSteps * 3).toFixed(0);
        };

        $(formClass+"[name='positions[11][quantity]']").val(quantity);
        setPriceWeight(11, quantity, formClass);

        // Декор
        if (numberType == 1) {
            quantity = 0;
        } else if (numberType == 2) {
            quantity = (WallSteps * 2).toFixed(0);
        } else if (numberType == 3) {
            quantity = (WallSteps * 2).toFixed(0);
        };

        $(formClass+"[name='positions[6][quantity]']").val(quantity);
        setPriceWeight(6, quantity, formClass);
        calculation(formClass);
    }

    $("body").on("change", ".change_delivery", function() {
        let formClass="."+$(this).parents("form").attr("class")+" ";
        calcDelivery(formClass);
    })

    function calcDelivery(formClass) {
        let deliveryValue = $(formClass+'select[name="attributes[delivery][id]"]').find('option:selected').attr("data-distance");
        let vehicleType = $(formClass+'select[name="attributes[vehicle_type][id]"]').find('option:selected').attr("data-type");
        let ratio = 1;
        let weight_zakaz_for_delivery = parseFloat($(formClass+"#weight_total").text());
        let weight=Math.ceil(weight_zakaz_for_delivery/1000)+'.0';

        if (deliveryValue < 25) {
            deliveryValue = 25;
        } else if (deliveryValue >= 25 && deliveryValue < 30) {
            deliveryValue = 30;
        } else if (deliveryValue >= 30 && deliveryValue < 35) {
            deliveryValue = 35;
        } else if (deliveryValue >= 35 && deliveryValue < 40) {
            deliveryValue = 40;
        } else if (deliveryValue >= 40 && deliveryValue < 50) {
            deliveryValue = 50;
        } else if (deliveryValue >= 50 && deliveryValue < 60) {
            deliveryValue = 60;
        } else if (deliveryValue >= 60 && deliveryValue < 70) {
            deliveryValue = 70;
        } else if (deliveryValue >= 70 && deliveryValue < 80) {
            deliveryValue = 80;
        } else if (deliveryValue >= 80 && deliveryValue < 90) {
            deliveryValue = 90;
        } else if (deliveryValue >= 90 && deliveryValue < 100) {
            deliveryValue = 100;
        } else if (deliveryValue >= 100 && deliveryValue < 120) {
            deliveryValue = 120;
        } else if (deliveryValue >= 120 && deliveryValue < 140) {
            deliveryValue = 140;
        } else if (deliveryValue >= 140 && deliveryValue < 160) {
            deliveryValue = 160;
        } else if (deliveryValue >= 160 && deliveryValue < 180) {
            deliveryValue = 180;
        } else if (deliveryValue >= 180 && deliveryValue < 200) {
            deliveryValue = 200;
        } else {
            deliveryValue = 220;
        }

        if (vehicleType == 3) {
            weight_zakaz_for_delivery = '20.0';

            if (weight > '21.0') {
                weight_zakaz_for_delivery='21.0';
            }
        } else if (vehicleType == 4) {

            weight_zakaz_for_delivery = '15.0';

            if (weight < '6.0') {
                weight_zakaz_for_delivery = '6.0';
            }else if (weight<'15.0'){
                weight_zakaz_for_delivery = weight;
            }
        } else if (vehicleType == 5) {
            weight_zakaz_for_delivery = '2.5'
        } else  if (vehicleType == 6){
            if (weight > '3.0') {
                weight_zakaz_for_delivery = '3.0';
            }else{
                weight_zakaz_for_delivery = weight;
            }
        }  else if (vehicleType == 2){
            weight_zakaz_for_delivery = '8.0';
        }

        if (shippingPrices) {
            let shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                .transport_type_id == vehicleType && item.tonnage == weight_zakaz_for_delivery)

            if (shippingPrice.length !== 0) {
                let price = shippingPrice[0].price * weight;
                let price2= Math.ceil(price/100)*100;
                $(formClass+'.price-tn.input').val(shippingPrice[0].price);
                $(formClass+'[name="attributes[deliveryPrice]"').val(price2);
            }
        }
    };

    $('form').submit(function(e){
        e.preventDefault();

        $.ajax({
          url: "api/order_ms/create",
          type: "POST",
          data: $(this).serialize(),
          beforeSend: function() {
            $(".preloader").addClass("active");
          },
          success: function(data) {
            $("#message").html(data);
          },
          error: function(response) {
            $("#message").html(response.responseText);
          },
          complete: function() {
            $(".preloader").removeClass("active");
          }
        });

    });

    $("body").on("click", "#button-modal", function(){
        $(".agent").toggleClass("active");
    });

    $(".delivery_select2").select2();
    $(".select2").select2();


    $("body").on("click", ".time-span", function(){
       let value=$(this).attr("data-time");
       $(".plan").val(value);
       $(".datetime-popup").toggleClass("active");
    });

    $("body").on("click", ".datetime", function(){
        let formClass="."+$(".CMR__input_calc_js:checked").attr("data-content");
        if (formClass!=".calcBeton"){
            $(formClass+".datetime-popup").toggleClass("active");
            width_datetime();
        }
     });


    let Length = 10; // длина забора
    let post_quantity = 2; // кол-во столбов
    let wallHeight = 200; // высота стенки
    let columnHeight = 200; // Высота колоны
    let numberType = ""; // номер типа забора

    let weight_zakaz = 0; // вес заказа
    let total_zakaz = 0; // всего за заказ

    let WallSteps = 0; //	Шагов стены
    let rowsBlocks = 0; // Рядов блока
    let lengthWalls = 0; // Длина стен общая
    let lengthColumns = 0; // Длина колонн общая


    MadeSlider_1(); // установка первого ползунка
    MadeSlider_2(); // установка 2 ползунка
    MadeSlider_3(); // установка 3 ползунка
    MadeSlider_4(); // установка 4 ползунка

    $(".select__list.CEB__select_color_js").each(function() {
        $(this).parent(".select").find(".select__head").css({
            "backgroundColor": $(this).find(".selected").attr("data-codecolor"),
            "color": $(this).find(".selected").attr("data-codecolortext"),
        }).text($(this).find(".selected").text());

        let group=$(this).attr("data-id");
        let formClass="."+$(this).parents("form").attr("class")+" ";

        $(formClass+".balance[data-id="+group+"]").text($(this).find(".selected").attr("data-balance"));
        $(formClass+"#price_client_"+group).text($(this).find(".selected").attr("data-price"));
        $(formClass+'[name="positions['+group+'][price]"]').val($(this).find(".selected").attr("data-price"));
    });

    $(".select__list.CEB__select_beton_js").each(function() {
        $(this).parent(".select").find(".select__head").text($(this).find(".selected").text());

        let group=$(this).attr("data-id");
        let formClass="."+$(this).parents("form").attr("class")+" ";

        $(formClass+"#price_client_"+group).text($(this).find(".selected").attr("data-price"));
        $(formClass+'[name="positions['+group+'][price]"]').val($(this).find(".selected").attr("data-price"));
    });

    // Задаем значение первому ползунку
    function MadeSlider_1(formClass) {
        jQuery("#CEB__inputLength").val(Length);
        jQuery("#CEB__textLength").text(Length);
        jQuery("#CEBQuestionW-slide1").slider({
            value: Length,
            min: 0,
            max: 300,
            range: 'min',
            step: 1,
            animate: true,
            slide: function(event, ui) {
                Length = ui.value;
                jQuery("#CEB__inputLength").val(Length);
                jQuery("#CEB__textLength").text(Length);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 2 ползунку
    function MadeSlider_2() {
        jQuery("#CEB__inputPost_quantity").val(post_quantity);
        jQuery("#CEB__textPost_quantity").text(post_quantity);
        jQuery("#CEBQuestionW-slide2").slider({
            value: post_quantity,
            min: 0,
            max: 120,
            range: 'min',
            step: 1,
            animate: true,
            slide: function(event, ui) {
                post_quantity = ui.value;
                jQuery("#CEB__inputPost_quantity").val(post_quantity);
                jQuery("#CEB__textPost_quantity").text(post_quantity);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 3 ползунку
    function MadeSlider_3() {
        jQuery("#CEB__input_wallHeight").val(wallHeight);
        jQuery("#CEB__text_wallHeight").text(wallHeight);

        jQuery("#CEBQuestionW-slide3").slider({
            value: wallHeight,
            min: 80,
            max: 320,
            range: 'min',
            step: 20,
            animate: true,
            slide: function(event, ui) {
                wallHeight = ui.value;
                jQuery("#CEB__input_wallHeight").val(wallHeight);
                jQuery("#CEB__text_wallHeight").text(wallHeight);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 4 ползунку
    function MadeSlider_4() {
        jQuery("#CEB__input_columnHeight").val(columnHeight);
        jQuery("#CEB__text_columnHeight").text(columnHeight);

        jQuery("#CEBQuestionW-slide4").slider({
            value: columnHeight,
            min: 100,
            max: 380,
            range: 'min',
            step: 20,
            animate: true,
            slide: function(event, ui) {
                columnHeight = ui.value;
                jQuery("#CEB__input_columnHeight").val(columnHeight);
                jQuery("#CEB__text_columnHeight").text(columnHeight);
                calculation0(".calcFence ");
            }
        });
    };

    //меняем тип забора
    $("body").on("change", ".CMR__change_js", function() {
        calculation0(".calcFence ");
    });


    $("body").on("click", ".CMR__input_calc_js", function() {
        let block=$(this).attr("data-content");
        $(".datetime-popup.active").removeClass("active");
        $(".tab-content.active").removeClass("active");

        $("#"+block).addClass("active");
    });

    window.onresize = function() {
        width_datetime();
    }



    function width_datetime(){
        let formClass="."+$(".CMR__input_calc_js:checked").attr("data-content");

        if (formClass!="calcBeton"){
            if ($(window).width()>=1650){
                let widthTotal=$(".main-1").width();
                let height=$(".CEB__wrapContent.df"+formClass).height();
                let width=$(".CEB").width();
                let widthItogo=(widthTotal-width)/2-30;
                $(".datetime-popup"+formClass).width(widthItogo).height(height);
            }else{
                $(".datetime-popup"+formClass).removeClass("active");
            }
        }
    }


});
