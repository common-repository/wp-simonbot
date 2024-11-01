<?php
/**
 * Top wordpress class for wp-simonbot
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

require_once(dirname(__FILE__) . '/api/SimonbotAPI.php');
require_once(dirname(__FILE__) . '/controllers/SimonbotControllers.php');
require_once(dirname(__FILE__) . '/views/SimonbotViews.php');

class Simonbot
{
  public $api;

  function Simonbot()
  {
    $this->__construct();
  }

  function __construct()
  {
    $this->api = new SimonbotAPI();
  }

  function register_for_actions_and_filters( $plugin_name )
  {
    add_action('admin_menu', array(&$this, 'simonbot_register_menu'));
    add_filter("plugin_action_links_$plugin_name", array(&$this, 'simonbot_plugin_settings_link') );
    add_action('init', array(&$this, 'app_output_buffer'));
  }

  function app_output_buffer() {
    ob_start(); //this is to allow the use of wp_redirect in the admin
    session_start(); //this is to allow the $_SESSION to store error & notices messages
  }

  function simonbot_register_menu()
  {
    wp_enqueue_style('wp-simonbot', plugins_url('wp-simonbot/css/wp-simonbot.css'), array(), '1.0.0', 'all'); 
    if($this->api->checkApiKey()){
      add_submenu_page('tools.php', __('Simonbot'), __('Simonbot'), 'manage_options', 'wp-simonbot/watchers', array(&$this, 'simonbot_list_watchers'));
      add_submenu_page('options-general.php', __('Simonbot settings'), __('Simonbot settings'), 'manage_options', 'wp-simonbot/options', array(&$this, 'simonbot_options'));
      add_submenu_page(NULL, __('Setup wizard'), __('Setup wizard'), 'manage_options', 'wp-simonbot/setup', array(&$this, 'simonbot_setup'));
      add_submenu_page(NULL, __('Watcher details'), __('Watcher details'), 'manage_options', 'wp-simonbot/watcher_details', array(&$this, 'simonbot_watcher_details'));
      add_submenu_page(NULL, __('Clear cache'), __('Clear cache'), 'manage_options', 'wp-simonbot/clear_cache', array(&$this, 'simonbot_clear_cache'));
    }
    add_submenu_page(NULL, __('Authenticate'), __('Authenticate'), 'manage_options', 'wp-simonbot/auth', array(&$this, 'simonbot_auth'));
  }

  function simonbot_plugin_settings_link($links) { 
    return array_merge(
      array(
        'authenticate' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=wp-simonbot/auth">Authenticate</a>',
        'setup' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=wp-simonbot/setup">Wizard</a>'),
      $links
    );
  }

  function simonbot_options()
  {
    self::process('SimonbotGeneralOptions', 'options');
  }

  function simonbot_clear_cache()
  {
    self::process('SimonbotGeneralOptions', 'clear_cache');
  }

  function simonbot_watcher_details()
  {
    self::process('SimonbotWatchers', 'watcher_details');
  }

  function simonbot_create_watchers()
  {
    self::process('SimonbotWatchers', 'create');
  }

  function simonbot_list_watchers()
  {
    self::process('SimonbotWatchers', 'list_watchers');
  }

  private function process($classname = 'SimonbotGeneralOptions', $action = 'auth'){
    self::createMessagesContainer();
    require_once(dirname(__FILE__) . '/controllers/'.$classname.'.php');
    $this->controller = new $classname;
    $action = 'simonbot_'.$action;
    $this->controller->$action();
  }

  private function createMessagesContainer(){
    if(!array_key_exists('simonbot_errors', $_SESSION))
      $_SESSION['simonbot_errors'] = array();
    if(!array_key_exists('simonbot_notices', $_SESSION))
      $_SESSION['simonbot_notices'] = array();
  }

  function simonbot_auth()
  {
    self::process('SimonbotGeneralOptions', 'auth');
  }

  function simonbot_setup()
  {
    self::process('SimonbotWatchers', 'setup');
  }
  }
?>
