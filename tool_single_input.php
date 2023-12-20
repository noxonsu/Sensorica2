?>
            <div class="form-section">
              <label for="<?php echo $input; ?>">
              <?php echo $input_data['description']; ?>
              </label>
              <?php 
              //default
              if (!isset($input_data['default'])) {
                $input_data['default'] = '';
              } else if ($input_data['default'] == 'window.location.href') {
                $input_data['default'] = "https://".$_SERVER['HTTP_HOST'];
              }
              ?>
<input class="form-control userinput" value="<?php echo $input_data['default'];?>" type="text" name="<?php echo $input; ?>" id="<?php echo $input; ?>"
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