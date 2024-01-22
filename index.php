<?php
#load required inputs as array and find for slug 

ini_set('display_errors', 1);
error_reporting(E_ALL);




if (isset($_GET['tool'])) {
  $tool = getToolInfo($_GET['tool']);
}


  //wp_enqueue_script('jquery'); and css
  wp_enqueue_script('jquery');
  wp_enqueue_style('sensorica2-style', SENSORICA2_URL . 'static/new.css', array(), SENSORICA2_VERSION, 'all');

?>
  <div class="sensorica_wrapper">
    <!-- header -->
    <header class='sensorica_header'>
      <div class="sensorica_header-logo">
        <img src="<?php echo (isset($tool['img'])) ? $tool['img'] : SENSORICA2_URL.'static/images/chat-gpt-logo.svg'; ?>" alt="" />
      </div>
      <h2 class="sensorica_deploymentPageTitle">
        <?php echo (isset($tool['title'])) ? $tool['title'] : "AI tools for WordPress"; ?>
       <br> 
      
    </header>
    <!--/ header -->
    <main>
      <div class="sensorica_row">
        <?php
        if (isset($_GET['tool'])) {
          include(SENSORICA2_PATH."tool_single.php");
        }
        
        if (!isset($_GET['tool'])) {
          include(SENSORICA2_PATH."select_tool.php");
        } ?>
      </div>
      <!-- footer -->
      <footer class='sensorica'>
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
            target="_blank">Discord</a> or <a href="https://t.me/sensorica2" target="_blank">Telegram</a>
        </div>
      </footer>
      <!--/ footer -->
    </main>
  </div>
  <!-- import svg -->
  <?php
  //import svg static/icons/icons.svg
  echo file_get_contents(SENSORICA2_PATH.'static/icons/icons.svg');
  ?>
