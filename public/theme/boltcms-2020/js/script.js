window.addEventListener('scroll', function (e) {
   if (window.scrollY <= 0) {
      document.getElementById('header').classList.remove('shadow');
   } else {
      document.getElementById('header').classList.add('shadow');
   }
});

document.addEventListener('DOMContentLoaded', function(){
   document.querySelector('.try-it').addEventListener('click', function(){
      _paq.push(['trackEvent', 'acquisition', 'install', 'try-it', window.location.pathname]);
   });

   document.querySelector('.install-bolt').addEventListener('click', function(){
      _paq.push(['trackEvent', 'acquisition', 'install', 'install-bolt-cta', window.location.pathname]);
   });
});
