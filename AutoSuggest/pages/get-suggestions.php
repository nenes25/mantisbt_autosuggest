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
#    2015-2018
#  http://www.h-hennes.fr/blog/

require_once( dirname(__FILE__) . '/../../../core.php' );

$t_action = gpc_get('action');
$t_search = gpc_get('search');
$t_project_id = helper_get_current_project();

$t_bug_table = db_get_table('mantis_bug_table');
$t_user_table = db_get_table('mantis_user_table');

$results = array();

switch ($t_action) {

    #Bug suggestions
    case 'bugs':
        $t_sql = "SELECT id,CONCAT (id ,'-',summary ) as label
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
            $results[] = array('label' => $t_result['label'], 'value' => $t_result['id']);
        }

        break;

    #Users suggestions    
    case 'users':
        $t_sql = "SELECT username as field
                  FROM " . $t_user_table .
                " WHERE username LIKE '" . $t_search . "%'";

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
