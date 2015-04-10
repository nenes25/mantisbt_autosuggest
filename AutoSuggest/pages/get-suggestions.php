<?php

/**
 * AutoSuggest Ajax Page
 *
 * @author Europe-internet <dev@europe-internet.net>
 * @version 0.1 | $Revision$
 * Last-Modified : $Date$
 * Id : $Id$
 */
require_once( dirname(__FILE__) . '/../../../core.php' );

$t_action = gpc_get('action');
$t_search = gpc_get('search');
$t_project_id = helper_get_current_project();

$t_bug_table = db_get_table('mantis_bug_table');
$t_user_table = db_get_table('mantis_user_table');

$results = array();


# Les requêtes sont génériques car le filtrage est fait via le plugin jquery autoSuggest
switch ($t_action) {

    case 'bugs':
        $t_sql = "SELECT id,CONCAT (id ,'-',summary ) as label
                  FROM " . $t_bug_table . "
                  WHERE project_id = " . $t_project_id." AND (
                   id LIKE '".$t_search."%'
                   OR summary LIKE '".$t_search."%'    
                  )";        
         $t_results = db_query($t_sql);

        #Parcours des résultats
        while ($t_result = db_fetch_array($t_results)) {
            $results[] = array('label' => $t_result['label'],'value' => $t_result['id']);
        }
        
        break;

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
