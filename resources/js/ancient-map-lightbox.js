/**
 * Lightbox bản đồ cổ — cùng logic fit viewport như hoangsa.dev (timeline).
 */
function fitMediaLightboxImage(lightbox, imgEl, footerEls = []) {
  if (!imgEl || !lightbox?.classList.contains('is-open')) return;
  if (!imgEl.naturalWidth) return;

  const styles = getComputedStyle(lightbox);
  const padX = parseFloat(styles.paddingLeft) + parseFloat(styles.paddingRight);
  const padY = parseFloat(styles.paddingTop) + parseFloat(styles.paddingBottom);
  const footerH = footerEls.reduce((sum, el) => sum + (el?.offsetHeight || 0), 0) + 12;
  const vw = window.innerWidth;
  const vh = window.innerHeight;
  const isLandscape = vw >= vh;
  const ratio = imgEl.naturalWidth / imgEl.naturalHeight;
  const maxW = vw - padX;
  const maxH = vh - padY - footerH;

  let w;
  let h;

  if (isLandscape) {
    h = maxH;
    w = h * ratio;
    if (w > maxW) {
      w = maxW;
      h = w / ratio;
    }
  } else {
    w = maxW;
    h = w / ratio;
    if (h > maxH) {
      h = maxH;
      w = h * ratio;
    }
  }

  imgEl.style.width = `${Math.round(w)}px`;
  imgEl.style.height = `${Math.round(h)}px`;
}

function preloadLightboxSrc(src) {
  if (!src) return;
  const probe = new Image();
  probe.decoding = 'async';
  probe.src = src;
}

function resolveLightboxSrc(trigger, cardImg) {
  const ds = trigger?.dataset?.src;
  if (ds) return ds;
  const loaded = cardImg?.currentSrc || cardImg?.src || '';
  if (loaded && !loaded.includes('data:image')) {
    return loaded;
  }
  return '';
}

function openLightboxShell(lightbox) {
  lightbox.classList.add('is-open');
  lightbox.setAttribute('aria-hidden', 'false');
  document.body.classList.add('is-lightboxOpen');
  document.body.style.overflow = 'hidden';
}

function closeLightboxShell(lightbox, imgEl) {
  lightbox.classList.remove('is-open');
  lightbox.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('is-lightboxOpen');
  document.body.style.overflow = '';
  if (imgEl) {
    imgEl.removeAttribute('src');
    imgEl.removeAttribute('style');
    imgEl.onload = null;
  }
}

function bindAncientMapLightbox() {
  const lightboxId = 'ancientMapLightbox';
  const lightbox = document.getElementById(lightboxId);
  if (!lightbox) return;

  if (lightbox.parentElement !== document.body) {
    document.body.appendChild(lightbox);
  }

  const imgEl = document.getElementById('ancientMapLightboxImg');
  const footerEl = document.getElementById('ancientMapLightboxMeta');
  const closeBtn = document.getElementById('ancientMapLightboxClose');
  const prevBtn = document.getElementById('ancientMapLightboxPrev');
  const nextBtn = document.getElementById('ancientMapLightboxNext');
  const links = [...document.querySelectorAll('[data-ancient-map-lightbox]')];
  if (!links.length || !imgEl) return;

  let idx = 0;
  const fit = () => fitMediaLightboxImage(lightbox, imgEl, footerEl ? [footerEl] : []);

  function onShow(link, index, total) {
    const indexEl = document.getElementById('ancientMapLightboxIndex');
    const eraEl = document.getElementById('ancientMapLightboxEra');
    const titleEl = document.getElementById('ancientMapLightboxTitle');
    if (indexEl) {
      indexEl.textContent = `${String(index + 1).padStart(2, '0')} / ${String(total).padStart(2, '0')}`;
    }
    if (eraEl) eraEl.textContent = link.dataset.era || '';
    if (titleEl) titleEl.textContent = link.dataset.title || '';
    imgEl.alt = link.dataset.alt || link.dataset.title || '';
  }

  function show(i) {
    idx = (i + links.length) % links.length;
    const link = links[idx];
    const cardImg = link.querySelector('img');
    const src = resolveLightboxSrc(link, cardImg);

    imgEl.removeAttribute('style');
    imgEl.style.objectPosition = 'center center';
    onShow(link, idx, links.length);

    const applyFit = () => fit();
    imgEl.onload = () => {
      applyFit();
      imgEl.onload = null;
    };
    imgEl.onerror = () => {
      applyFit();
      imgEl.onload = null;
    };
    imgEl.src = src;

    openLightboxShell(lightbox);

    const prev = links[(idx - 1 + links.length) % links.length];
    const next = links[(idx + 1) % links.length];
    preloadLightboxSrc(resolveLightboxSrc(prev, prev.querySelector('img')));
    preloadLightboxSrc(resolveLightboxSrc(next, next.querySelector('img')));

    if (imgEl.complete) applyFit();
  }

  function hide() {
    closeLightboxShell(lightbox, imgEl);
  }

  window.addEventListener('resize', () => {
    if (lightbox.classList.contains('is-open')) fit();
  });

  links.forEach((link, i) => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      show(i);
    });
  });

  closeBtn?.addEventListener('click', hide);
  prevBtn?.addEventListener('click', () => show(idx - 1));
  nextBtn?.addEventListener('click', () => show(idx + 1));
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) hide();
  });
  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') hide();
    if (e.key === 'ArrowLeft') show(idx - 1);
    if (e.key === 'ArrowRight') show(idx + 1);
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bindAncientMapLightbox, { once: true });
} else {
  bindAncientMapLightbox();
}
