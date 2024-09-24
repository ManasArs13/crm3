$(document).ready(function(){


    $('body').on('click','.create_to_ms', function(e){
        e.preventDefault();
        let url=$(this).attr("formaction");


        $.ajax({
            url: url,
            type: "POST",
            data: {"id": $(this).attr("data-id")},
            beforeSend: function() {
                $(this).html("обработка...")
            },
            success: function(data) {
                if ("id" in data){
                    $("#message").html(data["id"]);
                    $("#name").val(data["name"])
                }else{
                    $("#message").html(data)
                }
            },
            error: function(response) {
              $("#message").html(JSON.parse(response.responseText));
            //   $("#message").html(response.responseText);
            },
            complete: function() {
                $(this).html("Добавить в мс");
            }
          });
    });

    $('body').on("change", ".contact", function(e){
        e.preventDefault();

        let val=$(this).val();

        $.ajax({
            url: "/api/contact/get/balance",
            type: "GET",
            data: {"id": val},

            success: function(data) {
              let balance=parseFloat(data);
              $(".balance").html(data);
              if (balance>=0){
                if ($(".balance").hasClass("bg-red-300")){
                    $(".balance").removeClass("bg-red-300");
                }

                if (!$(".balance").hasClass("bg-green-300")){
                    $(".balance").addClass("bg-green-300");
                }

              }else{
                if (!$(".balance").hasClass("bg-red-300")){
                    $(".balance").addClass("bg-red-300");
                }

                if ($(".balance").hasClass("bg-green-300")){
                    $(".balance").removeClass("bg-green-300");
                }
              }
            },
            error: function(response) {
              $("#message").html(JSON.parse(response.responseText));
            //   $("#message").html(response.responseText);
            },
            complete: function() {
                $(this).html("Добавить в мс");
            }
          });


    })

});
