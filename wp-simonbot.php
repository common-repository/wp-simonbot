<?php
/*
Plugin Name: WP-Simonbot
Plugin URI: https://simonbot.com/wordpress
Description: WP-Simonbot allows you to monitor and check your wordpress front-end performance automatically.
Author: Simonbot - <a href='http://twitter.com/simonbot_'>@simonbot_</a> on twitter
Version: 1.0.0
Author URI: https://simonbot.com
License: GPL2	
 */

/**
 * Plugin entrance
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

require_once(dirname(__FILE__) . '/simonbot.php');
if(!function_exists('wp_get_current_user')) {
      include(ABSPATH . "wp-includes/pluggable.php"); 
}

class WPSimonbot {
  var $pluginPath;
  var $pluginUrl;

  public function __construct()
  {
    // Set Plugin Path
    $this->pluginPath = dirname(__FILE__);

    // Set Plugin URL
    $this->pluginUrl = WP_PLUGIN_URL . '/wp-simonbot';
    $plugin_name = plugin_basename(__FILE__); 
    $simonbot_admin_options = new Simonbot();
    $simonbot_admin_options->register_for_actions_and_filters( $plugin_name );
  }
}

$wpSimonbot = new WPSimonbot;
