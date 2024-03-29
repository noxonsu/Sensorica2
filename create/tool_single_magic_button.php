<?php
function generate_magic_prompt($input_data) {
  if (isset($input_data['magic_prompt'])) {
    echo '<div>';
    echo '<div class="sensorica_magic-prompt">';
    echo '<span alt="Generate" title="Generate"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">';
    echo '<path d="M20.7 11.38C16.13 12.53 12.53 16.12 11.38 20.7C11.28 21.1 10.71 21.1 10.61 20.7C9.47 16.13 5.87 12.53 1.3 11.38C0.9 11.28 0.9 10.71 1.3 10.61C5.87 9.47 9.47 5.87 10.62 1.3C10.72 0.9 11.29 0.9 11.39 1.3C12.54 5.87 16.13 9.47 20.71 10.62C21.11 10.72 21.11 11.29 20.71 11.39L20.7 11.38ZM30.82 24.77C28.08 24.08 25.92 21.92 25.23 19.18C25.17 18.94 24.83 18.94 24.77 19.18C24.08 21.92 21.92 24.08 19.18 24.77C18.94 24.83 18.94 25.17 19.18 25.23C21.92 25.92 24.08 28.08 24.77 30.82C24.83 31.06 25.17 31.06 25.23 30.82C25.92 28.08 28.08 25.92 30.82 25.23C31.06 25.17 31.06 24.83 30.82 24.77Z" fill="#94ABCF" />';
    echo '</svg></span>';
    echo '</div>';
    echo '</div>';
    echo '<style>';
    echo '.fade-out {';
    echo 'animation: fadeOut 0.1s;';
    echo 'opacity: 0;';
    echo '}';
    echo '.fade-in {';
    echo 'animation: fadeIn 0.1s;';
    echo 'opacity: 1;';
    echo '}';
    echo '@keyframes fadeOut {';
    echo 'from {';
    echo 'opacity: 1;';
    echo '}';
    echo 'to {';
    echo 'opacity: 0;';
    echo '}';
    echo '}';
    echo '@keyframes fadeIn {';
    echo 'from {';
    echo 'opacity: 0;';
    echo '}';
    echo 'to {';
    echo 'opacity: 1;';
    echo '}';
    echo '}';
    echo '</style>';
  }
}

// Usage:
// generate_magic_prompt($input_data);
?>