<?php
#load required inputs as array and find for slug 
$tools = json_decode(file_get_contents('tools.json'), true);
if ($_GET['tool']) {
  foreach ($tools as $tool) {

    if ($tool['slug'] == $_GET['tool']) {
      $tool = json_decode(file_get_contents('tools/' . $tool['slug'] . '/info.json'), true);
      break;
    }
  }

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
        <img src="./static/images/chat-gpt-logo.svg" alt="" />
      </div>
      <h2 class="deploymentPageTitle">
        <?php echo ($tool['title']) ? $tool['title'] : "AI tool creation wizard"; ?>
      </h2>
    </header>
    <!--/ header -->
    <main>
      <div class="row">
        <?php
        #hide if no tool selected
        
          ?>
          <div class="sidebar">
            <div class="title">Fill The Form Step By Step:</div>
            <ul class="nav">

              <?php
              if ($_GET['tool']) {
              foreach ($tool['inputs'] as $input => $input_data) {
                echo '<li><a href="javascript:void(0);" class="tab-title" data-title="title1"><svg class="icon"><use xlink:href="#'.$input_data['svg_icon_id'].'"></use></svg>' . $input_data['title'] . '</a></li>';
              }
            } else {
              ?>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-2"></use>
                  </svg>Select tool</a>
              </li>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-3"></use>
                  </svg>Specify the inputs</a>
              </li>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-7"></use>
                  </svg>Fetch the result</a>
              </li>
              <?php
            }
              ?>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-7"></use>
                  </svg>F.A.Q.</a>
              </li>
            </ul>

          </div>
        <?php  ?>
        <!-- content -->
        <div class="content">
          <?php
          if ($_GET['tool']) {
            include("single_tool.php");
          }

          if (!$_GET['tool']) {
            include("select_tool.php");
           } ?>
        </div>
        <!--/ content -->
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