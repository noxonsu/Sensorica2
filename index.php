<?php
#load required inputs as array and find for slug 

ini_set('display_errors', 1);
error_reporting(E_ALL);


function getToolInfo($toolSlug)
{
  $tools = json_decode(file_get_contents(SENSORICA2_PATH.'tools.json'), true);
  foreach ($tools as $tool) {
    if ($tool['slug'] == $toolSlug) {
      $tool = json_decode(file_get_contents(SENSORICA2_PATH.'tools/' . $tool['slug'] . '/info.json'), true);
      break;
    }
  }
  return $tool;
}

if (isset($_GET['tool'])) {
  $tool = getToolInfo($_GET['tool']);
}
// Check if we are inside a WordPress page
if (!function_exists('add_action')) {
  // Not a WordPress page
  echo '<html lang="en"><head><meta charset="UTF-8" /><title>AI wizard</title>';
  echo '<link rel="stylesheet" href="'. SENSORICA2_URL .'/static/new.css" />';
  echo '<meta content="width=device-width, initial-scale=1" name="viewport" />';
  echo '<meta name="theme-color" content="#5f48b0" />';
  echo '<meta name="description" content="Boost your website with our AI tools. Get better articles from market research, top Google ranks, more website visits, and user-friendly chat features. Try our Telegram bot for more customers." />';
  echo '<meta name="keywords" content="chatgpt bot, telegram bot, chatgpt telegram, ai bot, ai app, no-code telegram bot, earn on ai, onout" />';
  // Use wp_enqueue_script to add jQuery
  echo '<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>';
  
  echo '</head><body class=\'sensorica_body\'>';
} else {

  //wp_enqueue_script('jquery'); and css
  wp_enqueue_script('jquery');
  wp_enqueue_style('sensorica2-style', SENSORICA2_URL . 'static/new.css', array(), SENSORICA2_VERSION, 'all');

} ?>
  <div class="sensorica_wrapper">
    <!-- header -->
    <header>
      <div class="sensorica_header-logo">
        <img src="<?php echo (isset($tool['img'])) ? $tool['img'] : ' ./static/images/chat-gpt-logo.svg'; ?>" alt="" />
      </div>
      <h2 class="sensorica_deploymentPageTitle">
        <?php echo (isset($tool['title'])) ? $tool['title'] : "AI tools for WordPress"; ?>
       <br> 
      <a style='font-size:15px' href="/">Back</a></h2>
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

<?
// Check again to close the tags if not in WordPress
if (!function_exists('add_action')) {
  echo '</body></html>';
}
?>