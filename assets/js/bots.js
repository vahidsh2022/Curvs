if(typeof window._pageLang == 'undefined') {
     window._pageLang = {};
}



'use strict';

let dtBots;
$(document).ready(function () {
    const deleteBots = $('.delete_bots');
    if(deleteBots) {
        deleteBots.on('click', function () {
            var botId = $(this).attr('aria-data-id');
            if (confirm(_pageLang.delete_record_conform_msg)) {
                $.ajax({
                    type: 'POST',
                    url: SAP_SITE_URL + '/bots/delete/' + botId,
                    data: {},
                    complete: function(result) {
                        if (result.status)
                        {
                            $('#bot_row_' + botId).remove();
                            if ($("#bots-table tbody tr").length == 0) {
                                $("#bots-table").find('tbody').append('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>');
                            }
                        }
                    }

                });
            }
        });
    }


    const botsTable = $('#bots-table');
    if(botsTable) {
        dtBots = botsTable.DataTable({
            "oLanguage": {
                "sEmptyTable": _pageLang.no_data ?? '',
            },
            "aLengthMenu": [[15,25, 50, 100], [15,25, 50, 100]],
            "pageLength": 15,
            "bLengthChange": false,
            "order": [[4, "desc"]],
            "dom": 'lrtip',
            "responsive": true,
            // initComplete: function () {
            //     this.api().columns().every( function (colIdx) {
            //         if( colIdx == 2 ){
            //             var column = this;
            //             var select = $(`<select><option value="">${_pageLang.bot_types}</option></select>`)
            //                 .appendTo( $(column.header()).empty() )
            //                 .on( 'change', function () {
            //                     var val = $.fn.dataTable.util.escapeRegex(
            //                         $(this).val()
            //                     );
            //
            //                     column
            //                         .search( val ? '^'+val+'$' : '', true, false )
            //                         .draw();
            //                 } );
            //
            //             column.data().unique().sort().each( function ( d, j ) {
            //                 select.append( '<option value="'+d+'">'+d+'</option>' )
            //             } );
            //         }
            //     } );
            // }
        });

    }

    const searchInputBots = $('#searchInputBots');
    if(searchInputBots) {
        //// Attach DataTables search to custom input
        $('#searchInputBots').on('keyup', function() {
            dtBots.search(this.value).draw();
        });
    }


    const twitterRetweetPagesWrapper = $('#twitter_retweet_pages_wrapper');
    const tweeterRetweet = $('#twitter_retweet');

    if(tweeterRetweet) {
        if(tweeterRetweet.is(':checked')) {
            twitterRetweetPagesWrapper.show();
        }
        tweeterRetweet.on('change',function () {
            const isChecked = tweeterRetweet.is(':checked');
            if(isChecked) {
                twitterRetweetPagesWrapper.show();
            } else {
                twitterRetweetPagesWrapper.hide();
            }
        });
    }
});



