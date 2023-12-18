<div class="form-section" id="descriptionSection">
<p>
The System Prompt (instruction for GPT) consists of instructions for the bot. Note that users will not be able to see this text. This field is optional.
</p>
</div>
<button class="btn btn-prompt" type="button">Write a Prompt</button>
<div class="textarea-wrp">
<textarea class="prompt-area" name="<?php echo $input; ?>" id="promptArea">You are an AI assistent</textarea>
</div>
<div class="separator"><span>or</span></div>
<div class="title" id="listOfPromptsTitle">Choose Available Prompt Templates</div>
<ul class="accordionOptionsList" id="listOfPrompts"></ul>
<div class="more">
<a href="https://github.com/search?q=GPTs&type=repositories" target=_blank>Get more templates</a>
</div>
<div class="button-wrap">
<button type="button" class="btn btn-prompt" id="resetToOriginal">Reset to Original</button
                    ><button type="submit" class="btn" id="apply" data-action="save_prompt">Apply & Next step</button>
</div>

<script>

const prompts = [
  {
    "img": "static/icons/business.png",
    "title": "AI assistent",
    "description": "The bot will chat the same as ChatGPT",
    "prompt": "You are an AI assistent"
}, 
  {
    "img": "static/icons/resume.png",
    "title": "Talk to website's database",
    "description": "Add FAQ and other materials to the bot",
    "prompt": `Act like a Mascot. You are the beloved mascot of {ENTER YOUR COMPANY OR COMMUNITY THEME}. You're tasked with cheering up the community, keeping spirits high, and answering any questions they might have. Converse as if you're interacting with community members throughout an event or gathering. 
    
Your database
put here your FAQ and other documents in text 
    `
  }, 
  {
    "img": "static/icons/resume.png",
    "title": "Video Script Generator",
    "description": "Create  TikTok Video Script for a topic you want.",
    "prompt": `You are an expert in the field of topic, who wants to create engaging and informative content for TikTok. Your audience consists of young, inquisitive users who are eager to learn more about this subject. Write a TikTok video script that explains the topic in a concise yet comprehensive manner. The script should be crafted in a way that it grabs the viewer’s attention in the first few seconds, maintains the interest throughout, and ends with a call to action for further engagement. 

#Instructions
It should have a casual, conversational tone, utilize relevant TikTok trends if applicable, and should not exceed a duration of 15sec, 30sec or 60 sec. Moreover, include visual cues to illustrate key points, assuming the video will be a mix of direct-to-camera parts and visual overlays.
Write with markdown format. 

#Script Structure
**[time]**
*[visual, audio, speaker descriptions of video scenes]* 
"speaker text"

#Script Structure Simple Example
**[0:00-0:00]**
*[Speaker on screen, excited]* 
"text"`
  }
];
const listOfPrompts = document.getElementById("listOfPrompts");
const listOfPromptsTitle = document.getElementById("listOfPromptsTitle");
const promptArea = document.getElementById("promptArea");
const promptHeadline = document.getElementById("promptHeadline");
const descriptionSection = document.getElementById("descriptionSection");
const separator = document.getElementsByClassName("separator")[0];
const btnPrompt = document.getElementsByClassName("btn-prompt")[0];
const more = document.getElementsByClassName("more")[0];
const apply = document.getElementById("apply");

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
  icon.style.background = "#2D3A4F";
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
  // addCheckToTextareaWrp();
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
    changeSection(false, "Choose Available Prompt Templates");
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


</script>