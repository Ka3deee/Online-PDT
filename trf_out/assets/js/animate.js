window.addEventListener('load', function () {
    var preloader = document.getElementById('preloader');
    
    if (preloader) {
        preloader.style.transition = 'opacity 0.5s ease';
        preloader.style.opacity = '0';

        preloader.addEventListener('transitionend', function () {
            preloader.remove();
        });
    }
});
