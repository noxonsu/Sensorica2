<div class="sensorica_form-section" id="descriptionSection">
  <p>
    <?php echo esc_html_e('The System Prompt (instruction for GPT) consists of instructions for the bot. Note that users will not be able to see this text. This field is optional.', 'sensorica'); ?>
  </p>
</div>
<button class="sensorica_btn btn-prompt" type="button"><?php echo esc_html_e('Write a Prompt', 'sensorica'); ?></button>
<div class="sensorica_textarea-wrp">
  <textarea class="sensorica_prompt-area" name="<?php echo esc_attr($input); ?>" id="promptArea"><?php echo esc_html_e('You are an AI assistant', 'sensorica'); ?></textarea>
</div>
<div class="sensorica_separator"><span><?php echo esc_html_e('or', 'sensorica'); ?></span></div>
<div class="sensorica_title" id="listOfPromptsTitle"><?php echo esc_html_e('Select one of the prompt examples', 'sensorica'); ?></div>
<ul class="sensorica_accordionOptionsList" id="listOfPrompts"></ul>
<div class="sensorica_more">
  <a href="https://github.com/search?q=GPTs&type=repositories" target="_blank"><?php echo esc_html_e('Get more templates', 'sensorica'); ?></a>
</div>
<div class="sensorica_button-wrap">
  <button type="button" class="sensorica_btn sensorica_btn-prompt" id="resetToOriginal"><?php echo esc_html_e('Reset to Original', 'sensorica'); ?></button>
  <button type="submit" class="sensorica_btn" id="sensorica_apply" data-action="save_prompt"><?php echo esc_html_e('Apply & Next step', 'sensorica'); ?></button>
</div>

<?php 
function sensorica_enqueue_scripts_prompt_textarea() {
  // Enqueue the script first
  wp_enqueue_script(
      'prompt-textarea-script', // Handle for the script, using a more descriptive name
      sensorica_URL . '/static/js/prompt_textarea.js?r=' . rand(1, 22222), // Script URL with cache-busting
      array('jquery'), // Dependencies
      sensorica_VERSION, // Version
      true // In footer
  );

  // JavaScript code to set the global variable
  $inline_script = 'window.sensorica_plugin_url = "' . sensorica_URL . '";';

  // Add the inline script to 'prompt-textarea-script'
  wp_add_inline_script('prompt-textarea-script', $inline_script);
}

add_action('wp_enqueue_scripts', 'sensorica_enqueue_scripts_prompt_textarea');

?>
<style>
  #sensorinca_next {display:none}
</style>