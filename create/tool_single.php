<?php
if ($_GET['tool']) {
  $tool = getToolInfo($_GET['tool']);
}
if (!$tool) {
  die("Error: Tool not found");
}
?>
<div class="sensorica_sidebar">
  <div class="sensorica_title">
    <?php esc_html_e('Fill The Form Step By Step:', 'sensorica'); ?>
  </div>
  <ul class="sensorica_nav">
    <?php
    foreach ($tool['inputs'] as $input => $input_data) {
      if (!isset($input_data['title']) || $input_data['title'] == '') {
        $input_data['title'] = esc_html__('Untitled', 'sensorica'); // Use esc_html__() for assignment
      }
      echo '<li><a href="javascript:void(0);" data-ref="' . esc_attr($input_data['title']) . '" class="sensorica_tab-title" data-title="title1"><svg class="sensorica_icon"><use xlink:href="#' . esc_attr($input_data['svg_icon_id']) . '"></use></svg>' . esc_html($input_data['title']) . '</a></li>';
    }
    ?>
    <li>
      <a href="javascript:void(0);"
        class="sensorica_tab-title <?php echo (isset($_POST['action']) && $_POST['action'] == 'Finalize') ? 'sensorica_active' : ''; ?>"
        data-title="title8"><svg class="sensorica_icon">
          <use xlink:href="#ico-7"></use>
        </svg>
        <?php esc_html_e('Result', 'sensorica'); ?>
      </a>
    </li>
  </ul>
  <?php if (is_admin()) { ?>
    <br><br>
    <div class="sensorica_title">
      <?php esc_html_e('WP shortcode for this form:', 'sensorica'); ?>
    </div>
    <code>[sensorica_form create/tool_name='<?php echo esc_attr($tool['slug']); ?>']</code>
  <?php } ?>
</div>

<!-- content -->
<div class="sensorica_content">
  <form action="" method="post">
    <?php

    if (isset($_POST['action']) && $_POST['action'] == 'Finalize') {
      //if user click on finalize button, show the confrimation screen with all the inputs and it's value and submit button
      if (!isset($tool['main_action_php_file'])) {
        $tool['main_action_php_file'] = 'action.php';
      }
      include("tools/" . $tool['slug'] . "/" . $tool['main_action_php_file']); //include the main action file
    
    } else {
      //if user click on apply and next button, save input to hidden input in use already send its value
      foreach ($tool['inputs'] as $input => $input_data) {
        if (isset($_POST[$input])) {
          echo '<input type="hidden" name="' . $input . '" value="' . $_POST[$input] . '" />';
        }
      }
    }
    foreach ($tool['inputs'] as $input => $input_data) {
      if (isset($_POST[$input])) {
        //save input to hidden input in use already send its value
        echo '<input type="hidden" id="hd_' . $input . '" name="' . $input . '" value="' . $_POST[$input] . '" />';

        //if this input is the last one show the confrimation screen with all the inputs and it's value and submit button
        // Assign the array keys to a variable
        $keys = array_keys($tool['inputs']);

        // Now use end() on the variable
        if ($input == end($keys) && !isset($sensorica_hide_final_form)) {
          echo '<div class="sensorica_title">Confirm & finalize</div>';
          foreach ($tool['inputs'] as $input => $input_data) {
            // Check if $_POST[$input] is set to avoid undefined index notice
            $postValue = isset($_POST[$input]) ? $_POST[$input] : '';
            if ($input == 'NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT') {
              echo '<div class="sensorica_form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><textarea class="sensorica_prompt-area" disabled name="' . $input . '" id="' . $input . '">' . $postValue . '</textarea></div>';
            } else {
              echo '<div class="sensorica_form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><input type="text" class="sensorica_form-control sensorica_userinput" name="' . $input . '" id="' . $input . '" value="' . $postValue . '" disabled /></div>';
            }

          }
          echo '<input type="submit" value="Finalize" name="action" class="sensorica_btn" />';
        }

        //if this input is not the last one, continue
        continue;
      }

      ?>
      <div class="sensorica_tab-content active" data-content="title3">
        <div class="sensorica_headline" id='promptHeadline'>
          <span style="background: <?php if (@$input_data['background_color_svg']) {
            echo esc_attr($input_data['background_color_svg']);
          } else {
            esc_html_e('#fff', 'sensorica');
          } ?>"><svg class="icon" style="<?php if (isset($input_data['style_svg'])) {
             echo esc_attr($input_data['style_svg']);
           } else {
             esc_html_e('fill: black', 'sensorica');
           } ?>">
              <use xlink:href="#<?php echo esc_attr($input_data['svg_icon_id']); ?>"></use>
            </svg></span>

          <?php
          if (!isset($input_data['title']) || $input_data['title'] == '') {
            $input_data['title'] = esc_html_e('HTML widget:', 'sensorica');
          }
          echo esc_html_e($input_data['title'], 'sensorica'); ?>
        </div>
        <?php
        if (!isset($input_data['type'])) {
          include(sensorica_PATH."create/tool_single_input.php");
        } else if ($input_data['type'] == 'prompt_textarea') {

          include(sensorica_PATH."create/tool_single_prompt_textarea.php");
        } else if ($input_data['type'] == 'textarea') {
          include(sensorica_PATH."create/tool_single_textarea.php");
        } else if ($input_data['type'] == 'website') {
          include(sensorica_PATH."create/tool_single_website.php");
        } ?>

        <?php
        include_once(sensorica_PATH."create/tool_single_magic_button.php");
        ?>


        <input type="submit" id="sensorinca_next" value="<?php esc_html_e("Apply & Next", "sensorica") ?>"
          class='sensorica_btn' />

      </div>
      <?php
      //show only one setting per step
      ?>
      <script>
        jQuery("[data-ref='<?php echo $input_data['title']; ?>']").addClass('sensorica_active');
      </script>
      <?php
      break;
    } ?>
  </form>
</div>

<script>
  //neseserry inline script. can't be moved to separate file
  const dataToEl = {
    <?php
    foreach ($tool['inputs'] as $input => $input_data) {
      echo "'" . $input . "': '#hd_" . $input . "',";
    }
    ?>
  }
</script>
<?php 
function enqueue_custom_script() {
  wp_enqueue_script( 'custom-script', get_template_directory_uri() . '/static/js/create/tool_single.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_script' );
?><!--/ content -->
