// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTableKorisnici').dataTable(
    {
    "language":{
      "decimal":        "",
      "emptyTable":     "Trenutno nema korisnika za pregled.",
      "info":           "Prikazano: _START_ do _END_ od _TOTAL_",
      "infoEmpty":      "Prikazano 0 redova",
      "infoFiltered":   "",
      "infoPostFix":    "",
      "thousands":      ",",
      "lengthMenu":     "Prikaži _MENU_ redova",
      "loadingRecords": "Učitavanje...",
      "processing":     "Obrada...",
      "search":         "Traži:",
      "zeroRecords":    "Nema traženih rezultata.",
      "paginate": {
          "first":      "Prva",
          "last":       "Posljednja",
          "next":       "Sljedeća",
          "previous":   "Prethodna"
      },
      "aria": {
          "sortAscending":  ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      }
    } 
  );

  $('#dataTableMarke').dataTable(
    {
    "language":{
      "decimal":        "",
      "emptyTable":     "Trenutno nema marki za pregled.",
      "info":           "Prikazano: _START_ do _END_ od _TOTAL_",
      "infoEmpty":      "Prikazano 0 redova",
      "infoFiltered":   "",
      "infoPostFix":    "",
      "thousands":      ",",
      "lengthMenu":     "Prikaži _MENU_ redova",
      "loadingRecords": "Učitavanje...",
      "processing":     "Obrada...",
      "search":         "Traži:",
      "zeroRecords":    "Nema traženih rezultata.",
      "paginate": {
          "first":      "Prva",
          "last":       "Posljednja",
          "next":       "Sljedeća",
          "previous":   "Prethodna"
      },
      "aria": {
          "sortAscending":  ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      }
    } 
  );

  $('#dataTableVozila').dataTable(
    {
    "language":{
      "decimal":        "",
      "emptyTable":     "Trenutno nema vozila za pregled.",
      "info":           "Prikazano: _START_ do _END_ od _TOTAL_",
      "infoEmpty":      "Prikazano 0 redova",
      "infoFiltered":   "",
      "infoPostFix":    "",
      "thousands":      ",",
      "lengthMenu":     "Prikaži _MENU_ redova",
      "loadingRecords": "Učitavanje...",
      "processing":     "Obrada...",
      "search":         "Traži:",
      "zeroRecords":    "Nema traženih rezultata.",
      "paginate": {
          "first":      "Prva",
          "last":       "Posljednja",
          "next":       "Sljedeća",
          "previous":   "Prethodna"
      },
      "aria": {
          "sortAscending":  ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      }
    } 
  );

  $('#dataTableRezerviranaVozila').dataTable(
    {
    "language":{
      "decimal":        "",
      "emptyTable":     "Trenutno nema rezerviranih vozila za pregled.",
      "info":           "Prikazano: _START_ do _END_ od _TOTAL_",
      "infoEmpty":      "Prikazano 0 redova",
      "infoFiltered":   "",
      "infoPostFix":    "",
      "thousands":      ",",
      "lengthMenu":     "Prikaži _MENU_ redova",
      "loadingRecords": "Učitavanje...",
      "processing":     "Obrada...",
      "search":         "Traži:",
      "zeroRecords":    "Nema traženih rezultata.",
      "paginate": {
          "first":      "Prva",
          "last":       "Posljednja",
          "next":       "Sljedeća",
          "previous":   "Prethodna"
      },
      "aria": {
          "sortAscending":  ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      }
    } 
  );

  $('#dataTableOdsutnaVozila').dataTable(
    {
    "language":{
      "decimal":        "",
      "emptyTable":     "Trenutno nema vozila za pregled.",
      "info":           "Prikazano: _START_ do _END_ od _TOTAL_",
      "infoEmpty":      "Prikazano 0 redova",
      "infoFiltered":   "",
      "infoPostFix":    "",
      "thousands":      ",",
      "lengthMenu":     "Prikaži _MENU_ redova",
      "loadingRecords": "Učitavanje...",
      "processing":     "Obrada...",
      "search":         "Traži:",
      "zeroRecords":    "Nema traženih rezultata.",
      "paginate": {
          "first":      "Prva",
          "last":       "Posljednja",
          "next":       "Sljedeća",
          "previous":   "Prethodna"
      },
      "aria": {
          "sortAscending":  ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      }
    } 
  );


});