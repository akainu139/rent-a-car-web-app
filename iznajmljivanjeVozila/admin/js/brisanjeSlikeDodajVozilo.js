$(document).ready(function() {
    $(".tipkaZaBrisanje").on("click", function(){
    let nazivSlike = $(this).attr("id");
    
    $("#ukloniSlikuVozilaModal").modal("show");
    $("#obrisiSliku").attr("name", nazivSlike);
    });

    $("#obrisiSliku").on("click", function(){
        let slika = $(this).attr("name");
        $.ajax({
            async: true,
            type: "POST",
            url: "ajax/brisanje-slike-dodaj-vozilo.php",
            data: {
                slika: slika
                },
            success: function(data, status)
            {
                $("#ukloniSlikuVozilaModal").modal("hide");
                let klasa = `obrisiDiv${slika}`;
                const elementZaBrisanje = document.getElementsByClassName(klasa);
                while (elementZaBrisanje.length > 0) elementZaBrisanje[0].remove();
            }
        })
    })

 })