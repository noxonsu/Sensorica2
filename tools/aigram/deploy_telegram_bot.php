<?php
function esc_attr($v) {
    return $v;
}
function esc_url($v) {
    return $v;
}


// Check if the script is accessed via a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load and parse the JSON file
    $jsonString = file_get_contents("tools/aigram/actions.json");
    
    $json = json_decode($jsonString, true);
    if ($json === null) {
        die("Error: Unable to parse actions.json");
    }

    // Extract the necessary data from the parsed JSON file
    

    $api_url = addslashes($json['servers'][0]['url'] . "/bot/deploy");
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

    // Handle the response
    if ($response === false) {
        print_r($data);
        echo 'Error: Failed to deploy the bot. err '.curl_error($ch);
    } else {
        $responseData = json_decode($response, true);
        if (isset($responseData['success']) && $responseData['success']) {
            echo 'Success: Your Telegram bot is now deployed!';
        } else {
            echo 'Error: ' . ($responseData['message'] ?? 'Failed to deploy the bot. '.$responseData['message']);
        }
    }
} else {
    echo 'Invalid request method.';
}
?>
