<?php
/*
 * @version $Id: HEADER 2011-03-12 18:01:26 tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of Canvas, plugin of GLPI.

 Canvas is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Canvas is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Canvas; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 // ----------------------------------------------------------------------
 // Original Author of file: Damien Touraine
 // Purpose of file: plugin Canvas v0.1 - GLPI 0.84
 // ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginCanvasCanvas {

   static function onload() {
      return 'canvas();';
   }

   static function show($itemtype, $params=array()) {
      global $DB;

      $p['root_id'] = 0;
      $p['number_level'] = 5;

      foreach ($params as $key => $val) {
         $p[$key] = $val;
      }

      $root = new $itemtype();
      if (!$root->getFromDB($p['root_id'])) {
         $root->getEmpty();
      }

      $table = $root->getTable();
      $foreignKeyField = $root->getForeignKeyField();

      if (FieldExists($table, $foreignKeyField)) {

         $WHERE = array();
         $FIELDS = array("`id`", "`$foreignKeyField`", "`name`");

         $root_id = $p['root_id'];
         $WHERE[] = "`id` IN ('".implode("', '", getSonsOf($table, $root_id))."')";

         if (isset($root->fields['entities_id'])) {
            $FIELDS[] = "`entities_id`";
         } else {
            $FIELDS[] = "'0' as entities_id";
         }

         if (isset($root->fields['level'])) {
            $WHERE[] = "`level` <= '".($root->fields['level'] + $p['number_level'])."'";
         }

         $query = "SELECT ".implode(', ', $FIELDS)."
                   FROM `".$root->getTable()."`
                   WHERE ".implode(' AND ', $WHERE)."
                   ORDER BY id";

         $items = array();
         if ($root_id == 0) {
            $items[0] = array('canvas_id' => 0,
                              'name' => 'root',
                              'entities_id' => 0,
                              'ancestors_id' => 0);
         }

         foreach ($DB->request($query) as $item_entry) {
            $canvas_id = count($items);
            $items[$item_entry['id']] = array('canvas_id' => $canvas_id,
                                              'name' => $item_entry['name'],
                                              'entities_id' => $item_entry['entities_id'],
                                              'ancestors_id' => $item_entry[$foreignKeyField]);
            $canvas_id++;
         }

         $ancestors = getAncestorsOf($table, $root_id);
         $query = "SELECT `id`, `name`
                   WHERE `id` IN ('".implode("', '", $ancestors)."')";

         $ancestors = array();
         foreach ($DB->request($query) as $item_entry) {
            if ($item_entry['id'] != $root->getID())
               $ancestors[$item_entry['id']]  =$item_entry['name'];
         }
         if (($root->getID() != 0) && (!isset($ancestors[0]))) {
            $ancestors[0] = 'root';
         }

         self::drawCanvas($root, $items, $ancestors, $p);

      }
   }

   private static function drawCanvas($root, $items, $ancestors, $params) {
      global $LANG;

      $nodes = array();
      $edges = array();
      foreach ($items as $id => $node) {
         $nodes[] = "{id: 'Id" . $node['canvas_id'] . "', name:'" . $node['name'] . "', group:" .
                    $node['entities_id'] . ", items_id:$id,nodes_id:" . $node['ancestors_id'] . "}";
         if (($node['ancestors_id'] != $id) && (isset($items[$node['ancestors_id']]))) {
            $edges[] = "{id1:'Id" . $items[$node['ancestors_id']]['canvas_id'] .
                       "',  id2:'Id" . $node['canvas_id']."', value:1, root_id:" .
                       $node['ancestors_id'] . "}";
         }
      }

      if (count($nodes) > 0) {

         echo "
    <!--[if IE]><script type='text/javascript' src='../lib/canvas/excanvas.js'></script><![endif]-->
    <script type='text/javascript' src='../lib/canvas/canvasXpress.min.js'></script>";

         $canvas_options = array('graphType' => 'Network',
                                 'colorNodeBy' => 'group',
                                 'indicatorCenter' => 'rainbow',
                                 'layoutTime' => 30,
                                 'maxIterations' => 80,
                                 'gradient' => true,
                                 'backgroundGradient2Color' => 'rgb(112,179,222)',
                                 'backgroundGradient1Color' => 'rgb(226,236,248)',
                                 'nodeFontColor' => 'rgb(29,34,43)',
                                 'showAnimation' => false);

         echo "<script>
      var canvas = function () {
         new CanvasXpress('canvas', {";
         echo "nodes: [\n\t".implode(",\n\t", $nodes)."],";
         echo "edges: [\n\t".implode(",\n\t", $edges)."]";
         echo "}, {";

         $options = array();
         foreach ($canvas_options as $key => $value) {
            if (is_bool($value)) {
               $options[] = "$key: ".($value ? 'true' : 'false');
            } elseif (is_numeric($value)) {
               $options[] = "$key: $value";
            } else {
               $options[] = "$key: '$value'";
            }
         }
         echo implode(",\n", $options);
         $link = $root->getFormURL();
         $link .= (strpos($link,'?') ? '&amp;':'?').'id=';
         echo "}, {
            click: function(obj) {
               if (obj.nodes) {
                  var items_id=obj.nodes[0].items_id;
                  window.location.href='".$link."'+items_id;
               } else {
                  var root_id=obj.edges[0].root_id;
                  window.location.href='".$_SERVER['PHP_SELF']."?root_id='+root_id;
               }
            }
       });
      }
    </script>";

         echo "<table><tr><td>";
         echo "<canvas id='canvas' width='700' height='500'></canvas>";
         echo "</td><td>";
         if (count($ancestors) > 0) {
            echo $LANG['plugin_canvas']['ancestors']."&nbsp;:<br>\n";
            foreach ($ancestors as $id => $name) {
               echo "<li><a href='".$_SERVER['PHP_SELF']."?root_id=$id'>$name</a></li>";
            }
         }
         echo "</td></tr></table>";

      }

   }
}

?>
