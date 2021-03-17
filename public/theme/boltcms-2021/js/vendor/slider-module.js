type = "module"
let slider = tns({
    container: '.my-slider',
    "responsive": {
        "350": {
            "items": 1,
        },
        "500": {
            "items": 3
        }
    },
    controls: false,
    navPosition: 'bottom',
    autoWidth: true,
    center: true,
    gutter: 80,
    autoplay: false
});

window.addEventListener('load', function () {
    baguetteBox.run('.zoom-images');
});
