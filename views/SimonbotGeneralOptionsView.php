<?php
/**
 * Views for General options & auth
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

class SimonbotGeneralOptionsView extends SimonbotViews
{
  function SimonbotGeneralOptionsView()
  {
    $this->__construct();
  }

  function __construct()
  {
  }

  function simonbot_success_registration($result)
  {
    $this->messages();
?>
     <div class="wrap">
<h2><span class="simonbot-auth"></span>Congratulations</h2>
<div class="simonbot">
<div class="hero-unit">
<p>
You've successfully registered your account on Simonbot.
</p>
<p>Please follow instructions in the email we've just sent to you and click on the setup wizard.</p>
<p class="submit">
<a href="<?php echo admin_url('admin.php?page=wp-simonbot/setup') ?>" class="button-primary">Setup wizard</a>
</p>
</div>
</div>
<?php   }

  function simonbot_auth($user)
  {
    $this->messages();
?>
    <div class="wrap">
<h2><span class="simonbot-auth"></span>Simonbot authentication</h2>
<div class="simonbot">
<table class="widefat login">
  <tr>
  <td>
<h3>I already have a Simonbot account</h3>
<p>If you already have a Simonbot account, just fill in your account informations here to configure it automatically</p>
<form name="loginform" id="loginform" action="" method="post">
<p>
<label for="simonbot_login">E-mail<br>
<input type="text" name="simonbot_login" id="simonbot_login" class="input" value="" size="20" tabindex="10"></label>
<p class="description">Your email you're using to authenticate on Simonbot.com. If you don't have an account, use the form on the right.</p>
</p>
<p>
<label for="simonbot_pass">Password<br>
<input type="password" name="simonbot_pass" id="simonbot_pass" class="input" value="" size="20" tabindex="20"></label>
<p class="description">Simonbot password (this is never stored on Wordpress).</p>
</p>
<p class="submit">
<input type="submit" name="wp-submit" id="wp-submit"id class="button-primary" value="Link my account" tabindex="25">
</p>
</form>
</td>
<td>
<h3>Create an account</h3>
<p>If you don't have a Simonbot account, you can create an account really easily by checking the informations in the form below and validate it.</p>
<form name="createaccount" id="createform" action="" method="post">
<p>
<label for="simonbot_name">Name<br>
<input type="text" name="simonbot_name" id="simonbot_name" class="input" value="<?php echo (is_array($user) ? $user['simonbot_name'] : $user->display_name) ?>" size="20" tabindex="30"></label>
</p>
<p>
<label for="simonbot_email">E-mail<br>
<input type="text" name="simonbot_email" id="simonbot_email" class="input" value="<?php echo (is_array($user) ? $user['simonbot_email'] : $user->user_email) ?>" size="20" tabindex="40"></label>
</p>
<p>
<label for="simonbot_password">Password<br>
<input type="password" name="simonbot_password" id="simonbot_password" class="input" value="" size="20" tabindex="50"></label>
</p>
<p>
<label for="simonbot_password_check">Re-enter password<br>
<input type="password" name="simonbot_password_check" id="simonbot_password_check" class="input" value="" size="20" tabindex="60"></label>
</p>
<p class="submit">
<input type="submit" name="wp-submit" id="wp-submit"id class="button-primary" value="Create my account" tabindex="100">
</p>
</form>
</td>
</tr>
</table>
</div>
</div>
<?php
  }

  function simonbot_options($clusters)
  {
?>

    <div class="wrap">
<h2><span class="simonbot-settings"></span>Simonbot options</h2>
<div class="simonbot">
<div class="widefat top_menu">
<ul>
<li>
<?php
  if( SimonCache::canCache() )
    {
?>
<a href="<?php echo admin_url('admin.php?page=wp-simonbot/clear_cache') ?>" class="button-secondary">Clear cache</a>
<?php } ?>
</li>
<li>
<a href="<?php echo admin_url('admin.php?page=wp-simonbot/setup') ?>" class="button-secondary">Setup wizard</a>
</li>
<li>
<a href="<?php echo admin_url('admin.php?page=wp-simonbot/auth') ?>" class="button-secondary">Authentication</a>
</li>
</ul>
    </div>

<?php
    $this->messages();

?>

      <form action="" method="post">

        <table class="form-table">
            <tr valign="top">
              <th scope="row"><label for="simonbot_api_key">Simonbot API key</label></th>
              <td>
<input class="regular-txt" value="<?php echo (get_option('simonbot_api_key') !== false ? get_option('simonbot_api_key') : 'Connect your account!'); ?>" name="simonbot_api_key" id="simonbot_api_key" type="text"/>
<p class="description">This key allows this Wordpress to authenticate automaticaly with Simonbot.</p>
</td>
            </tr>

         <tr valign="top">
            <th scope="row"><label for="cluster_id">Linked cluster</label></th>
            <td>
<?php
    $cluster_id = get_option('simonbot_cluster_id');
    if($cluster_id == false && count($clusters) == 0){
      echo '<p>Create one through the <a href="'.admin_url('admin.php?page=wp-simonbot/setup').'">setup wizard.</a></p>';
    }else{
?>
              <select name="cluster_id" id="cluster_id">

<?php foreach($clusters as $cluster){
  $selected = ($cluster_id == $cluster['_id']) ? "selected" : "";
                echo '<option value="'.$cluster['_id'].'" '.$selected.'>'.$cluster['name'].'</option>';
                  } ?>
              </select>
<?php } ?>
<p class="description">This is the cluster related to this Wordpress installation.</p>
<p class="description">You have to link a cluster of URLs to get informations into Wordpress.</p>
            </td>
          </tr>

<?php
    if( SimonCache::canCache() )
    {
?>
            <tr valign="top">
              <th scope="row"><label for="simonbot_cache_timeout">Cache Timeout (seconds)</label></th>
              <td><input class="regular-txt" value="<?php echo (get_option('simonbot_cache_timeout') !== false ? get_option('simonbot_cache_timeout') : '60'); ?>" name="simonbot_cache_timeout" id="simonbot_cache_timeout" type="text"/>
<p class="description">For better performances, Simonbot for Wordpress is caching some data coming from your Simonbot account.</p> 
<p class="description">Choose here the time in seconds the time to live of this data.</p>
</td>
            </tr>

<?php
    }
    else
    {
?>
            <tr valign="top">
              <th colspan="2"><span style="padding: 10px;" class="error">The configuration of your server will prevent response caching.</span></th>
            </tr>
<?php
    }
?>

        </table>

        <p class="submit">
          <input type="submit" name="SubmitOptions" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </p>

      </form>

    </div>
    </div>

<?php
  }
}
?>
