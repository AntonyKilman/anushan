"use strict";

$("[data-checkboxes]").each(function() {
    var me = $(this),
        group = me.data('checkboxes'),
        role = me.data('checkbox-role');

    me.change(function() {
        var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
            checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
            dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
            total = all.length,
            checked_length = checked.length;

        if (role == 'dad') {
            if (me.is(':checked')) {
                all.prop('checked', true);
            } else {
                all.prop('checked', false);
            }
        } else {
            if (checked_length >= total) {
                dad.prop('checked', true);
            } else {
                dad.prop('checked', false);
            }
        }
    });
});

$(document).ready(function() {
    $('#my_table').DataTable();
});

$("#table-1").dataTable();

$(document).ready(function () {
    $("#table-desc").dataTable({
        "columnDefs": [
            { "sortable": false, "targets": [1,2,3,4,5,6] }
        ],
        "order": [[ 0, 'desc' ]],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ]
    });
});





$(document).ready(function() {
    $('#print_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ]
    });

    $('.print_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ]
    });
});


$("#table-2").dataTable({
    "columnDefs": [
        { "sortable": false, "targets": [0, 2, 3] }
    ],
    order: [
            [1, "asc"]
        ] //column indexes is zero based

});
$('#save-stage').DataTable();

$('#tableExport').DataTable({
    dom: 'Bfrtip',
    buttons: [
        // 'copy', 'csv', 'excel', 'pdf', 'print'
         'csv', 'excel',  'print'
        
    ]
});
