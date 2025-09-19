var navWindow = document.querySelector('.nav-window');
var uploadPoWrapper = document.querySelector(".upload-wrapper");

function openUpload() {
  if (isUserSet == false) { 
    alert('User is required. Please set a user first.');
    return 0;
  }
  document.querySelector(".upload-wrapper").style.display = 'block';
  navWindow.style.display = 'block';
}

function closeUpload() {
  uploadPoWrapper.style.display = 'none';
  navWindow.style.display = 'none';
  navWindow.style.pointerEvents = 'auto';
}

function importFile(event) {
  var fileImport = document.getElementById('file-import');
  var warningMsg = document.getElementById('warning-msg');
  if (fileImport.value.trim() === '' || fileImport.value.trim() === null) {
      warningMsg.style.display = 'flex';
      document.getElementById('file-name').textContent = "";
      return 0;
  } else {
      var name = event.target.files[0].name;
      document.getElementById('file-name').textContent = name;
      warningMsg.style.display = 'none';
  }
} 

function toggleModal() {
  const modal = document.getElementById('modal');

  if (modal.style.display === 'block') {

    modal.style.transition = 'opacity 0.5s';
    modal.style.opacity = '0';
    setTimeout(function () {
      modal.style.display = 'none';
    }, 300);

  } else {

    modal.style.display = 'block';
    setTimeout(function () {
      modal.style.transition = 'opacity 0.5s';
      modal.style.opacity = '1';
    }, 0);
  }
}
  
window.addEventListener('load', function () {
  
  var preloader = document.getElementById('preloader');
  
  if (preloader) {
      preloader.style.transition = 'opacity 0.5s ease';
      preloader.style.opacity = '0';


      preloader.addEventListener('transitionend', function () {
          preloader.remove();
      });
  }

  var trf = sessionStorage.getItem('trfOutList');
  var trfOutList = document.getElementById("trf-out-list");
  if (trfOutList) {
      trfOutList.value = trf;
  }
});
