<?php

function sensorica_settings_page()
{
  // Check admin permission
  if (!current_user_can('manage_options')) {
    return;
  }

  if (isset($_POST['sensorica_envato_key'])) {
    // Save settings
    if (!isset($_POST['sensorica_dont_use_openaiproxy'])) {
      $_POST['sensorica_dont_use_openaiproxy'] = 0;
    }

    $sensorica_theme = sanitize_text_field($_POST['sensorica_theme']);
    update_option('sensorica_theme', $sensorica_theme);
    $sensorica_dont_use_openaiproxy = sanitize_text_field($_POST['sensorica_dont_use_openaiproxy']);
    update_option('sensorica_dont_use_openaiproxy', $sensorica_dont_use_openaiproxy);

    if (!isset($_POST['sensorica_openaiproxy'])) {
      $_POST['sensorica_openaiproxy'] = 'https://telegram.onout.org/';
    }

    $sensorica_back = sanitize_text_field($_POST['sensorica_openaiproxy']);
    
    update_option('sensorica_envato_key', sanitize_text_field($_POST['sensorica_envato_key']));
    update_option('OPENAI_API_KEY', sanitize_text_field($_POST['OPENAI_API_KEY']));
    // Check the envato license and save domain to whitelist

    $envato_key = sanitize_text_field($_POST['sensorica_envato_key']);

    //check key match regex pattern of envato keys 
    if (false) {
      //temproray disable this check
      //if (!preg_match('/^([a-zA-Z0-9]{8})-([a-zA-Z0-9]{4})-([a-zA-Z0-9]{4})-([a-zA-Z0-9]{4})-([a-zA-Z0-9]{12})$/', $envato_key)) {
      ?>
      <div id="message" class="notice is-dismissible">
        <p>
          <span style="color: red;">
            <?php esc_html_e('Invalid Envato Key', 'sensorica'); ?>
          </span>
        </p>
      </div>
      <?php

    } else {

      if (substr($sensorica_back, -1) !== '/') {
        $sensorica_back .= '/';
      }

      $url = $sensorica_back . "envatocheckandsave";

      $post_data = [
        'registeredurl' => base64_encode(home_url() . "/?rest_route=/sensorica/v1/shortcode/{id}"),
        'key' => $envato_key,
        'rsa_private_key' => 'not used in this version'
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
      $con = curl_exec($ch);
      curl_close($ch);

      ?>
      <div id="message" class="notice is-dismissible">
        <p>
          <?php
          $con_json = json_decode($con);
          if (isset($con_json->id)) {
            update_option('sensorica_openaiproxy', sanitize_text_field($_POST['sensorica_openaiproxy']));
            update_option('sensorica_client_id', esc_attr($con_json->id));
            
            echo '<span style="color: green;">';
            esc_html_e('Settings saved.', 'sensorica');
            echo esc_html($con_json->message);
            echo '</span>';

          } else {
            echo '<span style="color: red;">';
            esc_html_e('Invalid Envato Key or Proxy Url', 'sensorica');
            echo "<br>";
            echo esc_html($con);
            echo '</span>';
          }
          ?>
        </p>
      </div>
      <?php
    }
  }
  ?>

  <div class="wrap">
    <div class="">
      <h2>
        <?php esc_html_e(get_admin_page_title(), 'sensorica'); ?>
      </h2>
      <h2 class="nav-tab-wrapper sensorica-nav-tabs wp-clearfix">
        <a href="#sensorica-tab-1" class="nav-tab nav-tab-active">
          <?php esc_html_e('Main Setting', 'sensorica'); ?>
        </a>
        <a href="#sensorica-tab-2" class="nav-tab">
          <?php esc_html_e('Advanced Setting', 'sensorica'); ?>
        </a>
      </h2>

      <div class="sensorica-panel-tab panel-tab-active" id="sensorica-tab-1">
        <div class="sensorica-shortcode-panel-row">
          <form action="#" method="post" class="wp-sensorica-widget-form">
            <input type="hidden" name="sensorica_save_setting" value="yes" />
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row">
                    <label>
                      <?php esc_html_e('Your license key', 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <input type="text" name="sensorica_envato_key" required id="sensorica_envato_key"
                      value="<?php echo esc_attr(get_option('sensorica_envato_key', '...')); ?>" />
                    <p class="description">
                      <?php esc_html_e('Put your envato license key here.', 'sensorica'); ?>
                    </p>
                  </td>
                </tr>
                <?php
                // Dark or light theme selector
                $sensorica_theme = get_option('sensorica_theme', 'light');
                ?>
                <tr>
                  <th scope="row">
                    <label>
                      <?php esc_html_e('Theme', 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <select name="sensorica_theme" id="sensorica_theme">
                      <option value="sensorica_light_theme" <?php selected($sensorica_theme, 'sensorica_light_theme'); ?>>
                        <?php esc_html_e('Light', 'sensorica'); ?>
                      </option>
                      <option value="sensorica_dark_theme" <?php selected($sensorica_theme, 'sensorica_dark_theme'); ?>>
                        <?php esc_html_e('Dark', 'sensorica'); ?>
                      </option>
                    </select>
                    <p class="description">
                      <?php esc_html_e('Select theme.', 'sensorica'); ?>
                    </p>
                  </td>
                </tr>
                <tr style='display:none'>
                  <th scope="row">
                    <label>
                      <?php esc_html_e('Default OpenAI API key', 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <input type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY"
                      value="<?php echo esc_attr(get_option('OPENAI_API_KEY')); ?>" />
                    <p class="description">
                      <?php esc_html_e('Put your OpenAI API key here.', 'sensorica'); ?>
                    </p>
                  </td>
                </tr>
                <tr>
                  <th scope="row"></th>
                  <td>
                    <input type="submit" name="mcwallet-add-token" class="button button-primary mcwallet-add-token"
                      value="<?php esc_attr_e('Save', 'sensorica'); ?>" />
                    <span class="spinner"></span>
                  </td>
                </tr>
              </tbody>

            </table>
            
            
            <?php sensorica_show_footer(); ?>
           
          
        </div>
      </div>

      <div class="sensorica-panel-tab" id="sensorica-tab-2">
        <div class="sensorica-shortcode-panel-row">
          
            <input type="hidden" name="sensorica_save_setting" value="yes" />
            <table class="form-table">
              <tbody>
                <tr>
                  <?php //dont use openai proxy ?>
                  <th scope="row">
                    <label>
                      <?php esc_html_e("Avoid Using NodeJS Backend", 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <input type="checkbox" name="sensorica_dont_use_openaiproxy" id="sensorica_dont_use_openaiproxy"
                      value="1" <?php if (get_option('sensorica_dont_use_openaiproxy', '0') == 1) echo "checked"; ?> /> Prefer not to use Sensorica's shared backend (This is generally not advised).
                    <p class="description">
                      <?php esc_html_e("Heads Up! Keep in mind that certain services and functionalities, like streaming, need the OpenAI proxy because they rely on NodeJS which not supported on your hosting.", 'sensorica'); ?>
                      <br>
                      <?php esc_html_e("This particular API endpoint doesn't retain any prompts, messages, or OpenAI keys. You have the option to configure your own following the provided instructions (you will need a VPS) or use a shared one by default (Free for our customers).", 'sensorica'); ?>
                      
                    </p>
                  </td>
                </tr>
                <?php
                if (!empty($sensorica_client_id)) {
                  ?>
                  <tr>
                    <th scope="row">
                      <label>
                        <?php esc_html_e('Your client id', 'sensorica'); ?>
                      </label>
                    </th>
                    <td>
                      <input type="text" disabled name="sensorica_client_id" id="sensorica_client_id"
                        value="<?php echo esc_attr($sensorica_client_id); ?>" />
                      <p class="description">
                        <?php esc_html_e('Your client id.', 'sensorica'); ?>
                      </p>
                    </td>
                  </tr>

                  <?php
                }
                ?>
                <tr>
                  <th scope="row"></th>
                  <td>
                    <input type="submit" name="mcwallet-add-token" class="button button-primary mcwallet-add-token"
                      value="<?php esc_attr_e('Save', 'sensorica'); ?>" />
                    <span class="spinner"></span>
                  </td>
                </tr>
              </tbody>
            </table>
            
            
            <?php sensorica_show_footer(); ?>
           
          </form>
        </div>
      </div>
    </div>
  </div>
 

  <?php
 
  // Import svg static/icons/icons.svg
  echo file_get_contents(sensorica_PATH . 'static/icons/icons.svg');
  
}

?>