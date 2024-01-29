<div class="sensorica_form-section">
  <label for="<?php echo esc_attr($input); ?>">
    <?php esc_html_e($input_data['description'], 'sensorica'); ?>
  </label>
  <?php 
  // Default value handling
  if (!isset($input_data['default'])) {
    $input_data['default'] = '';
  } elseif ($input_data['default'] == 'window.location.href') {
    $input_data['default'] = "https://".$_SERVER['HTTP_HOST'];
  }
  ?>
  <input class="sensorica_form-control sensorica_userinput" value="<?php echo esc_attr($input_data['default']); ?>" type="text" name="<?php echo esc_attr($input); ?>" id="<?php echo esc_attr($input); ?>"
         placeholder="<?php echo esc_attr($input_data['placeholder'] ?? ''); ?>" />
  

  <details>
    <summary>
      <?php esc_html_e('Instructions', 'sensorica'); ?>
      <div class="sensorica_chevron">
        <svg class="icon">
          <use xlink:href="#chevron"></use>
        </svg>
      </div>
    </summary>
    <div class="sensorica_summary-content">
      <ol>
        <?php
        foreach ($input_data['help_image'] as $image) {
          // Assuming $image contains safe HTML. If not, further escaping is needed
            echo '<li>' . wp_kses_post($image) . '</li>';
        }
        ?>
      </ol>
    </div>
  </details>
</div>
