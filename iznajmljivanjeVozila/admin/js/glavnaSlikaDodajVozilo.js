$(document).ready(function() {
    $(".glavnaSlika").on("click", function(){
        let slika = $(this).attr("value");
        
        $.ajax({
            async: true,
            type: "POST",
            url: "ajax/glavna-slika-dodaj-vozilo.php",
            data: {
                slika: slika
                },
            success: function(data, status)
            {
                const elementZaBrisanje = document.getElementsByClassName("card");
                while (elementZaBrisanje.length > 0) elementZaBrisanje[0].remove();
                $('#pregledSlika').html(data);
            }
        })
    })


})