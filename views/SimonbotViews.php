<?php
/**
 * Top class views
 *
 * @package simonbot-performance-monitor
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

class SimonbotViews
{
  function error(){
    $this->messages();
    ?>
    <div class="wrap">
      <h2>Error...</h2>
<div class="simonbot">
<?php
   echo '<p>Sadly, an error occured...</p>';
?>
</div>
</div>
<?php
  }

  function messages()
  {
    if(count($_SESSION['simonbot_notices']) > 0){
      $this->display_messages($_SESSION['simonbot_notices'], 'updated');
    }
    if(count($_SESSION['simonbot_errors']) > 0){
      $this->display_messages($_SESSION['simonbot_errors'], 'error');
    }
  }

  private function display_messages(&$to_display, $type = 'error'){
    if(is_array($to_display)) {
      $this->process_messages($to_display, $type);
    }
  }

  private function process_messages(&$messages, $type = 'error'){
    echo '<div id="message" class="'.$type.' fade">';
    foreach($messages as $message){
      echo '<p><strong>' . array_pop($messages) . '</strong></p>';
    }
    echo '</div>';
  }
}
?>
