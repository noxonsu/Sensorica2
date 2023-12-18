<?php

// Delete Vercel Project Function
function deleteVercelProject($projectName, $token) {
    $url = "https://api.vercel.com/v9/projects/$projectName";
    $headers = [
        "Authorization: Bearer $token"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch)) {
        return "Curl error: " . curl_error($ch);
    }

    return $response;
}

// Create New Vercel Project Function
function createVercelProject($projectName, $token, $defaultSystemPrompt, $openAiApiKey, $mainTitle, $owner, $repo) {
    $url = "https://api.vercel.com/v9/projects";
    $body = json_encode([
        "name" => $projectName,
        "buildCommand" => "next build",
        "devCommand" => "next dev --port \$PORT",
        "environmentVariables" => [
            ["key" => "NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT", "value" => $defaultSystemPrompt, "type" => "plain", "target" => "production"],
            ["key" => "OPENAI_API_KEY", "value" => $openAiApiKey, "type" => "plain", "target" => "production"],
            ["key" => "NEXT_PUBLIC_MAIN_TITLE", "value" => $mainTitle, "type" => "plain", "target" => "production"]
        ],
        "framework" => "nextjs",
        "gitRepository" => ["repo" => "$owner/$repo", "type" => "github"],
        "publicSource" => true,
        "skipGitConnectDuringLink" => true
    ]);

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch)) {
        return "Curl error: " . curl_error($ch);
    }

    return json_decode($response, true);
}

// Check Deployment Status on Vercel Function
function checkVercelDeployment($projectName, $token) {
    sleep(10); // Delay to allow time for deployment
    $url = "https://api.vercel.com/v9/projects/$projectName";

    $headers = [
        "Authorization: Bearer $token"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch)) {
        return "Curl error: " . curl_error($ch);
    }

    $data = json_decode($response, true);
    return $data;
}

?>
