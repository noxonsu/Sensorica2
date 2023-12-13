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
      echo $tool['main_action'];
      include("tools/".$tool['slug']."/".$tool['main_action']); //include the main action file
      
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
        
        //if this input is the last one show the confrimation screen with all the inputs and it's value and submit button
        if ($input == end(array_keys($tool['inputs']))) {
          foreach ($tool['inputs'] as $input => $input_data) {
            echo '<div class="form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><input type="text" class="form-control" name="' . $input . '" id="' . $input . '" value="' . $_POST[$input] . '" disabled /></div>';
          }
          echo '<input type="submit" value="Finalize" name="action" class="btn" />';
          
        }
        //if this input is not the last one, continue
        continue;
      }

      ?>
      <div class="tab-content active" data-content="title3">
        <div class="headline" id='promptHeadline'>
          <span style="background: #fff"><svg class="icon" style="fill: #f6821f">
              <use xlink:href="#ico-3"></use>
            </svg></span>
          <?php echo $input_data['title']; ?>
        </div>
        <?php
        
          if ($input_data['textarea'] == true) {
            
            include("toole_single_prompt_textarea.php");
          } else { ?>
        <div class="form-section">
          <label for="<?php echo $input; ?>">
            <?php echo $input_data['description']; ?>
          </label>

          
          
          
          <?php 
          if ($input_data['magic_prompt']) { 
            //add small icon to form-control input to the right with link to the magic prompt
            ?>
            <div>
          <input class="form-control userinput" type="text" name="<?php echo $input; ?>" id="<?php echo $input; ?>"
            placeholder="<?php echo $input_data['placeholder']; ?>" />

          <div class="magic-prompt">
            <span alt="Generate" title="Generate">🪄</span>
          </div>
          </div>
          <style>
            .magic-prompt {
              
              left: -40px;
              top: -40px;
              position: relative;
              width: 40px;
              color: #fff;
              display: flex;
              align-items: center;
              justify-content: center;
              cursor: pointer;
            }
            </style>
            <script>
              //call ajax.php?magic_prompt=1&userinput= and put value instead of userinput
              $(document).ready(function () {
                $('.magic-prompt').click(function () {
                  var userinput = $(this).parent().find('.userinput').val();
                  $.ajax({
                    url: "ajax.php?magic_prompt=1&userinput=" + userinput,
                    //show 'stop' icon in .magic-prompt while loading
                    beforeSend: function () {
                      $('.magic-prompt').html('⏹');
                    },
                    //show '🪄' icon in .magic-prompt after loading
                    complete: function () {
                      $('.magic-prompt').html('🪄');
                    },
                    success: function (result) {
                      //json
                      var obj = JSON.parse(result);
                      //put the result in the input
                      $('.userinput').val(obj.message);
                    }
                  });
                });
              });
            </script>
            <?php
          } else {
            ?>
            <input type="text" class="form-control" name="<?php echo $input; ?>" id="<?php echo $input; ?>"
            placeholder="<?php echo $input_data['placeholder']; ?>" />
            <?php
          }
          ?>
          

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
        <?php } ?>      
        
      </div>
      <?php
      //show only one setting per step
      break;
    } ?>
  </form>
</div>
<!--/ content -->