<?php
#load required inputs as array and find for slug 

ini_set('display_errors', 1);
error_reporting(E_ALL);




if (isset($_GET['tool'])) {
  $tool = getToolInfo($_GET['tool']);
}


  //wp_enqueue_script('jquery'); and css
wp_enqueue_script('jquery');
wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

//add get_option sensorica_theme class to body


?>
  <div class="sensorica_wrapper <?php echo get_option('sensorica_theme', 'sensorica_light_theme'); ?>">
  
    <main>
      <div class="sensorica_row">
        <?php
        if (isset($_GET['tool'])) {
          include(sensorica_PATH."tool_single.php");
        }
        
        if (!isset($_GET['tool'])) {
          include(sensorica_PATH."select_tool.php");
        } ?>
      </div>
      <!-- footer -->
      
      <footer class='sensorica'><?php //only FOR ADMIN
      if (current_user_can('administrator')) {
      ?>
        <div class="sensorica_supportWrapper">
          Support:
          <a href="mailto:support@onout.org" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-eml"></use>
            </svg>Email
          </a>
          or
          <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-tlg"></use>
            </svg>Telegram
          </a>
        </div>
        <div class="sensorica_supportWrapper">
          Community: <a href="https://discord.com/channels/898545581591506975/1078964362783506442"
            target="_blank">Discord</a> or <a href="https://t.me/sensorica" target="_blank">Telegram</a>
        </div>
        <?php } ?>
      </footer>
      
      <!--/ footer -->
    </main>
  </div>
  <!-- import svg -->
  <?php
  //import svg static/icons/icons.svg
  echo file_get_contents(sensorica_PATH.'static/icons/icons.svg');
  ?>
