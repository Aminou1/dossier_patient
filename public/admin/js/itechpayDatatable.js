function dataTable() {
    //Exportable table
    $(id).DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}
$(document).ready(function() {
    dataTable("#table_mouvement");

});
