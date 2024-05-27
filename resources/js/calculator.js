import Inputmask from "inputmask";

$(document).ready(function(){
    Inputmask({"mask": "+79999999999"}).mask(".phone");

    $("body").on("change", ".CMR__change_js", function() {
        let quantity=$(this).val();
        let group=$(this).data("id");
        let select=$('[name="positions['+group+'][product_id]"]');
        let price=select.find('option:selected').data("price");
        let weight=select.find('option:selected').data("weight");

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
        calcDelivery();
    };


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
    }; //end function

    $('#form').submit(function(e){
        e.preventDefault();
        $.ajax({
          url: "api/order_ms/create",
          type: "POST",
          data: $('#form').serialize(),
          success: function(data) {
            $("#message").text(data);
          },
          error: function(response) {
            $("#message").text(response.responseText);
          }
        });
    });




});
