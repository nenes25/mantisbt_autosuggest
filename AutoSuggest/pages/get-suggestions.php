<?php

/*
  Plugin AutoSuggest pour Mantis BugTracker :
 */
require_once( dirname(__FILE__) . '/../../../core.php' );

$t_action = gpc_get('action');
$t_search = gpc_get('search');
$t_project_id = helper_get_current_project();

$t_bug_table = db_get_table('mantis_bug_table');
$t_user_table = db_get_table('mantis_user_table');

$results = array();

switch ($t_action) {

    #Suggestion des bugs
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

        #Parcours des résultats
        while ($t_result = db_fetch_array($t_results)) {
            $results[] = array('label' => $t_result['label'], 'value' => $t_result['id']);
        }

        break;

    #Suggestion des utilisateurs    
    case 'users':
        $t_sql = "SELECT username as field
                  FROM " . $t_user_table .
                " WHERE username LIKE '" . $t_search . "%'";

        $t_results = db_query($t_sql);

        #Parcours des résultats
        while ($t_result = db_fetch_array($t_results)) {
            $results[] = array('value' => $t_result['field']);
        }

        break;

    default:
        return json_encode(array());
}

#renvoi des résultats au format json
echo json_encode($results);
?>
