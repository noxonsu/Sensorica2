<div class="sensorica_form-section" id="descriptionSection">
  <p>
    <?php echo esc_html_e('The System Prompt (instruction for GPT) consists of instructions for the bot. Note that users will not be able to see this text. This field is optional.', 'sensorica'); ?>
  </p>
</div>
<button class="sensorica_btn btn-prompt" type="button"><?php echo esc_html_e('Write a Prompt', 'sensorica'); ?></button>
<div class="sensorica_textarea-wrp">
  <textarea class="sensorica_prompt-area" name="<?php echo esc_attr($input); ?>" id="promptArea"><?php echo esc_html_e('You are an AI assistant', 'sensorica'); ?></textarea>
</div>
<div class="sensorica_separator"><span><?php echo esc_html_e('or', 'sensorica'); ?></span></div>
<div class="sensorica_title" id="listOfPromptsTitle"><?php echo esc_html_e('Select one of the prompt examples', 'sensorica'); ?></div>
<ul class="sensorica_accordionOptionsList" id="listOfPrompts"></ul>
<div class="sensorica_more">
  <a href="https://github.com/search?q=GPTs&type=repositories" target="_blank"><?php echo esc_html_e('Get more templates', 'sensorica'); ?></a>
</div>
<div class="sensorica_button-wrap">
  <button type="button" class="sensorica_btn sensorica_btn-prompt" id="resetToOriginal"><?php echo esc_html_e('Reset to Original', 'sensorica'); ?></button>
  <button type="submit" class="sensorica_btn" id="sensorica_apply" data-action="save_prompt"><?php echo esc_html_e('Apply & Next step', 'sensorica'); ?></button>
</div>


