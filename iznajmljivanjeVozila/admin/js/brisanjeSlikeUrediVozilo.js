$(document).ready(function() {
    $(".tipkaZaBrisanje").on("click", function(){
    let nazivSlike = $(this).attr("id");
    let idVozila = $(this).data('idvozila');

    $("#ukloniSlikuVozilaModal").modal("show");
    $("#obrisiSliku").attr("name", nazivSlike);
    $('#obrisiSliku').data('idvozila',idVozila);

    });

    $("#obrisiSliku").on("click", function(){
        let slika = $(this).attr("name");
        let idVozila = $(this).data('idvozila');

        $.ajax({
            async: true,
            type: "POST",
            url: "ajax/brisanje-slike-uredi-vozilo.php",
            data: {
                slika: slika,
                idVozila: idVozila
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


