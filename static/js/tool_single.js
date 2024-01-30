; (() => {
  const formData = {};
  let firstInit = false;
  const loadFormFromLS = () => {
    try {
      let data = localStorage.getItem('sensorica_bot_params');
      if (data) {
        data = JSON.parse(data);
        console.log("Loaded Data:", data); // Debugging

        Object.keys(data).forEach((dataKey) => {
          if (dataKey && dataToEl[dataKey]) { // Check if key is not empty and exists in dataToEl
            console.log("Key:", dataKey, "Value:", data[dataKey]); // Debugging

            const element = document.querySelector(dataToEl[dataKey].replace("hd_", ""));
            if (element) {
              element.value = data[dataKey];
              console.log("Element found for", dataKey, ": Setting value to", data[dataKey]); // Debugging
            } else {
              console.log("Element not found for", dataKey); // Debugging
            }
          }
        });
      }
    } catch (err) {
      console.log('>> fail load form from LS', err);
    }
  };

  const saveFormToLS = () => {
    localStorage.setItem('sensorica_bot_params', JSON.stringify(formData));
  };

  const inputs = document.querySelectorAll('input, textarea');
  loadFormFromLS();
  inputs.forEach((input) => {
    input.addEventListener('input', () => {
      formData[input.name] = input.value;
      saveFormToLS();
    });
  });

})();