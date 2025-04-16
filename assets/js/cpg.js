'use strict';

$(document).ready(function () {
    const dtCpgs = $('#cpgs-table').DataTable({
        "oLanguage": {
            "sEmptyTable": _pageLang.no_data ?? '',
        },
        "aLengthMenu": [[15,25, 50, 100], [15,25, 50, 100]],
        "pageLength": 15,
        "bLengthChange": false,
        "order": [[4, "desc"]],
        "dom": 'lrtip',
        "responsive": true,
    });


    //// Attach DataTables search to custom input
    $('#searchInputCpgs').on('keyup', function() {
        dtCpgs.search(this.value).draw();
    });
});

