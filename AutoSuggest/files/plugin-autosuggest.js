/**
# MantisBT - A PHP based bugtracking system
# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

#
#  AutoSuggest Plugin for Mantis BugTracker :
#  © Hennes Hervé <contact@h-hennes.fr>
#    2015-2017
#  http://www.h-hennes.fr/blog/
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
                }
            });
        }
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
                }
            });
        }
    });
});


