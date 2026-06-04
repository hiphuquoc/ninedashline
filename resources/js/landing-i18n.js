function readLandingConfig() {
  const el = document.getElementById('landing-config');
  if (!el?.textContent) return {};
  try {
    return JSON.parse(el.textContent);
  } catch {
    return {};
  }
}

const LANDING = readLandingConfig();
const HS_IMG_PLACEHOLDER = LANDING.imgPlaceholder ?? '';

function hydrateImage(img) {
  const src = img.getAttribute('data-src');
  if (!src || img.classList.contains('is-loaded')) return;
  const finish = () => {
    img.classList.add('is-loaded');
    img.removeAttribute('data-src');
  };
  img.decoding = 'async';
  img.onload = finish;
  img.onerror = finish;
  if (img.dataset.priority === 'high' && 'fetchPriority' in img) {
    img.fetchPriority = 'high';
  }
  img.src = src;
}

function hydrateFlagsIn(root) {
  if (!root) return;
  root.querySelectorAll('img[data-flag-lazy][data-src]').forEach(hydrateImage);
}

const NAV_LANG_UI = LANDING.navLangUi ?? {};
const NAV_LANGUAGES = LANDING.navLanguages ?? {};
const LOCALE_DEFAULT = LANDING.localeDefault ?? 'en';
const LOCALE_HOME_PATHS = LANDING.localeHomePaths ?? {};
const NAV_DEFAULT_LOCALE = LANDING.navDefaultLocale ?? LOCALE_DEFAULT;
const SERVER_LOCALE = LANDING.locale ?? NAV_DEFAULT_LOCALE;
let navLocale = SERVER_LOCALE;
if (!NAV_LANGUAGES[navLocale]) navLocale = LOCALE_DEFAULT;

function homePathForLocale(code) {
  if (LOCALE_HOME_PATHS[code]) return LOCALE_HOME_PATHS[code];
  return code === LOCALE_DEFAULT ? '/' : `/${encodeURIComponent(code)}`;
}

function pathForLocale(code) {
  return homePathForLocale(code);
}

function normalizeLocalePath(path) {
  if (!path || path === '/') return '/';
  const p = path.replace(/\/+$/, '');
  return p === '' ? '/' : p;
}

function currentLocalePath() {
  return normalizeLocalePath(window.location.pathname);
}

const HTML_LANG_MAP = { 'zh-cn': 'zh-CN', 'zh-tw': 'zh-TW', fil: 'fil' };

function htmlLangFromCode(code) {
  return HTML_LANG_MAP[code] || code;
}

function pageDirForLocale(code) {
  const meta = NAV_LANGUAGES[code];
  return meta?.dir === 'rtl' ? 'rtl' : 'ltr';
}

function applyNavLocaleUI() {
  const ui = NAV_LANG_UI;
  document.documentElement.lang = htmlLangFromCode(navLocale);
  document.documentElement.dir = pageDirForLocale(navLocale);
  document.querySelectorAll('[data-lang-ui]').forEach((el) => {
    const key = el.getAttribute('data-lang-ui');
    if (!ui[key]) return;
    if (el.tagName === 'INPUT') {
      el.setAttribute('placeholder', ui[key]);
      el.setAttribute('aria-label', ui[key]);
    } else {
      el.textContent = ui[key];
    }
  });
  document.querySelectorAll('[data-nav-label]').forEach((el) => {
    const key = el.getAttribute('data-nav-label');
    if (ui[key]) el.textContent = ui[key];
  });
  document.querySelectorAll('.nav-step-label[data-nav-ui]').forEach((el) => {
    const key = el.getAttribute('data-nav-ui');
    if (ui[key]) el.textContent = ui[key];
  });
  document.querySelectorAll('[data-nav-ui]').forEach((el) => {
    const key = el.getAttribute('data-nav-ui');
    if (!ui[key]) return;
    if (el.hasAttribute('aria-label')) el.setAttribute('aria-label', ui[key]);
    else el.textContent = ui[key];
  });
  const soundBtn = document.getElementById('soundBtn');
  if (soundBtn) {
    soundBtn.setAttribute('aria-label', soundOn ? ui.sound_on : ui.sound_off);
  }
  const meta = NAV_LANGUAGES[navLocale];
  const flagEl = document.getElementById('langTriggerFlag');
  const codeEl = document.getElementById('langTriggerCode');
  if (meta && flagEl) {
    flagEl.alt = meta.name_native;
    flagEl.classList.remove('is-loaded');
    flagEl.setAttribute('data-src', meta.flag);
    flagEl.src = HS_IMG_PLACEHOLDER;
    hydrateImage(flagEl);
  }
  if (meta && codeEl) codeEl.textContent = meta.code_short || meta.code_display;
}

let soundOn = false;

