<?php

//detect root path
$rootPath = $_SERVER['DOCUMENT_ROOT'];

include $rootPath . '/connect_github.php';


$githubToken = $_POST['GITHUB_ACCESS_TOKEN'];
$repositoryFullName = 'noxonsu/gpt-crawler';

//check if https://raw.githubusercontent.com/noxonsu/gpt-crawler/main/host-1.json exists
//if exists, then exit
//if not exists, then fork and dispatch workflow
$domain = parse_url($_POST['URL'], PHP_URL_HOST);
$domain = str_replace('www.', '', $domain);

$con = file_get_contents('https://raw.githubusercontent.com/noxonsu/gpt-crawler/main/' . $domain . '-1.json');
if ($con) {
    echo "Good news! This domain is already in the database.";
    echo '<textarea style="width: 100%; height: 100px;">' . $con . '</textarea>';
    
} else {




    $url = $_POST['URL'];

    $match = "**";
    if (isset($_POST['MATCH'])) {
        $match = $_POST['MATCH'];
    }



    // Validate GitHub Access Token
    if (empty($githubToken)) {
        exit("Please provide a GitHub access token.");
    }

    echo "Start";
    echo $repositoryFullName;

    $response = forkRepository($repositoryFullName, $githubToken);

    // Handle Fork Response
    if (!empty($response['error'])) {
        exit("Error: " . $response['error']['message']);
    }

    if (!empty($response['owner']['login'])) {
        echo "Forked successfully. Owner: " . $response['owner']['login'];
        $repositoryOwner = $response['owner']['login'];
        $repositoryName = $response['name'];
    } else {
        print_r($response);
        exit("Forking failed.");
    }

    echo "Dispatching workflow...\n";
    $responce = dispatchGitHubWorkflow($repositoryOwner, $repositoryName, $githubToken, $url, $match);

    echo $responce;

}