jQuery(document).ready(function () {
    jQuery('.sensorica_magic-prompt').click(function () {
      var userinput = jQuery('#hd_NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT').val();
  
      // Mimic beforeSend functionality
      beforeSend();
  
      // Call the OpenAI API
      OpenaiFetchAPI(userinput, function (response) {
        // Handle the API response
          jQuery('.sensorica_userinput').val(response.choices[0].message.content.replace(/(\r\n|\n|\r|\")/gm, " "));
  
        // Mimic complete functionality
        complete();
      });
    });
  });
  
  function beforeSend() {
    var magicPrompt = jQuery('.sensorica_magic-prompt');
    magicPrompt.removeClass('fade-in').addClass('fade-out');
    setTimeout(function () {
      magicPrompt.html(`<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d = "M16 3C23.17 3 29 8.83 29 16C29 23.17 23.17 29 16 29C8.83 29 3 23.17 3 16C3 8.83 8.83 3 16 3ZM16 1C7.72 1 1 7.72 1 16C1 24.28 7.72 31 16 31C24.28 31 31 24.28 31 16C31 7.72 24.28 1 16 1ZM21 19.5V12.5C21 11.67 20.33 11 19.5 11H12.5C11.67 11 11 11.67 11 12.5V19.5C11 20.33 11.67 21 12.5 21H19.5C20.33 21 21 20.33 21 19.5Z" fill = "#94ABCF" />
                </svg> `); // Replace with your 'stop' icon SVG
    magicPrompt.removeClass('fade-out').addClass('fade-in');
    }, 100);
  }
  
  function complete() { 
    var magicPrompt = jQuery('.sensorica_magic-prompt');
    magicPrompt.removeClass('fade-in').addClass('fade-out');
    setTimeout(function () {
      magicPrompt.html(`<svg width="32" height="32" viewBox="0 0 32 32" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M20.7 11.38C16.13 12.53 12.53 16.12 11.38 20.7C11.28 21.1 10.71 21.1 10.61 20.7C9.47 16.13 5.87 12.53 1.3 11.38C0.9 11.28 0.9 10.71 1.3 10.61C5.87 9.47 9.47 5.87 10.62 1.3C10.72 0.9 11.29 0.9 11.39 1.3C12.54 5.87 16.13 9.47 20.71 10.62C21.11 10.72 21.11 11.29 20.71 11.39L20.7 11.38ZM30.82 24.77C28.08 24.08 25.92 21.92 25.23 19.18C25.17 18.94 24.83 18.94 24.77 19.18C24.08 21.92 21.92 24.08 19.18 24.77C18.94 24.83 18.94 25.17 19.18 25.23C21.92 25.92 24.08 28.08 24.77 30.82C24.83 31.06 25.17 31.06 25.23 30.82C25.92 28.08 28.08 25.92 30.82 25.23C31.06 25.17 31.06 24.83 30.82 24.77Z"
          fill="#94ABCF" />
      </svg>`); // Replace with your '🪄' icon SVG
    magicPrompt.removeClass('fade-out').addClass('fade-in');
    }, 200);
  }
  
  function OpenaiFetchAPI(userInput, callback) {
    console.log("Calling GPT");

    var url = "https://api.openai.com/v1/chat/completions";
    var bearer = 'Bearer ' + document.getElementById('hd_OPENAI_API_KEY').value;
    fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': bearer,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        "messages": [
          {
            "role": "system",
            "content": "Create a title for chat window with agent who has such instruction. For example if instruction like 'You are AI agent' title will be like 'Welcome to AI agetn. Say hello to me'. Return without quotes"
          },
          {
            "role": "user",
            "content": userInput
          }
        ],
        "model": 'gpt-4-turbo-preview',
        "temperature": 1
      })
    }).then(response => {
      if (response.status === 401) {
        alert("Check your OpenAI api key. Server returned 401 unauthorized");
      }
      return response.json();
    }).then(data => {
      console.log(data);
      callback(data);
    }).catch(error => {
      console.log('Something bad happened ' + error);
      complete(); // Call complete even if there is an error
    });
  }