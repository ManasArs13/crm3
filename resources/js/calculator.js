$(document).ready(function(){

    let fence_type_id=$("#fence_type_id").val();
    let fence_length=$("#fence_length").val();
    let number_of_columns=$("#number_of_columns").val();
    let wall_id=Number($("#wall_id option:selected").text());
    let column_id=Number($("#column_id option:selected" ).text());

    $("#phone").inputmask("+79999999999");
    calc();

    $(".calculator").on("change","#fence_type_id",function(){
        fence_type_id=$("#fence_type_id").val();
        calc();
    });

    $(".calculator").on("change","#fence_length",function(){
        fence_length=$("#fence_length").val();
        calc();
    });

    $(".calculator").on("change","#number_of_columns",function(){
        number_of_columns=$("#number_of_columns").val();
        calc();
    });

    $(".calculator").on("change","#wall_id",function(){
        wall_id=Number($("#wall_id option:selected").text());
        calc();
    });

    $(".calculator").on("change","#column_id",function(){
        column_id=Number($("#column_id option:selected").text());
        calc();
    });
    $(".calculator").on("change","#delivery_id",function(e){
        delivery();
    });

    $(".calculator").on("change",".color",function(){
        let cat_id=$(this).attr("cat_id");
        let color_id=$(this).val();

        if ($("#price_"+cat_id).text()!=window.staticStore.products[cat_id][color_id]["price"]) {
            $("#price_"+cat_id).text(window.staticStore.products[cat_id][color_id]["price"]);
            calc();
        }

        var products=JSON.parse($("#products_"+cat_id).val());
        products["id"]=window.staticStore.products[cat_id][color_id]["id"];
        $("#products_"+cat_id).text(JSON.stringify(products));
    });

    $(".calculator").on("change",".vehicle_type_id",function(){
         delivery();
    });

    function calc(){
        let total_column_length=(0.28*number_of_columns).toFixed(2);
        let total_wall_length=(fence_length-total_column_length).toFixed(2);
        let wall_step=Math.ceil(total_wall_length/0.4);
        let total_sum=0;
        let total_weight=0;
        let block_rows=wall_id/20;

        if (fence_type_id==2 || fence_type_id==3)
            block_rows=wall_id/20-1;

        $("#wall_step").text(wall_step);
        $("#block_rows").text(block_rows);
        $("#total_column_length").text(total_column_length);
        $("#total_wall_length").text(total_wall_length);

        let quantity_block=wall_step*block_rows;
        let color_id_block=$("#color_id_8a32b2b3-4bd4-11e9-9109-f8fc0011b70f").val();
        let weight_block=quantity_block*window.staticStore.products['8a32b2b3-4bd4-11e9-9109-f8fc0011b70f'][color_id_block]["weight_kg"];
        let sum_block=quantity_block*window.staticStore.products['8a32b2b3-4bd4-11e9-9109-f8fc0011b70f'][color_id_block]["price"];
        $("#quantity_8a32b2b3-4bd4-11e9-9109-f8fc0011b70f").text(quantity_block);
        var product_block=window.staticStore.products['8a32b2b3-4bd4-11e9-9109-f8fc0011b70f'][color_id_block];
        product_block["quantity"]=quantity_block;

        $("#products_8a32b2b3-4bd4-11e9-9109-f8fc0011b70f").val(JSON.stringify(product_block));
        $("#weight_kg_8a32b2b3-4bd4-11e9-9109-f8fc0011b70f").text((weight_block/1000).toFixed(2));
        $("#sum_8a32b2b3-4bd4-11e9-9109-f8fc0011b70f").text(sum_block);

        let quantity_decor=0;
        if (fence_type_id==2 || fence_type_id==3)
            quantity_decor=wall_step*2;
        let color_id_decor=$("#color_id_63ed558e-9f46-11ea-0a80-05d800085fa4").val();
        let weight_decor=quantity_decor*window.staticStore.products['63ed558e-9f46-11ea-0a80-05d800085fa4'][color_id_decor]["weight_kg"];
        let sum_decor=quantity_decor*window.staticStore.products['63ed558e-9f46-11ea-0a80-05d800085fa4'][color_id_decor]["price"];
        $("#quantity_63ed558e-9f46-11ea-0a80-05d800085fa4").text(quantity_decor);
        var product_decor=window.staticStore.products['63ed558e-9f46-11ea-0a80-05d800085fa4'][color_id_decor];
        product_decor["quantity"]=quantity_decor;
        $("#products_63ed558e-9f46-11ea-0a80-05d800085fa4").val(JSON.stringify(product_decor));
        $("#weight_kg_63ed558e-9f46-11ea-0a80-05d800085fa4").text((weight_decor/1000).toFixed(2));
        $("#sum_63ed558e-9f46-11ea-0a80-05d800085fa4").text(sum_decor);

        let quantity_parapet=0;
        quantity_parapet=wall_step;
        if (fence_type_id==3)
            quantity_parapet=wall_step*3;
        let color_id_parapet=$("#color_id_89392d42-4bd5-11e9-9107-50480012181f").val();
        let weight_parapet=quantity_parapet*window.staticStore.products['89392d42-4bd5-11e9-9107-50480012181f'][color_id_parapet]["weight_kg"];
        let sum_parapet=quantity_parapet*window.staticStore.products['89392d42-4bd5-11e9-9107-50480012181f'][color_id_parapet]["price"];
        $("#quantity_89392d42-4bd5-11e9-9107-50480012181f").text(quantity_parapet);
        var product_parapet=window.staticStore.products['89392d42-4bd5-11e9-9107-50480012181f'][color_id_parapet];
        product_parapet["quantity"]=quantity_parapet;
        $("#products_89392d42-4bd5-11e9-9107-50480012181f").val(JSON.stringify(product_parapet));
        $("#weight_kg_89392d42-4bd5-11e9-9107-50480012181f").text((weight_parapet/1000).toFixed(2));
        $("#sum_89392d42-4bd5-11e9-9107-50480012181f").text(sum_parapet);

        let quantity_cover=number_of_columns;
        let color_id_cover=$("#color_id_9fa1ece2-4bd5-11e9-9ff4-34e800129536").val();
        let weight_cover=quantity_cover*window.staticStore.products['9fa1ece2-4bd5-11e9-9ff4-34e800129536'][color_id_cover]["weight_kg"];
        let sum_cover=quantity_cover*window.staticStore.products['9fa1ece2-4bd5-11e9-9ff4-34e800129536'][color_id_cover]["price"];
        $("#quantity_9fa1ece2-4bd5-11e9-9ff4-34e800129536").text(quantity_cover);
        var product_cover=window.staticStore.products['9fa1ece2-4bd5-11e9-9ff4-34e800129536'][color_id_cover];
        product_cover["quantity"]=quantity_cover;
        $("#products_9fa1ece2-4bd5-11e9-9ff4-34e800129536").val(JSON.stringify(product_cover));
        $("#weight_kg_9fa1ece2-4bd5-11e9-9ff4-34e800129536").text((weight_cover/1000).toFixed(2));
        $("#sum_9fa1ece2-4bd5-11e9-9ff4-34e800129536").text(sum_cover);

        let quantity_column=number_of_columns*column_id/20;
        let color_id_column=$("#color_id_fe72b162-a056-11e7-7a6c-d2a900046be1").val();
        let weight_column=quantity_column*window.staticStore.products['fe72b162-a056-11e7-7a6c-d2a900046be1'][color_id_column]["weight_kg"];
        let sum_column=quantity_column*window.staticStore.products['fe72b162-a056-11e7-7a6c-d2a900046be1'][color_id_column]["price"];
        $("#quantity_fe72b162-a056-11e7-7a6c-d2a900046be1").text(quantity_column);
        var product_column=window.staticStore.products['fe72b162-a056-11e7-7a6c-d2a900046be1'][color_id_column];
        product_column["quantity"]=quantity_column;
        $("#products_fe72b162-a056-11e7-7a6c-d2a900046be1").val(JSON.stringify(product_column));
        $("#weight_kg_fe72b162-a056-11e7-7a6c-d2a900046be1").text((weight_column/1000).toFixed(2));
        $("#sum_fe72b162-a056-11e7-7a6c-d2a900046be1").text(sum_column);


        total_sum=sum_block+sum_decor+sum_parapet+sum_cover+sum_column;
        total_weight=weight_block+weight_decor+weight_parapet+weight_cover+weight_column;

        $("#total_sum").text(total_sum);
        $('#total_weight').text((total_weight/1000).toFixed(2));

        $("input[name='weight']").val((total_weight/1000).toFixed(2));
        $("input[name='sum']").val(total_sum);
        delivery();
    }

    function delivery(){
        var data={
            "delivery_id": $("#delivery_id").val(),
            "vehicle_type_id": $("#vehicle_type_id").val(),
            "weight_kg":  $("#total_weight").text(),
            "_token": $("input[name='_token']").val()
        };

        $.ajax({
            type: "POST",
            url: window.staticStore.urlDelivery,
            data: data,
            timeout: 800000,
            success: function (res) {
                $.each(res["prices"], function (index, value) {
                    $("[name ='deliveryPrice["+index+"]']").val(value);
                });
            },
            error:function (jqXHR, textStatus,  errorThrown) {
                console.log(textStatus+":"+errorThrown);
            }
        });
    }

});
