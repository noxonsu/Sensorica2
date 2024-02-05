<?php
# Load required inputs as an array and find for slug
//dissallow direct open
if (!defined('ABSPATH')) {
  exit;
}


ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['tool'])) {
  $tool = getToolInfo($_GET['tool']);
}

// Enqueue jQuery script and CSS
wp_enqueue_script('jquery');
wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

?>
<div class="sensorica_wrapper <?php echo esc_attr(get_option('sensorica_theme', 'sensorica_light_theme')); ?> sensorica">
  
  <main>
    <div class="sensorica_row">
      <?php
      if (isset($_GET['tool'])) {
        include(sensorica_PATH . "create/tool_single.php");
      }
      
      if (!isset($_GET['tool'])) {
        include(sensorica_PATH . "select_tool.php");
      }
      ?>
    </div>
    <!-- footer -->
    
    <footer class='sensorica'>
      <?php // Only for admin
      if (current_user_can('administrator')) :
      ?>
        <div class="sensorica_supportWrapper">
          <?php esc_html_e('Support:', 'sensorica'); ?>
          <a href="mailto:support@onout.org" target="_blank" rel="noreferrer">
            <svg class="icon">
              <use xlink:href="#ico-eml"></use>
            </svg>
            <?php esc_html_e('Email', 'sensorica'); ?>
          </a>
          <?php esc_html_e('or', 'sensorica'); ?>
          <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer">
            <svg class="icon">
              <use xlink:href="#ico-tlg"></use>
            </svg>
            <?php esc_html_e('Telegram', 'sensorica'); ?>
          </a>
        </div>
        <div class="sensorica_supportWrapper">
          <?php esc_html_e('Community:', 'sensorica'); ?>
          <a href="https://discord.com/channels/898545581591506975/1078964362783506442" target="_blank">
            <?php esc_html_e('Discord', 'sensorica'); ?>
          </a>
          <?php esc_html_e('or', 'sensorica'); ?>
          <a href="https://t.me/sensorica" target="_blank">
            <?php esc_html_e('Telegram', 'sensorica'); ?>
          </a>
        </div>
      <?php endif; ?>
    </footer>
    <!--/ footer -->
  </main>
</div>
<!-- import svg -->
<?php
// Import svg static/icons/icons.svg
echo file_get_contents(sensorica_PATH . 'static/icons/icons.svg');
?>
