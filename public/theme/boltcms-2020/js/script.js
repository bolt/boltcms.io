window.addEventListener('scroll', function (e) {
   if (window.scrollY === 0) {
      document.getElementById('header').classList.remove('shadow');
   } else {
      document.getElementById('header').classList.add('shadow');
   }
});
