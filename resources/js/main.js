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
              $("#message").html(data);
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

});
