<?php 
function generate_form_section_textarea($input, $input_data) {
  echo '<div class="form-section">';
  echo '<label for="' . esc_attr($input) . '">';
  echo esc_html($input_data['description']);
  echo '</label>';
  echo '<textarea class="form-control" style="min-height: 300px" name="' . esc_attr($input) . '" id="' . esc_attr($input) . '" placeholder="' . esc_attr(@$input_data['placeholder']) . '"></textarea>';
  echo '<details>';
  echo '<summary>';
  esc_html_e('Instructions', 'sensorica');
  echo '<div class="chevron">';
  echo '<svg class="icon">';
  echo '<use xlink:href="#chevron"></use>';
  echo '</svg>';
  echo '</div>';
  echo '</summary>';
  echo '<div class="summary-content">';
  echo '<ol>';
  if (!empty($input_data['help_image'])) {
    foreach ($input_data['help_image'] as $image) {
      echo '<li>' . esc_html($image) . '</li>';
    }
  }
  echo '</ol>';
  echo '</div>';
  echo '</details>';
  echo '</div>';
}

// Usage:
// generate_form_section($input, $input_data);
