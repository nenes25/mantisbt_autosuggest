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

class AutoSuggestPlugin extends MantisPlugin {

    function register() {
        $this->name = plugin_lang_get('title');
        $this->description = plugin_lang_get( 'description');
        $this->version = '0.1.4';
        $this->requires = array(
            'MantisCore' => '2.2',
            'jQueryUI' => '1.12',
        );
        $this->author = 'Hennes Hervé';
		$this->contact = "contact@h-hennes";
        $this->url = 'http://www.h-hennes.fr/blog/';
    }

    function init() {
        plugin_event_hook('EVENT_LAYOUT_RESOURCES', 'resources');
    }

    /**
     * Add autosuggest file in header
     */
    function resources($p_event) {
        return '<script type="text/javascript" src="' . plugin_file('plugin-autosuggest.js') . '"></script>';
    }

}