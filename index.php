<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>deploy Telegram ChatGPT bot</title>
    <link rel="stylesheet" href="./static/new.css" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="theme-color" content="#5f48b0" />
    <meta name="description" content="Deploy your own ChatGPT bot on Telegram" />
    <meta
      name="keywords"
      content="chatgpt bot, telegram bot, chatgpt telegram, ai bot, ai app, no-code telegram bot, earn on ai, onout"
    />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="wrapper">
      <!-- header -->
      <header>
        <div class="header-logo">
          <img src="./static/images/chat-gpt-logo.svg" alt="" />
        </div>
        <h2 class="deploymentPageTitle">AI tool creation wizard</h2>
      </header>
      <!--/ header -->
      <?php 
      /*
      tools.json
      [
  {
    "title": "aigram",
    "slug": "aigram",
    "img": "/static/images/chat-gpt-logo.svg",
    "description": "ChatGPT integration for Telegram",
    "introduction": "Welcome to the ChatGPT Telegram Bot setup. I'll guide you through the steps to deploy your bot.",
    "inputs": {
      "cf_account_id": {
        "description": "CloudFlare account ID. Log into your Cloudflare account and copy the ID from the browser URL.",
        "help_image": "https://telegram.onout.org/static/images/where-account-id.png",
        "error_message": "Please enter a valid Cloudflare account ID.",
        "required": true
      },
      "cf_api_key": {
        "description": "CloudFlare API Key. Follow the steps on Cloudflare to create a token for Cloudflare Workers.",
        "help_image": [
          "https://telegram.onout.org/static/images/cf-select-create-token.png",
          "https://telegram.onout.org/static/images/cf-select-token-for-workers.png",
          "https://telegram.onout.org/static/images/cf-select-all-accounts-and-zones-for-token.png"
        ],
        "error_message": "Please enter a valid Cloudflare API key.",
        "required": true
      },
      "openai_sk": {
        "description": "OpenAI API key. Find this in your OpenAI account under API settings.",
        "error_message": "Please enter a valid OpenAI API key that starts with 'sk-...'.",
        "required": true
      },
      "tg_token": {
        "description": "Telegram Bot API token. Get this from the BotFather on Telegram.",
        "help_text": "To create a new Telegram bot, message @BotFather on Telegram and use the /newbot command.",
        "error_message": "Please enter a valid Telegram Bot API token.",
        "required": true
      }
    },
    "system_prompt": {
      "description": "Optional custom system prompt for your bot. You can set one if you like.",
      "confirmation": "Would you like to set a custom system prompt? (Yes/No)",
      "required": false
    },
    "monetization": {
      "description": "Optional monetization settings for your bot. Set a number of free messages and an activation code.",
      "confirmation": "Do you wish to monetize your bot? (Yes/No)",
      "required": false
    },
    "deployment_confirmation": "Do you want to proceed with deploying your Telegram bot with the provided details? (Yes/No)",
    "completion_message": "Your Telegram bot is now deployed! You can access it at [Bot URL]. For any changes, you can restart this setup process.",
    "required": true
  }]
      */
      ?>
      <main>
        <div class="row">
        <?php 
            #hide if no tool selected
            if ($_GET['tool']) {
            ?>
          <div class="sidebar">
            <div class="title">Fill The Form Step By Step:</div>
            <ul class="nav">
              
              <?php 
              #load required inputs as array and find for slug 
              $tools = json_decode(file_get_contents('tools.json'), true);
              foreach ($tools as $tool) {
                if ($tool['slug'] == $_GET['tool']) {
                  $tool = json_decode(file_get_contents('tools/'.$tool['slug'].'/info.json'), true);
                  break;
                }
              }
              foreach ($tool['inputs'] as $input => $input_data) {
                echo '<li><a href="javascript:void(0);" class="tab-title" data-title="title1"><svg class="icon"><use xlink:href="#ico-1"></use></svg>'.$input_data['description'].'</a></li>';
              }
              
              ?>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"
                  ><svg class="icon"><use xlink:href="#ico-7"></use></svg>F.A.Q.</a
                >
              </li>
            </ul>
            
          </div>
          <?php } ?>
          <!-- content -->
          <div class="content">
            <?php 
            if ($_GET['tool']) {
             
              ?>
              <div class="tab-content active" data-content="title3">
              <div class="headline">
              <span style="background: #fff"><svg class="icon" style="fill: #f6821f">
              <use xlink:href="#ico-3"></use></svg></span>CloudFlare API Key
              </div>
              <?php 
               foreach ($tool['inputs'] as $input => $input_data) { 
                
                ?>
              <div class="form-section">
              <label for="cloudflareAccountID">
              <?php echo $input_data['description']; ?>
              </label>
              <input type="text" name="cf_account_id" placeholder="7ff3w2b012834b34e5a0410261a35dak" id="cloudflareAccountID" required="" class="form-control">
              <details>
              <summary>
              How to get ?
              <div class="chevron">
              <svg class="icon">
              <use xlink:href="#chevron"></use>
              </svg>
              </div>
              </summary>
              <div class="summary-content">
              <ol>
              <?php 
              
              foreach ($input_data['help_image'] as $image) {
                  echo '<li>'.$image.'</li>';
              }
              
              ?>
              </ol>
              </div>
              </details>
              </div>
              <?php } ?>
              <button class="btn" data-action="save_cloudflare">Apply &amp; Next Page</button>
              </div>
              <?php
            }
            if (!$_GET['tool']) {
            ?>
            <form method="post" action="bot/deploy">
              <div class="tab-container">
                <!-- tab 5 -->
                <div class="tab-content active" data-content="title5">
                  <div class="headline" id="promptHeadline">
                    <span id="customIcon" style="background: #e00094"
                      ><svg class="icon">
                        <use xlink:href="#ico-1"></use>
                      </svg> </span
                    >Select the tool template to deploy
                  </div>
                  <div class="form-section" id="descriptionSection">
                    <p>
                      Select the tool template to deploy
                    </p>
                  </div>
                  
                  
                  <ul class="accordionOptionsList" id="listOfPrompts">
                    <?php
                    //load toola from tools.json and show them <li><button data-prompt="undefined" type="button"><img src="static/icons/business.png" alt="undefined"><span>Chat on a Website</span>Interactive mascot for websites</button></li>
                    
                    $tools = json_decode(file_get_contents('tools.json'), true);
                    foreach ($tools as $tool) {
                      //on click go to ?tool=slug
                      
                      echo '<li><button onclick="window.location=\'?tool='.$tool['slug'].'\'" data-prompt="'.$tool['slug'].'" type="button"><img src="'.$tool['img'].'" alt="'.$tool['slug'].'"><span>'.$tool['title'].'</span>'.$tool['description'].'</button></li>';
                    }
                    ?>
                  </ul>
                  
                  <ul class="accordionOptionsList" id="listOfPrompts"></ul>
                  <div class="more">
                    <a href="#">Get more templates</a>
                  </div>
                  <div class="button-wrap">
                    <button type="button" class="btn btn-prompt" id="resetToOriginal">Reset to Original</button
                    ><button type="button" class="btn" id="apply" data-action="save_prompt">Apply & Next step</button>
                  </div>
                </div>
                <!--/ tab 5 -->
              </div>
            </form>
            <?php } ?>
          </div>
          <!--/ content -->
        </div>
        <!-- footer -->
        <footer>
          <div class="supportWrapper">
            Support:
            <a href="mailto:support@onout.org" target="_blank" rel="noreferrer"
              ><svg class="icon"><use xlink:href="#ico-eml"></use></svg>Email
            </a>
            or
            <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer"
              ><svg class="icon"><use xlink:href="#ico-tlg"></use></svg>Telegram
            </a>
          </div>
        </footer>
        <!--/ footer -->
      </main>
    </div>
   
  </body>
</html>
   
 
   