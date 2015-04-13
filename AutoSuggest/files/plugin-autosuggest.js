/**
 * Plugin autoSuggest
 */
jQuery(function($) {

    //Bug relationships ( My view page + Top right corner )
    $("input[name='dest_bug_id'], input[name='bug_id']").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "plugin.php?page=AutoSuggest/get-suggestions.php",
                dataType: "json",
                data: {
                    action: 'bugs',
                    search: request.term
                },
                success: function(data) {
                    response(data);
                },
            });
        },
    });

    //Users monitoring this issue
    $("input[name='username']").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "plugin.php?page=AutoSuggest/get-suggestions.php",
                dataType: "json",
                data: {
                    action: 'users',
                    search: request.term
                },
                success: function(data) {
                    response(data);
                },
            });
        },
    });
});


