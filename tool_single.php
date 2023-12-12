<?php 
if ($_GET['tool']) {
  $tool = getToolInfo($_GET['tool']);
}
//print_r($tool['inputs']);
?>
<div class="sidebar">
  <div class="title">Fill The Form Step By Step:</div>
  <ul class="nav">

    <?php
   
    foreach ($tool['inputs'] as $input => $input_data) {
      $activeClass = ($_GET['input'] == $input) ? 'active' : ''; // Check if the current input is active
      echo '<li><a href="javascript:void(0);" class="tab-title ' . $activeClass . '" data-title="title1"><svg class="icon"><use xlink:href="#' . $input_data['svg_icon_id'] . '"></use></svg>' . $input_data['title'] . '</a></li>';
    }
    ?>
    
    <li>
      <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
          <use xlink:href="#ico-7"></use>
        </svg>F.A.Q.</a>
    </li>
  </ul>
  <Br><br>
  <div class="title">WP shortcode for this form:</div>
    <code>
      [sensorica_form tool_name='<?php echo $tool['slug']; ?>']
    </code>
</div>

<!-- content -->
<div class="content">
  <form action="" method="post">
    <?php
    if ($_POST['action'] == 'Finalize') {
      //if user click on finalize button, show the confrimation screen with all the inputs and it's value and submit button
      foreach ($tool['inputs'] as $input => $input_data) {
        echo '<div class="form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><input type="text" class="form-control" name="' . $input . '" id="' . $input . '" value="' . $_POST[$input] . '" disabled /></div>';
        echo '<input type="submit" value="Finalize" name="action" class="btn" />';
      }
      
      //TODO save all variables to database to show in Sensorica Submitions -> Toolname

      include($tool['main_action']); //include the main action file

    } else {
      //if user click on apply and next button, save input to hidden input in use already send its value
      foreach ($tool['inputs'] as $input => $input_data) {
        if ($_POST[$input]) {
          echo '<input type="hidden" name="' . $input . '" value="' . $_POST[$input] . '" />';
        }
      }
    }
    foreach ($tool['inputs'] as $input => $input_data) {
      if ($_POST[$input]) {
        //save input to hidden input in use already send its value
        echo '<input type="hidden" name="' . $input . '" value="' . $_POST[$input] . '" />';
        //if this input is not the last one, continue
        continue;
        //if this input is the last one show the confrimation screen with all the inputs and it's value and submit button
        if ($input == end(array_keys($tool['inputs']))) {
          foreach ($tool['inputs'] as $input => $input_data) {
            echo '<div class="form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><input type="text" class="form-control" name="' . $input . '" id="' . $input . '" value="' . $_POST[$input] . '" disabled /></div>';
            echo '<input type="submit" value="Finalize" name="action" class="btn" />';
          }
        }
      }

      ?>
      <div class="tab-content active" data-content="title3">
        <div class="headline">
          <span style="background: #fff"><svg class="icon" style="fill: #f6821f">
              <use xlink:href="#ico-3"></use>
            </svg></span>
          <?php echo $input_data['title']; ?>
        </div>
        <div class="form-section">
          <label for="<?php echo $input; ?>">
            <?php echo $input_data['description']; ?>
          </label>
          <input type="text" class="form-control" name="<?php echo $input; ?>" id="<?php echo $input; ?>"
            placeholder="<?php echo $input_data['placeholder']; ?>" />
          <details>
            <summary>
              Instructions
              <div class="chevron">
                <svg class="icon">
                  <use xlink:href="#chevron"></use>
                </svg>
              </div>
            </summary>
            <div class="summary-content">
              <ol>
                <?php

                foreach ($input_data['help_image'] as $image) {
                  echo '<li>' . $image . '</li>';
                }

                ?>
              </ol>
            </div>
          </details>
        </div>

        <input type="submit" value="Apply & Next" class='btn' />
      </div>
      <?php
      //show only one setting per step
      break;
    } ?>
  </form>
</div>
<!--/ content -->