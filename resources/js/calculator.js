import Inputmask from "inputmask";
import { jsPDF } from "jspdf";

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
        $(formClass+"#weight_total_"+group).text((weight*quantity).toFixed(1));
        $(formClass+"#price_total_"+group).text(price*quantity);
        $(formClass+".balance[data-id="+group+"]").text($(this).attr("data-balance"));
        $(formClass+".quantity[data-id="+group+"]").attr("max", $(this).attr("data-balance"));

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
        step=parseFloat(input.attr("step"));

     btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        var newVal = oldValue + step;

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
        let tr=$(this).parents("tr");
        let max=$(this).attr('max');

        let price=0;
        let weight=0;
        let select=$(formClass+'.select_product[data-id='+group+']');

        price=select.find('.selected').attr("data-price");
        weight=select.find('.selected').attr("data-weight");

        $(formClass+"#weight_total_"+group).text((weight*quantity).toFixed(1));
        $(formClass+"#price_total_"+group).text(price*quantity);

        if (quantity>max){
            if (!tr.hasClass("row-active")){
                tr.addClass("row-active");
            }
        }else{
            if (tr.hasClass("row-active")){
                tr.removeClass("row-active");
            }
        }
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

    $("body").on("focusout", ".price-tn", function() {
        let priceTn=parseInt($(this).val());
        $(this).addClass("disabled");
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let deliveryDistance = parseInt($(formClass+'select[name="attributes[delivery][id]"]').find('option:selected').attr("data-distance"));
        let weightTn= parseFloat($(formClass+".weight-tn").val());
        $(formClass+'.deliveryPrice').val(priceTn*weightTn).addClass("disabled");
    });

    $("body").on("focusout", '.deliveryPrice', function() {
        let priceDelivery=parseInt($(this).val());
        $(this).addClass("disabled");
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let deliveryDistance = parseInt($(formClass+'select[name="attributes[delivery][id]"]').find('option:selected').attr("data-distance"));
        let weightTn= parseFloat($(formClass+".weight-tn").val());
        $(formClass+'.price-tn').val(priceDelivery/weightTn).addClass("disabled");
    });

    function calculation(formClass){
        var weigth_total=0;
        var price_total=0;
        var countPallets=0;

        $(formClass+".weight").each(function() {
            weigth_total+=parseFloat($(this).text());
        });

        $(formClass+".price").each(function() {
            price_total+=parseFloat($(this).text());
        });

        if (formClass!=".calcBeton "){
            $(formClass+".change_js").each(function(){
                let group=$(this).attr("data-id");
                let countPal=parseInt($(formClass+'.select_product[data-id='+group+']').find('.selected').attr("data-countPallets"));
                if (countPal!=0){
                    countPallets+=Math.ceil($(this).val()/countPal); //количество паллетов
                }
            });

            let palletForm=$(formClass+'[name="positions[pallet][quantity]"]');
            palletForm.val(countPallets);

            let palletWeight=palletForm.attr("data-weight")*countPallets;
            $(formClass+"#weight_total_pallet").text((palletWeight).toFixed(1));

            weigth_total+=palletWeight;

            let palletPrice=palletForm.attr("data-price")*countPallets;
            $(formClass+"#price_total_pallet").text(palletPrice);
            price_total+=palletPrice;
        }else{

        }


        let weight_total_tn=weigth_total/1000;

        $(formClass+"#weight_total").text(weight_total_tn.toFixed(1));
        $(formClass+"#price_total").text(price_total);


        if (formClass==".calcBeton "){
            if (weight_total_tn<8){
                weight_total_tn=8
            }
            $(formClass+".weight-tn").val(weight_total_tn.toFixed(1));
        }else{
            weight_total_tn=Math.ceil(weight_total_tn);
            $(formClass+".weight-tn").val(weight_total_tn);
        }

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
        $(formClass+"#weight_total_"+group).text((weight*quantity).toFixed(1));
        $(formClass+"#price_total_"+group).text(price*quantity);
    }

    function calculation0(formClass){

        $(".labelCustomRadio_type.checked").removeClass("checked");
        numberType = $(".CMR__input_typeZabor_js:checked").attr("data-numberType");
        let reserve=$("#CEB__textReserve").val()/100;
        $(".CMR__input_typeZabor_js:checked").parent().addClass("checked");

        let Length = parseInt($("#CEB__textLength").val()); // длина забора
        let post_quantity = parseInt($("#CEB__textPost_quantity").val()); // кол-во столбов
        let wallHeight = parseInt($("#CEB__text_wallHeight").val()); // высота стенки
        let columnHeight = parseInt($("#CEB__text_columnHeight").val()); // Высота колоны


        lengthWalls = Length - (post_quantity * 0.28); // Длина стен общая +
        lengthColumns = Length - lengthWalls;  // Длина колонн общая

        //сколько рядов блока +
        if (numberType == 1) {
            rowsBlocks = wallHeight / 20;
        } else if (numberType == 2) {
            rowsBlocks = wallHeight / 20 - 1;
        } else if (numberType == 3) {
            rowsBlocks = wallHeight / 20 - 1;
        };

        WallSteps = Math.ceil(lengthWalls / 0.39); //шагов стены для заборного блока и парапетов

        //Блоки+
        let quantityBlock=WallSteps * rowsBlocks;
        quantityBlock=Math.ceil(quantityBlock+quantityBlock*reserve);
        $(formClass+"[name='positions[12][quantity]']").val(quantityBlock);
        setPriceWeight(12, quantityBlock, formClass);

        //Колонны
        let quantityColumn=post_quantity * columnHeight / 20;
        $(formClass+"[name='positions[21][quantity]']").val(quantityColumn);
        setPriceWeight(21, quantityColumn, formClass);

        //Крышек
        let quantityCover=post_quantity;
        $(formClass+"[name='positions[15][quantity]']").val(quantityCover);
        setPriceWeight(15, quantityCover, formClass);

        //Парапет
        let quantityParapet=0
        if (numberType == 1) {
            quantityParapet = WallSteps;
        } else if (numberType == 2) {
            quantityParapet = WallSteps;
        } else if (numberType == 3) {
            quantityParapet = WallSteps * 3;
        };

        quantityParapet=Math.ceil(quantityParapet+quantityParapet*reserve);

        $(formClass+"[name='positions[11][quantity]']").val(quantityParapet);
        setPriceWeight(11, quantityParapet, formClass);

        // Декор
        let quantity=0;
        if (numberType == 1) {
            quantity = 0;
        } else if (numberType == 2) {
            quantity =  Math.ceil(lengthWalls / 0.19);
        } else if (numberType == 3) {
            quantity =  Math.ceil(lengthWalls / 0.19);
        };

        quantity=Math.ceil(quantity+quantity*0.004);
        $(formClass+"[name='positions[6][quantity]']").val(quantity);
        setPriceWeight(6, quantity, formClass);
        calculation(formClass);
    }

    $("body").on("change", ".change_delivery", function() {
        let formClass="."+$(this).parents("form").attr("class")+" ";
        calculation(formClass);
        calcDelivery(formClass);
    })

    $("body").on("change", ".change_state", function() {
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let $val=$(this).next().find('.select2-selection__rendered');

        $(formClass+'[name="state"]').val($val.find('.state').attr("data-id"));
    });

    $("body").on("change", ".price-tt",function(){
        let $parent=$(this).parents(".CEB_block.services");
        let quantity=$parent.find(".quantity-tt").val();
        let price=$(this).val();

        $parent.find(".deliveryPrice-tt").val(price*quantity);
        calculation(".calcBeton ");
    });

    $("body").on("change", ".deliveryPrice-tt",function(){
        let $parent=$(this).parents(".CEB_block.services");
        let deliveryPrice=$(this).val();
        let quantity=$parent.find(".quantity-tt").val();

        $parent.find(".price-tt").val(deliveryPrice/quantity);
        calculation(".calcBeton ");
    });

    $("body").on("change", ".quantity-tt",function(){
        let $parent=$(this).parents(".CEB_block.services");
        let quantity=parseInt($(this).val());
        let price=$parent.find(".price-tt").val();

        $parent.find(".deliveryPrice-tt").val(price*quantity);
        calculation(".calcBeton ");
    });

    function calcDelivery(formClass) {
        if (!$(formClass+'.deliveryPrice').hasClass("disabled") && !$(formClass+'.price-tn.input').hasClass("disabled")){
            let deliveryValue = $(formClass+' .delivery').next().find('.select2-selection__rendered span').attr("data-distance");

            $(formClass+'[name="attributes[delivery][id]"]').val($(formClass+' .delivery').next().find('.select2-selection__rendered span').attr("data-id"));
            let vehicleType = $(formClass+'select[name="attributes[vehicle_type][id]"]').find('option:selected').attr("data-type");
            let weight = $(formClass+".weight-tn").val();
            let data = {"weightTn": weight, "distance": deliveryValue, "vehicleType": vehicleType};
            let priceTotal=parseInt($(formClass+'#price_total').html());

            $.ajax({
                url: '/api/shipping_price/get',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function(data){
                    $(formClass+'.price-tn.input').val(data.price);
                    $(formClass+".weight-tn").val(data.weightTn);
                    $(formClass+'.deliveryPrice').val(data.deliveryPrice);
                    priceTotal=priceTotal+data.deliveryPrice;
                    if (formClass==".calcBeton "){
                        $(".service:checked").each(function(){
                            let id=$(this).attr("id");
                            let deliveryPrice=$('[data-type="'+id+'"]').find(".deliveryPrice-tt").val();
                            priceTotal+=parseFloat(deliveryPrice);
                        });
                    }
                    $(formClass+'#message').text('');
                    $(formClass+'.total').html(priceTotal);
                },
                error: function(response) {
                    $("#message").html(response.responseJSON.error);
                    $(formClass+'.price-tn.input').val(0);
                    $(formClass+'.deliveryPrice').val(0);
                    $(formClass+'.total').html(priceTotal);
                }
            });




        }
    };


    $("body").on("change", ".service", function() {
        let id=$(this).attr("id");
        $('[data-type="'+id+'"]').toggleClass("hidden");
        calculation(".calcBeton ");
    });

    $('body').on('click','.print', function(){
        let pdf = new jsPDF('p','pt','a4');

        let section=$('.tab-content.active').html();

        pdf.html(section, {
            callback: function(pdf) {
              pdf.save('page.pdf');
            }
        });


    });
    
    $('body').on('click','.submit', function(e){
        e.preventDefault();
        let $form =$(this).parents("form");
        let url=$(this).attr("formaction");

        $.ajax({
            url: url,
            type: "POST",
            data: $form.serialize(),
            beforeSend: function() {
                console.log($form.serialize());
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


    $(".vehicle_type").each(function(){
        var $this = $(this);

        $this.select2({
            dropdownParent: $(this).parent()
        });
    });

    $(".change_phone").each(function(){
        var $this = $(this);
        $this.select2({
            width: '220px',
        //    tags: 'true',
            maximumInputLength: 12,
            dropdownParent: $(this).parent(),
            ajax: {
                url: '/api/contacts/get/phone',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data,params) {
                    params.current_page = params.current_page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.current_page * data.per_page) < data.total
                        }
                    };
                },
                cache: true,
                error: function(response) {
                    $("#message").html(response.responseJSON.message + response.responseJSON.file+ response.responseJSON.line);
                }
            },
            language: {
                noResults: function() {
                    let $searchInput=$(".select2-search__field");
                    let span=$searchInput.attr("aria-controls");

                    let typed = $searchInput.val();
                    if (typed.length>10){
                        $("[aria-owns='"+span+"'] .select2-selection__rendered").html(typed).attr("title", typed);
                        let formClass="."+$("[aria-owns='"+span+"']").parents("form").attr("class")+" ";
                        $(formClass+"[name='agent[id]']").val(0);
                        $(formClass+"[name='agent[phone]']").val(typed);
                    }
                }
            },
            templateResult: formatPhone,
            templateSelection: formatPhone
        });
    });


    $("body").on("keyup",".address", function(){
        let apikey='6bf2f721-5fc7-48cd-9b69-e2249c499235';
        let lang='ru_RU';
        let geocode=$(this).val();
        let addressPopup=$(this).next(".address-popup");
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let ull="34.01251650000001,45.12410907456747";
        let bbox="32.14,44~36.69,46";
        let url='https://suggest-maps.yandex.ru/v1/suggest?text='+geocode+'&bbox='+bbox+'&apikey='+apikey+'&format=json&lang=ru_RU&print_address=1&ull='+ull;

        if (geocode.length>10){
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data){
                    let results=data.results;
                    $(formClass+'.select2-results__options').empty();
                    $(formClass+'.address-popup').show();
                    $.each(results,function(index, value){
                        $(formClass+".select2-results__options").append('<li class="select2-results__option click" data-distance="'+(value.distance.value/1000).toFixed(2)+'">'+value.address.formatted_address+'</li>');
                    });
                },
                error: function(rjqXHR, textStatus, errorThrown) {
                    $(formClass+"#message").html(response.responseJSON.error);
                }
            });
        }else{
            $(formClass+".distance").val('');
            $(formClass+"#message").html('');
        }
    });

    $(".address-popup").on("click", ".select2-results__option", function(){
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let val=$(this).text();
        $(formClass+".address").val(val);
        $(formClass+".address-popup").hide();

        let km=$(this).data("distance");
        $(formClass+".distance").val(km);
        $(formClass+"#message").html('');
        // calcDelivery(formClass);
    });

    $(".delivery").each(function(){
        var $this = $(this);
        $this.select2({
            width: '220px',
            dropdownParent: $(this).parent(),
            ajax: {
                url: '/api/deliveries/get/name',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data,params) {
                    params.current_page = params.current_page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.current_page * data.per_page) < data.total
                        }
                    };
                },
                cache: true,
                error: function(response) {
                    $("#message").html(response.responseJSON.message + response.responseJSON.file+ response.responseJSON.line);
                }
            },
            language: {
                noResults: function() {
                    $(formClass+" [attributes[delivery][id]]").val("");
                }
            },
            templateResult: formatDelivery,
            templateSelection: formatDelivery
        });
    });

    $(".change_state").each(function(){
        var $this = $(this);
        let style= $this.parent().find(".state_input").attr("data-style-default");

        $this.select2({
            width: '220px',
            dropdownParent: $(this).parent(),
            ajax: {
                url: 'api/states/get/name',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data,params) {
                    params.current_page = params.current_page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.current_page * data.per_page) < data.total
                        }
                    };
                },
                cache: true,
                error: function(response) {
                    $("#message").html(response.responseJSON.message + response.responseJSON.file+ response.responseJSON.line);
                }
            },
            templateResult: formatState,
            templateSelection: formatState
        });

        $(this).parent().find(".select2-selection__rendered").attr("style",style);

    });

    function formatDelivery(state) {
        if (!state.id){
            return state.text;
        }
		var $state = $(
			'<span data-id="'+state.ms_id+'" data-distance="'+state.distance+'">'+state.name+'</span>'
		);
		return $state;
	};

    function formatName(state) {
        if (!state.id){
            return state.text;
        }
		var $state = $(
			'<span data-id="'+state.ms_id+'" data-phone="'+state.phone+'" data-name="'+state.name+'" data-change="change_phone">'+state.name+'</span>'
		);
		return $state;
	};

    function formatPhone(state) {
        if (!state.id){
            return state.text;
        }
		var $state = $(
			'<span data-id="'+state.ms_id+'" data-name="'+state.name+'" data-phone="'+state.phone+'" data-change="change_name">'+state.phone+' ('+state.name+')</span>'
		);
		return $state;
	};

    function formatState(state) {
        if (!state.id){
            return state.text;
        }
		var $state = $(
			'<div class="state" data-id="'+state.ms_id+'" style="background:'+state.color+'">'+state.name+'</div>'
		);
		return $state;
	};

    $(".change_name").each(function(){
        var $this = $(this);
        $this.select2({
            width: '220px',
    //        tags: 'true',
            dropdownParent: $(this).parent(),
            ajax: {
                url: '/api/contacts/get/name',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data,params) {
                    params.current_page = params.current_page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.current_page * data.per_page) < data.total
                        }
                    };
                },
                cache: true,
                error: function(response) {
                    $("#message").html(response.responseJSON.message+response.responseJSON.file+response.responseJSON.line);
                }
            },
            language: {
                noResults: function() {
                    let $searchInput=$(".select2-search__field");
                    let span=$searchInput.attr("aria-controls");

                    let typed = $searchInput.val();

                    $("[aria-owns='"+span+"'] .select2-selection__rendered").html(typed).attr("title", typed);
                    let formClass="."+$("[aria-owns='"+span+"']").parents("form").attr("class")+" ";
                    $(formClass+"[name='agent[id]']").val(0);
                    $(formClass+"[name='agent[name]']").val(typed);
                }
            },
            templateResult: formatName,
            templateSelection: formatName
        });
    });

    $("body").on("click", ".time-span", function(){
       let value=$(this).attr("data-time");
       let formClass="."+$(this).parents(".datetime-popup").attr("data-class");

       $(formClass+' [name="deliveryPlannedMoment"]').val(value);
       $(formClass+" .plan").val(value.substr(0,19));
       $(formClass+".datetime-popup").removeClass("active");
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
    let columnHeight = 220; // Высота колоны
    let numberType = ""; // номер типа забора

    let weight_zakaz = 0; // вес заказа
    let total_zakaz = 0; // всего за заказ

    let WallSteps = 0; //	Шагов стены
    let rowsBlocks = 0; // Рядов блока
    let lengthWalls = 0; // Длина стен общая
    let lengthColumns = 0; // Длина колонн общая



    MadeSlider_1(Length); // установка первого ползунка
    MadeSlider_2(post_quantity); // установка 2 ползунка
    MadeSlider_3(wallHeight); // установка 3 ползунка
    MadeSlider_4(columnHeight); // установка 4 ползунка

    $("body").on("change", ".change_length", function(){
        let val=$(this).val();
        MadeSlider_1(val);
        calculation0(".calcFence ");
    });

    $("body").on("change", ".agent_change", function(){
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let $val=$(this).next().find('.select2-selection__rendered');
        let val='';

        $(formClass+"[name='agent[id]']").val($val.find('span').attr("data-id"));
        $(formClass+"[name='agent[phone]']").val("");
        $(formClass+"[name='agent[name]']").val("");

        let selectClass=$val.find('span').attr("data-change");

        $(formClass+"."+selectClass).append($(this).find("option:last-child").prop('outerHTML'));

        if (selectClass=="change_name"){
            val=$val.find('span').attr("data-name");
        }else{
            val=$val.find('span').attr("data-phone");
        }

        $(formClass+"."+selectClass).next().find(".select2-selection__rendered").html($val.html()).find("span").text(val);
    });


    $("body").on("change", ".state_change", function(){
        let formClass="."+$(this).parents("form").attr("class")+" ";
        let $val=$(this).next().find('.select2-selection__rendered');
        $(formClass+"[name='state']").val($val.find('span').attr("data-id"));
    });

    $("body").on("change", ".change_postQuantity", function(){
        let val=$(this).val();
        MadeSlider_2(val);
        calculation0(".calcFence ");
    });

    $("body").on("change", ".change_wallHeight", function(){
        let val=$(this).val();
        MadeSlider_3(val);
        calculation0(".calcFence ");
    });

    $("body").on("change", ".change_columnHeight", function(){
        let val=$(this).val();
        MadeSlider_4(val);
        calculation0(".calcFence ");
    });


    $(".select__list.CEB__select_color_js").each(function() {
        $(this).parent(".select").find(".select__head").css({
            "backgroundColor": $(this).find(".selected").attr("data-codecolor"),
            "color": $(this).find(".selected").attr("data-codecolortext"),
        }).text($(this).find(".selected").text());

        let group=$(this).attr("data-id");
        let formClass="."+$(this).parents("form").attr("class")+" ";

        $(formClass+".balance[data-id="+group+"]").text($(this).find(".selected").attr("data-balance"));
        $(formClass+".change_js[data-id="+group+"]").attr("max", $(this).find(".selected").attr("data-balance"));
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
    function MadeSlider_1(Length) {
        $("#CEB__inputLength").val(Length);
        $("#CEB__textLength").val(Length);
        $("#CEBQuestionW-slide1").slider({
            value: Length,
            min: 0,
            max: 300,
            range: 'min',
            step: 1,
            animate: true,
            slide: function(event, ui) {
                Length = ui.value;
                $("#CEB__inputLength").val(Length);
                $("#CEB__textLength").val(Length);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 2 ползунку
    function MadeSlider_2(post_quantity) {
        $("#CEB__inputPost_quantity").val(post_quantity);
        $("#CEB__textPost_quantity").val(post_quantity);
        $("#CEBQuestionW-slide2").slider({
            value: post_quantity,
            min: 0,
            max: 120,
            range: 'min',
            step: 1,
            animate: true,
            slide: function(event, ui) {
                post_quantity = ui.value;
                $("#CEB__inputPost_quantity").val(post_quantity);
                $("#CEB__textPost_quantity").val(post_quantity);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 3 ползунку
    function MadeSlider_3(wallHeight) {
        $("#CEB__input_wallHeight").val(wallHeight);
        $("#CEB__text_wallHeight").val(wallHeight);

        $("#CEBQuestionW-slide3").slider({
            value: wallHeight,
            min: 80,
            max: 320,
            range: 'min',
            step: 20,
            animate: true,
            slide: function(event, ui) {
                wallHeight = ui.value;
                $("#CEB__input_wallHeight").val(wallHeight);
                $("#CEB__text_wallHeight").val(wallHeight);
                calculation0(".calcFence ");
            }
        });
    };

    // Задаем значение 4 ползунку
    function MadeSlider_4(columnHeight) {
        $("#CEB__input_columnHeight").val(columnHeight);
        $("#CEB__text_columnHeight").val(columnHeight);

        $("#CEBQuestionW-slide4").slider({
            value: columnHeight,
            min: 80,
            max: 320,
            range: 'min',
            step: 20,
            animate: true,
            slide: function(event, ui) {
                columnHeight = ui.value;
                $("#CEB__input_columnHeight").val(columnHeight);
                $("#CEB__text_columnHeight").val(columnHeight);
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
        width_datetime();
    });

    window.onresize = function() {
        width_datetime();
    }

    width_datetime();

    function width_datetime(){
        let formClass="."+$(".CMR__input_calc_js:checked").attr("data-content");
        let height=$(".datetime-popup"+formClass+" .date-time:nth-child(1)").outerHeight(true)+$(".datetime-popup"+formClass+" .date-time:nth-child(2)").outerHeight(true)+$(".datetime-popup"+formClass+" .date-time:nth-child(3)").outerHeight(true)+$(".datetime-popup"+formClass+" .date-time:nth-child(4)").outerHeight(true);
        $(".datetime-popup"+formClass).height(height+"px").css("max-height",height+"px");


        if ($(window).width()>1473){
            let itogo=$(formClass+" .td-sum").outerWidth()+$(formClass+" .td-sum").siblings(".td-price").outerWidth()+$(formClass+" .td-sum").siblings(".td-price").siblings(".td-weight").outerWidth();
            $(formClass+' .sim').css("width", itogo+"px");
            let width=$(formClass+" .td-name").outerWidth()-10;
            $(formClass+" .input-delivery").css("width", width+"px");
            $(formClass+" .input-sum").css("width",$(formClass+" .td-sum").outerWidth()+"px");
            $(formClass+" .input-price").css("width",$(formClass+" .td-price").outerWidth()+"px");
            $(formClass+" .input-weight").css("width",$(formClass+" .td-weight").outerWidth()+"px");
        }else{
            $(formClass+' .sim').removeAttr("style");
            $(formClass+" .input-delivery").removeAttr("style");
            $(formClass+" .input-sum").removeAttr("style");
            $(formClass+" .input-price").removeAttr("style");
            $(formClass+" .input-weight").removeAttr("style");
        }
    }


});
