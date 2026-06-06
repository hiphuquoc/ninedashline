/**
 * Reveal on scroll — landing footer/copyright cuối trang.
 * Dùng class .visible (khớp welcome + landing.css); tránh rootMargin âm phía dưới.
 */
export function initRevealOnScroll(root = document) {
  const revealEls = root.querySelectorAll('.reveal');
  if (!revealEls.length) return;

  const markVisible = (el) => {
    el.classList.add('visible');
  };

  if (
    window.matchMedia('(prefers-reduced-motion: reduce)').matches
    || !('IntersectionObserver' in window)
  ) {
    revealEls.forEach(markVisible);
    return;
  }

  const revealInViewport = (slackTop = 100, slackBottom = 120) => {
    const vh = window.innerHeight || document.documentElement.clientHeight;
    revealEls.forEach((el) => {
      if (el.classList.contains('visible')) return;
      const rect = el.getBoundingClientRect();
      if (rect.top < vh + slackBottom && rect.bottom > -slackTop) {
        markVisible(el);
      }
    });
  };

  const revealIfPageBottom = () => {
    const doc = document.documentElement;
    const scrollBottom = window.scrollY + window.innerHeight;
    if (scrollBottom < doc.scrollHeight - 32) return;
    revealEls.forEach(markVisible);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      markVisible(entry.target);
      observer.unobserve(entry.target);
    });
  }, {
    threshold: [0, 0.06, 0.12],
    rootMargin: '80px 0px 18% 0px',
  });

  revealEls.forEach((el) => observer.observe(el));

  revealInViewport();
  requestAnimationFrame(() => {
    revealInViewport();
    revealIfPageBottom();
  });

  let scrollRaf = 0;
  const onScrollReveal = () => {
    if (scrollRaf) return;
    scrollRaf = requestAnimationFrame(() => {
      scrollRaf = 0;
      revealInViewport();
      revealIfPageBottom();
    });
  };

  window.addEventListener('scroll', onScrollReveal, { passive: true });
  window.addEventListener('resize', onScrollReveal, { passive: true });
}
