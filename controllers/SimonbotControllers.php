<?php
/**
 * Master class for all controllers. Instanciating the connected view and handling messages for the view
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

class SimonbotControllers
{
  public $api;
  public $ui;

  function __construct()
  {
    $this->api = new SimonbotAPI();
    require_once(dirname(__FILE__) . '/../views/'.get_class($this).'View.php');
    $view_classname = get_class($this).'View';
    $this->ui = new $view_classname;
  }

  function isSuccess(){
    if(count($_SESSION['simonbot_errors']) > 0 ) 
      return false;
    return true;
  }

  function newError($message){
    array_push($_SESSION['simonbot_errors'], $message);
  }

  function newNotice($message){
    array_push($_SESSION['simonbot_notices'], $message);
  }
 }
?>
