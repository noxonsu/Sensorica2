<?php
function esc_attr($v) {
  return $v;
}
function esc_url($v) {
  return $v;
}

// Check if the script is accessed via a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data using WordPress escape functions
    $openai_sk = esc_attr($_POST['openai_sk'] ?? '');
    $cf_account_id = esc_attr($_POST['cf_account_id'] ?? '');
    $_POST['cf_wrangler_key'] = $_POST['cf_api_key'];
    $cf_wrangler_key = esc_attr($_POST['cf_wrangler_key'] ?? '');
    $tg_token = esc_attr($_POST['tg_token'] ?? '');
    $prompt = esc_attr($_POST['system_prompt'] ?? '');
    $_POST['free_messages'] = $_POST['monetization'];
    $free_messages = intval($_POST['free_messages'] ?? 0);
    $activation_code = esc_attr($_POST['activation_code'] ?? '');
    $payment_link = esc_url($_POST['payment_link'] ?? '');

    // Prepare data for the API request
    $data = [
        'openai_sk' => $openai_sk,
        'cf_account_id' => $cf_account_id,
        'cf_wrangler_key' => $cf_wrangler_key,
        'tg_token' => $tg_token,
        'prompt' => $prompt,
        'free_messages' => $free_messages,
        'activation_code' => $activation_code,
        'payment_link' => $payment_link,
    ];

    // Define the API endpoint
    $api_url = 'https://telegram.onout.org/bot/deploy';

    // Use cURL for the API request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute the request and capture the response
    $response = curl_exec($ch);
    curl_close($ch);
    print_r($response);
    // Handle the response
    if ($response === false) {
        // Handle error
        echo 'Error: Failed to deploy the bot.';
    } else {
        $responseData = json_decode($response, true);
        if (isset($responseData['success']) && $responseData['success']) {
            echo 'Success: Your Telegram bot is now deployed!';
            // You can also display the botUsername or other response data here
        } else {
            // Handle failure response
            echo 'Error: ' . ($responseData['message'] ?? 'Failed to deploy the bot.');
        }
    }
} else {
    echo 'Invalid request method.';
}
?>
