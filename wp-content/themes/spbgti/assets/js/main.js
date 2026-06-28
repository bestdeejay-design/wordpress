document.addEventListener('DOMContentLoaded', function() {
  const countdownEl = document.querySelector('.countdown');
  if (countdownEl) {
    const target = countdownEl.dataset.target;
    const targetDate = new Date(target).getTime();
    
    function updateCountdown() {
      const now = Date.now();
      const diff = targetDate - now;
      
      if (diff <= 0) {
        countdownEl.innerHTML = '<p>Юбилей фонда уже наступил!</p>';
        return;
      }
      
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);
      
      document.getElementById('countdown-days').textContent = days;
      document.getElementById('countdown-hours').textContent = hours;
      document.getElementById('countdown-minutes').textContent = minutes;
      document.getElementById('countdown-seconds').textContent = seconds;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
  }

  var hamburger = document.querySelector('.hamburger');
  var nav = document.querySelector('.nav');
  var overlay = document.querySelector('.nav-overlay');

  function closeNav() {
    hamburger.classList.remove('active');
    nav.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
  }

  if (hamburger && nav) {
    hamburger.addEventListener('click', function() {
      if (nav.classList.contains('open')) { closeNav(); }
      else { hamburger.classList.add('active'); nav.classList.add('open'); if (overlay) overlay.classList.add('open'); }
    });
    if (overlay) overlay.addEventListener('click', closeNav);
  }

  var dropdownToggle = document.querySelector('.dropdown-item > a');
  if (dropdownToggle) {
    dropdownToggle.addEventListener('click', function(e) {
      e.preventDefault();
      var menu = this.nextElementSibling;
      if (menu) {
        menu.style.display = menu.style.display === 'block' ? '' : 'block';
      }
    });
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown-item')) {
        document.querySelectorAll('.dropdown-menu').forEach(function(m) {
          m.style.display = '';
        });
      }
    });
  }

  document.querySelectorAll('.nav-links a').forEach(function(link) {
    link.addEventListener('click', closeNav);
  });

  var lightbox = document.querySelector('.lightbox');
  if (lightbox) {
    var lbImg = lightbox.querySelector('.lb-content img');
    var lbClose = lightbox.querySelector('.close');
    var lbPrev = lightbox.querySelector('.lb-prev');
    var lbNext = lightbox.querySelector('.lb-next');
    var lbThumbs = lightbox.querySelector('.lb-thumbs');
    var currentImages = [];
    var currentIndex = 0;

    function openLightbox(images, index) {
      currentImages = images;
      currentIndex = index;
      showImage(index);
      renderThumbs();
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function showImage(index) {
      if (!currentImages[index]) return;
      var img = currentImages[index];
      lbImg.src = img.full;
      lbImg.alt = img.alt || '';
      currentIndex = index;
      lbPrev.style.display = currentImages.length > 1 ? '' : 'none';
      lbNext.style.display = currentImages.length > 1 ? '' : 'none';
      lbThumbs.querySelectorAll('.lb-thumb').forEach(function(t, i) {
        t.classList.toggle('active', i === index);
        t.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
      });
    }

    function renderThumbs() {
      lbThumbs.innerHTML = '';
      currentImages.forEach(function(img, i) {
        var thumb = document.createElement('img');
        thumb.className = 'lb-thumb' + (i === currentIndex ? ' active' : '');
        thumb.src = img.thumb;
        thumb.alt = img.alt || '';
        thumb.addEventListener('click', function() { showImage(i); });
        lbThumbs.appendChild(thumb);
      });
    }

    function closeLightbox() {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
      currentImages = [];
    }

    document.querySelectorAll('.news-gallery img, .gallery-grid img').forEach(function(img) {
      img.addEventListener('click', function(e) {
        var parent = this.closest('.news-gallery') || this.closest('.gallery-grid');
        if (!parent) return;
        var items = parent.querySelectorAll('img');
        var images = [];
        items.forEach(function(item, i) {
          images.push({ full: item.dataset.full || item.src, thumb: item.src, alt: item.alt });
        });
        var index = Array.from(items).indexOf(this);
        openLightbox(images, index);
      });
    });

    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', function() { showImage(currentIndex > 0 ? currentIndex - 1 : currentImages.length - 1); });
    if (lbNext) lbNext.addEventListener('click', function() { showImage(currentIndex < currentImages.length - 1 ? currentIndex + 1 : 0); });
    lightbox.addEventListener('click', function(e) { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', function(e) {
      if (!lightbox.classList.contains('open')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') { if (lbPrev) lbPrev.click(); }
      if (e.key === 'ArrowRight') { if (lbNext) lbNext.click(); }
    });
  }

  /*
  if (window.location.search.includes('debug')) {
    var bar = document.createElement('div');
    bar.id = 'debug-bar';
    bar.style.cssText = 'position:fixed;bottom:0;left:0;right:0;z-index:9999;background:rgba(0,0,0,0.85);color:#0f0;font:12px monospace;padding:6px 12px;display:flex;gap:20px;flex-wrap:wrap;pointer-events:none';
    document.body.appendChild(bar);

    function updateDebug() {
      var w = window.innerWidth;
      var h = window.innerHeight;
      var dpr = window.devicePixelRatio || 1;
      var bp = w >= 1024 ? 'desktop' : w >= 768 ? 'tablet' : 'mobile';
      var headerH = document.querySelector('header')?.offsetHeight || 0;
      var headerW = document.querySelector('.header-inner')?.offsetWidth || 0;
      var navW = document.querySelector('.nav-links')?.offsetWidth || 0;
      bar.innerHTML = [
        'vp:' + w + 'x' + h,
        'dpr:' + dpr.toFixed(1),
        'bp:' + bp,
        'hdr:' + headerW + 'x' + headerH,
        'nav:' + navW + 'px'
      ].join(' | ');
    }
    updateDebug();
    window.addEventListener('resize', updateDebug);
  }
  */
});