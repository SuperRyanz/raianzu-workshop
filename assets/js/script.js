
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('a[href^="#"]').forEach(anchor=>{
    anchor.addEventListener('click', function(e){
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Back to top button
  var btn = document.getElementById('backToTop');
  if (btn) {
    window.addEventListener('scroll', function(){
      if (window.scrollY > 300) btn.classList.add('visible'); else btn.classList.remove('visible');
    });
    btn.addEventListener('click', function(){ window.scrollTo({top:0, behavior:'smooth'}); });
  }

  // Lazy load images using loading attr fallback (modern browsers handle loading=lazy)
});
