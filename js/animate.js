/*
  Author : Rainier Barbacena
  Date : June 19, 2023
*/
let navWindow = document.querySelector('.nav-window');
let uploadPoWrapper = document.querySelector(".upload-po-wrapper");
let uploadPoData = document.querySelector("#upload-po-data");
let fileImport = document.querySelector("#file-import");
let importMsg = document.querySelector('.import-msg');

uploadPoData.addEventListener("click", () => {
  uploadPoWrapper.style.display = 'block';
  navWindow.style.display = 'block';
})
navWindow.addEventListener("click", () => {
  uploadPoWrapper.style.display = 'none';
  navWindow.style.display = 'none';
})
navWindow.style.pointerEvents = 'auto';

fileImport.addEventListener('change', function(event) {
  var name = event.target.files[0].name;
  document.getElementById('file-name').textContent = name;
});

document.getElementById('submit-file').addEventListener('submit', function(event) {
  if (fileImport.value.trim() === '') {
    importMsg.style.display = 'block';
    event.preventDefault();
  } else {
    importMsg.style.display = 'none';
  }
});
