<?php
error_reporting(E_ALL);
include 'connect_github.php';
include 'connect_vercel.php';

// log function
function logToFile($filename, $msg)
{
    $fd = fopen($filename, "a");
    $str = "[" . date("Y/m/d h:i:s", time()) . "] " . $msg;
    fwrite($fd, $str . "\n");
    fclose($fd);
}

//create log file
$filename = "./log.txt";
$fd = fopen($filename, "w");
fclose($fd);

logToFile($filename, print_r($_POST, true) ." ". print_r($_GET, true));

logToFile($filename, "Start");

// Environment Variables
$defaultSystemPrompt = $_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'];
$openAiApiKey = $_POST['OPENAI_API_KEY'];
$mainTitle = $_POST['NEXT_PUBLIC_MAIN_TITLE'];
$vercelToken = $_POST['YOUR_VERCEL_TOKEN'];
$githubToken = $_POST['GITHUB_ACCESS_TOKEN'];

// Repository Information
$repositoryFullName = 'noxonsu/chate';
$projectNameVercel = 'chate';

// Validate GitHub Access Token
if (empty($githubToken)) {
    exit("Please provide a GitHub access token.");
}


$response = forkRepository($repositoryFullName, $githubToken);

// Handle Fork Response
if (!empty($response['error'])) {
    exit("Error: " . $response['error']['message']);
}

if (!empty($response['owner']['login'])) {
    echo "Forked successfully. Owner: " . $response['owner']['login'];
    $repositoryOwner = $response['owner']['login'];
    $repositoryName = $response['name'];
    logToFile($filename, "Forked successfully. Owner: " . $response['owner']['login']);
} else {
    exit("Forking failed.");
}

// Define Debug Mode

$deleteResponse = deleteVercelProject($projectNameVercel, $vercelToken);

logToFile($filename, "Delete Response:\n");
logToFile($filename, print_r($deleteResponse, true));

if ($debugMode) {
    echo "Delete Response:\n";
    print_r($deleteResponse);
    
}


$vercelProjectResponse = createVercelProject($projectNameVercel, $vercelToken, $defaultSystemPrompt, $openAiApiKey, $mainTitle, $repositoryOwner, $repositoryName);

if (!empty($vercelProjectResponse['error'])) {
    exit("Error creating Vercel project: " . $vercelProjectResponse['error']['message']);
}

if ($debugMode) {
    echo "Vercel Project creation Response:\n";
    print_r($vercelProjectResponse);
}

logToFile($filename, "Vercel Project creation Response:\n");
logToFile($filename, print_r($vercelProjectResponse, true));

// Usage Example
$filePath = time() . '.txt'; // Replace with your desired file name
$commitMessage = 'Add new file'; // Your commit message
$fileContent = 'Sample content for the file'; // The content of the new file

$newFileResponse = createNewGitHubFile($repositoryOwner, $repositoryName, $filePath, $commitMessage, $fileContent, $githubToken);

if ($debugMode) {
    echo "New File Response:\n";
    print_r($newFileResponse);
}

if (!isset($newFileResponse['content']) || $newFileResponse['content']['path'] != $filePath) {
    exit("Failed to create new file in GitHub repository.");
}

// Check Deployment Status on Vercel


$deploymentData = checkVercelDeployment($projectNameVercel, $vercelToken);

if ($debugMode) {
    echo "Deployment Data:\n";
    print_r($deploymentData);
}

// Display Deployment URL
$deploymentUrl = $deploymentData['alias'][0] ?? "https://$projectNameVercel-$repositoryOwner.vercel.app";
echo "Deployment URL: $deploymentUrl";

// End of the Script
$iframeCode = "<textarea readonly style='width:100%; height:120px;'>";
$iframeCode .= "<iframe style='border:0; min-width:320px; min-height:600px' src='" . $deploymentUrl . "' class='' id='onoutiframe' width='100%' height='100%'></iframe>\n";
$iframeCode .= "</textarea>";
echo $iframeCode;
