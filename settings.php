<?php
use phpseclib\Crypt\RSA;

function sensorica_settings_page()
{
  //check admin permission
  if (!current_user_can('manage_options')) {
    return;
  }
  if (isset($_POST['sensorica_envato_key'])) {

    //save settings

    $sensorica_theme = sanitize_text_field($_POST['sensorica_theme']);
    update_option('sensorica_theme', $sensorica_theme);
  

    $sensorica_back = sanitize_text_field($_POST['sensorica_openaiproxy']);
    update_option('sensorica_envato_key', sanitize_text_field($_POST['sensorica_envato_key']));
    update_option('OPENAI_API_KEY', sanitize_text_field($_POST['OPENAI_API_KEY']));
    //check the envato license and save domain to whitelist
    $envato_key = sanitize_text_field($_POST['sensorica_envato_key']);

    $rsa = new RSA();
    $rsa_key = $rsa->createKey();

    if (substr($sensorica_back, -1) !== '/') {
      $sensorica_back .= '/';
    }

    $url = $sensorica_back . "envatocheckandsave";

    $post_data = [
      'rsa_private_key' => $rsa_key['privatekey'],
      'registeredurl' => base64_encode(home_url()."/wp-json/sensorica/v1/shortcode/{id}"),
      'key' => $envato_key
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
            update_option('sensorica_backend_rsa_openkey_base64', esc_attr($con_json->publicKeyBase64));

            echo '<span style="color: green;">';
            esc_html_e('Settings saved. ', 'sensorica');
            echo $con_json->message;
            echo '</span>';
           
        } else {
            echo '<span style="color: red;">';
            esc_html_e('Invalid Envato Key or Proxy Url', 'sensorica');
            echo "<br>";
            esc_html_e($con);
            echo '</span>';
        }
        ?>
    </p>
</div>

    <?php
  }


  ?>
  <div class="wrap">
    <div class="">
      <h2>
        <?php echo esc_html(get_admin_page_title()); ?>
      </h2>
      <h2 class="nav-tab-wrapper sensorica-nav-tabs wp-clearfix">
        <a href="#sensorica-tab-1" class="nav-tab nav-tab-active">
          <?php esc_html_e('Main Setting', 'sensorica'); ?>
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
                      <?php echo esc_html('Your envato license key', 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <input type="text" name="sensorica_envato_key" id="sensorica_envato_key"
                      value="<?php esc_attr_e(get_option('sensorica_envato_key', '...')) ?>" />
                    <p class="description">
                      <?php esc_html_e('Put here your envato license key here'); ?>
                    </p>
                  </td>
                </tr>
                <?php 
                //dark or light theme selector
                $sensorica_theme = get_option('sensorica_theme', 'light');
                if (isset($sensorica_theme)) {
                  ?>
                  <tr>
                    <th scope="row">
                      <label>
                        <?php echo esc_html('Theme', 'sensorica'); ?>
                      </label>
                    </th>
                    <td>
                      <select name="sensorica_theme" id="sensorica_theme">
                        <option value="sensorica_light_theme" <?php if ($sensorica_theme == 'sensorica_light_theme') {
                                              echo 'selected';
                                            } ?>>
                          <?php esc_html_e('Light', 'sensorica'); ?>
                        </option>
                        <option value="sensorica_dark_theme" <?php if ($sensorica_theme == 'sensorica_dark_theme') {
                                              echo 'selected';
                                            } ?>>
                          <?php esc_html_e('Dark', 'sensorica'); ?>
                        </option>
                      </select>
                      <p class="description">
                        <?php esc_html_e('Select theme'); ?>
                      </p>
                    </td>
                  </tr>
                  
                  <tr>
                    <th scope="row">
                      <label>
                        <?php echo esc_html('Default OpenAI API key', 'sensorica'); ?>
                      </label>
                    </th>
                    <td>
                      <input type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY"
                        value="<?php esc_attr_e(get_option('OPENAI_API_KEY')) ?>" />
                      <p class="description">
                        <?php esc_html_e('Put here your OpenAI API key here'); ?>
                      </p>
                    </td>
                  <?php
                }
                ?>
                

                <tr>
                  <th scope="row">
                    <label>
                      <?php echo esc_html('OpenAI proxy url', 'sensorica'); ?>
                    </label>
                  </th>
                  <td>
                    <input type="text" name="sensorica_openaiproxy" id="sensorica_openaiproxy"
                      value="<?php esc_attr_e(get_option('sensorica_openaiproxy', 'https://telegram.onout.org/')) ?>" />
                    <p class="description">
                      <?php esc_html_e('This backend requires. You may setup own with instruction or use shared'); ?>
                    </p>
                  </td>
                </tr>

                <?php
                $sensorica_client_id = get_option('sensorica_client_id', '');
                $sensorica_backend_rsa_openkey_base64 = get_option('sensorica_backend_rsa_openkey_base64', '');
                
                if ($sensorica_client_id !== '' && $sensorica_backend_rsa_openkey_base64 !== '') {
                  ?>
                  <tr>
                    <th scope="row">
                      <label>
                        <?php echo esc_html('Your client id', 'sensorica'); ?>
                      </label>
                    </th>
                    <td>
                      <input type="text" disabled name="sensorica_client_id" id="sensorica_client_id"
                        value="<?php esc_attr_e($sensorica_client_id) ?>" />
                      <p class="description">
                        <?php esc_html_e('Your client id'); ?>
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      <label>
                        <?php echo esc_html('Backend public key', 'sensorica'); ?>
                      </label>
                    </th>
                    <td>
                      <textarea disabled name="sensorica_backend_rsa_openkey_base64" id="sensorica_backend_rsa_openkey_base64"
                        rows="10"
                        cols="100"><?php esc_attr_e(base64_decode($sensorica_backend_rsa_openkey_base64)); ?></textarea>
                      <p class="description">
                        <?php esc_html_e('Backend public key'); ?>
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
            Support: support@onout.org / https://t.me/onoutsupportbot
          </form>
        </div>
      </div>
    </div>
  </div>
<?php } ?>