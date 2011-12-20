<?php
/*
 * @version $Id: HEADER 2011-03-12 18:01:26 tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of IPHelper, plugin of GLPI.

 IPHelper is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 IPHelper is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with IPHelper; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 // ----------------------------------------------------------------------
 // Original Author of file: Damien Touraine
 // Purpose of file: plugin IPHelper v0.1 - GLPI 0.84
 // ----------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_canvas() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   $PLUGIN_HOOKS['menu_entry']['canvas'] = 'front/essai.php';
}

// Get the name and the version of the plugin - Needed
function plugin_version_canvas() {
   global $LANG;

   return array (
                 'name' => $LANG['plugin_canvas']['main'],
                 'version' => '0.1',
                 'author'=>'Damien Touraine',
                 'license' => 'GPL v3',
                 'homepage'=>'https://forge.indepnet.net/projects/show/canvas'
                 );

}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_canvas_check_prerequisites() {
   if (GLPI_VERSION>=0.84) {
      return true;
   } else {
      echo "GLPI version not compatible need 0.84";
   }
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add
// to message after redirect
function plugin_canvas_check_config() {
   return true;
}

?>