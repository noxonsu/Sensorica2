<?php
if (isset($input_data['magic_prompt'])) {
  //add small icon to form-control input to the right with link to the magic prompt
  ?>
  <div>

    <div class="sensorica_magic-prompt">
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
    .sensorica_magic-prompt {

      right: 15px;
      top: 161px;
      position: absolute;
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
  <?php 
function sensorica_enqueue_scripts_magic_prompt() {
    // Enqueue the script first
    wp_enqueue_script(
        'magic-prompt-script', // Updated handle for the new script
        sensorica_URL . '/static/js/magic_prompt.js?r=' . rand(1, 22222), // Updated script URL for 'magic_prompt.js' with cache-busting
        array('jquery'), // Dependencies remain the same
        sensorica_VERSION, // Version
        true // In footer
    );

    // JavaScript code to set the global variable remains the same
    $inline_script = 'window.sensorica_plugin_url = "' . sensorica_URL . '";';

    // Add the inline script to 'magic-prompt-script' (updated handle)
    wp_add_inline_script('magic-prompt-script', $inline_script);
}

// Updated hook function name to reflect the new script's purpose
add_action('wp_enqueue_scripts', 'sensorica_enqueue_scripts_magic_prompt');
?>

  <?php
}
?>