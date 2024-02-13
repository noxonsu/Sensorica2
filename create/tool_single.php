<?php

include(sensorica_PATH."create/tool_single_input.php");
include(sensorica_PATH."create/tool_single_prompt_textarea.php");
include_once(sensorica_PATH."create/tool_single_magic_button.php");
       

if ($_GET['sensorica_tool']) {
  $sensorica_tool = getToolInfo($_GET['sensorica_tool']);
}
if (!$sensorica_tool) {
  die("Error: Tool not found");
}
?>
<div class="sensorica_sidebar">
  <div class="sensorica_title">
    <?php esc_html_e('Fill The Form Step By Step:', 'sensorica'); ?>
  </div>
  <ul class="sensorica_nav">
    <?php
    foreach ($sensorica_tool['inputs'] as $sensorica_input => $sensorica_input_data) {
      if (!isset($sensorica_input_data['title']) || $sensorica_input_data['title'] == '') {
        $sensorica_input_data['title'] = esc_html__('Untitled', 'sensorica'); // Use esc_html__() for assignment
      }
      echo '<li><a href="javascript:void(0);" data-ref="' . esc_attr($sensorica_input_data['title']) . '" class="sensorica_tab-title" data-title="title1"><svg class="sensorica_icon"><use xlink:href="#' . esc_attr($sensorica_input_data['svg_icon_id']) . '"></use></svg>' . esc_html($sensorica_input_data['title']) . '</a></li>';
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
    <code>[sensorica_form tool_name='<?php echo esc_attr($sensorica_tool['slug']); ?>']</code>
  <?php } ?>
</div>

<!-- content -->
<div class="sensorica_content">
  <form action="" method="post">
    <?php

    if (isset($_POST['action']) && $_POST['action'] == 'Finalize') {
      //if user click on finalize button, show the confirmation screen with all the inputs and their values and submit button
      if (!isset($sensorica_tool['main_action_php_file'])) {
        $sensorica_tool['main_action_php_file'] = 'action.php';
      }
      include(sensorica_PATH."tools/" . esc_attr($sensorica_tool['slug']) . "/" . esc_attr($sensorica_tool['main_action_php_file'])); //include the main action file
    
    } else {
      //if user click on apply and next button, save input to hidden input in use already send its value
      foreach ($sensorica_tool['inputs'] as $sensorica_input => $sensorica_input_data) {
        if (isset($_POST[$sensorica_input])) {
          //sanitize the input the same as in wp admin editor (wp_kses_post)
          $_POST[$sensorica_input] = stripslashes($_POST[$sensorica_input]);
          $sensorica_value = wp_kses_post($_POST[$sensorica_input]);
          //save it to WordPress session
          $_SESSION[$sensorica_input] = $sensorica_value;
          echo '<textarea id="hd_'.esc_attr($sensorica_input).'" name="' . esc_attr($sensorica_input) . '" style="display:none;">' . $sensorica_value . '</textarea>';
          
        }
      }
    }
    foreach ($sensorica_tool['inputs'] as $sensorica_input => $sensorica_input_data) {
      if (isset($_POST[$sensorica_input])) {
        
        //if this input is the last one, show the confirmation screen with all the inputs and their values and submit button
        // Assign the array keys to a variable
        $keys = array_keys($sensorica_tool['inputs']);

        // Now use end() on the variable
        if ($sensorica_input == end($keys) && !isset($sensorica_hide_final_form)) {
          sensorica_show_confirmation_form($sensorica_tool);
        }

        //if this input is not the last one, continue
        continue;
      }

      ?>
      <div class="sensorica_tab-content active" data-content="title3">
        <div class="sensorica_headline" id='promptHeadline'>
          <span style="background: <?php if (@$sensorica_input_data['background_color_svg']) {
            echo esc_attr($sensorica_input_data['background_color_svg']);
          } else {
            esc_html_e('#fff', 'sensorica');
          } ?>"><svg class="icon" style="<?php if (isset($sensorica_input_data['style_svg'])) {
             echo esc_attr($sensorica_input_data['style_svg']);
           } else {
             esc_html_e('fill: black', 'sensorica');
           } ?>">
              <use xlink:href="#<?php echo esc_attr($sensorica_input_data['svg_icon_id']); ?>"></use>
            </svg></span>

          <?php
          if (!isset($sensorica_input_data['title']) || $sensorica_input_data['title'] == '') {
            $sensorica_input_data['title'] = esc_html_e('HTML widget:', 'sensorica');
          }
          echo esc_html_e($sensorica_input_data['title'], 'sensorica'); ?>
        </div>
        <?php
        if (!isset($sensorica_input_data['type'])) {
          
          echo generate_sensorica_form_section($sensorica_input, $sensorica_input_data);
        } else if ($sensorica_input_data['type'] == 'prompt_textarea') {
          echo generate_sensorica_prompt_section($sensorica_input, $sensorica_input_data);
        } 
        
        ?>

        <input type="submit" id="sensorinca_next" value="<?php esc_html_e("Apply & Next", "sensorica") ?>"
          class='sensorica_btn' />

      </div>
      <?php
      //show only one setting per step
      ?>
      <script>
        jQuery("[data-ref='<?php echo $sensorica_input_data['title']; ?>']").addClass('sensorica_active');
      </script>
      <?php
      break;
    } ?>
  </form>
</div>

<script>
  //necessary inline script. can't be moved to a separate file
  const dataToEl = {
    <?php
    foreach ($sensorica_tool['inputs'] as $sensorica_input => $sensorica_input_data) {
      echo "'" . $sensorica_input . "': '#hd_" . $sensorica_input . "',";
    }
    ?>
  }
</script>
<?php 
?>
