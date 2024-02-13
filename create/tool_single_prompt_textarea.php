<?php 

function generate_sensorica_prompt_section($input) {
  echo '<div class="sensorica_form-section" id="descriptionSection">';
  echo '<p>';
  esc_html_e('The System Prompt (instruction for GPT) consists of instructions for the bot with your custom context. Note that users will not be able to see this text. This field is optional.', 'sensorica');
  echo '</p>';
  echo '</div>';

  echo '<button class="sensorica_btn btn-prompt" type="button">';
  esc_html_e('Write a Prompt', 'sensorica');
  echo '</button>';

  echo '<div class="sensorica_textarea-wrp">';
  echo '<textarea class="sensorica_prompt-area" name="' . esc_attr($input) . '" id="promptArea">';
  esc_html_e('You are an AI assistant', 'sensorica');
  echo '</textarea>';
  echo '</div>';

  echo '<div class="sensorica_separator"><span>';
  esc_html_e('or', 'sensorica');
  echo '</span></div>';

  echo '<div class="sensorica_title" id="listOfPromptsTitle">';
  esc_html_e('Select one of the prompt examples', 'sensorica');
  echo '</div>';

  echo '<ul class="sensorica_accordionOptionsList" id="listOfPrompts"></ul>';

  echo '<div class="sensorica_more">';
  echo '<a href="https://github.com/search?q=GPTs&type=repositories" target="_blank">';
  esc_html_e('Get more templates', 'sensorica');
  echo '</a>';
  echo '</div>';

  echo '<div class="sensorica_button-wrap">';
  echo '<button type="button" class="sensorica_btn sensorica_btn-prompt" id="resetToOriginal">';
  esc_html_e('Reset to Original', 'sensorica');
  echo '</button>';
  echo '<button type="submit" class="sensorica_btn" id="sensorica_apply" data-action="save_prompt">';
  esc_html_e('Apply & Next step', 'sensorica');
  echo '</button>';
  echo '</div>';

  wp_add_inline_style( 'sensorica-style', '#sensorinca_next { display: none; }' );
  
  add_action('wp_enqueue_scripts', 'sensorica_enqueue_scripts_prompt_textarea');
  add_action('admin_enqueue_scripts', 'sensorica_enqueue_scripts_prompt_textarea');
}