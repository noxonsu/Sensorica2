<?php
if ($_GET['tool']) {
  $tool = getToolInfo($_GET['tool']);
}
if (!$tool) {
  die("Error: Tool not found");
}
?>
<div class="sidebar">
  <div class="title">Fill The Form Step By Step:</div>
  <ul class="nav">

    <?php

    foreach ($tool['inputs'] as $input => $input_data) {
      echo '<li><a href="javascript:void(0);" data-ref="'.$input_data['title'].'" class="tab-title" data-title="title1"><svg class="icon"><use xlink:href="#' . $input_data['svg_icon_id'] . '"></use></svg>' . $input_data['title'] . '</a></li>';
    }
    ?>
    <li>
      <a href="javascript:void(0);" class="tab-title <?php echo ($_POST['action'] == 'Finalize') ? 'active' : ''; ?>" data-title="title8"><svg class="icon">
          <use xlink:href="#ico-2"></use>
        </svg>Result</a>
    </li>
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
      echo "<h2>Finalization</h2>";
      include("tools/" . $tool['slug'] . "/" . $tool['main_action_php_file']); //include the main action file
    
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
          <span style="background: <?php if ($input_data['background_color_svg']) {echo ''.$input_data['background_color_svg']; } else { echo '#fff'; } ?>"><svg class="icon" style="<?php if ($input_data['style_svg']) {echo ''.$input_data['style_svg']; } else { echo 'fill: black'; } ?>">
              <use xlink:href="#<?php echo $input_data['svg_icon_id']; ?>"></use>
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
                  <span alt="Generate" title="Generate"><svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M20.7 11.38C16.13 12.53 12.53 16.12 11.38 20.7C11.28 21.1 10.71 21.1 10.61 20.7C9.47 16.13 5.87 12.53 1.3 11.38C0.9 11.28 0.9 10.71 1.3 10.61C5.87 9.47 9.47 5.87 10.62 1.3C10.72 0.9 11.29 0.9 11.39 1.3C12.54 5.87 16.13 9.47 20.71 10.62C21.11 10.72 21.11 11.29 20.71 11.39L20.7 11.38ZM30.82 24.77C28.08 24.08 25.92 21.92 25.23 19.18C25.17 18.94 24.83 18.94 24.77 19.18C24.08 21.92 21.92 24.08 19.18 24.77C18.94 24.83 18.94 25.17 19.18 25.23C21.92 25.92 24.08 28.08 24.77 30.82C24.83 31.06 25.17 31.06 25.23 30.82C25.92 28.08 28.08 25.92 30.82 25.23C31.06 25.17 31.06 24.83 30.82 24.77Z"
                        fill="#94ABCF" />
                    </svg>
                  </span>
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

                .fade-out {
                  animation: fadeOut 0.1s;
                  opacity: 0;
                }

                .fade-in {
                  animation: fadeIn 0.1s;
                  opacity: 1;
                }

                @keyframes fadeOut {
                  from {
                    opacity: 1;
                  }

                  to {
                    opacity: 0;
                  }
                }

                @keyframes fadeIn {
                  from {
                    opacity: 0;
                  }

                  to {
                    opacity: 1;
                  }
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
                        var magicPrompt = $('.magic-prompt');
                        magicPrompt.removeClass('fade-in').addClass('fade-out');

                        // Wait for the fade-out animation to complete 
                        setTimeout(function () {
                          magicPrompt.html(`<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M16 3C23.17 3 29 8.83 29 16C29 23.17 23.17 29 16 29C8.83 29 3 23.17 3 16C3 8.83 8.83 3 16 3ZM16 1C7.72 1 1 7.72 1 16C1 24.28 7.72 31 16 31C24.28 31 31 24.28 31 16C31 7.72 24.28 1 16 1ZM21 19.5V12.5C21 11.67 20.33 11 19.5 11H12.5C11.67 11 11 11.67 11 12.5V19.5C11 20.33 11.67 21 12.5 21H19.5C20.33 21 21 20.33 21 19.5Z" fill="#94ABCF"/>
      </svg>`); // Your 'stop' icon SVG here
                          magicPrompt.removeClass('fade-out').addClass('fade-in');
                        }, 100);
                      },

                      complete: function () {
                        var magicPrompt = $('.magic-prompt');
                        magicPrompt.removeClass('fade-in').addClass('fade-out');

                        // Wait for the fade-out animation to complete 
                        setTimeout(function () {
                          magicPrompt.html(`<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M20.7 11.38C16.13 12.53 12.53 16.12 11.38 20.7C11.28 21.1 10.71 21.1 10.61 20.7C9.47 16.13 5.87 12.53 1.3 11.38C0.9 11.28 0.9 10.71 1.3 10.61C5.87 9.47 9.47 5.87 10.62 1.3C10.72 0.9 11.29 0.9 11.39 1.3C12.54 5.87 16.13 9.47 20.71 10.62C21.11 10.72 21.11 11.29 20.71 11.39L20.7 11.38ZM30.82 24.77C28.08 24.08 25.92 21.92 25.23 19.18C25.17 18.94 24.83 18.94 24.77 19.18C24.08 21.92 21.92 24.08 19.18 24.77C18.94 24.83 18.94 25.17 19.18 25.23C21.92 25.92 24.08 28.08 24.77 30.82C24.83 31.06 25.17 31.06 25.23 30.82C25.92 28.08 28.08 25.92 30.82 25.23C31.06 25.17 31.06 24.83 30.82 24.77Z" fill="#94ABCF"/>
      </svg>`); // Your '🪄' icon SVG here
                          magicPrompt.removeClass('fade-out').addClass('fade-in');
                        }, 200);
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
      ?>
      <script>
        jQuery("[data-ref='<?php echo $input_data['title']; ?>']").addClass('active');
      </script>
      <?php
      break;
    } ?>
  </form>
</div>
<!--/ content -->