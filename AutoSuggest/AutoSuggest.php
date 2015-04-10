<?php

/*
  Plugin AutoSuggest pour Mantis BugTracker :
 */

class AutoSuggestPlugin extends MantisPlugin {

    function register() {
        $this->name = 'AutoSuggestPlugin';
        $this->description = 'AutoSuggest plugin';
        $this->version = '0.1.0';
        $this->requires = array(
            'MantisCore' => '1.2.0',
        );
        $this->author = 'Hennes Hervé';
        $this->url = 'http://www.h-hennes.fr';
    }

    function init() {
        plugin_event_hook('EVENT_LAYOUT_RESOURCES', 'resources');
    }

    function resources($p_event) {
        #@ToDo : Inclure proprement les dépendances, mais plugin mantis jquery ui plus à jour        
        return '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                <script src="//code.jquery.com/jquery-1.10.2.js"></script>
                <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type="text/javascript" src="' . plugin_file('plugin-autosuggest.js') . '"></script>';
    }

}

?>