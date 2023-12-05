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

