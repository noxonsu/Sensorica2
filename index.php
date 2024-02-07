<?php
# Load required inputs as an array and find for slug
//dissallow direct open
if (!defined('ABSPATH')) {
  exit;
}


ini_set('display_errors', 1);
error_reporting(E_ALL);


if (isset($_GET['sensorica_tool'])) {
  $sensorica_tool = getToolInfo($_GET['sensorica_tool']);
}

// Enqueue jQuery script and CSS
wp_enqueue_script('jquery');
wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

?>
<div class="sensorica_wrapper <?php echo esc_attr(get_option('sensorica_theme', 'sensorica_light_theme')); ?> sensorica">
  
  <main>
    <div class="sensorica_row">
      <?php
      if (isset($_GET['sensorica_tool'])) {
        include(sensorica_PATH . "create/tool_single.php");
      }
      
      if (!isset($_GET['sensorica_tool'])) {
        include(sensorica_PATH . "/create/select_tool.php");
      }
      ?>
    </div>
    <!-- footer -->
    <?php 
    echo sensorica_show_footer();
    ?>
    <!--/ footer -->
  </main>
</div>
<!-- import svg -->
<?php
// Import svg static/icons/icons.svg
echo file_get_contents(sensorica_PATH . 'static/icons/icons.svg');
?>
