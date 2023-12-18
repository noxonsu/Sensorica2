<?php

// cURL Request Function
function makeCurlRequest($url, $token, $postData = null, $method = 'POST') {
    $headers = [
        "Accept: application/vnd.github+json",
        "Authorization: Bearer $token",
        "User-Agent: YourAppName" // Replace with your app name
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers
    ]);

    if ($postData) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    }

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Fork Repository Function
function forkRepository($repositoryFullName, $githubToken) {
    $url = "https://api.github.com/repos/$repositoryFullName/forks";
    [$org, $repo] = explode("/", $repositoryFullName);

    $postData = [
        "owner" => $org,
        "name" => $repo,
        "default_branch_only" => true
    ];

    echo "Forking repository '$repo'...\n";
    return makeCurlRequest($url, $githubToken, $postData);
}

// Create a New File in the GitHub Repository Function
function createNewGitHubFile($owner, $repo, $filePath, $commitMessage, $content, $githubToken) {
    $url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    $data = [
        'message' => $commitMessage,
        'content' => base64_encode($content)
    ];

    return makeCurlRequest($url, $githubToken, $data, 'PUT');
}

?>
