$(document).ready(function(){


    $('body').on('click','.create_to_ms', function(e){
        e.preventDefault();
        let url=$(this).attr("formaction");


        $.ajax({
            url: url,
            type: "POST",
            data: {"id": $(this).attr("data-id")},
            beforeSend: function() {
            //   $(".preloader").addClass("active");
            },
            success: function(data) {
              $("#message").html(data);
            },
            error: function(response) {
              $("#message").html(response.responseText);
            },
            complete: function() {
            //   $(".preloader").removeClass("active");
            }
          });
    });

});
