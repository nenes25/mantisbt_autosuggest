<?php
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
#    2015-2020
#  http://www.h-hennes.fr/blog/

#Basic restriction : non authentificated users cannot see suggestions
if ( current_user_is_anonymous()){
    return json_encode(array());
}
$t_action = gpc_get('action');
$t_search = gpc_get('search');
$t_project_id = helper_get_current_project();

$t_bug_table = db_get_table('mantis_bug_table');
$t_project_user_table = db_get_table('mantis_project_user_list_table');
$t_user_table = db_get_table('mantis_user_table');

$results = array();

switch ($t_action) {

    #Bug suggestions
    case 'bugs':
        $t_sql = "SELECT id,summary
                  FROM " . $t_bug_table . "
                  WHERE ";
        if ($t_project_id != 0)
            $t_sql .="project_id = " . $t_project_id . " AND ";
        $t_sql .= "(
                   id LIKE '" . $t_search . "%'
                   OR summary LIKE '" . $t_search . "%'    
                  )";
        $t_results = db_query($t_sql);

        while ($t_result = db_fetch_array($t_results)) {
            $results[] = array(
                'label' => bug_format_id($t_result['id']).' : '.$t_result['summary'],
                'value' => $t_result['id']
            );
        }

        break;

    #Users suggestions    
    case 'users':
        $t_sql = "SELECT username as field
                  FROM " . $t_user_table ." u
                  INNER JOIN ".$t_project_user_table. " p ON ( p.user_id = u.id AND p.project_id = ".$t_project_id.")
                  WHERE u.username LIKE '" . $t_search . "%'
				  AND u.enabled=1";

        $t_results = db_query($t_sql);

        while ($t_result = db_fetch_array($t_results)) {
            $results[] = array('value' => $t_result['field']);
        }

        break;

    default:
        return json_encode(array());
}

#display results in json format
echo json_encode($results);
?>
