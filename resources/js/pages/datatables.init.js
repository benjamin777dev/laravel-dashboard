/*
Template Name: zPortal - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Datatables Js File
*/

$(document).ready(function() {
    $('#datatable_transaction').DataTable();
    var pipelineTable =  $('#datatable_pipe_transaction').DataTable();
    var contactTable =  $('#datatable_contact').DataTable();

    // contact search here custom button
    $('#contactSearch').keyup(function(){
        contactTable.search($(this).val()).draw() ;
  })
   // pipeline search here custom button
  $('#pipelineSearch').keyup(function(){
    pipelineTable.search($(this).val()).draw() ;
})
$('#Reset_All').click(function(){
    console.log('yes hitting')
    pipelineTable.search("").draw() ;
    $('#pipelineSearch').val("");
    $('#related_to_stage').val("");
    
})

$('#related_to_stage').change(function(){
    pipelineTable.search($(this).val()).draw() ;
})

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    $(".dataTables_length select").addClass('form-select form-select-sm');
});