<?php function generate_sensorica_form_section($input, $input_data) {
  echo '<div class="sensorica_form-section">';
  echo '<label for="' . esc_attr($input) . '">';
  esc_html_e($input_data['description'], 'sensorica');
  echo '</label>';
  
  // Default value handling
  if (!isset($input_data['default'])) {
    $input_data['default'] = '';
  } elseif ($input_data['default'] == 'window.location.href') {
    $input_data['default'] = "https://" . $_SERVER['HTTP_HOST'];
  }
  
  echo '<input class="sensorica_form-control sensorica_userinput" value="' . esc_attr($input_data['default']) . '" type="text" name="' . esc_attr($input) . '" id="' . esc_attr($input) . '" placeholder="' . esc_attr($input_data['placeholder'] ?? '') . '" />';
  
  echo '<details>';
  echo '<summary>';
  esc_html_e('Instructions', 'sensorica');
  echo '<div class="sensorica_chevron">';
  echo '<svg class="icon">';
  echo '<use xlink:href="#chevron"></use>';
  echo '</svg>';
  echo '</div>';
  echo '</summary>';
  echo '<div class="sensorica_summary-content">';
  echo '<ol>';
  foreach ($input_data['help_image'] as $image) {
    // Assuming $image contains safe HTML. If not, further escaping is needed
    echo '<li>' . wp_kses_post($image) . '</li>';
  }
  echo '</ol>';
  echo '</div>';
  echo '</details>';
  
  echo '</div>';
}