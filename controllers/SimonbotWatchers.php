<?php
/**
 * Views for all the watchers: listing and details
 *
 * @package wp-simonbot
 * @author Simonbot Dev Team
 * @copyright Copyright (c) 2013, Chateauclos SARL
 * @licence GPL2
 * @link https://simonbot.com
 * @since Version 1.0
 *
 * Copyright (c) 2013, Chateauclos SARL (email : dev@simonbot.com)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class SimonbotWatchers extends SimonbotControllers
{
  function SimonbotWatchers()
  {
    $this->__construct();
  }

    function simonbot_watcher_details()
  {
    $this->ui->simonbot_watcher_details(
      $this->api->getWatcher($_GET['kpi'], $_GET['id'])
    );
  }

  function simonbot_list_watchers()
  {
    $cluster_id = get_option('simonbot_cluster_id');
    if($cluster_id == false){
      wp_redirect('options-general.php?page=wp-simonbot/options');
    }else{
      $this->ui->watchers = $this->api->getWatchers();
      if(is_array($this->ui->watchers)){
        $this->ui->simonbot_list_watchers(
          $this->api->getWatcher('lt', $cluster_id)
        );
      }else
      {
        wp_redirect('options-general.php?page=wp-simonbot/options');
      }
    }
  }

  function simonbot_setup()
  {
    if(count($_POST) > 0){
      $cluster = array();
      $cluster['urls_attributes'] = array();
      foreach($_POST['urls'] as  $key => $url){
        if(array_key_exists('address', $url)){
          $cluster['urls_attributes']['new_'.$key] = array();
          $cluster['urls_attributes']['new_'.$key]['address'] = $url['address'];
          $cluster['urls_attributes']['new_'.$key]['location_id'] = $url['location'];
        }
      }
      $cluster['name'] = get_bloginfo( 'name');
      $result = $this->api->createCluster($cluster);
      if(is_array($result) && !array_key_exists('status', $result)){
        update_option('simonbot_cluster_id', $result['_id']);
        $this->newNotice("All your URLs are now linked to your Simonbot account and Simon is already checking them");
        $this->ui->simonbot_setup_success($result);
      }else $this->ui->error();
    }else{
      $plan = $this->api->getPlan();
      $locations = $this->api->getLocations();
      $this->ui->simonbot_setup($plan, $locations);
    }
  }
}
?>