<script>

  const prompts = [
    {
      "img": "<?php echo sensorica_URL; ?>static/icons/business.png",
      "title": "AI assistent",
      "description": "The bot will chat the same as ChatGPT",
      "prompt": "You are an AI assistent"
    },
    {
      "img": "<?php echo sensorica_URL; ?>static/icons/resume.png",
      "title": "Pre-sale consultant",
      "description": "Will talk about your product/service with users",
      "prompt": `Act like a knowledgeable and approachable presale consultant who is demonstrating a {your prdoduct name}. 

put here your FAQ and other documents in text 
    `
    },
    {
      "img": "<?php echo sensorica_URL; ?>static/icons/resume.png",
      "title": "Funny Mascot persona",
      "description": "Add funny mascot to the bot",
      "prompt": `Create a 
    `
    },
    {
      "img": "<?php echo sensorica_URL; ?>static/icons/resume.png",
      "title": "Single page content answering",
      "description": "Allow user to talk with a single page (publication) content",
      "prompt": `You answer a questions based on current page content. Content:`
    }
  ];
  const listOfPrompts = document.getElementById("listOfPrompts");
  const listOfPromptsTitle = document.getElementById("listOfPromptsTitle");
  const promptArea = document.getElementById("promptArea");
  const promptHeadline = document.getElementById("promptHeadline");
  const descriptionSection = document.getElementById("descriptionSection");
  const separator = document.getElementsByClassName("sensorica_separator")[0];
  const btnPrompt = document.getElementsByClassName("sensorica_btn btn-prompt")[0];
  const more = document.getElementsByClassName("sensorica_more")[0];
  const apply = document.getElementById("sensorica_apply");

  const createBackButton = () => {
    const button = document.createElement("button");
    button.setAttribute("id", "goBackButton");
    button.setAttribute("class", "go-back-button");
    button.setAttribute("type", "button");
    promptHeadline.appendChild(button);
  };

  const changeHeader = (img, alt, title) => {
    promptHeadline.innerText = title;
    const newImg = document.createElement("img");
    newImg.setAttribute("src", img);
    newImg.setAttribute("alt", alt);
    promptHeadline.appendChild(newImg);
    promptHeadline.classList.add("with-image-icon");
  };

  const changeDescription = (description) => {
    const p = document.createElement("p");
    descriptionSection.innerHTML = null;
    p.innerText = description;
    descriptionSection.appendChild(p);
  };

  const changeSection = (clear = true, title = null) => {
    if (clear) {
      document.body.classList.add("edit-mode");
    } else {
      document.body.classList.remove("edit-mode");
    }
    if (clear) {
      listOfPrompts.innerHTML = null;
      createBackButton();
    } else {
      goBackButton.remove();
    }
    listOfPromptsTitle.innerHTML = title;
    more.style.display = clear ? "none" : "block";
    separator.style.display = clear ? "none" : "block";
  };

  const whiteAPrompt = () => {
    document.body.classList.add("write-a-prompt");
    const icon = document.getElementById("customIcon");
    //icon.style.background = "#2D3A4F";
  };

  btnPrompt.addEventListener("click", function () {
    changeSection();
    whiteAPrompt();
    this.style.display = "none";
    promptHeadline.classList.add("with-image-icon");
  });

  const getLastClickedPromptIndex = () => {
    return Number(sessionStorage.getItem("lastClickedPromptIndex"));
  };

  const handleTextareaChange = () => {
    if (promptArea.value.trim() !== "") {
      apply.removeAttribute("disabled");
      promptArea.parentElement.classList.add("non-empty");
    } else {
      apply.setAttribute("disabled", true);
      promptArea.parentElement.classList.remove("non-empty");
    }
  };

  promptArea.addEventListener("input", handleTextareaChange);

  const toggleCheck = (parent, isToggle = true) => {
    const check = document.createElement("div");
    check.classList.add("check-icon");
    if (isToggle) {
      parent.appendChild(check);
    } else {
      check.remove();
    }
  };

  const saveLastClickedPromptIndex = (index) => {
    sessionStorage.setItem("lastClickedPromptIndex", index);
  };

  const handleReset = () => {
    saveLastClickedPromptIndex(-1);
    promptArea.value = '';
    handleTextareaChange();
  }

  document.getElementById("resetToOriginal").addEventListener("click", handleReset);

  const addCheckToTextareaWrp = () => {
    const textareaWrp = document.querySelector(".textarea-wrp");
    const check = document.createElement("div");
    check.classList.add("check-icon");
    textareaWrp.appendChild(check);
  };

  apply.addEventListener("click", () => {
     addCheckToTextareaWrp();
  });

  const createPrompts = () => {
    prompts.forEach(({ img, alt, title, description, prompt }, index) => {
      const li = document.createElement("li");
      const button = document.createElement("button");
      const image = document.createElement("img");
      const span = document.createElement("span");

      image.setAttribute("src", img);
      image.setAttribute("alt", alt);
      button.setAttribute("data-prompt", prompt);
      button.setAttribute("type", "button");

      button.onclick = function () {
        const prompt = this.dataset.prompt;
        if (prompt) promptArea.value = prompt;
        changeHeader(img, alt, title);
        changeDescription(description);
        changeSection();
        btnPrompt.style.display = "none";
        handleTextareaChange();
        saveLastClickedPromptIndex(index);
      };

      span.append(title);
      button.appendChild(image);
      button.appendChild(span);
      button.append(description);
      li.appendChild(button);
      toggleCheck(li, index === getLastClickedPromptIndex());
      listOfPrompts.appendChild(li);
    });
  };

  createPrompts();

  const handleGoBack = (event) => {
    const goBackButton = event.target;
    if (goBackButton && goBackButton.id === "goBackButton") {
      createPrompts();
      changeSection(false, "Prompt examples");
      promptHeadline.classList.remove("with-image-icon");
      promptHeadline.innerHTML = "Write or Choose Prompt";
      const icon = document.createElement("span");
      icon.setAttribute("id", "customIcon");
      icon.style.background = "#e00094";
      icon.innerHTML = "<svg class='icon'><use xlink:href='#ico-1'></use></svg>";
      promptHeadline.appendChild(icon);
      promptArea.value = null;
      promptArea.parentNode.classList.remove("non-empty");
      document.body.classList.remove("write-a-prompt");
      apply.setAttribute("disabled", true);
      btnPrompt.style.display = "block";
      descriptionSection.innerHTML =
        "<p>The System Prompt consists of instructions for the bot. Note that users will not be able to see this text. This field is optional.</p>";
    }
  };
  promptHeadline.addEventListener("click", handleGoBack);

  function makeRandomString(length) {
    let result = "";
    const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    const charactersLength = characters.length;
    let counter = 0;

    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter++;
    }

    return result;
  }


</script><style>
  #sensorinca_next {display:none}
</style>