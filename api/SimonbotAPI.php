<?php

/**
 * Abstraction class to interact with Simonbot API
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

require_once(dirname(__FILE__) . '/../lib/libcurlemu-1.0.4/libcurlemu.inc.php');
require_once(dirname(__FILE__) . '/../lib/Pest/PestJSON.php');
require_once(dirname(__FILE__) . '/../lib/SimonCache.php');

class SimonbotAPI {
  // url to simonbot api
  //var $apiUrl = 'https://simonbot.com/api/v1';
  var $apiUrl = 'http://localhost:5000/api/v1';
  // simonbot apikey
  var $api_key;
  // cache timeout
  var $cache_timeout = 60;

  /**
   * constructor
   * @access public
   * @param none
   * @return none
   */

  public function __construct()
  {
    $this->api_key = get_option('simonbot_api_key');
  }

  /**
   * get all monitors from the linked account
   * @access public
   * @param none
   * @return array
   */

  public function getWatchers()
  {
    $cluster_id = get_option('simonbot_cluster_id');
    $url = '/watchers/clusters/'.$cluster_id .'?raw=true';
    return $this->getRemoteObject($url);
  }

  //get all clusters from the linked account
  public function getClusters()
  {
    return $this->getRemoteObject('/watchers/clusters');
  }

  //get the monitor iframe url for one action and one monitor id
  public function getWatcher($action, $id, $raw = false, $width = null, $height = null)
  {
    $url = '/watchers/'.$id . '?waction=' . $action;
    if($raw == true){
      $url .= '&raw='.$raw.'&width='.$width.'&height='.$height;
    }
    return $this->getRemoteObject($url);
  }

  public function getPlan(){
    return $this->getRemoteObject('/plan');
  }

  public function getLocations(){
    return $this->getRemoteObject('/locations');
  }

  public function get_api_key($login, $password){
    try{
      $pest = new PestJSON($this->apiUrl . '/user');
      $pest->setupAuth($login, $password);
      return $pest->get('');
    }
    catch (Pest_Exception $e)
    {
      $this->handleErrorMessage($pest);
    }
  }

  public function createUser($user_array){
    try{
      $pest = new PestJSON($this->apiUrl);
      return $pest->post('/user', $user_array);
    }
    catch (Pest_Exception $e)
    {
      $this->handleErrorMessage($pest);
    }
  }

  public function createCluster($cluster){
    try{
      $pest = new PestJSON($this->apiUrl);
      return $pest->post('/watchers/clusters', $cluster, array('X-API-KEY: '.$this->api_key));
    }
    catch (Pest_Exception $e)
    {
      $this->handleErrorMessage($pest);
    }
  }

  public function checkApiKey()
  {
    if(isset($this->api_key) && $this->api_key != '')
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  private function getRemoteObject($url){
    if(!SimonCache::isExpired($url, $this->cache_timeout))
    {
      return SimonCache::cacheGet($url);
    }
    else
    {
      try {
        $pest = new PestJSON($this->apiUrl);
        $pest->curl_opts[CURLOPT_HTTPHEADER] = array('X-API-KEY: '.$this->api_key);
        $return_values =  $pest->get($url);
        SimonCache::cachePut($url, $return_values, $this->cache_timeout);
        return $return_values;
      }
      catch (Pest_Exception $e)
      {
        $this->handleErrorMessage($pest);
      }
    }
  }

  private function handleErrorMessage($pest){
    $result  = $pest->processBody($pest->last_response['body']);
    if(!is_null($result)){
      if(array_key_exists('error', $result))SimonbotControllers::newError($result['error']);
      if(array_key_exists('errors', $result)){
        if(is_array($result['errors'])){
          foreach($result['errors'] as $item => $error){
            if(is_array($error)) SimonbotControllers::newError($item.' : '. $error[0]);
            else SimonbotControllers::newError($error);
          }
        }else{
          SimonbotControllers::newError($result['errors']);
        }
      }
    }
  }
}
