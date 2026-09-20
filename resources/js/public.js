// Dunco School Management System - Public Website Interactivity

(function () {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ─── 1. Scroll Animations ──────────────────────────────────────────────────

  function initScrollAnimations() {
    if (reducedMotion) {
      document.querySelectorAll('.section-animate, .stagger-child, [data-animate]').forEach(el => {
        el.classList.add('animate-in');
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            observer.unobserve(entry.target);
          }
        });
      },
      { rootMargin: '-50px 0px' }
    );

    document.querySelectorAll('.section-animate, .stagger-child, [data-animate]').forEach((el) => {
      observer.observe(el);
    });
  }

  // ─── 2. Counter Animation ──────────────────────────────────────────────────

  function animateCounter(element, target, duration) {
    if (reducedMotion) {
      element.textContent = formatCounterValue(target, element.dataset.suffix || '');
      return;
    }

    const suffix = element.dataset.suffix || '';
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.round(eased * target);

      element.textContent = formatCounterValue(current, suffix);

      if (progress < 1) {
        requestAnimationFrame(update);
      }
    }

    requestAnimationFrame(update);
  }

  function formatCounterValue(value, suffix) {
    return value.toLocaleString('en-US') + suffix;
  }

  function initCounterAnimation() {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const el = entry.target;
            const target = parseInt(el.dataset.target, 10) || 0;
            const duration = parseInt(el.dataset.duration, 10) || 2000;
            animateCounter(el, target, duration);
            observer.unobserve(el);
          }
        });
      },
      { rootMargin: '-50px 0px' }
    );

    counters.forEach((el) => observer.observe(el));
  }

  // ─── 3. Smooth Scroll Navigation ───────────────────────────────────────────

  function initSmoothScroll() {
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href^="#"]');
      if (!link) return;

      const hash = link.getAttribute('href');
      if (hash === '#' || hash === '#0') return;

      const target = document.querySelector(hash);
      if (!target) return;

      e.preventDefault();

      const navbarHeight = 72;
      const top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

      window.scrollTo({ top, behavior: reducedMotion ? 'auto' : 'smooth' });

      history.pushState(null, '', hash);
    });
  }

  // ─── 4. Mobile Navigation Menu ─────────────────────────────────────────────

  function initMobileMenu() {
    const toggle = document.querySelector('.mobile-menu-toggle, .hamburger, [data-mobile-toggle]');
    const menu = document.querySelector('.mobile-menu, .nav-menu, [data-mobile-menu]');
    if (!toggle || !menu) return;

    function openMenu() {
      toggle.setAttribute('aria-expanded', 'true');
      menu.setAttribute('aria-hidden', 'false');
      menu.classList.add('is-open');
      toggle.classList.add('is-active');
      document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
      toggle.setAttribute('aria-expanded', 'false');
      menu.setAttribute('aria-hidden', 'true');
      menu.classList.remove('is-open');
      toggle.classList.remove('is-active');
      document.body.style.overflow = '';
    }

    function toggleMenu() {
      const isOpen = menu.classList.contains('is-open');
      isOpen ? closeMenu() : openMenu();
    }

    toggle.addEventListener('click', toggleMenu);

    menu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menu.classList.contains('is-open')) {
        closeMenu();
        toggle.focus();
      }
    });

    closeMenu();
  }

  // ─── 5. Navbar Scroll Effect ───────────────────────────────────────────────

  function initNavbarScroll() {
    const navbar = document.querySelector('.navbar, nav, [data-navbar]');
    if (!navbar) return;

    let ticking = false;

    function onScroll() {
      if (!ticking) {
        requestAnimationFrame(() => {
          if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
          } else {
            navbar.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ─── 6. FAQ Accordion ──────────────────────────────────────────────────────

  function initAccordion() {
    const items = document.querySelectorAll('.accordion-item');
    if (!items.length) return;

    function closeAll(except) {
      items.forEach((item) => {
        if (item !== except && item.classList.contains('active')) {
          item.classList.remove('active');
          const content = item.querySelector('.accordion-content, .accordion-body');
          if (content) content.style.maxHeight = null;
        }
      });
    }

    function toggleItem(item) {
      const content = item.querySelector('.accordion-content, .accordion-body');
      if (!content) return;

      const isOpen = item.classList.contains('active');

      if (isOpen) {
        item.classList.remove('active');
        content.style.maxHeight = null;
      } else {
        closeAll(item);
        item.classList.add('active');
        content.style.maxHeight = content.scrollHeight + 'px';
      }
    }

    items.forEach((item) => {
      const trigger = item.querySelector('.accordion-header, .accordion-trigger, button');

      if (trigger) {
        trigger.addEventListener('click', () => toggleItem(item));

        trigger.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleItem(item);
          }
          if (e.key === 'Escape') {
            item.classList.remove('active');
            const content = item.querySelector('.accordion-content, .accordion-body');
            if (content) content.style.maxHeight = null;
          }
        });
      } else {
        item.addEventListener('click', () => toggleItem(item));
      }
    });
  }

  // ─── 7. Testimonial Slider ─────────────────────────────────────────────────

  function initTestimonialSlider() {
    const slider = document.querySelector('.testimonial-slider, [data-testimonial-slider]');
    if (!slider) return;

    const testimonials = slider.querySelectorAll('.testimonial-item, .testimonial-slide');
    const dots = slider.querySelectorAll('.testimonial-dot, [data-slide-dot]');
    if (testimonials.length < 2) return;

    let currentIndex = 0;
    let autoplayTimer = null;
    const interval = 5000;

    function goTo(index) {
      testimonials[currentIndex].classList.remove('is-active');
      if (dots[currentIndex]) dots[currentIndex].classList.remove('is-active');

      currentIndex = (index + testimonials.length) % testimonials.length;

      testimonials[currentIndex].classList.add('is-active');
      if (dots[currentIndex]) dots[currentIndex].classList.add('is-active');
    }

    function startAutoplay() {
      stopAutoplay();
      autoplayTimer = setInterval(() => goTo(currentIndex + 1), interval);
    }

    function stopAutoplay() {
      if (autoplayTimer) clearInterval(autoplayTimer);
    }

    testimonials[0].classList.add('is-active');
    if (dots[0]) dots[0].classList.add('is-active');

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        goTo(i);
        startAutoplay();
      });
    });

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    if (!reducedMotion) startAutoplay();
  }

  // ─── 8. Page Transitions ──────────────────────────────────────────────────

  function initPageTransitions() {
    if (reducedMotion) return;

    document.body.classList.add('page-enter');

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        document.body.classList.add('page-enter-active');
        document.body.classList.remove('page-enter');
        setTimeout(() => document.body.classList.remove('page-enter-active'), 300);
      });
    });

    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;

      const href = link.getAttribute('href');
      if (
        !href ||
        href.startsWith('#') ||
        href.startsWith('javascript:') ||
        link.target === '_blank' ||
        link.hostname !== window.location.hostname
      ) return;

      e.preventDefault();
      document.body.classList.add('page-exit');

      setTimeout(() => {
        window.location.href = href;
      }, 250);
    });
  }

  // ─── 9. Typing / Rotating Text Effect ──────────────────────────────────────

  function initTypingEffect() {
    const el = document.querySelector('[data-typing], .typing-text');
    if (!el || reducedMotion) return;

    const words = (el.dataset.words || el.textContent).split('|');
    el.textContent = '';

    const cursor = document.createElement('span');
    cursor.className = 'typing-cursor';
    cursor.textContent = '|';
    cursor.style.animation = 'blink 1s step-end infinite';

    const textSpan = document.createElement('span');
    textSpan.className = 'typing-text-content';

    el.appendChild(textSpan);
    el.appendChild(cursor);

    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let timeout = null;

    function type() {
      const currentWord = words[wordIndex];

      if (isDeleting) {
        charIndex--;
        textSpan.textContent = currentWord.substring(0, charIndex);
      } else {
        charIndex++;
        textSpan.textContent = currentWord.substring(0, charIndex);
      }

      let delay = isDeleting ? 50 : 100;

      if (!isDeleting && charIndex === currentWord.length) {
        delay = 2000;
        isDeleting = true;
      } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        delay = 500;
      }

      timeout = setTimeout(type, delay);
    }

    type();

    const style = document.createElement('style');
    style.textContent = '@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}';
    document.head.appendChild(style);
  }

  // ─── 10. Image Lazy Loading ────────────────────────────────────────────────

  function initLazyLoad() {
    const images = document.querySelectorAll('img[data-src], img[data-lazy]');
    if (!images.length) return;

    if ('loading' in HTMLImageElement.prototype) {
      images.forEach((img) => {
        img.src = img.dataset.src || img.dataset.lazy;
        img.removeAttribute('data-src');
        img.removeAttribute('data-lazy');
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const img = entry.target;
            const src = img.dataset.src || img.dataset.lazy;

            img.classList.add('lazy-loading');

            img.onload = () => {
              img.classList.remove('lazy-loading');
              img.classList.add('lazy-loaded');
            };

            img.onerror = () => {
              img.classList.remove('lazy-loading');
              img.classList.add('lazy-error');
              img.alt = img.alt || 'Image failed to load';
            };

            img.src = src;
            img.removeAttribute('data-src');
            img.removeAttribute('data-lazy');
            observer.unobserve(img);
          }
        });
      },
      { rootMargin: '200px 0px' }
    );

    images.forEach((img) => observer.observe(img));
  }

  // ─── 11. Form Enhancements ─────────────────────────────────────────────────

  function initForms() {
    initFloatingLabels();
    initFormValidation();
    initFormSubmission();
  }

  function initFloatingLabels() {
    document.querySelectorAll('.form-group, .input-group').forEach((group) => {
      const input = group.querySelector('input, textarea');
      const label = group.querySelector('label');
      if (!input || !label) return;

      function checkValue() {
        if (input.value || input.placeholder) {
          group.classList.add('has-value');
        } else {
          group.classList.remove('has-value');
        }
      }

      input.addEventListener('focus', () => group.classList.add('is-focused'));
      input.addEventListener('blur', () => {
        group.classList.remove('is-focused');
        checkValue();
      });
      input.addEventListener('input', checkValue);

      checkValue();
    });
  }

  function initFormValidation() {
    document.querySelectorAll('form[data-validate], form.needs-validation').forEach((form) => {
      const fields = form.querySelectorAll('[required], [data-required], [pattern]');

      fields.forEach((field) => {
        field.addEventListener('blur', () => validateField(field));
        field.addEventListener('input', () => {
          if (field.classList.contains('is-invalid')) {
            validateField(field);
          }
        });
      });

      form.addEventListener('submit', (e) => {
        let valid = true;
        fields.forEach((field) => {
          if (!validateField(field)) valid = false;
        });

        if (!valid) {
          e.preventDefault();
          e.stopPropagation();
        }

        form.classList.add('was-validated');
      });
    });
  }

  function validateField(field) {
    let isValid = true;

    if (field.required && !field.value.trim()) {
      isValid = false;
    }

    if (field.pattern && field.value) {
      const regex = new RegExp(field.pattern);
      isValid = regex.test(field.value);
    }

    if (field.type === 'email' && field.value) {
      isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    }

    if (isValid) {
      field.classList.remove('is-invalid');
      field.classList.add('is-valid');
    } else {
      field.classList.remove('is-valid');
      field.classList.add('is-invalid');
    }

    return isValid;
  }

  function initFormSubmission() {
    document.querySelectorAll('form[data-ajax], form.ajax-form').forEach((form) => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn ? submitBtn.textContent : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = submitBtn.dataset.loadingText || 'Sending...';
          submitBtn.classList.add('is-loading');
        }

        try {
          const formData = new FormData(form);
          const response = await fetch(form.action || window.location.href, {
            method: form.method || 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
          });

          const data = await response.json().catch(() => ({ message: 'Done!' }));

          if (response.ok) {
            showToast(data.message || 'Success!', 'success');
            form.reset();
            form.querySelectorAll('.is-valid, .is-invalid').forEach((el) => {
              el.classList.remove('is-valid', 'is-invalid');
            });
          } else {
            showToast(data.message || 'Something went wrong.', 'error');
          }
        } catch {
          showToast('Network error. Please try again.', 'error');
        } finally {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            submitBtn.classList.remove('is-loading');
          }
        }
      });
    });
  }

  function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.setAttribute('role', 'alert');
    toast.textContent = message;

    Object.assign(toast.style, {
      position: 'fixed',
      bottom: '24px',
      right: '24px',
      padding: '12px 24px',
      borderRadius: '8px',
      color: '#fff',
      fontWeight: '500',
      zIndex: '10000',
      transform: 'translateY(20px)',
      opacity: '0',
      transition: 'all 0.3s ease',
      backgroundColor: type === 'success' ? '#10b981' : '#ef4444',
    });

    document.body.appendChild(toast);

    requestAnimationFrame(() => {
      toast.style.transform = 'translateY(0)';
      toast.style.opacity = '1';
    });

    setTimeout(() => {
      toast.style.transform = 'translateY(20px)';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }

  // ─── 12. Initialization ────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', () => {
    const initFunctions = [
      initScrollAnimations,
      initCounterAnimation,
      initSmoothScroll,
      initMobileMenu,
      initNavbarScroll,
      initAccordion,
      initTestimonialSlider,
      initPageTransitions,
      initTypingEffect,
      initLazyLoad,
      initForms,
    ];

    initFunctions.forEach((fn) => {
      try {
        fn();
      } catch (err) {
        console.error(`Error initializing ${fn.name}:`, err);
      }
    });
  });

})();
