<?php
#load required inputs as array and find for slug 
function getToolInfo($toolSlug)
{
  $tools = json_decode(file_get_contents('tools.json'), true);
  foreach ($tools as $tool) {
    if ($tool['slug'] == $toolSlug) {
      $tool = json_decode(file_get_contents('tools/' . $tool['slug'] . '/info.json'), true);
      break;
    }
  }
  return $tool;
}

if ($_GET['tool']) {
  $tool = getToolInfo($_GET['tool']);
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>AI wizard</title>
  <link rel="stylesheet" href="./static/new.css" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="theme-color" content="#5f48b0" />
  <meta name="description" content="Deploy your own ChatGPT bot on Telegram" />
  <meta name="keywords"
    content="chatgpt bot, telegram bot, chatgpt telegram, ai bot, ai app, no-code telegram bot, earn on ai, onout" />
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<body>
  <div class="wrapper">
    <!-- header -->
    <header>
      <div class="header-logo">
      <img src="<?php echo ($tool['img']) ? $tool['img'] : ' ./static/images/chat-gpt-logo.svg'; ?>" alt="" />
      </div>
      <h2 class="deploymentPageTitle">
        <?php echo ($tool['title']) ? $tool['title'] : "AI tool creation wizard"; ?>
      </h2>
    </header>
    <!--/ header -->
    <main>
      <div class="row">
        <?php
        if ($_GET['tool']) {
          include("tool_single.php");
        }

        if (!$_GET['tool']) {
          include("select_tool.php");
        } ?>
      </div>
      <!-- footer -->
      <footer>
        <div class="supportWrapper">
          Support:
          <a href="mailto:support@onout.org" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-eml"></use>
            </svg>Email
          </a>
          or
          <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-tlg"></use>
            </svg>Telegram
          </a>
        </div>
        <div class="supportWrapper">
          Community: <a href="https://discord.com/channels/898545581591506975/1078964362783506442"
            target="_blank">Discord</a> or <a href="https://t.me/sensorica2" target="_blank">Telegram</a>
        </div>
      </footer>
      <!--/ footer -->
    </main>
  </div>
  <!-- import svg -->
  <?php
  //import svg static/icons/icons.svg
  echo file_get_contents('static/icons/icons.svg');
  ?>

</body>

</html>