<?php
function esc_attr($v)
{
    return $v;
}
function esc_url($v)
{
    return $v;
}

// Define your environment variables
$nextPublicDefaultSystemPrompt = $_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'];
$openaiApiKey = $_POST['OPENAI_API_KEY'];
$mainTitle = $_POST['main_title'];
$yourVercelToken = $_POST['YOUR_VERCEL_TOKEN']; // Assuming 'YOUR_VERCEL_TOKEN' is the key in the POST request
$githubAccessToken = $_POST['githubAccessToken']; // Replace with your GitHub access token

// Main logic
$repoFullName = 'noot/op-deposit';

$projectNameVercel = 'chate';






if (empty($githubAccessToken)) {
    echo "Please provide a GitHub access token.";
    die();
}
// Function to make a cURL request
function makeCurlRequest($url, $githubAccessToken, $postData = null, $customRequest = 'POST')
{
    $headers = [
        "Accept: application/vnd.github+json",
        "Authorization: Bearer $githubAccessToken",
        "User-Agent: sensorica2" // Replace with your app name
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if (!empty($postData)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    }

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);

    return json_decode($response, true);
}


// Fork a repository


$url = "https://api.github.com/repos/$repoFullName/forks";
echo $url;
$org = explode("/", $repoFullName);
$repo = $org[1];
$org = $org[0];
$postData = [
    "owner" => $org, // Replace with the actual organization name if needed
    "name" => $repo,     // Replace with the desired repository name
    "default_branch_only" => true
];

echo "Repository '$repo' forking...\n";
$response = makeCurlRequest($url, $githubAccessToken, $postData);

if ($response['error']) {
    echo "Error: " . $response['error']['message'];
    die();
}



if (isset($response['owner']['login'])) {
    echo "Forked successfully. Owner: " . $response['owner']['login'];
} else {
    echo "Forking failed.";
    die();
}

$repo = $response['name'];
$owner = $response['owner']['login'];

if ($debug == true)
    print_r($response);
if ($debug == true)
    echo "\n";





function deleteVercelProject($projectNameVercel, $vercelToken)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.vercel.com/v9/projects/$projectNameVercel",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $vercelToken"
        ],
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        // Handle cURL error here
    }
    curl_close($ch);

    return $response;
}



// Step 1: Delete existing project (if it exists)
$deleteResponse = deleteVercelProject($projectNameVercel, $yourVercelToken);

if ($debug == true) {
    print "deleteResponse:\n";
    print_r($deleteResponse);
}

echo "<br>vercel project deleted (if exists)<br>";
// cURL setup
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, "https://api.vercel.com/v9/projects");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Prepare the body of the POST request
$body = json_encode(array(
    "name" => $projectNameVercel,
    "buildCommand" => "next build",
    "devCommand" => "next dev --port $PORT",
    "environmentVariables" => array(
        array(
            "key" => "NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT",
            "value" => $nextPublicDefaultSystemPrompt,
            "type" => "plain",
            "target" => "production"
        ),
        array(
            "key" => "OPENAI_API_KEY",
            "value" => $openaiApiKey,
            "type" => "plain",
            "target" => "production"
        ),
        array(
            "key" => "main_title",
            "value" => $mainTitle,
            "type" => "plain",
            "target" => "production"
        )
    ),
    "framework" => "nextjs",
    "gitRepository" => array(
        "repo" => $owner . "/" . $repo,
        "type" => "github"
    ),
    "publicSource" => true,
    "skipGitConnectDuringLink" => true
));

curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $yourVercelToken",
    "Content-Type: application/json"
));


// Execute cURL session
$response = curl_exec($ch);

if (curl_errno($ch)) {
    // Handle cURL error here
}

// Close cURL session
curl_close($ch);

$json = json_decode($response, true);


if ($json['error']) {
    echo "Error add project: " . $json['error']['message'];
    die();
}

if ($json['id']) {
    echo "Project added successfully. ID: " . $json['id'];
} else {
    echo "Project adding failed.";
    die();
}

// 3 trigger ci
function createNewFile($owner, $repo, $path, $message, $content, $githubAccessToken, $sha = null)
{
    $url = "https://api.github.com/repos/$owner/$repo/contents/$path";
    $data = [
        'message' => $message,
        'content' => base64_encode($content)
    ];

    // Add sha if it's provided (for updating an existing file)
    if ($sha !== null) {
        $data['sha'] = $sha;
    }

    return makeCurlRequest($url, $githubAccessToken, $data, 'PUT');
}

// Usage
$filePath = time() . '.txt'; // Replace with your desired file name
$commitMessage = 'Add new file'; // Your commit message
$fileContent = time(); // The content of the new file

// If updating an existing file, provide the SHA. Otherwise, omit it or pass null.
$response = createNewFile($owner, $repo, $filePath, $commitMessage, $fileContent, $githubAccessToken, $sha /* or null for a new file */);

echo "File creation response:\n";
if ($debug == true)
    print_r($response);

// Check success of file creation
if (isset($response['content']) && $response['content']['path'] == $filePath) {
    echo "File created successfully.";
} else {
    echo "File creation failed.";
    print_r($response);
    die();
}


$debug = true;



echo "check deployments\n";

function getDeploymentDomain($projectNameVercel, $yourVercelToken)
{
    sleep(10);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.vercel.com/v9/projects/$projectNameVercel",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $yourVercelToken"
        ],
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            $data = json_decode($response, true); // Decode JSON response
            print_r($data);
            if (isset($data['latestDeployments']) && is_array($data['latestDeployments'])) {
                foreach ($data['latestDeployments'] as $deployment) {
                    if (isset($deployment['alias']) && is_array($deployment['alias'])) {
                        foreach ($deployment['alias'] as $alias) {
                            return $alias;
                        }
                    }
                }
            }
        } else {
            echo "HTTP Error Code: " . $httpCode;
        }
    }
    curl_close($ch);

    return null;
}

if (true) {
    $alias = getDeploymentDomain($projectNameVercel, $yourVercelToken);
} else {
    $alias = "https://chate-{$owner}.vercel.app";
}
if (!empty($alias)) {
    echo "Deployment domain: " . $alias;
} else {
    echo "No deployment found.";
    die();
}


die();
?>