async function toggleSound() {
  const btn = document.getElementById('soundBtn');
  const ui = NAV_LANG_UI;
  if (!btn) return;

  if (!soundOn) {
    soundOn = true;
    btn.classList.add('is-playing');
    btn.setAttribute('aria-pressed', 'true');
    btn.setAttribute('aria-label', ui.sound_on ?? '');
  } else {
    soundOn = false;
    btn.classList.remove('is-playing');
    btn.setAttribute('aria-pressed', 'false');
    btn.setAttribute('aria-label', ui.sound_off ?? '');
  }
}

document.getElementById('soundBtn')?.addEventListener('click', toggleSound);

(function initLangSwitcher() {
  const root = document.querySelector('[data-lang-switcher]');
  if (!root) return;

  const trigger = root.querySelector('.lang-switcher__trigger');
  let menu = root.querySelector('.lang-switcher__menu');
  let backdrop = root.querySelector('[data-lang-backdrop]');
  const applyBtn = root.querySelector('[data-lang-apply]');
  const listCol = root.querySelector('[data-lang-col="list"]');
  const searchInput = root.querySelector('[data-lang-search]');
  const emptyEl = root.querySelector('[data-lang-empty]');
  const cancelBtns = root.querySelectorAll('[data-lang-cancel]');
  let currentLang = navLocale;
  let pendingLang = currentLang;
  let pendingPath = pathForLocale(currentLang);

  function filterLanguages() {
    const q = (searchInput?.value || '').trim().toLowerCase();
    let visible = 0;
    listCol?.querySelectorAll('.lang-switcher__item').forEach((el) => {
      const blob = el.getAttribute('data-search') || '';
      const show = !q || blob.includes(q);
      el.hidden = !show;
      if (show) visible++;
    });
    emptyEl?.classList.toggle('is-visible', visible === 0 && q.length > 0);
  }

  searchInput?.addEventListener('input', filterLanguages);

  function syncCurrentMarkers() {
    currentLang = navLocale;
    pendingLang = currentLang;
    pendingPath = pathForLocale(currentLang);
    root.setAttribute('data-current-locale', currentLang);
    listCol?.querySelectorAll('.lang-switcher__item').forEach((el) => {
      const code = el.getAttribute('data-lang-code');
      const isCur = code === currentLang;
      el.classList.toggle('is-current', isCur);
      el.classList.toggle('is-selected', isCur);
      el.setAttribute('aria-checked', isCur ? 'true' : 'false');
    });
    refreshApply();
  }

  function getSelected() {
    return listCol?.querySelector('.lang-switcher__item.is-selected') || null;
  }

  function refreshApply() {
    const sel = getSelected();
    pendingLang = sel ? sel.getAttribute('data-lang-code') || currentLang : currentLang;
    pendingPath = sel
      ? sel.getAttribute('data-lang-url') || pathForLocale(pendingLang)
      : pathForLocale(pendingLang);
    const changed = normalizeLocalePath(pendingPath) !== currentLocalePath();
    applyBtn?.classList.toggle('is-ready', changed);
  }

  function resetSelection() {
    listCol?.querySelectorAll('.lang-switcher__item').forEach((el) => {
      const isCur = el.classList.contains('is-current');
      el.classList.toggle('is-selected', isCur);
      el.setAttribute('aria-checked', isCur ? 'true' : 'false');
    });
    refreshApply();
  }

  const LANG_DRAWER_MQ = window.matchMedia('(max-width: 768px)');
  let langLayerPortaled = false;

  function isLangDrawerMode() {
    return LANG_DRAWER_MQ.matches;
  }

  function clearLangMenuPosition() {
    if (!menu) return;
    menu.style.removeProperty('top');
    menu.style.removeProperty('left');
    menu.style.removeProperty('right');
    menu.style.removeProperty('bottom');
    menu.style.removeProperty('width');
  }

  function positionLangMenuPanel() {
    if (!menu || !trigger || isLangDrawerMode()) {
      clearLangMenuPosition();
      return;
    }
    const margin = 12;
    const gap = 10;
    const rect = trigger.getBoundingClientRect();
    const maxW = Math.min(760, window.innerWidth - margin * 2);
    menu.style.width = `${maxW}px`;
    menu.style.top = `${rect.bottom + gap}px`;
    menu.style.bottom = 'auto';
    const rtl = document.documentElement.dir === 'rtl';
    if (rtl) {
      let left = rect.left;
      if (left + maxW > window.innerWidth - margin) {
        left = window.innerWidth - margin - maxW;
      }
      menu.style.left = `${Math.max(margin, left)}px`;
      menu.style.right = 'auto';
    } else {
      let right = window.innerWidth - rect.right;
      if (rect.right - maxW < margin) right = margin;
      menu.style.right = `${Math.max(margin, right)}px`;
      menu.style.left = 'auto';
    }
  }

  function portalLangLayer() {
    if (!backdrop || !menu || !trigger || langLayerPortaled) return;
    document.body.appendChild(backdrop);
    document.body.appendChild(menu);
    backdrop.classList.add('lang-switcher__layer--portaled');
    menu.classList.add('lang-switcher__layer--portaled');
    langLayerPortaled = true;
  }

  function restoreLangLayer() {
    if (!backdrop || !menu || !trigger || !root || !langLayerPortaled) return;
    backdrop.classList.remove('lang-switcher__layer--portaled');
    menu.classList.remove('lang-switcher__layer--portaled');
    clearLangMenuPosition();
    root.insertBefore(backdrop, trigger);
    root.insertBefore(menu, trigger.nextSibling ?? null);
    langLayerPortaled = false;
  }

  function finalizeLangClose() {
    if (root.classList.contains('open')) return;
    restoreLangLayer();
    if (menu) {
      menu.hidden = true;
      menu.setAttribute('aria-hidden', 'true');
    }
    backdrop?.setAttribute('aria-hidden', 'true');
  }

  function setOpen(open) {
    if (open) {
      document.body.classList.add('is-langMenuOpen');
      if (menu) {
        menu.hidden = false;
        menu.removeAttribute('hidden');
        menu.setAttribute('aria-hidden', 'false');
      }
      backdrop?.setAttribute('aria-hidden', 'false');
      portalLangLayer();
      root.classList.add('open');
      trigger?.setAttribute('aria-expanded', 'true');
      positionLangMenuPanel();
      requestAnimationFrame(positionLangMenuPanel);
      refreshApply();
      filterLanguages();
      hydrateFlagsIn(menu);
      setTimeout(() => searchInput?.focus(), 80);
      return;
    }
    root.classList.remove('open');
    trigger?.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('is-langMenuOpen');
    resetSelection();
    if (searchInput) {
      searchInput.value = '';
      filterLanguages();
    }
    finalizeLangClose();
  }

  function closeMenu() {
    setOpen(false);
  }

  trigger?.addEventListener('click', (e) => {
    e.stopPropagation();
    setOpen(!root.classList.contains('open'));
  });

  backdrop?.addEventListener('click', closeMenu);

  listCol?.querySelectorAll('.lang-switcher__item').forEach((item) => {
    item.addEventListener('click', () => {
      listCol.querySelectorAll('.lang-switcher__item').forEach((el) => {
        el.classList.remove('is-selected', 'is-current');
        el.setAttribute('aria-checked', 'false');
      });
      item.classList.add('is-selected');
      item.setAttribute('aria-checked', 'true');
      refreshApply();
    });
  });

  applyBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    refreshApply();
    const dest = normalizeLocalePath(pendingPath);
    if (dest === currentLocalePath()) {
      closeMenu();
      return;
    }
    const hash = window.location.hash || '';
    const qs = window.location.search || '';
    window.location.href = dest + qs + hash;
  });

  cancelBtns.forEach((btn) => btn.addEventListener('click', closeMenu));

  document.addEventListener('click', (e) => {
    if (!root.classList.contains('open')) return;
    const t = e.target;
    if (root.contains(t) || menu?.contains(t) || backdrop?.contains(t)) return;
    closeMenu();
  });

  window.addEventListener('resize', () => {
    if (root.classList.contains('open')) positionLangMenuPanel();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && root.classList.contains('open')) closeMenu();
  });

  syncCurrentMarkers();
  applyNavLocaleUI();
  root.classList.remove('open');
  document.body.classList.remove('is-langMenuOpen');
  if (menu) {
    menu.hidden = true;
    menu.setAttribute('aria-hidden', 'true');
  }
  backdrop?.setAttribute('aria-hidden', 'true');
})();

const SHARE_TITLE = LANDING.shareTitle ?? document.title;
const SHARE_TEXT = LANDING.shareText ?? '';
const TOAST_COPIED = LANDING.toastCopied ?? 'Link copied';

document.getElementById('shareBtn')?.addEventListener('click', (e) => {
  const sb = e.currentTarget;
  if (navigator.share) {
    e.preventDefault();
    navigator.share({ title: SHARE_TITLE, text: SHARE_TEXT, url: location.href }).catch(() => {});
  } else if (navigator.clipboard) {
    e.preventDefault();
    navigator.clipboard.writeText(location.href).then(() => {
      const l = sb.querySelector('.nav-action__label');
      if (l) {
        const o = l.textContent;
        l.textContent = TOAST_COPIED;
        setTimeout(() => {
          l.textContent = o;
        }, 1600);
      }
    }).catch(() => {});
  }
});

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => hydrateFlagsIn(document), { once: true });
} else {
  hydrateFlagsIn(document);
}
