<div class="wrap">
  <div class="">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
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
                  <label><?php echo esc_html('Your envato license key', 'sensorica'); ?></label>
                </th>
                <td>
                <input type="text" name="sensorica_envato_key" id="sensorica_envato_key" value="<?php esc_attr_e(get_option('sensorica_envato_key', '...'))?>" />
                  <p class="description">
                    <?php esc_html_e('Put here your envato license kety'); ?>
                  </p>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label><?php echo esc_html('OpenAI proxy url', 'sensorica'); ?></label>
                </th>
                <td>
                <input type="text" name="sensorica_openaiproxy" id="sensorica_openaiproxy" value="<?php esc_attr_e(get_option('sensorica_openaiproxy', 'https://apisensorica13006.onout.org/'))?>" />
                  <p class="description">
                    <?php esc_html_e('This backend requires. You may setup own with instruction or use shared'); ?>
                  </p>
                </td>
              </tr>

              <tr>
                <th scope="row"></th>
                <td>
                
                  <input type="submit" name="mcwallet-add-token"
                    class="button button-primary mcwallet-add-token"
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