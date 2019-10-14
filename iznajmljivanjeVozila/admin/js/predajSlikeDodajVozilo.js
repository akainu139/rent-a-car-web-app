$(document).ready(function(){  
    $('#predajSlike').on('change', function(e){ 
        e.preventDefault();
        const elementZaBrisanje = document.getElementsByClassName("provjeraZaBrojSlika");
        while (elementZaBrisanje.length > 0) elementZaBrisanje[0].remove();
        
        $.ajax({  
            async: true,
            url:"ajax/predaj-slike-dodaj-vozilo.php",
            method:"POST",
            data: new FormData(this),
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