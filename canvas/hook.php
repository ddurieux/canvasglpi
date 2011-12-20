<?php
/*
 * @version $Id: HEDER 2011-03-12 18:01:26 tsmr $
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

function plugin_canvas_install() {
   if (!Session::haveRight('internet', 'r')) return false;
   global $DB;

   /*
   include_once(GLPI_ROOT ."/plugins/canvas/inc/network.class.php");

   $network = new IPNetwork();
   $tableFieldList = $DB->list_fields($network->getTable());
   $fieldsToAdd = array();
   foreach (PluginCanvasNetwork::getOwnFields() as $name => $attributs) {
      if (!isset($tableDieldList[$name])) {
         $fieldsToAdd[] = "ADD `$name` ".$attributs["type"]." DEFAULT '".$attributs["default"]."'";
      }
   }
   if (count($fieldsToAdd) > 0) {
      $query = "ALTER TABLE `".$network->getTable()."` ".implode(', ',$fieldsToAdd);
      $DB->query($query);
   }
   */

   return true;
}

function plugin_canvas_uninstall() {
   if (!Session::haveRight('internet', 'r')) return false;
   global $DB;

   /*
   include_once(GLPI_ROOT ."/plugins/canvas/inc/network.class.php");

   $network = new IPNetwork();
   $tableFieldList = $DB->list_fields($network->getTable());
   $fieldsToRemove = array();
   foreach (PluginCanvasNetwork::getOwnFields() as $name => $attributs) {
      if (isset($tableFieldList[$name])) {
         $fieldsToRemove[] = "DROP `$name`";
      }
   }
   if (count($fieldsToRemove) > 0) {
      $query = "ALTER TABLE `".$network->getTable()."` ".implode(', ',$fieldsToRemove);
      $DB->query($query);
   }
   */

   return true;
}

// Define Dropdown tables to be manage in GLPI :
function plugin_canvas_getDropdown() {
   global $LANG;
   return array();
}

function plugin_canvas_get_headings($item, $withtemplate) {
   global $LANG;
   /*
   switch ($item->getType()) {
   case 'NetworkName':
      return array(1 => $LANG['plugin_canvas']['tab'][$item->getType()]);
      break;
   case 'IPNetwork':
      if (!$item->isNewID($item->getID()))
         return array(1 => $LANG['plugin_canvas']['tab'][$item->getType()]);
      break;
   }
   */
   return false;
}

function plugin_canvas_headings_actions($item) {
   /*
   switch ($item->getType()) {
   case 'NetworkName':
      return array(1 => "plugin_canvas_networkAddress_tab");
      break;
   case 'IPNetwork':
      if (!$item->isNewID($item->getID()))
         return array(1 => "plugin_canvas_territory_tab");
      break;
   }
   */
   return false;
}

function plugin_canvas_networkAddress_tab($networkAddress, $withtemplate=0) {
   //PluginCanvasNetwork::showNetworkNameTab($networkAddress, $withtemplate);
}

function plugin_canvas_territory_tab($territory, $withtemplate=0) {
   //PluginCanvasNetwork::showForm($territory, $withtemplate);
}

?>