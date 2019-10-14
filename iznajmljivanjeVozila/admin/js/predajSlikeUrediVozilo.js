$(document).ready(function(){  
    $('#predajSlike').on('change', function(e){ 
        e.preventDefault();
        const elementZaBrisanje = document.getElementsByClassName("provjeraZaBrojSlika");
        while (elementZaBrisanje.length > 0) elementZaBrisanje[0].remove();
        
        let idVozila = $(this).data('idvozilaform');
        const formData = new FormData(this);
        formData.append('idvozila', idVozila);
        $.ajax({  
            async: true,
            url:"ajax/predaj-slike-uredi-vozilo.php",
            method:"POST",
            data: formData,
            contentType:false,
            //cache:false,
            processData:false,
            success:function(data)
            {
                $('#pregledSlika').html(data);
            }
        })
    });
    
});  