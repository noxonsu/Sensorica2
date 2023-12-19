
            <div class="form-section">
              <label for="<?php echo $input; ?>">
              <?php echo $input_data['description']; ?>
              </label>
              <textarea class="form-control" style='min-height:300px' name="<?php echo $input; ?>" id="<?php echo $input; ?>"
                placeholder="<?php echo @$input_data['placeholder']; ?>"></textarea>
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
            
