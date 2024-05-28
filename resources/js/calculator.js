import Inputmask from "inputmask";

$(document).ready(function(){
    Inputmask({"mask": "+79999999999"}).mask(".phone");

    $("body").on("change", ".change_js", function() {
        let quantity=$(this).val();
        let group=$(this).data("id");
        let isColor=$(this).data("color");
        let price=0;
        let weight=0;
        let select=$('[name="positions['+group+'][product_id]"]');

        if (isColor){
            price=select.find('option:selected').data("price");
            weight=select.find('option:selected').data("weight");
        }else{
            price=select.data("price");
            weight=select.data("weight");
        }

        $("#weight_total_"+group).text(weight*quantity);
        $("#price_total_"+group).text(price*quantity);
        calculation();
    });

    $("body").on("change", ".CEB__select_color_js", function() {
        let select=$(this);
        let group=$(this).data("id");
        let quantity=$('[name="positions['+group+'][quantity]"]').val();

        let price=select.find('option:selected').data("price");
        let weight=select.find('option:selected').data("weight");

        $("#price_client_"+group).text(price);
        $('[name="positions['+group+'][price]"]').val(price);
        $("#weight_total_"+group).text(weight*quantity);
        $("#price_total_"+group).text(price*quantity);

        calculation();
    });

    function calculation(){
        var weigth_total=0;
        var price_total=0;

        $(".weight").each(function() {
            weigth_total+=parseFloat($(this).text());
        });

        $(".price").each(function() {
            price_total+=parseFloat($(this).text());
        });

        $("#weight_total").text(weigth_total);
        $("#price_total").text(price_total);


        $(".CEB__select_color_js").each(function() {
            $(this).css({
                "backgroundColor": $(this).find("option:selected").attr("data-codecolor"),
                "color": $(this).find("option:selected").attr("data-codecolortext"),
            });
        });
        calcDelivery();
    };

    function setPriceWeight(group, quantity){
        let select=$('[name="positions['+group+'][product_id]"]');
        let price=select.find('option:selected').data("price");
        let weight=select.find('option:selected').data("weight");

        $("#price_client_"+group).text(price);
        $('[name="positions['+group+'][price]"]').val(price);
        $("#weight_total_"+group).text(weight*quantity);
        $("#price_total_"+group).text(price*quantity);
    }

    function calculation0(){
        numberType = +$(".CMR__input_typeZabor_js:checked").attr("data-numberType");
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
        let quantity=WallSteps * rowsBlocks;
        $(".formCalc [name='positions[12][quantity]']").val(quantity);
        setPriceWeight(12, quantity);

        //Колонны
        quantity=post_quantity * columnHeight / 20;
        $(".formCalc [name='positions[21][quantity]']").val(quantity);
        setPriceWeight(21, quantity);

        //Крышек
        $(".formCalc [name='positions[15][quantity]']").val(post_quantity);
        setPriceWeight(15, post_quantity);

        //Парапет
        if (numberType == 1) {
            quantity = +(WallSteps).toFixed(2);
        } else if (numberType == 2) {
            quantity = +(WallSteps).toFixed(2);
        } else if (numberType == 3) {
            quantity = +(WallSteps * 3).toFixed(2);
        };
        $(".formCalc [name='positions[11][quantity]']").val(quantity);
        setPriceWeight(11, quantity);

        // Декор
        if (numberType == 1) {
            quantity = 0;
        } else if (numberType == 2) {
            quantity = (WallSteps * 2).toFixed(2);
        } else if (numberType == 3) {
            quantity = (WallSteps * 2).toFixed(2);
        };

        $(".formCalc [name='positions[6][quantity]']").val(quantity);
        setPriceWeight(6, quantity);

        calculation();
    }


    function calcDelivery() {
        deliveryValue = $('#delivery').val();
        vehicleType = $('#vehicleType').val();
        weight_zakaz_for_delivery = parseFloat($("#weight_total").text());

        if (deliveryValue < 25) {
            deliveryValue = 25
        } else if (deliveryValue >= 25 && deliveryValue < 30) {
            deliveryValue = 30
        } else if (deliveryValue >= 30 && deliveryValue < 35) {
            deliveryValue = 35
        } else if (deliveryValue >= 35 && deliveryValue < 40) {
            deliveryValue = 40
        } else if (deliveryValue >= 40 && deliveryValue < 50) {
            deliveryValue = 50
        } else if (deliveryValue >= 50 && deliveryValue < 60) {
            deliveryValue = 60
        } else if (deliveryValue >= 60 && deliveryValue < 70) {
            deliveryValue = 70
        } else if (deliveryValue >= 70 && deliveryValue < 80) {
            deliveryValue = 80
        } else if (deliveryValue >= 80 && deliveryValue < 90) {
            deliveryValue = 90
        } else if (deliveryValue >= 90 && deliveryValue < 100) {
            deliveryValue = 100
        } else if (deliveryValue >= 100 && deliveryValue < 120) {
            deliveryValue = 120
        } else if (deliveryValue >= 120 && deliveryValue < 140) {
            deliveryValue = 140
        } else if (deliveryValue >= 140 && deliveryValue < 160) {
            deliveryValue = 160
        } else if (deliveryValue >= 160 && deliveryValue < 180) {
            deliveryValue = 180
        } else if (deliveryValue >= 180 && deliveryValue < 200) {
            deliveryValue = 200
        } else {
            deliveryValue = 220
        }

        if (vehicleType == 3) {

            weight_zakaz_for_delivery = '20.0'

            if (weight_zakaz > 20000) {
                ratio = Math.ceil(weight_zakaz / 20000)
            }

            if (shippingPrices) {
                let shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                    .transport_type_id == vehicleType && item.tonnage == weight_zakaz_for_delivery)
                if (shippingPrice.length !== 0) {
                    $('#resultAll').text(shippingPrice[0].price * ratio);
                } else {
                    $('#resultAll').text('ошибка');
                }
            }

        } else if (vehicleType == 4) {

            weight_zakaz_for_delivery = '15.0'

            if (weight_zakaz > 15000) {
                ratio = Math.ceil(weight_zakaz / 15000)
            }

            if (shippingPrices) {
                let shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                    .transport_type_id == vehicleType && item.tonnage == weight_zakaz_for_delivery)
                if (shippingPrice.length !== 0) {
                    $('#resultAll').text(shippingPrice[0].price * ratio);
                } else {
                    $('#resultAll').text('ошибка');
                }
            }


        } else if (vehicleType == 5) {
            weight_zakaz_for_delivery = '2.5'

            if (weight_zakaz > 2500) {
                ratio = Math.ceil(weight_zakaz / 2500)
            }

            if (shippingPrices) {
                let shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                    .transport_type_id == vehicleType && item.tonnage == weight_zakaz_for_delivery)
                if (shippingPrice.length !== 0) {
                    $('#resultAll').text(shippingPrice[0].price * ratio);
                } else {
                    $('#resultAll').text('ошибка');
                }
            }
        } else {
            if (shippingPrices) {
                let shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                    .transport_type_id == vehicleType && item.tonnage == String(Math.round(weight_zakaz /
                        1000) + ".0"))
                console.log(shippingPrice)
                if (shippingPrice.length !== 0) {
                    $('#resultAll').text(shippingPrice[0].price);
                } else {
                    shippingPrice = shippingPrices.filter(item => item.distance == deliveryValue && item
                        .transport_type_id == vehicleType && item.tonnage == '1.0')
                    if (shippingPrice.length !== 0) {
                        $('#resultAll').text(shippingPrice[0].price * weight_zakaz / 1000);
                    } else {
                        $('#resultAll').text('ошибка');
                    }
                }
            }
        }
    };

    $('.form').submit(function(e){
        e.preventDefault();
        $.ajax({
          url: "api/order_ms/create",
          type: "POST",
          data: $(this).serialize(),
          success: function(data) {
            $("#message").text(data);
          },
          error: function(response) {
            $("#message").text(response.responseText);
          }
        });
    });

    $("body").on("click", ".tab-link", function(){
        $(".tab-link.active").removeClass("active");
        $(this).addClass("active");
    });

    let Length = 10; // длина забора
    let post_quantity = 2; // кол-во столбов
    let wallHeight = 100; // высота стенки
    let columnHeight = 140; // Высота колоны
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

    calculation0();

    // Задаем значение первому ползунку
    function MadeSlider_1() {
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
                calculation0();
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
                calculation0();
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
                calculation0();
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
                calculation0();
            }
        });
    };

    //меняем тип забора
    $("body").on("change", ".CMR__change_js", function() {
        calculation0();
    });

});
