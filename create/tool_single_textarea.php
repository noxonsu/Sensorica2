<div class="form-section">
  <label for="<?php echo esc_attr($input); ?>">
    <?php echo esc_html($input_data['description']); ?>
  </label>
  <textarea class="form-control" style='min-height:300px' name="<?php echo esc_attr($input); ?>"
    id="<?php echo esc_attr($input); ?>" placeholder="<?php echo esc_attr(@$input_data['placeholder']); ?>"></textarea>
  <details>
    <summary>
      <?php esc_html_e('Instructions', 'sensorica'); ?>
      <div class="chevron">
        <svg class="icon">
          <use xlink:href="#chevron"></use>
        </svg>
      </div>
    </summary>
    <div class="summary-content">
      <ol>
        <?php
        if (!empty($input_data['help_image'])) {
          foreach ($input_data['help_image'] as $image) {
            echo '<li>' . esc_html($image) . '</li>';
          }
        }
        ?>
      </ol>
    </div>
  </details>
</div>