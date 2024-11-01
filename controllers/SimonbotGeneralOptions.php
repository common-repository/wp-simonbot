<?php
/**
 * General options for wp-simonbot
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

class SimonbotGeneralOptions extends SimonbotControllers
{
  function SimonbotGeneralOptions()
  {
    $this->__construct();
  }

  function simonbot_clear_cache(){
    if(SimonCache::canCache()){
      SimonCache::clearCache();
      $this->newNotice('Cache cleared');
    }
    wp_redirect('?page=wp-simonbot/options');
  }

  function simonbot_options()
  {
    if(count($_POST) > 0){
      update_option('simonbot_cluster_id', $_POST['cluster_id']);
      update_option('simonbot_api_key', $_POST['simonbot_api_key']);
      update_option('simonbot_cache_timeout', $_POST['simonbot_cache_timeout']);
      $this->newNotice('Options successfuly updated');
    }
    $cluster_id = get_option('simonbot_cluster_id');
    if($cluster_id == false){
      $this->newError('There is no cluster connected to this Wordpress installation. Please select one below or create one through the <a href="'.admin_url('admin.php?page=wp-simonbot/setup').'">setup wizard.</a>');
    }
    $this->ui->simonbot_options(
      $this->api->getClusters()
    );
  }

  function simonbot_auth(){
    if(!$this->api->checkApiKey()){
      $this->ui->info_message = "Simonbot is not yet connected with your account on Simonbot.com, <a href=\"".admin_url('admin.php?page=simonbot_auth')."\">Click here to connect it now</a>";
    }
    global $current_user;
    get_currentuserinfo();
    #we create an user account
    if(sizeof($_POST) > 0 && array_key_exists('simonbot_password_check', $_POST)){
      if($_POST['simonbot_password'] == $_POST['simonbot_password_check']){
        $result = $this->api->createUser(array('name' => $_POST['simonbot_name'], 'password' => $_POST['simonbot_password'], 'email' => $_POST['simonbot_email']));
        if($this->isSuccess()){
          $this->newNotice("Account successfully registered");
          $result = $this->api->get_api_key($_POST['simonbot_email'], $_POST['simonbot_password']);
          update_option('simonbot_api_key', $result['authentication_token']);
          if(SimonCache::canCache()){
            SimonCache::clearCache();
          }
          delete_option('simonbot_cluster_id');
          $this->ui->simonbot_success_registration($result);
        }else $this->ui->simonbot_auth($_POST);
      }else {
        $this->newError("The passwords does'nt match");
        $this->ui->simonbot_auth($_POST);
      }
      #existing account
    }elseif(sizeof($_POST) > 0 && !array_key_exists('simonbot_password_check', $_POST)){
      $result = $this->api->get_api_key($_POST['simonbot_login'], $_POST['simonbot_pass']);
      delete_option('simonbot_cluster_id');
      if(is_array($result)){
        $key = $result['authentication_token'];
        update_option('simonbot_api_key', $key);
        if(SimonCache::canCache()){
          SimonCache::clearCache();
        }
        $this->newNotice('Your account has been successfully linked with Wordpress. You just have to select the cluster you want to display in Wordpress.');
        wp_redirect(admin_url('admin.php?page=wp-simonbot/options'));
      }else {
        $this->ui->simonbot_auth($current_user);
      }
    }else{
      $this->ui->simonbot_auth($current_user);
    }
  }
}
?>
