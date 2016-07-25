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
#    2015-2016
#  http://www.h-hennes.fr/blog/

class AutoSuggestPlugin extends MantisPlugin {

    function register() {
        $this->name = 'AutoSuggestPlugin';
        $this->description = 'AutoSuggest plugin';
        $this->version = '0.1.3';
        $this->requires = array(
            'MantisCore' => '1.3.0',
        );
        $this->author = 'Hennes Hervé';
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