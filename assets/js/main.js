/**
 * Zhanpeng Electronics - Main JavaScript
 * 铜陵展鹏电子有限公司企业官网
 */

(function() {
  'use strict';

  /* ==========================================
     Header Scroll Effect
     ========================================== */
  const header = document.querySelector('.header');
  const backToTop = document.querySelector('.back-to-top');

  function handleScroll() {
    const scrollY = window.scrollY;

    // Header shadow on scroll
    if (scrollY > 50) {
      header?.classList.add('header--scrolled');
    } else {
      header?.classList.remove('header--scrolled');
    }

    // Back to top button
    if (scrollY > 600) {
      backToTop?.classList.add('back-to-top--visible');
    } else {
      backToTop?.classList.remove('back-to-top--visible');
    }
  }

  window.addEventListener('scroll', handleScroll, { passive: true });

  // Back to top click
  backToTop?.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* ==========================================
     Mobile Navigation
     ========================================== */
  const navToggle = document.querySelector('.header__toggle');
  const nav = document.querySelector('.nav');

  navToggle?.addEventListener('click', function() {
    const isOpen = nav.classList.toggle('nav--open');
    this.setAttribute('aria-expanded', isOpen);

    // Animate hamburger
    const spans = this.querySelectorAll('span');
    if (isOpen) {
      spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
      spans[1].style.opacity = '0';
      spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
    } else {
      spans[0].style.transform = '';
      spans[1].style.opacity = '';
      spans[2].style.transform = '';
    }
  });

  // Close nav on link click (mobile)
  document.querySelectorAll('.nav__link').forEach(link => {
    link.addEventListener('click', () => {
      nav?.classList.remove('nav--open');
      navToggle?.setAttribute('aria-expanded', 'false');
      const spans = navToggle?.querySelectorAll('span');
      if (spans) {
        spans[0].style.transform = '';
        spans[1].style.opacity = '';
        spans[2].style.transform = '';
      }
    });
  });

  /* ==========================================
     Scroll Animations (Intersection Observer)
     ========================================== */
  const animateElements = document.querySelectorAll('.fade-in, .stat-item, .category-card, .advantage-card, .product-card, .news-card');

  const observerOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in--visible');

        // Trigger counter animation for stat numbers
        const counter = entry.target.querySelector('.counter');
        if (counter && !counter.dataset.counted) {
          animateCounter(counter);
        }

        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  animateElements.forEach(function(el) {
    observer.observe(el);
  });

  /* ==========================================
     Number Counter Animation
     ========================================== */
  function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    const duration = 2000; // ms
    const step = target / (duration / 16); // ~60fps
    let current = 0;
    const suffix = el.dataset.suffix || '';

    el.dataset.counted = 'true';

    function update() {
      current += step;
      if (current < target) {
        el.textContent = Math.floor(current) + suffix;
        requestAnimationFrame(update);
      } else {
        el.textContent = target + suffix;
      }
    }

    requestAnimationFrame(update);
  }

  /* ==========================================
     Smooth Scroll for Anchor Links
     ========================================== */
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const targetEl = document.querySelector(targetId);
      if (targetEl) {
        e.preventDefault();
        const headerHeight = header ? header.offsetHeight : 72;
        const top = targetEl.getBoundingClientRect().top + window.scrollY - headerHeight;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* ==========================================
     Product Filter (if on product page)
     ========================================== */
  const filterButtons = document.querySelectorAll('.product-filter__btn');
  const productItems = document.querySelectorAll('.product-grid-card[data-category]');

  filterButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      // Update active state
      filterButtons.forEach(b => b.classList.remove('product-filter__btn--active'));
      this.classList.add('product-filter__btn--active');

      const filter = this.dataset.filter;

      productItems.forEach(function(item) {
        if (filter === 'all' || item.dataset.category === filter) {
          item.style.display = '';
          setTimeout(() => { item.style.opacity = '1'; item.style.transform = ''; }, 10);
        } else {
          item.style.opacity = '0';
          item.style.transform = 'scale(0.95)';
          setTimeout(() => { item.style.display = 'none'; }, 300);
        }
      });
    });
  });

  /* ==========================================
     Contact Form Validation
     ========================================== */
  const contactForm = document.querySelector('.form--contact');

  contactForm?.addEventListener('submit', function(e) {
    e.preventDefault();

    let valid = true;
    const requiredFields = this.querySelectorAll('[required]');

    // Reset errors
    this.querySelectorAll('.form__error').forEach(el => el.remove());
    this.querySelectorAll('.form__input--error, .form__textarea--error').forEach(el => {
      el.classList.remove('form__input--error', 'form__textarea--error');
    });

    requiredFields.forEach(function(field) {
      if (!field.value.trim()) {
        valid = false;
        showFieldError(field, '请填写此字段');
      } else if (field.type === 'email' && !isValidEmail(field.value)) {
        valid = false;
        showFieldError(field, '请输入有效的邮箱地址');
      } else if (field.type === 'tel' && field.value && !isValidPhone(field.value)) {
        valid = false;
        showFieldError(field, '请输入有效的电话号码');
      }
    });

    if (valid) {
      // Show success state
      const submitBtn = this.querySelector('.form__submit');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = '提交成功 ✓';
      submitBtn.disabled = true;
      submitBtn.style.background = 'var(--color-success)';

      // Reset after delay
      setTimeout(function() {
        contactForm.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        submitBtn.style.background = '';
      }, 3000);
    }
  });

  function showFieldError(field, message) {
    field.classList.add(field.tagName === 'TEXTAREA' ? 'form__textarea--error' : 'form__input--error');
    const error = document.createElement('span');
    error.className = 'form__error';
    error.textContent = message;
    error.style.cssText = 'color: var(--color-error); font-size: var(--font-size-xs); margin-top: 4px; display: block;';
    field.parentNode.appendChild(error);
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function isValidPhone(phone) {
    return /^[\d\-\+\s()]{7,20}$/.test(phone);
  }

  /* ==========================================
     Lazy Loading Images
     ========================================== */
  if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    }, { rootMargin: '100px' });

    lazyImages.forEach(function(img) {
      imageObserver.observe(img);
    });
  }

  /* ==========================================
     Product Image Gallery (Detail Page)
     ========================================== */
  const mainImage = document.querySelector('.product-detail__main-image img');
  const thumbs = document.querySelectorAll('.product-detail__thumb');

  thumbs.forEach(function(thumb) {
    thumb.addEventListener('click', function() {
      if (mainImage) {
        mainImage.src = this.dataset.full;
        mainImage.alt = this.alt || '';
      }
      thumbs.forEach(t => t.classList.remove('product-detail__thumb--active'));
      this.classList.add('product-detail__thumb--active');
    });
  });

  /* ==========================================
     Datasheet Download Tracking
     ========================================== */
  document.querySelectorAll('.datasheet-download').forEach(function(link) {
    link.addEventListener('click', function() {
      const productName = this.dataset.product || '';
      // Track download if analytics is set up
      if (typeof gtag !== 'undefined') {
        gtag('event', 'download', {
          event_category: 'datasheet',
          event_label: productName
        });
      }
    });
  });

})();
