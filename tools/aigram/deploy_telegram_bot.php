<?php


//check we are in wordpress
if (!defined('ABSPATH')) {
    exit;
}

// Check if the script is accessed via a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load and parse the JSON file
    $jsonString = file_get_contents(sensorica_PATH."tools/aigram/actions.json");
    
    $json = json_decode($jsonString, true);
    if ($json === null) {
        die("Error: Unable to parse actions.json");
    }

    // Extract the necessary data from the parsed JSON file
    
    $sensorica_openaiproxy = get_option("sensorica_openaiproxy");
    $sensorica_openaiproxy = "https://refactored-fortnight-w945676vw9h9pqp-3010.app.github.dev/" ;   
    $api_url =  esc_url($sensorica_openaiproxy)."bot/deploy";
    // Extract the schema properties
    $schemaProperties = $json['paths']['/bot/deploy']['post']['requestBody']['content']['application/json']['schema']['properties'];
   
    // Dynamically populate the data array based on the schema
    $data = [];
    foreach ($schemaProperties as $key => $property) {
        if (isset($_POST[$key])) {
            // Sanitize and assign the values based on type
            $data[$key] = $property['type'] === 'string' ? esc_attr($_POST[$key]) : intval($_POST[$key]);
        }
    }
    echo $api_url;
    // Use WordPress HTTP API for the API request
    $response = wp_remote_post($api_url, array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode($data),
        'timeout' => 50
    ));
    
    // Handle the response
    if (is_wp_error($response)) {
        print_r($data);
        echo 'Error: Failed to deploy the bot. ' . $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        echo $response_body;
        if ($response_code === 200 && isset($responseData['success']) && $responseData['success']) {
            echo 'Success: Your Telegram bot is now deployed!';
        } else {
            echo 'Error: ' . ($responseData['message'] ?? 'Failed to deploy the bot. ' . $responseData['message']);
        }
    }
} else {
    echo 'Invalid request method.';
}
?>
