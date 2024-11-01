<?php
/**
 * Views for watchers
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

class SimonbotWatchersView extends SimonbotViews
{
  var $watchers;

  function SimonbotWatchersView()
  {
    $this->__construct();
  }

  function __construct()
  {
  }

  function display_admin_dashboard_widget( $watcher_details)
  {
?>
<iframe src=<?php echo $watcher_details['iframe']['url'] ?> width="100%" height="<?php echo $watcher_details['iframe']['height'] ?>"></iframe>

<?php
  }

  function simonbot_watcher_details($watcher_details)
  {
?>
    <div class="wrap">
<?php $this->messages(); ?>
    <h2><span class="simonbot-<?php echo $_GET['kpi'] ?>"></span><?php echo $this->get_watcher_name($watcher_details['watcher']) ?></h2>
<div class="simonbot">
<div class="widefat top_menu">
<a href="<?php echo admin_url('admin.php?page=wp-simonbot/watchers') ?>" class="add-new-h2">Back to my watchers</a>
<ul>
<li>
<?php echo $this->details_link($watcher_details, 'dashboard', 'Dashboard') ?>
</li>
<li>
<?php echo $this->details_link($watcher_details, 'lt', 'Load time') ?>
</li>
<li>
<?php echo $this->details_link($watcher_details, 'w', 'Weight') ?>
</li>
<li>
<?php echo $this->details_link($watcher_details, 'r', 'Requests') ?>
</li>
<li>
<?php echo $this->details_link($watcher_details, 'yslow', 'YSlow') ?>
</li>
<li>
<?php echo $this->details_link($watcher_details, 'nfe', 'Not found elements') ?>
</li>
</ul>
    </div>
<iframe src=<?php echo $watcher_details['iframe']['url'] ?> width="100%" height="<?php echo $watcher_details['iframe']['height'] ?>"></iframe>
    </div>
    </div>

<?php
  }
    function simonbot_list_watchers($cluster)
  {
?>

    <div class="wrap">
<h2><span class="simonbot-watchers"></span>My watchers</h2>
<?php
    $this->messages();
?>
<div class="simonbot">
<iframe src=<?php echo $cluster['iframe']['url'] ?> width="100%" height="<?php echo $cluster['iframe']['height'] ?>"></iframe>
        <table class="wp-list-table widefat fixed watchers">
<thead>
<tr valign="top">
            <th class="watcher">Watcher</th>
            <th class="last_check">Last check</th>
            <th class="actions">Actions</th>
          </tr>
</thead>
<?php $date_format = get_option('date_format');
  if(!strstr($date_format, 'i')) $date_format .= ' G:i';
?>
<?php foreach ($this->watchers as $watcher) {
?>
    <tr>
      <td><a href="<?php echo $this->details_url($watcher, 'dashboard')  ?>"><?php echo $this->get_watcher_name($watcher) ?></a></td>
      <td><?php echo date($date_format, strtotime($watcher['last_check_date'])) ?></td>
      <td class="actions">
<ul>
<li>
<a href="<?php echo $this->details_url($watcher, 'dashboard') ?>" class="button-secondary">Dashboard</a>
</li>
<li>
<a href="<?php echo $this->details_url($watcher, 'lt')  ?>" class="button-secondary">Load time</a>
</li>
<li>
<a href="<?php echo $this->details_url($watcher, 'w')  ?>" class="button-secondary">Weight</a>
</li>
<li>
<a href="<?php echo $this->details_url($watcher, 'r')  ?>" class="button-secondary">Requests</a>
</li>
<li>
<a href="<?php echo $this->details_url($watcher, 'yslow')  ?>" class="button-secondary">YSlow</a>
</li>
<li>
<a href="<?php echo $this->details_url($watcher, 'nfe')  ?>" class="button-secondary">Not found elements</a>
</li>
</ul>
</td>
   </tr>
<?php } ?>
</thead>
        </table>
    </div>
    </div>

<?php
  }

  function simonbot_setup_success($cluster){
  $this->messages();
?>
    <div class="wrap">
<h2><span class="simonbot-settings"></span>Success!</h2>
<div class="simonbot">
<div class="hero-unit">
<?php
  echo '<p>A cluster has been added to your Simonbot account. The cluster is named : "'.$cluster['name'].'" and you can visualize it either <a href="https://simonbot.com/monitors/clusters/'.$cluster['_id'].'/dashboard">on your Simonbot account</a> or in Simonbot for Wordpress.</p>';
?>
</div>
</div>
</div>
<?php
  }

  function simonbot_setup($plan, $locations)
  {
  $this->messages();
  $url = site_url();
    $recent_posts= wp_get_recent_posts( );
?>
     <div class="wrap">
<h2><span class="simonbot-settings"></span>Setup wizard</h2>
<div class="simonbot">
<div class="hero-unit">
<p>
Choose here what URL you want Simonbot to monitor on this website.
</p>
<p>You're on the <?php echo $plan['name'] ?> plan, so you can add up to <?php echo $plan['urls'] ?> urls. If you want to add more urls, you <a href="https://simonbot.com/user/plan/choose">can upgrade your account</a></p>
</div>
<input type="hidden" id="max_urls" value="<?php echo $plan['urls'] ?>"/>
      <form action="" method="post">
<fieldset id="watcher_urls">
<legend>Select Urls below</legend>
        <table class="wp-list-table widefat fixed watchers_setup">
<thead>
<tr valign="top">
            <th class="checkbox"></th>
            <th class="title">Title</th>
            <th class="url">URL</th>
            <th class="location">Check location</th>
          </tr>
</thead>
<tr>
    <td><input type="checkbox" name="urls[home][address]" value="<?php echo $url ?>" checked="checked" id="home_url"/></td>
    <td><label for="home_url">Home url</label></td><td><?php echo $url ?></td>
<?php 
  echo '<td><select name="urls[home][location]">';
  foreach($locations as $location){
    echo '<option value="'.$location["_id"].'">'.$location["country"].' ('.$location["city"].')</option>';
  }
  echo '</select></td></tr>';
  foreach( $recent_posts as $recent ){
    echo '<td><input type="checkbox" name="urls['.$recent["ID"].'][address]" value="'.get_permalink($recent["ID"]).'" id="post_'.$recent["ID"].'"/></td>';
    echo '<td><label for="post_'.$recent["ID"].'">' . $recent["post_title"].'</label><td>'.get_permalink($recent["ID"]).')</td>';
    echo '<td><select name="urls['.$recent["ID"].'][location]">';
    foreach($locations as $location){
      echo '<option value="'.$location["_id"].'">'.$location["country"].' ('.$location["city"].')</option>';
    }
    echo '</select></td></tr>';
  }
wp_enqueue_script('the-script-handle', plugins_url('wp-simonbot/js/limit_checkboxes.js'), array('jquery'), '1.0', true);
?>
</table>
</fieldset>
<?php $disabled = SimonbotControllers::isSuccess(); ?>
        <p class="submit">
<input type="submit" class="button-primary" value="Save" <?php disabled($disabled, false, true) ?>/>
</p>
</div>
<?php  
  }

  private function get_watcher_name($watcher){
      return $watcher['_type'] == 'Url' ? $watcher['address'] : $watcher['name'];
  }

  private function details_url($watcher, $kpi){
    return admin_url('admin.php?page=wp-simonbot/watcher_details&kpi='.$kpi.'&id='.$watcher['_id']); 
  }

  private function details_link($watcher_details, $action, $title){
    $active = '';
    if($action == $_GET['kpi']) $active = ' active';
    return '<a href="'.$this->details_url($watcher_details['watcher'], $action).'" class="button-secondary'.$active.'">'.$title.'</a>';
  }
}

?>
