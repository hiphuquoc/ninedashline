<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ page_text_dir($urlLocale ?? 'en') }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials.favicon')
<meta name="description" content="{{ $metaDescription }}">
@include('partials.seo-head')
<title>{{ $metaTitle }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://flagcdn.com" crossorigin>
<link href="{{ $googleFontsUrl }}" rel="stylesheet">
@vite(['resources/css/landing-fab.css', 'resources/css/landing-lang-switcher.css', 'resources/js/landing-i18n.js', 'resources/js/ancient-map-lightbox.js', 'resources/js/welcome-reveal.js'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --red:#DA251D;
  --red-soft:#f1795e;
  --gold:#FFCC00;
  --gold-deep:#d9a400;
  --navy:#001F3F;
  --navy2:#002B5B;
  --blue:#6caeff;
  --teal:#74d2b3;
  --cream:#F5EDD6;
  --white:#FFFFFF;
  --dark:#050A14;
  --surface-deep:#000511;
  --surface-base:#050A14;
  --surface-mid:#030b1a;
  --surface-elevated:#0a1424;
  --line:rgba(255,255,255,.08);
  --line-2:rgba(255,255,255,.12);
  --grain:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.08'/%3E%3C/svg%3E");

  --section-max:1140px;
  --section-x:60px;
  --section-pad-y:112px;
  --intra-gap:44px;
  --intra-gap-md:36px;
  --thesis-gap:clamp(32px,4vw,52px);
  --gap-content:16px;
  --gap-card:14px;
  --gap-grid:clamp(16px,2.2vw,22px);
  --pad-card:clamp(22px,2.6vw,28px);
  --pad-card-sm:clamp(18px,2.2vw,24px);
  --stack-sm:12px;
  --stack-md:14px;
  --nav-offset:70px;
  --nav-chip-radius:8px;
  --nav-chip-pad-icon:9px;
  --nav-chip-pad-text:12px;

  --font-sans:'Lexend',sans-serif;
  --font-serif-title:'Playfair Display',serif;
  --font-serif-text:'Cormorant Garamond',serif;
  --font-display:'Barlow Condensed',sans-serif;
}

html{scroll-behavior:smooth}
body{font-family:var(--font-sans);background:var(--dark);color:var(--cream);overflow-x:hidden;cursor:none;-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}
::selection{background:var(--gold);color:#1a1405}
img{max-width:100%;display:block}
a{color:inherit;text-decoration:none}
button{font:inherit;color:inherit;background:none;border:none;cursor:none}

::-webkit-scrollbar{width:9px}
::-webkit-scrollbar-track{background:#02060f}
::-webkit-scrollbar-thumb{background:#13233c;border-radius:5px}
::-webkit-scrollbar-thumb:hover{background:var(--gold)}

/* ---------- Global atmosphere ---------- */
.grain-fixed{position:fixed;inset:0;background-image:var(--grain);background-size:150px;opacity:.05;pointer-events:none;z-index:9990;mix-blend-mode:overlay}
.vignette{position:fixed;inset:0;pointer-events:none;z-index:9985;box-shadow:inset 0 0 240px 50px rgba(0,0,0,.7)}
.cursor{position:fixed;width:10px;height:10px;background:var(--gold);border-radius:50%;pointer-events:none;z-index:99999999;transform:translate(-50%,-50%);transition:width .3s,height .3s,background .3s,opacity .3s;mix-blend-mode:difference}
.cursor.expand{width:44px;height:44px;background:var(--red);opacity:.55}
.cursor-ring{position:fixed;width:36px;height:36px;border:1px solid rgba(255,204,0,.4);border-radius:50%;pointer-events:none;z-index:99999998;transform:translate(-50%,-50%);transition:transform .18s ease,width .3s,height .3s,border-color .3s}
.cursor-ring.lens{width:90px;height:90px;border-color:var(--gold);background:rgba(255,204,0,.04)}
.cursor-ring.lens::after{content:"SOI";position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);font-family:var(--font-display);font-size:11px;letter-spacing:2px;color:var(--gold)}
@media(pointer:coarse){.cursor,.cursor-ring{display:none}body{cursor:auto}}

/* ---------- Loader ---------- */
#loader{position:fixed;inset:0;z-index:100000;background:var(--surface-deep);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;transition:opacity .9s ease,visibility .9s}
#loader.done{opacity:0;visibility:hidden;pointer-events:none}
#loader .brand-mark--loader{pointer-events:none;text-decoration:none;font-size:clamp(17px,4.2vw,22px)}
#loader .brand-mark--loader .brand-mark__mark{height:4.8em;max-height:76px}
.loader-mark{font-family:var(--font-display);font-weight:600;letter-spacing:.32em;text-indent:.32em;font-size:12px;color:rgba(245,237,214,.45);text-transform:uppercase}

/* (Scroll-progress bar & vertical index-rail removed — replaced by the header nav-rail à la hoangsa.dev) */

/* ---------- Decor ---------- */
.light-leak{position:absolute;width:340px;height:340px;border-radius:50%;filter:blur(85px);pointer-events:none}
.leak-red{background:rgba(218,37,29,.08)}
.leak-gold{background:rgba(255,204,0,.06)}
.leak-blue{background:rgba(0,100,220,.06)}

/* ============ NAV (ported from hoangsa.dev) ============ */
nav{position:fixed;top:0;left:0;right:0;z-index:9995;padding:14px 48px 12px;display:flex;align-items:center;justify-content:space-between;gap:20px;background:linear-gradient(to bottom,rgba(5,10,20,.88),rgba(5,10,20,.55) 70%,transparent);transition:background .4s ease,padding .38s cubic-bezier(.32,.72,0,1),box-shadow .4s ease,gap .38s cubic-bezier(.32,.72,0,1)}
nav.scrolled{background:rgba(5,10,20,.96);backdrop-filter:blur(16px);padding:10px 40px;box-shadow:0 8px 32px rgba(0,0,0,.35)}

/* brand-mark — đường lưỡi bò (U) 9 nét, loader + nav */
.brand-mark{display:inline-flex;align-items:center;flex-wrap:nowrap;white-space:nowrap;line-height:1;font-family:var(--font-display),'Arial Narrow',Arial,sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.04em;min-width:0}
.brand-mark__inner{display:inline-flex;align-items:center;gap:.5em;min-width:0;max-width:100%}
.brand-mark__mark{width:auto;aspect-ratio:60/50;height:1.55em;max-height:32px;flex-shrink:0;overflow:visible}
.brand-mark__name{color:var(--gold);letter-spacing:.05em;text-shadow:0 0 12px rgba(255,204,0,.22);transition:color .25s,text-shadow .25s}
.ndl-seg{fill:none;stroke:var(--gold);stroke-width:2.6;stroke-linecap:round;stroke-dasharray:6 5;opacity:.42;animation:ndlSegPulse 4.05s ease-in-out infinite}
.ndl-seg--1{animation-delay:0s}
.ndl-seg--2{animation-delay:.45s}
.ndl-seg--3{animation-delay:.9s}
.ndl-seg--4{animation-delay:1.35s}
.ndl-seg--5{animation-delay:1.8s}
.ndl-seg--6{animation-delay:2.25s}
.ndl-seg--7{animation-delay:2.7s}
.ndl-seg--8{animation-delay:3.15s}
.ndl-seg--9{animation-delay:3.6s}
@keyframes ndlSegPulse{
  0%,72%,100%{stroke:#ffcc00;opacity:.4;filter:none}
  6%,20%{stroke:#da251d;opacity:1;filter:drop-shadow(0 0 5px rgba(218,37,29,.7))}
}
.nav-logo.brand-mark{flex-shrink:0;min-width:0;max-width:min(100%,22rem);font-size:clamp(15px,1.65vw,20px);text-decoration:none;transition:opacity .38s cubic-bezier(.32,.72,0,1),transform .38s cubic-bezier(.32,.72,0,1),max-height .38s cubic-bezier(.32,.72,0,1)}
.nav-logo.brand-mark:hover .brand-mark__name,.nav-logo.brand-mark.is-active .brand-mark__name{color:#ffe566;text-shadow:0 0 16px rgba(255,204,0,.4)}
.nav-logo,.nav-end{transition:opacity .38s cubic-bezier(.32,.72,0,1),transform .38s cubic-bezier(.32,.72,0,1),max-height .38s cubic-bezier(.32,.72,0,1),flex .38s cubic-bezier(.32,.72,0,1),width .38s cubic-bezier(.32,.72,0,1),margin .38s cubic-bezier(.32,.72,0,1),padding .38s cubic-bezier(.32,.72,0,1)}

/* nav-rail (center steps + progress) */
.nav-rail{flex:1 1 0;min-width:0;margin:0;padding:4px 8px;position:relative;overflow-x:auto;overflow-y:hidden;scrollbar-width:none;-webkit-overflow-scrolling:touch}
.nav-rail::-webkit-scrollbar{display:none}
.nav-rail-steps{--nav-step-count:7;position:relative;display:grid;grid-template-columns:repeat(var(--nav-step-count),minmax(min-content,1fr));width:max-content;min-width:100%;align-items:center}
.nav-rail-track{position:absolute;left:0;top:7px;width:0;height:1px;background:rgba(255,204,0,.15);pointer-events:none;z-index:0}
.nav-rail-progress{position:absolute;left:0;top:7px;height:1px;width:0;background:linear-gradient(90deg,var(--red),var(--gold));pointer-events:none;z-index:1;transition:width .1s linear}
.nav-step{display:flex;flex-direction:column;align-items:center;justify-self:center;gap:8px;padding:0 clamp(4px,.55vw,10px);text-decoration:none;cursor:none;position:relative;z-index:2;min-width:min-content}
.nav-step-dot{width:9px;height:9px;border-radius:50%;border:2px solid rgba(255,204,0,.35);background:#050a14;box-sizing:border-box;transition:transform .35s,background .35s,border-color .35s,box-shadow .35s}
.nav-step-label{display:block;text-align:center;font-family:var(--font-sans);font-size:9px;font-weight:500;letter-spacing:1.2px;text-transform:uppercase;color:rgba(245,237,214,.38);line-height:1.2;white-space:nowrap;transition:color .35s,font-weight .35s}
.nav-step:hover .nav-step-dot{border-color:rgba(255,204,0,.65);transform:scale(1.15)}
.nav-step:hover .nav-step-label{color:rgba(255,204,0,.7)}
.nav-step.is-passed .nav-step-dot{background:rgba(255,204,0,.55);border-color:rgba(255,204,0,.65)}
.nav-step.is-passed .nav-step-label{color:rgba(245,237,214,.55)}
.nav-step.is-active .nav-step-dot{width:13px;height:13px;background:var(--red);border-color:var(--gold);box-shadow:0 0 0 4px rgba(255,204,0,.15),0 0 16px rgba(218,37,29,.45)}
.nav-step.is-active .nav-step-label{color:var(--gold);font-weight:600;letter-spacing:1.5px}

/* nav-end + actions */
.nav-end{flex-shrink:0;display:flex;align-items:center}
.nav-actions{display:flex;align-items:center;gap:6px;position:relative;z-index:120}
.nav-action{box-sizing:border-box;display:inline-flex;align-items:center;gap:7px;min-height:38px;padding:0 10px;border-radius:8px;border:1px solid rgba(255,204,0,.25);background:rgba(8,18,36,.88);box-shadow:0 2px 12px rgba(0,0,0,.28);cursor:none;transition:border-color .2s,background .2s,color .2s;text-decoration:none;color:rgba(245,237,214,.9);font-family:var(--font-sans)}
.nav-action__icon{flex:0 0 28px;width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,204,0,.08);color:var(--gold);transition:background .2s}
.nav-action__icon svg{width:16px;height:16px;display:block}
.nav-action__label{font-size:9px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;white-space:nowrap;line-height:1}
/* sound */
.nav-action--sound{position:relative;overflow:hidden;isolation:isolate;width:38px;height:38px;min-width:38px;padding:0;gap:0;justify-content:center;border-color:rgba(218,37,29,.48);background:linear-gradient(155deg,rgba(218,37,29,.32) 0%,rgba(218,37,29,.08) 42%,rgba(8,18,36,.96) 100%);color:#ffd8d4;transition:border-color .25s,background .25s,color .25s,box-shadow .25s,transform .2s}
.nav-action--sound::before{content:"";position:absolute;inset:0;opacity:0;background:radial-gradient(circle at 30% 25%,rgba(218,37,29,.35),transparent 65%);transition:opacity .25s;pointer-events:none;z-index:0}
.nav-action--sound .nav-action__icon{position:relative;z-index:1;flex:1 1 auto;width:100%;height:100%;border-radius:0;background:transparent;box-shadow:none;color:inherit}
.nav-action--sound .sound-icon-slash{opacity:1;transition:opacity .2s}
.nav-action--sound .sound-icon-waves{opacity:.55;transition:opacity .2s}
.nav-action--sound:hover{border-color:rgba(218,37,29,.65);color:var(--white);box-shadow:0 4px 20px rgba(218,37,29,.18)}
.nav-action--sound:hover::before{opacity:1}
.nav-action--sound.is-playing{border-color:rgba(255,204,0,.65);background:linear-gradient(155deg,rgba(255,204,0,.42) 0%,rgba(255,204,0,.12) 40%,rgba(12,8,18,.96) 100%);color:#ffe8a8;animation:navSoundPulse 1.8s ease-in-out infinite}
.nav-action--sound.is-playing::before{opacity:1;background:radial-gradient(circle at 35% 30%,rgba(255,204,0,.38),transparent 68%)}
.nav-action--sound.is-playing .sound-icon-slash{opacity:0}
.nav-action--sound.is-playing .sound-icon-waves{opacity:1}
@keyframes navSoundPulse{0%,100%{box-shadow:0 2px 14px rgba(0,0,0,.32),0 0 0 0 rgba(255,204,0,.45)}50%{box-shadow:0 4px 22px rgba(255,204,0,.25),0 0 0 7px rgba(255,204,0,0)}}
/* share/contribute pill */
.nav-action--share{position:relative;overflow:hidden;isolation:isolate;padding-block:0;padding-inline:var(--nav-chip-pad-icon) var(--nav-chip-pad-text);gap:6px;border-color:rgba(255,255,255,.1);background:linear-gradient(165deg,#c41e18 0%,var(--red) 48%,#a81812 100%);color:#fff8f0;box-shadow:0 2px 14px rgba(218,37,29,.38),inset 0 1px 0 rgba(255,255,255,.14);transition:border-color .25s,background .25s,color .25s,box-shadow .25s,transform .2s}
.nav-action--share::before{content:"";position:absolute;inset:0;opacity:0;background:radial-gradient(circle at 22% 20%,rgba(255,255,255,.18),transparent 65%);transition:opacity .25s;pointer-events:none;z-index:0}
.nav-action--share .nav-action__icon{position:relative;z-index:1;flex:0 0 22px;width:22px;height:22px;border-radius:0;background:transparent;box-shadow:none;color:inherit}
.nav-action--share .nav-action__icon svg{width:15px;height:15px}
.nav-action--share .nav-action__label{position:relative;z-index:1;letter-spacing:1.1px;color:inherit;font-weight:800}
.nav-action--share:hover{border-color:rgba(255,204,0,.42);background:linear-gradient(165deg,#dc2a22 0%,#e8352d 50%,#c42018 100%);color:#fff;box-shadow:0 4px 22px rgba(218,37,29,.45)}
.nav-action--share:hover::before{opacity:1}
/* language switcher — see resources/css/landing-lang-switcher.css */

/* nav responsive */
@media(max-width:1023px){
  :root{--nav-offset:108px;--hero-pad-extra:25px;--nav-chip-pad-icon:6px;--nav-chip-pad-text:8px}
  #hero{
    height:auto;
    min-height:0;
    padding-top:calc(var(--nav-offset) + env(safe-area-inset-top,0px) + clamp(14px,3vh,22px) + var(--hero-pad-extra));
    padding-bottom:calc(clamp(22px,5vh,36px) + var(--hero-pad-extra));
    align-items:center;
    justify-content:center;
  }
  nav{display:grid;grid-template-columns:1fr auto;grid-template-rows:auto auto;grid-template-areas:"logo end" "rail rail";align-items:center;padding:10px 16px 8px;gap:8px 12px;transition:background .4s,padding .38s cubic-bezier(.32,.72,0,1),box-shadow .4s,gap .38s cubic-bezier(.32,.72,0,1),grid-template-rows .38s cubic-bezier(.32,.72,0,1)}
  .nav-logo{grid-area:logo;justify-self:start;align-self:center}
  .nav-end{grid-area:end;justify-self:end;align-self:center}
  .nav-rail{grid-area:rail;flex:unset;width:100%;max-width:none;margin:0;padding:4px 2px 2px}
  .nav-rail-steps{grid-template-columns:repeat(var(--nav-step-count),max-content)}
  .nav-step{padding:0 clamp(6px,1.6vw,10px)}
  .nav-step-label{font-size:clamp(7px,1.8vw,9px);letter-spacing:.06em}
  nav.is-nav-compact{grid-template-rows:0 minmax(44px,auto);gap:0;padding-top:6px;padding-bottom:6px}
  nav.is-nav-compact.scrolled{background:rgba(5,10,20,.94)}
  nav.is-nav-compact .nav-rail{flex:1 1 auto;max-width:100%;padding-top:4px}
  nav.is-nav-compact .nav-logo,nav.is-nav-compact .nav-end{opacity:0;transform:translateY(-10px) scale(.98);pointer-events:none;max-height:0;overflow:hidden;margin:0 !important;padding-top:0 !important;padding-bottom:0 !important;border:0}
  .nav-action--sound{width:32px;height:32px;min-width:32px}
  .nav-action--share{height:32px;gap:4px}
  .nav-action--share .nav-action__icon{flex:0 0 18px;width:18px;height:18px}
  .nav-action--share .nav-action__label{font-size:7px;letter-spacing:.08em}
  .nav-actions{gap:4px}
  .nav-action{min-height:32px;padding:3px 5px;gap:4px}
  .lang-switcher__trigger.nav-action{height:32px;min-height:32px;padding:0 6px;gap:3px}
  .lang-switcher__flag{width:15px;height:15px}
}
@media(max-width:768px){
  nav{padding:10px 16px 8px}
  nav.scrolled{padding:8px 16px}
  .nav-logo.brand-mark{max-width:min(100%,16.5rem);font-size:clamp(14px,3.5vw,17px)}
  .brand-mark__mark{max-height:28px}
  .hero-fact{display:none !important}
  .nav-action--share .nav-action__label{display:none}
  .nav-action--share{padding:0 8px}
}

/* footer brand (kept for footer markup) */
.brand{font-family:var(--font-display);font-weight:700;text-transform:uppercase;letter-spacing:.05em;font-size:clamp(16px,1.9vw,21px);display:inline-flex;align-items:center;gap:.55em;white-space:nowrap}
.brand .dashes{display:inline-flex;gap:3px;align-items:center}
.brand .dashes i{width:8px;height:2px;background:var(--red);display:block;transition:.3s}
.brand:hover .dashes i{background:var(--gold)}
.brand .b-gold{color:var(--gold);text-shadow:0 0 12px rgba(255,204,0,.2)}

/* ---------- Section framework ---------- */
section{position:relative;overflow:hidden}
.section-inner{max-width:var(--section-max);margin:0 auto;padding:var(--section-pad-y) var(--section-x)}
.section-label{font-family:var(--font-sans);font-size:15px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--gold);margin-bottom:var(--gap-content);display:flex;align-items:center;gap:12px}
.section-label::before{content:"";width:30px;height:1px;background:var(--gold)}
.section-label .lnum{font-family:var(--font-display);font-weight:700;color:rgba(245,237,214,.35);margin-right:2px}
.section-title{font-family:var(--font-serif-title);font-size:clamp(36px,5vw,64px);font-weight:700;line-height:1.1;color:var(--white);margin-bottom:var(--gap-content);letter-spacing:-.01em}
.section-title .accent{color:var(--gold)}
.section-title .red-accent{color:var(--red)}
.section-title em{font-style:italic;font-weight:500}
.section-subtitle{font-family:var(--font-serif-title);font-size:clamp(22px,2.4vw,32px);font-weight:600;color:var(--white)}
.prose-lead{font-family:var(--font-serif-text);font-size:clamp(22px,2.2vw,25px);font-weight:500;line-height:1.7;color:rgba(245,237,214,.7);max-width:62ch}
.prose-lead strong{color:var(--gold);font-weight:600}
.prose-lead em{font-style:italic;color:rgba(245,237,214,.85)}
.prose-body{font-family:var(--font-sans);font-size:clamp(18px,1.15vw,20px);font-weight:300;line-height:1.7;color:rgba(245,237,214,.9)}
.prose-body em{color:var(--red);font-style:normal;font-weight:600}
.prose-body strong{color:var(--gold);font-weight:600}
.prose-caption{font-family:var(--font-sans);font-size:11px;letter-spacing:2px;text-transform:uppercase;color:rgba(245,237,214,.5)}
.prose-quote{border-left:3px solid var(--red);padding:22px 30px;background:rgba(218,37,29,.05);font-family:var(--font-serif-text);font-size:clamp(20px,2.2vw,27px);font-style:italic;font-weight:500;color:rgba(245,237,214,.85);line-height:1.55}
.prose-quote strong{color:var(--gold);font-style:normal;font-weight:600}

/* ========== Responsive spacing tokens (docs/typography.md §8–9) ========== */
@media(max-width:1199px){
  :root{
    --section-max:1100px;
    --section-x:52px;
    --section-pad-y:96px;
    --intra-gap:38px;
    --intra-gap-md:32px;
    --gap-content:15px;
    --gap-grid:clamp(16px,2vw,20px);
  }
}
@media(max-width:1023px){
  :root{
    --section-x:44px;
    --section-pad-y:84px;
    --intra-gap:34px;
    --intra-gap-md:28px;
    --gap-content:14px;
    --pad-card:clamp(20px,2.4vw,26px);
  }
}
@media(max-width:990px){
  :root{
    --section-max:100%;
    --section-x:36px;
    --section-pad-y:72px;
    --intra-gap:30px;
    --intra-gap-md:26px;
    --gap-grid:clamp(14px,2vw,18px);
    --thesis-gap:clamp(28px,5vw,44px);
  }
}
@media(max-width:768px){
  :root{
    --section-x:28px;
    --section-pad-y:56px;
    --intra-gap:24px;
    --intra-gap-md:20px;
    --gap-content:12px;
    --gap-card:12px;
    --gap-grid:clamp(12px,2.8vw,14px);
    --pad-card:clamp(18px,2.6vw,22px);
    --pad-card-sm:clamp(16px,2.4vw,20px);
    --thesis-gap:clamp(24px,6vw,36px);
  }
  .section-label{font-size:14px;gap:10px}
  .section-label::before{width:24px}
  .hero-content{padding-left:var(--section-x);padding-right:var(--section-x)}
  .hero-actions{gap:var(--stack-md)}
}
@media(max-width:567px){
  :root{
    --section-x:22px;
    --section-pad-y:52px;
    --intra-gap:22px;
    --intra-gap-md:18px;
    --gap-content:10px;
    --gap-card:10px;
    --gap-grid:12px;
    --pad-card:clamp(16px,4vw,20px);
    --pad-card-sm:clamp(14px,3.5vw,18px);
    --stack-sm:10px;
    --stack-md:12px;
    --thesis-gap:22px;
  }
  .section-label{font-size:13px;letter-spacing:1.6px}
}

/* reveal */
.reveal{opacity:0;transform:translateY(42px);transition:opacity .9s cubic-bezier(.2,.7,.2,1),transform .9s cubic-bezier(.2,.7,.2,1)}
.reveal.visible{opacity:1;transform:none}
.reveal-delay-1{transition-delay:.1s}
.reveal-delay-2{transition-delay:.2s}
.reveal-delay-3{transition-delay:.34s}
.reveal-delay-4{transition-delay:.48s}

/* coastline divider */
.divider-coast{position:relative;height:90px;margin-top:-90px;pointer-events:none;z-index:3}
.divider-coast svg{width:100%;height:100%;display:block}

/* ---------- HERO ---------- */
#hero{height:100vh;height:100dvh;min-height:720px;display:flex;align-items:center;justify-content:center;box-sizing:border-box;padding-top:var(--nav-offset);background:radial-gradient(130% 100% at 50% -10%,#08203c 0%,#04101f 38%,var(--surface-deep) 100%)}
/* Back-most hero background image (Biển Đông night map) */
.hero-bg{position:absolute;inset:0;z-index:0;background:url('/storage/images/background-slider-ban-do-duong-luoi-bo.png') center 44%/cover no-repeat;filter:brightness(1.1) saturate(1.08) contrast(1.02);transform:scale(1.06);animation:heroKen 28s ease-in-out infinite alternate;will-change:transform;isolation:isolate}
/* colored light grade — screen-blended glows add vibrance + a focal accent */
.hero-bg::before{content:"";position:absolute;inset:0;mix-blend-mode:screen;pointer-events:none;background:radial-gradient(115% 78% at 76% 16%,rgba(46,132,196,.26),transparent 56%),radial-gradient(95% 72% at 14% 90%,rgba(218,37,29,.20),transparent 58%),radial-gradient(58% 44% at 50% 56%,rgba(255,204,0,.10),transparent 70%)}
/* depth: soft cushion behind the headline, brighter map ring around it, dark vignette at edges */
.hero-bg::after{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(180deg,rgba(5,10,20,.70) 0%,rgba(5,10,20,.20) 22%,rgba(5,10,20,.08) 46%,rgba(5,10,20,.52) 80%,rgba(5,10,20,.97) 100%),radial-gradient(64% 52% at 50% 53%,rgba(4,9,18,.58) 0%,rgba(4,9,18,.22) 42%,transparent 72%),radial-gradient(138% 116% at 50% 38%,transparent 54%,rgba(2,6,14,.78) 100%)}
@keyframes heroKen{from{transform:scale(1.06)}to{transform:scale(1.13) translateY(-1.5%)}}
.hero-stars{position:absolute;inset:0;background-image:radial-gradient(1.4px 1.4px at 18% 28%,rgba(255,255,255,.5),transparent),radial-gradient(1px 1px at 72% 18%,rgba(255,255,255,.4),transparent),radial-gradient(1.2px 1.2px at 40% 66%,rgba(255,255,255,.3),transparent),radial-gradient(1px 1px at 86% 56%,rgba(255,255,255,.42),transparent),radial-gradient(1px 1px at 56% 40%,rgba(255,255,255,.25),transparent),radial-gradient(1.3px 1.3px at 30% 80%,rgba(255,255,255,.3),transparent);opacity:.32}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(circle at 50% 45%,#000 0%,transparent 72%);-webkit-mask-image:radial-gradient(circle at 50% 45%,#000 0%,transparent 72%)}
.wave-container{position:absolute;bottom:0;left:0;width:100%;height:42%;pointer-events:none}
.wave{position:absolute;bottom:0;left:0;width:200%;height:100%}
.wave svg{width:50%;height:100%;float:left}
.wave1{animation:waveMove 20s linear infinite;opacity:.7}
.wave2{animation:waveMove 28s linear infinite reverse;opacity:.6}
.wave3{animation:waveMove 38s linear infinite;opacity:.85}
@keyframes waveMove{to{transform:translateX(-50%)}}
.island-silhouette{position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:min(960px,92%);height:100px;pointer-events:none;opacity:.8}
.hero-fact{position:absolute;top:98px;left:50%;transform:translateX(-50%);z-index:4;display:inline-flex;align-items:center;gap:10px;max-width:calc(100% - 40px);padding:9px 18px;border:1px solid rgba(255,204,0,.3);border-radius:99px;background:rgba(5,10,20,.5);backdrop-filter:blur(10px);font-size:13px;letter-spacing:.3px;color:rgba(245,237,214,.88);white-space:nowrap;opacity:0;animation:fadeIn .8s ease forwards .25s}
.hero-fact .ic{width:16px;height:16px;flex-shrink:0;color:var(--red-soft)}
.hero-fact b{color:var(--gold);font-weight:600}
.hero-content{position:relative;z-index:4;text-align:center;max-width:1000px;margin:0 auto;padding:0 var(--section-x)}
.hero-label{font-family:var(--font-sans);font-size:13px;font-weight:600;letter-spacing:3px;color:var(--gold);text-transform:uppercase;margin-bottom:28px;opacity:0;animation:fadeUp .8s ease forwards .4s}
.hero-title{font-family:var(--font-serif-title);font-size:clamp(46px,8.4vw,104px);font-weight:800;line-height:1.02;color:var(--white);margin-bottom:14px;letter-spacing:-.02em;filter:drop-shadow(0 4px 16px rgba(0,0,0,.55))}
.hero-title .l{display:block;opacity:0;transform:translateY(40px);animation:fadeUp .95s cubic-bezier(.2,.7,.2,1) forwards}
.hero-title .l1{animation-delay:.55s}
.hero-title .l2{animation-delay:.72s;margin-top:.04em}
.hero-title .red-accent{color:var(--red)}
/* "là gì?" — combined accent phrase with self-drawing nine-dash underline */
.hero-title .ask{position:relative;display:inline-block;font-style:italic;font-weight:600;color:var(--red);padding:0 .06em .04em;text-shadow:0 0 38px rgba(218,37,29,.45)}
.hero-title .ask::after{content:"";position:absolute;left:3%;bottom:-.02em;height:5px;width:0;background:repeating-linear-gradient(90deg,var(--red) 0 13px,transparent 13px 25px);animation:askLine 1.1s cubic-bezier(.2,.7,.2,1) forwards 1.25s;filter:drop-shadow(0 0 6px rgba(218,37,29,.5))}
@keyframes askLine{to{width:94%}}
.hero-divider{width:84px;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);margin:26px auto;opacity:0;animation:fadeIn .8s ease forwards 1s}
.hero-sub{font-family:var(--font-serif-text);font-size:clamp(18px,2.6vw,27px);font-weight:400;font-style:italic;color:rgba(245,237,214,.86);max-width:660px;margin:0 auto 42px;line-height:1.5;opacity:0;animation:fadeUp .8s ease forwards 1.15s}
.hero-sub em{color:var(--gold);font-style:normal;font-weight:500}
.hero-actions{display:flex;gap:var(--gap-card);justify-content:center;flex-wrap:wrap;opacity:0;animation:fadeUp .8s ease forwards 1.35s}
@keyframes fadeUp{to{opacity:1;transform:none}}
@keyframes fadeIn{to{opacity:1}}
.cta{display:inline-flex;align-items:center;gap:10px;font-family:var(--font-sans);font-size:13.5px;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:16px 38px;position:relative;overflow:hidden;transition:color .3s;border:1px solid var(--gold)}
.cta-gold{color:var(--dark);background:var(--gold)}
.cta-gold::before{content:"";position:absolute;inset:0;background:var(--red);transform:scaleX(0);transform-origin:left;transition:transform .4s ease;z-index:0}
.cta-gold:hover::before{transform:scaleX(1)}
.cta-gold:hover{color:var(--white)}
.cta-gold span{position:relative;z-index:1}
.cta-ghost{color:var(--gold);background:transparent;border-color:rgba(255,204,0,.45)}
.cta-ghost:hover{background:rgba(255,204,0,.1)}
.scroll-indicator{position:absolute;bottom:32px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;color:rgba(255,255,255,.45);font-size:10px;letter-spacing:3px;text-transform:uppercase;z-index:4;opacity:0;animation:fadeIn 1s ease forwards 2s}
.scroll-line{width:1px;height:48px;background:linear-gradient(to bottom,var(--gold),transparent);position:relative;overflow:hidden}
.scroll-line::after{content:"";position:absolute;top:0;left:0;width:100%;height:40%;background:var(--gold);animation:scrollDot 1.9s ease-in-out infinite}
@keyframes scrollDot{0%{transform:translateY(-100%)}100%{transform:translateY(260%)}}

/* ---------- Stat band ---------- */
.stat-band{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border:1px solid var(--line);margin-top:var(--intra-gap);border-radius:3px;overflow:hidden}
.stat-cell{background:var(--surface-elevated);padding:30px 22px;text-align:center;position:relative;transition:background .35s;overflow:hidden}
.stat-cell::before{content:"";position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--red),var(--gold));transform:scaleX(0);transform-origin:left;transition:transform .45s ease}
.stat-cell:hover{background:#0d1a2e}
.stat-cell:hover::before{transform:scaleX(1)}
.stat-num{font-family:var(--font-display);font-weight:700;font-size:clamp(36px,4.6vw,58px);line-height:1;color:var(--gold)}
.stat-num.red{color:var(--red)}
.stat-label{margin-top:10px;font-size:12px;letter-spacing:1.3px;text-transform:uppercase;color:rgba(245,237,214,.6);line-height:1.5}

/* ---------- Definition grid — map sticky khi list dài hơn ---------- */
.def-feature{display:grid;grid-template-columns:minmax(0,1.08fr) minmax(0,.92fr);gap:clamp(28px,3.6vw,56px);align-items:start;margin-top:var(--intra-gap)}
.def-figure-wrap{position:relative;min-height:0}
.def-figure-sticky{position:sticky;top:clamp(92px,13vh,124px);z-index:2;will-change:transform;transition:transform .45s cubic-bezier(.2,.7,.2,1),filter .45s}
.def-figure-sticky::before{content:"";position:absolute;inset:-16px -8px -16px -16px;background:radial-gradient(ellipse 80% 55% at 50% 38%,rgba(255,204,0,.09),transparent 72%);pointer-events:none;z-index:-1;opacity:0;transition:opacity .5s}
.def-figure-sticky.is-pinned::before{opacity:1}
.def-figure-sticky.is-pinned .def-figure{box-shadow:0 36px 90px rgba(0,0,0,.55),0 0 0 1px rgba(255,204,0,.22)}
@media(prefers-reduced-motion:reduce){.def-figure-sticky{position:relative;top:auto;transition:none}}

/* Featured map figure */
.def-figure{position:relative;margin:0;border-radius:6px;overflow:hidden;border:1px solid rgba(255,204,0,.14);background:#040c1a;box-shadow:0 30px 80px rgba(0,0,0,.5);isolation:isolate}
.def-figure img{width:100%;height:auto;display:block;filter:saturate(1.06) contrast(1.03)}
.def-figure::after{content:"";position:absolute;inset:0;pointer-events:none;z-index:1;background:radial-gradient(125% 90% at 50% 32%,transparent 58%,rgba(2,6,14,.55) 100%)}
.def-figure__frame{position:absolute;inset:11px;z-index:2;pointer-events:none;border:1px solid rgba(255,204,0,.16);border-radius:3px}
.def-figure .corner{position:absolute;width:16px;height:16px;z-index:3;pointer-events:none;border:2px solid rgba(255,204,0,.55)}
.def-figure .corner.tl{top:11px;left:11px;border-right:0;border-bottom:0}
.def-figure .corner.tr{top:11px;right:11px;border-left:0;border-bottom:0}
.def-figure .corner.bl{bottom:11px;left:11px;border-right:0;border-top:0}
.def-figure .corner.br{bottom:11px;right:11px;border-left:0;border-top:0}
.fig-index{position:absolute;top:22px;left:22px;z-index:4;display:inline-flex;align-items:center;font-family:var(--font-display);font-weight:600;font-size:11px;letter-spacing:2.4px;text-transform:uppercase;color:var(--gold);background:rgba(5,10,20,.55);backdrop-filter:blur(6px);padding:6px 11px;border:1px solid rgba(255,204,0,.28);border-radius:4px}
.fig-legend{position:absolute;top:22px;right:22px;z-index:4;display:inline-flex;align-items:center;gap:9px;font-family:var(--font-sans);font-size:11px;letter-spacing:.4px;color:rgba(245,237,214,.85);background:rgba(5,10,20,.55);backdrop-filter:blur(6px);padding:6px 12px;border:1px solid rgba(255,255,255,.12);border-radius:4px}
.fig-legend .dash{width:24px;height:0;border-top:2.5px dashed var(--red);display:inline-block;filter:drop-shadow(0 0 4px rgba(218,37,29,.6))}
.fig-cap{position:absolute;left:0;right:0;bottom:0;z-index:4;padding:34px 22px 16px;background:linear-gradient(to top,rgba(2,6,14,.95) 0%,rgba(2,6,14,.55) 55%,transparent);font-family:var(--font-sans);font-size:12.5px;font-weight:300;letter-spacing:.2px;color:rgba(245,237,214,.78);line-height:1.55}
.fig-cap b{color:var(--gold);font-weight:600}

/* Definition list */
.def-list{display:flex;flex-direction:column;gap:0;list-style:none;counter-reset:dl}
.def-item{display:grid;grid-template-columns:auto 1fr;gap:16px;align-items:start;padding:17px 0;border-top:1px solid var(--line)}
.def-item:first-child{border-top:0;padding-top:0}
.def-item .idx{display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,204,0,.28);background:rgba(255,204,0,.07);font-family:var(--font-display);font-weight:700;font-size:14px;letter-spacing:.5px;color:var(--gold);line-height:1}
.def-item h4{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(17px,1.5vw,19px);color:var(--white);margin-bottom:5px;line-height:1.25}
.def-item p{font-family:var(--font-sans);font-size:clamp(18px,1.15vw,20px);font-weight:300;line-height:1.7;color:rgba(245,237,214,.9)}
.def-item p strong{color:var(--gold);font-weight:600}

/* ---------- Origin scrollytelling ---------- */
#origin{background:linear-gradient(180deg,var(--surface-base) 0%,#040d1a 50%,var(--surface-base) 100%)}
/* ---------- 02 Nguồn gốc — timeline + voices ---------- */
.origin-tl{margin-top:var(--intra-gap)}
.mile{display:grid;grid-template-columns:18px 1fr;gap:0 clamp(16px,2vw,26px);position:relative;padding-bottom:clamp(28px,3.6vw,46px)}
.mile:last-child{padding-bottom:0}
.mile__rail{position:relative;display:flex;justify-content:center;padding-top:7px}
.mile__rail::before{content:"";position:absolute;top:0;left:50%;transform:translateX(-50%);width:1px;height:100%;background:linear-gradient(180deg,var(--line-2),var(--line-2) 88%,transparent)}
.mile:last-child .mile__rail::before{height:20px}
.mile__dot{position:relative;z-index:1;width:14px;height:14px;border-radius:50%;background:var(--surface-deep);border:2px solid rgba(255,204,0,.5);transition:transform .4s,box-shadow .4s}
.mile.is-key .mile__dot{background:var(--red);border-color:var(--red);box-shadow:0 0 0 5px rgba(218,37,29,.15),0 0 14px rgba(218,37,29,.4)}
.mile.is-verdict .mile__dot{background:var(--gold);border-color:var(--gold);box-shadow:0 0 0 5px rgba(255,204,0,.15),0 0 14px rgba(255,204,0,.45)}
.mile__head{display:flex;align-items:baseline;gap:14px;flex-wrap:wrap;margin-bottom:7px}
.mile__year{font-family:var(--font-display);font-weight:700;font-size:clamp(27px,3.2vw,42px);line-height:.85;color:var(--white)}
.mile.is-key .mile__year{color:var(--red)}
.mile.is-verdict .mile__year{color:var(--gold)}
.mile__tag{font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:1.8px;text-transform:uppercase;color:var(--gold)}
.mile__title{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(17px,1.7vw,21px);color:var(--white);margin-bottom:9px;line-height:1.25}
.mile p{font-family:var(--font-sans);font-size:clamp(18px,1.15vw,20px);font-weight:300;line-height:1.7;color:rgba(245,237,214,.9);max-width:62ch}
.mile p em{color:var(--red-soft);font-style:normal;font-weight:500}
.mile p strong{color:var(--gold);font-weight:600}
.dash-marks{display:flex;align-items:center;gap:5px;margin:0 0 11px;flex-wrap:wrap}
.dash-marks i{width:15px;height:3px;border-radius:2px;background:var(--red);box-shadow:0 0 6px rgba(218,37,29,.45)}
.dash-marks i.muted{background:rgba(245,237,214,.22);box-shadow:none}
.dash-marks .n{margin-left:7px;font-family:var(--font-display);font-weight:700;font-size:12px;letter-spacing:1px;color:rgba(245,237,214,.45)}

/* Origin — hồ sơ nguồn gốc (typography đồng bộ hoangsa: lead 25px, body 20px) */
.voices-eyebrow{font-family:var(--font-sans);font-size:15px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(245,237,214,.55);display:flex;align-items:center;gap:12px;margin-bottom:24px}
.voices-eyebrow::before{content:"";width:30px;height:1px;background:var(--gold);opacity:.45}
.origin-record{margin-top:var(--intra-gap-md);border:1px solid rgba(255,204,0,.18);border-radius:10px;background:linear-gradient(165deg,rgba(255,204,0,.04),rgba(5,10,20,.55) 42%,rgba(8,18,36,.4));padding:var(--pad-card);position:relative;overflow:hidden}
.origin-record::before{content:"";position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--gold),rgba(255,204,0,.25))}
.origin-record>.prose-lead{margin-bottom:32px;max-width:58ch}
.orig-banner{position:relative;display:grid;grid-template-columns:minmax(130px,auto) 1fr;grid-template-rows:auto auto;gap:20px 36px;padding:clamp(30px,4.5vw,44px);margin-bottom:36px;border-radius:12px;border:1px solid rgba(255,204,0,.32);background:radial-gradient(110% 90% at 0% 0%,rgba(255,204,0,.12),transparent 48%),radial-gradient(70% 55% at 100% 100%,rgba(218,37,29,.08),transparent 50%),linear-gradient(145deg,rgba(12,22,42,.98),rgba(5,10,20,.92));box-shadow:0 0 0 1px rgba(255,204,0,.06) inset,0 28px 70px rgba(0,0,0,.5),0 0 100px rgba(255,204,0,.05);overflow:hidden}
.orig-banner__glow{position:absolute;top:-60%;left:-15%;width:70%;height:200%;background:radial-gradient(circle,rgba(255,204,0,.15),transparent 62%);pointer-events:none;animation:origGlow 8s ease-in-out infinite alternate}
@keyframes origGlow{to{transform:translate(8%,6%) scale(1.08);opacity:.85}}
.orig-banner__stat{position:relative;z-index:1;align-self:stretch;display:flex}
.orig-banner__stat-inner{flex:1;display:flex;flex-direction:column;justify-content:center;padding:clamp(18px,2.8vw,26px) clamp(20px,3vw,28px);border-radius:10px;background:linear-gradient(155deg,rgba(32,48,78,.55) 0%,rgba(14,24,44,.72) 55%,rgba(8,14,28,.85) 100%);box-shadow:inset 0 1px 0 rgba(255,255,255,.06),inset 0 -1px 0 rgba(0,0,0,.25)}
.orig-banner__num{display:block;font-family:var(--font-display);font-weight:700;font-size:clamp(52px,6.5vw,76px);line-height:.92;color:var(--gold);letter-spacing:.02em;text-shadow:0 0 40px rgba(255,204,0,.18)}
.orig-banner__unit{display:block;margin-top:12px;font-family:var(--font-sans);font-size:clamp(12px,1.1vw,13px);font-weight:600;letter-spacing:.06em;line-height:1.45;color:rgba(245,237,214,.62);max-width:12em;text-transform:none}
.orig-banner__body{position:relative;z-index:1;align-self:center}
.orig-banner__tag{font-family:var(--font-sans);font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gold);margin-bottom:12px;display:flex;align-items:center;gap:10px}
.orig-banner__tag::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--red);box-shadow:0 0 10px rgba(218,37,29,.6)}
.orig-banner__body .prose-body{max-width:52ch;margin:0}
.orig-banner__timeline{grid-column:1/-1;display:flex;align-items:center;gap:14px;padding-top:4px;border-top:1px solid rgba(255,204,0,.15)}
.orig-banner__timeline span{font-family:var(--font-display);font-weight:600;font-size:13px;letter-spacing:1.5px;color:rgba(245,237,214,.5);flex-shrink:0}
.orig-banner__timeline .dash-track{flex:1;height:3px;border-radius:99px;background:repeating-linear-gradient(90deg,var(--red) 0 14px,transparent 14px 26px);opacity:.75;filter:drop-shadow(0 0 6px rgba(218,37,29,.35))}
.orig-banner__timeline .mid{font-family:var(--font-serif-text);font-style:italic;font-size:15px;color:rgba(245,237,214,.4);letter-spacing:0;flex-shrink:0}
@media(prefers-reduced-motion:reduce){.orig-banner__glow{animation:none}}
/* Kết luận nguồn gốc — ngoài khung origin-record, dạng Q→A */
.orig-verdict{margin-top:var(--intra-gap-md)}
.orig-verdict__head{margin-bottom:clamp(28px,3.5vw,40px);max-width:62ch}
.orig-verdict__head .voices-eyebrow{margin-bottom:18px}
.orig-verdict__title{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(24px,2.6vw,32px);color:var(--white);line-height:1.2;margin:0 0 14px}
.orig-verdict__intro{font-family:var(--font-sans);font-size:clamp(18px,1.15vw,20px);font-weight:300;line-height:1.65;color:rgba(245,237,214,.82);margin:0}
.orig-verdict__intro strong{color:var(--gold);font-weight:600}
.orig-verdict__stack{display:flex;flex-direction:column;gap:var(--gap-card)}
.o-answer{display:grid;grid-template-columns:minmax(200px,.95fr) minmax(0,1.35fr);gap:0;border:1px solid var(--line);border-radius:8px;overflow:hidden;background:var(--surface-elevated);transition:border-color .35s}
.o-answer:hover{border-color:rgba(255,204,0,.22)}
.o-answer__q{position:relative;padding:clamp(26px,3vw,34px) clamp(22px,2.6vw,30px);min-height:100%;background:linear-gradient(165deg,rgba(255,204,0,.07),rgba(5,10,20,.4) 72%);border-inline-end:1px solid var(--line);overflow:hidden;display:flex;flex-direction:column;justify-content:center}
.o-answer__q::before{content:"";position:absolute;inset-inline-start:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,var(--gold),rgba(255,204,0,.2))}
.o-answer__num{position:absolute;inset-inline-end:-4px;inset-block-end:-18px;font-family:var(--font-display);font-weight:700;font-size:clamp(88px,10vw,120px);line-height:.85;letter-spacing:-3px;color:rgba(255,204,0,.055);pointer-events:none;user-select:none;z-index:0}
.o-answer__label{position:relative;z-index:1;font-family:var(--font-sans);font-size:10px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:rgba(255,204,0,.55);margin-bottom:12px}
.o-answer__q h4{position:relative;z-index:1;font-family:var(--font-serif-title);font-weight:600;font-size:clamp(20px,2vw,24px);color:var(--white);line-height:1.28;margin:0;max-width:14ch}
.o-answer__a{padding:clamp(22px,2.8vw,30px) clamp(22px,2.6vw,32px);display:flex;flex-direction:column;gap:14px;justify-content:center}
.o-answer__a .prose-body{margin:0;font-size:clamp(17px,1.1vw,19px);line-height:1.68;max-width:none}
.o-answer__fact{padding:12px 14px;border-radius:6px;background:rgba(218,37,29,.06);border:1px solid rgba(218,37,29,.2);font-family:var(--font-sans);font-size:clamp(14px,.95vw,15px);font-weight:400;line-height:1.6;color:rgba(245,237,214,.85)}
.o-answer__fact-tag{font-weight:600;color:var(--white)}
.o-answer__fact-tag::before{content:"";display:inline-block;width:6px;height:6px;margin-right:9px;border-radius:50%;background:var(--red);box-shadow:0 0 10px rgba(218,37,29,.55);vertical-align:middle;position:relative;top:-1px}
.orig-verdict__stamp{margin-top:clamp(28px,3.5vw,40px);position:relative}
.orig-stamp__frame{position:relative;padding:clamp(28px,3.6vw,44px) clamp(26px,3.2vw,40px);border-radius:12px;border:1px solid rgba(255,204,0,.24);background:linear-gradient(155deg,rgba(218,37,29,.1) 0%,rgba(10,18,34,.97) 38%,rgba(5,10,20,.94) 100%);box-shadow:0 28px 64px rgba(0,0,0,.48),inset 0 1px 0 rgba(255,255,255,.05);overflow:hidden}
.orig-stamp__frame::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--red) 0%,var(--gold) 50%,rgba(218,37,29,.4) 100%)}
.orig-stamp__glow{position:absolute;top:-35%;right:-8%;width:min(420px,55%);height:140%;background:radial-gradient(circle,rgba(255,204,0,.12),transparent 68%);pointer-events:none}
.orig-stamp__glow--red{left:-12%;right:auto;top:auto;bottom:-40%;width:min(360px,48%);background:radial-gradient(circle,rgba(218,37,29,.14),transparent 70%)}
.orig-stamp__head{position:relative;z-index:1;display:flex;align-items:center;gap:14px;margin-bottom:clamp(20px,2.5vw,28px)}
.orig-stamp__dot{flex-shrink:0;width:10px;height:10px;border-radius:50%;background:var(--red);box-shadow:0 0 0 4px rgba(218,37,29,.18),0 0 16px rgba(218,37,29,.55)}
.orig-stamp__label{font-family:var(--font-display);font-weight:700;font-size:clamp(13px,1.1vw,15px);letter-spacing:3px;text-transform:uppercase;color:var(--gold)}
.orig-stamp__rule{flex:1;height:3px;border-radius:99px;background:repeating-linear-gradient(90deg,var(--red) 0 12px,transparent 12px 22px);opacity:.55}
.orig-stamp__lede{position:relative;z-index:1;margin:0 0 clamp(18px,2.2vw,24px);font-family:var(--font-serif-title);font-weight:600;font-size:clamp(20px,2.2vw,26px);line-height:1.35;color:var(--white);max-width:52ch}
.orig-stamp__chips{position:relative;z-index:1;display:flex;flex-wrap:wrap;gap:10px;margin:0 0 clamp(20px,2.5vw,26px);padding:0;list-style:none}
.orig-stamp__chips li{font-family:var(--font-sans);font-size:clamp(13px,.95vw,14px);font-weight:600;letter-spacing:.04em;padding:10px 16px;border-radius:99px;border:1px solid rgba(255,204,0,.32);background:rgba(255,204,0,.08);color:rgba(245,237,214,.92);box-shadow:0 4px 18px rgba(0,0,0,.25)}
.orig-stamp__chips li::before{content:"";display:inline-block;width:5px;height:5px;margin-right:8px;border-radius:50%;background:var(--red);box-shadow:0 0 8px rgba(218,37,29,.5);vertical-align:middle;position:relative;top:-1px}
.orig-stamp__foot{position:relative;z-index:1;margin:0;font-family:var(--font-sans);font-size:clamp(17px,1.1vw,19px);font-weight:300;line-height:1.65;color:rgba(245,237,214,.82);max-width:62ch;padding-top:clamp(18px,2.2vw,22px);border-top:1px solid rgba(255,204,0,.14)}
@media(prefers-reduced-motion:reduce){.orig-stamp__dot{box-shadow:0 0 0 4px rgba(218,37,29,.18)}}
.orig-ledger{display:flex;flex-direction:column;gap:0;border:1px solid var(--line);border-radius:8px;overflow:hidden}
.orig-row{display:grid;grid-template-columns:88px 1fr;gap:20px;padding:24px 26px;background:var(--surface-elevated);border-top:1px solid var(--line)}
.orig-row:first-child{border-top:0}
.orig-row:nth-child(even){background:rgba(10,20,36,.65)}
.orig-yr{font-family:var(--font-display);font-weight:700;font-size:clamp(20px,2.2vw,26px);letter-spacing:1px;color:var(--gold);line-height:1;padding-top:4px}
.orig-row h4{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.8vw,22px);color:var(--white);margin-bottom:10px;line-height:1.3}
.orig-row .prose-body{max-width:62ch}
/* Voices (mục 03 — trích dẫn pháp lý) */
.vq-cite{display:flex;align-items:center;gap:13px;margin-top:22px}
.vq-cite__badge{flex:0 0 auto;width:40px;height:40px;border-radius:50%;overflow:hidden;border:1px solid var(--line-2);background:#0a1424;display:flex;align-items:center;justify-content:center}
.vq-cite__badge img{width:100%;height:100%;object-fit:cover}
.vq-cite__badge svg{width:20px;height:20px;color:var(--gold)}
.vq-cite__who{display:flex;flex-direction:column;line-height:1.3}
.vq-cite__name{font-family:var(--font-sans);font-size:14px;font-weight:600;color:var(--white)}
.vq-cite__role{font-family:var(--font-sans);font-size:12px;font-weight:300;color:rgba(245,237,214,.6)}

/* ---------- 03 Vì sao gây tranh cãi (typography: docs — lead 25px, body 20px) ---------- */
#dispute{background:radial-gradient(90% 60% at 88% 8%,rgba(218,37,29,.07) 0%,transparent 55%),radial-gradient(70% 50% at 8% 92%,rgba(0,80,160,.12) 0%,transparent 50%),var(--surface-base)}
#dispute .prose-lead{max-width:62ch}
#dispute .prose-body{max-width:62ch}
#dispute .d-issue .prose-body,#dispute .d-coll-case__body,#dispute .d-coll__rule .prose-body,#dispute .d-coll-shift .prose-body,#dispute .d-coll__stamp .prose-body,#dispute .d-pillar-head .prose-body,#dispute .d-pillar .prose-body{max-width:68ch}
.dispute-thesis{margin-top:var(--intra-gap)}
.d-pillar-head{margin-bottom:clamp(24px,3vw,32px);max-width:62ch}
.d-pillar-head__eyebrow{font-family:var(--font-sans);font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,204,0,.65);margin-bottom:10px}
.d-pillar-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:var(--gap-card)}
.d-pillar{position:relative;display:grid;grid-template-columns:auto 1fr;gap:16px 20px;padding:clamp(22px,2.8vw,30px);border-radius:10px;border:1px solid var(--line);background:linear-gradient(145deg,rgba(12,22,42,.85),rgba(5,10,20,.55));overflow:hidden;transition:border-color .35s,box-shadow .35s}
.d-pillar:hover{border-color:rgba(255,204,0,.24);box-shadow:0 16px 40px rgba(0,0,0,.35)}
.d-pillar::after{content:"";position:absolute;inset-inline-end:-20px;inset-block-end:-24px;width:100px;height:100px;border-radius:50%;background:radial-gradient(circle,rgba(218,37,29,.08),transparent 70%);pointer-events:none}
.d-pillar__idx{font-family:var(--font-display);font-weight:700;font-size:clamp(36px,4.6vw,48px);line-height:1;color:rgba(255,204,0,.22);padding-top:2px}
.d-pillar__body{position:relative;z-index:1}
.d-pillar__tag,.d-issue__tag,.d-coll-case__tag{display:inline-block;width:fit-content;font-family:var(--font-sans);font-size:10px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;padding:6px 13px 5px;border-radius:4px;border:1px solid rgba(218,37,29,.32);background:linear-gradient(135deg,rgba(218,37,29,.12),rgba(218,37,29,.04));color:rgba(255,200,190,.95);box-shadow:inset 0 1px 0 rgba(255,255,255,.06);margin:0 0 14px}
.d-pillar__tag::before,.d-issue__tag::before,.d-coll-case__tag::before{content:"";display:inline-block;width:5px;height:5px;margin-right:8px;border-radius:50%;background:var(--red);box-shadow:0 0 8px rgba(218,37,29,.45);vertical-align:middle;position:relative;top:-1px}
.d-pillar h4{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.8vw,22px);color:var(--white);line-height:1.28;margin:0 0 12px}
.d-pillar.is-geo .d-pillar__tag{border-color:rgba(108,174,255,.35);background:linear-gradient(135deg,rgba(108,174,255,.14),rgba(0,43,91,.08));color:rgba(200,225,255,.95)}
.d-pillar.is-geo .d-pillar__tag::before{background:var(--blue);box-shadow:0 0 8px rgba(108,174,255,.45)}
.d-pillar.is-geo::after{background:radial-gradient(circle,rgba(108,174,255,.1),transparent 70%)}

.dispute-ledger{margin-top:var(--thesis-gap);display:flex;flex-direction:column;gap:var(--gap-card)}
.d-issue{display:grid;grid-template-columns:56px 1fr;gap:clamp(18px,2.4vw,28px);padding:clamp(24px,3.2vw,34px);border:1px solid var(--line);border-radius:8px;background:var(--surface-elevated);position:relative;overflow:hidden;transition:border-color .35s}
.d-issue::before{content:"";position:absolute;inset-inline-start:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,var(--red),rgba(218,37,29,.2));opacity:.85}
.d-issue:hover{border-color:rgba(255,204,0,.22)}
.d-issue__num{font-family:var(--font-display);font-weight:700;font-size:clamp(32px,3.6vw,42px);line-height:1;color:rgba(245,237,214,.18);text-align:center;padding-top:6px}
.d-issue.is-warn .d-issue__num{color:rgba(218,37,29,.35)}
.d-issue h3{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.8vw,22px);color:var(--white);margin:0 0 14px;line-height:1.28}
.d-issue .prose-body{max-width:none;margin-bottom:16px}
.d-issue .d-fact{padding:14px 16px;border-radius:6px;background:rgba(255,204,0,.06);border:1px solid rgba(255,204,0,.14);font-family:var(--font-sans);font-size:clamp(17px,1.05vw,18px);font-weight:300;line-height:1.65;color:rgba(245,237,214,.88)}
.d-issue .d-fact::before{content:"→";display:inline;margin-right:8px;color:var(--gold);font-weight:700;font-size:18px;line-height:1;vertical-align:baseline}
.d-issue .d-fact b{display:inline;color:var(--gold);font-weight:600}
.d-issue .d-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px}
.d-chip{font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;padding:6px 12px;border-radius:99px;border:1px solid var(--line-2);color:rgba(245,237,214,.65)}
.d-chip.red{border-color:rgba(218,37,29,.35);color:rgba(255,200,190,.9);background:rgba(218,37,29,.08)}

.dispute-collision{margin-top:var(--thesis-gap)}
.d-coll{position:relative;padding:clamp(28px,3.6vw,44px);border-radius:12px;border:1px solid rgba(108,174,255,.24);background:linear-gradient(160deg,rgba(0,43,91,.38) 0%,rgba(8,18,36,.94) 48%,rgba(5,10,20,.92));box-shadow:0 28px 64px rgba(0,0,0,.42);overflow:hidden}
.d-coll::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),rgba(108,174,255,.4) 55%,transparent)}
.d-coll__glow{position:absolute;top:-25%;right:-10%;width:min(440px,55%);height:110%;background:radial-gradient(circle,rgba(108,174,255,.12),transparent 68%);pointer-events:none}
.d-coll__head{position:relative;z-index:1;margin-bottom:clamp(22px,2.8vw,30px);max-width:62ch}
.d-coll__head .voices-eyebrow{margin-bottom:14px}
.d-coll__title{margin:0 0 14px;font-family:var(--font-serif-title);font-weight:600;font-size:clamp(22px,2.4vw,32px);color:var(--white);line-height:1.22;letter-spacing:-.01em}
.d-coll__intro{margin:0;font-family:var(--font-serif-text);font-size:clamp(22px,2.2vw,25px);font-weight:500;line-height:1.7;color:rgba(245,237,214,.75);max-width:62ch}
.d-coll__intro strong{color:var(--blue);font-weight:600;font-family:var(--font-sans)}
.d-coll__rule{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,1fr) minmax(200px,.85fr);gap:clamp(16px,2.5vw,28px);padding:clamp(22px,2.8vw,28px);margin-bottom:clamp(22px,2.8vw,28px);border-radius:8px;border:1px solid rgba(108,174,255,.28);background:rgba(0,43,91,.25)}
.d-coll__rule h4{margin:0 0 12px;font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.8vw,22px);color:var(--white);line-height:1.28}
.d-coll__rule .prose-body strong{color:var(--blue);font-weight:600}
.d-coll__meter-side{display:flex;flex-direction:column;justify-content:center;gap:8px}
.d-coll__meter-cap{font-family:var(--font-display);font-weight:700;font-size:clamp(36px,4.2vw,48px);line-height:1;color:var(--blue);text-align:right}
.d-coll__meter-cap span{display:block;font-family:var(--font-sans);font-size:12px;font-weight:600;letter-spacing:1.3px;text-transform:uppercase;color:rgba(245,237,214,.55);margin-top:6px;text-align:right}
.d-coll__meter-ref{height:10px;border-radius:5px;background:linear-gradient(90deg,rgba(108,174,255,.25),var(--blue));box-shadow:0 0 12px rgba(108,174,255,.25)}
/* — Stack ví dụ EEZ (tái thiết kế: nhịp rõ, không chồng chữ) — */
.d-coll__stack{
  position:relative;z-index:1;
  display:flex;flex-direction:column;gap:var(--gap-grid);
  margin-top:clamp(8px,1vw,12px);
}
.d-coll__stack::before{
  content:"";
  position:absolute;left:20px;top:28px;bottom:28px;width:2px;
  background:linear-gradient(180deg,rgba(108,174,255,.35),rgba(108,174,255,.08) 85%,transparent);
  pointer-events:none;
}
.d-coll-case{
  position:relative;
  display:flex;flex-direction:column;gap:var(--gap-card);
  padding:var(--pad-card);
  padding-inline-start:clamp(48px,5vw,56px);
  border-radius:10px;
  border:1px solid rgba(108,174,255,.18);
  background:linear-gradient(165deg,rgba(8,18,36,.92),rgba(5,10,20,.88));
  box-shadow:0 12px 36px rgba(0,0,0,.28);
}
.d-coll-case::after{
  content:"";
  position:absolute;inset-inline-start:12px;inset-block-start:var(--pad-card);
  width:18px;height:18px;border-radius:50%;
  border:3px solid var(--surface-base);
  background:var(--blue);
  box-shadow:0 0 0 2px rgba(108,174,255,.35),0 0 14px rgba(108,174,255,.4);
  z-index:2;
}
.d-coll-case.is-map{
  border-color:rgba(218,37,29,.28);
  background:linear-gradient(165deg,rgba(218,37,29,.08) 0%,rgba(8,18,36,.94) 38%,rgba(5,10,20,.9));
}
.d-coll-case.is-map::after{background:var(--red);box-shadow:0 0 0 2px rgba(218,37,29,.35),0 0 14px rgba(218,37,29,.45)}
.d-coll-case__head{display:flex;flex-direction:column;align-items:flex-start;gap:10px}
.d-coll-case__tag{margin:0}
.d-coll-case__tag{border-color:rgba(108,174,255,.32);background:linear-gradient(135deg,rgba(108,174,255,.12),rgba(0,43,91,.06));color:rgba(200,225,255,.95)}
.d-coll-case__tag::before{background:var(--blue);box-shadow:0 0 8px rgba(108,174,255,.45)}
.d-coll-case.is-map .d-coll-case__tag{border-color:rgba(218,37,29,.32);background:linear-gradient(135deg,rgba(218,37,29,.12),rgba(218,37,29,.04));color:rgba(255,200,190,.95)}
.d-coll-case.is-map .d-coll-case__tag::before{background:var(--red);box-shadow:0 0 8px rgba(218,37,29,.5)}
.d-coll-case h4{margin:0;font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.9vw,23px);color:var(--white);line-height:1.32;max-width:52ch}
.d-coll-case__body{margin:0;max-width:62ch}
/* Khối trực quan khoảng cách */
.d-coll-viz{
  display:flex;flex-direction:column;gap:14px;
  padding:clamp(18px,2.4vw,22px);
  border-radius:8px;
  border:1px solid rgba(108,174,255,.22);
  background:rgba(0,43,91,.22);
}
.d-coll-case.is-map .d-coll-viz{border-color:rgba(218,37,29,.22);background:rgba(218,37,29,.06)}
.d-coll-viz__hero{
  display:flex;flex-wrap:wrap;align-items:baseline;gap:8px 14px;
}
.d-coll-viz__num{
  font-family:var(--font-display);font-weight:700;
  font-size:clamp(40px,5.5vw,56px);line-height:1;
  color:var(--gold);
}
.d-coll-case.is-map .d-coll-viz__num{color:var(--gold)}
.d-coll-viz__unit{
  font-family:var(--font-sans);font-size:clamp(14px,1vw,15px);font-weight:500;
  color:rgba(245,237,214,.65);line-height:1.4;max-width:16em;
}
.d-coll-meter{margin:0}
.d-coll-meter__labels{
  display:flex;justify-content:space-between;align-items:center;
  margin-bottom:8px;
  font-family:var(--font-sans);font-size:12px;font-weight:600;
  letter-spacing:.04em;color:rgba(245,237,214,.5);
}
.d-coll-meter__labels .end{
  color:var(--blue);
  text-align:right;
  font-size:11px;letter-spacing:1px;text-transform:uppercase;
}
.d-coll-meter__track{
  position:relative;height:22px;border-radius:11px;
  background:rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.06);
}
.d-coll-meter__fill{
  display:block;height:100%;width:0;border-radius:10px;
  background:linear-gradient(90deg,rgba(0,43,91,.9),var(--gold));
  transition:width 1.4s cubic-bezier(.2,.7,.2,1);
}
.d-coll-meter__cap{
  position:absolute;top:0;right:0;bottom:0;
  width:3px;border-radius:2px;
  background:var(--blue);
  box-shadow:0 0 12px rgba(108,174,255,.55);
  transform:none;
}
.d-coll-meter__legend{
  display:flex;flex-direction:column;align-items:flex-start;gap:6px;
  margin-top:10px;padding-top:10px;
  border-top:1px solid rgba(108,174,255,.15);
  font-family:var(--font-sans);font-size:clamp(15px,1vw,16px);font-weight:500;
  line-height:1.55;color:rgba(245,237,214,.82);
}
.d-coll-meter__legend .leg-top{display:flex;flex-wrap:wrap;align-items:baseline;gap:6px 10px}
.d-coll-meter__legend .leg-row{display:block;width:100%}
.d-coll-meter__legend .sep{opacity:.45;user-select:none}
.d-coll-meter__legend b{color:var(--gold);font-weight:700}
.d-coll-meter__legend .rest{color:rgba(108,174,255,.85);font-weight:600}
/* So sánh bản đồ / thực tế (ví dụ 3) */
.d-coll-dual{
  display:grid;grid-template-columns:1fr 1fr;gap:var(--gap-card);
  margin:0;
}
.d-coll-dual__card{
  display:flex;flex-direction:column;gap:8px;
  padding:clamp(18px,2.2vw,22px);
  border-radius:8px;
  border:1px solid var(--line);
  background:rgba(5,10,20,.5);
  text-align:center;
}
.d-coll-dual__card.is-real{
  border-color:rgba(218,37,29,.35);
  background:rgba(218,37,29,.08);
}
.d-coll-dual__lbl{
  margin:0;
  font-family:var(--font-sans);font-size:10px;font-weight:700;
  letter-spacing:1.8px;text-transform:uppercase;
  color:rgba(245,237,214,.55);
}
.d-coll-dual__num{
  margin:0;
  font-family:var(--font-display);font-weight:700;
  font-size:clamp(36px,5vw,48px);line-height:1;
  color:var(--gold);
}
.d-coll-dual__card.is-real .d-coll-dual__num{color:var(--red-soft)}
.d-coll-dual__sub{
  margin:0;
  font-family:var(--font-sans);font-size:clamp(14px,.95vw,15px);font-weight:300;
  line-height:1.45;color:rgba(245,237,214,.7);
}
.d-coll-dual__arrow{
  display:none;
}
.d-coll-viz__note{
  margin:0;
  font-family:var(--font-sans);font-size:clamp(14px,.95vw,15px);font-weight:500;
  line-height:1.5;color:rgba(108,174,255,.9);
  text-align:center;
}
.d-coll-case.is-map .d-coll-viz__note{color:rgba(255,200,190,.9)}
/* Kết luận từng ví dụ */
.d-coll-case__insight{
  margin:0;padding:14px 16px 14px 38px;
  border-radius:8px;
  border:1px solid rgba(108,174,255,.22);
  background:rgba(108,174,255,.07);
  font-family:var(--font-sans);font-size:clamp(16px,1.05vw,18px);font-weight:400;
  line-height:1.65;color:rgba(245,237,214,.92);
  position:relative;
}
.d-coll-case__insight::before{
  content:"→";
  position:absolute;inset-inline-start:14px;inset-block-start:14px;
  font-weight:700;font-size:16px;line-height:1;
  color:var(--blue);
  width:auto;height:auto;margin:0;border-radius:0;background:none;box-shadow:none;
}
.d-coll-case.is-map .d-coll-case__insight{
  border-color:rgba(218,37,29,.28);background:rgba(218,37,29,.08);
}
.d-coll-case.is-map .d-coll-case__insight::before{color:var(--red)}
/* Dịch chuyển đoạn 2 */
.d-coll-shift{
  display:flex;flex-direction:column;gap:var(--gap-card);
  padding:var(--pad-card);
  padding-inline-start:clamp(48px,5vw,56px);
  border-radius:10px;
  border:1px solid rgba(218,37,29,.35);
  background:linear-gradient(135deg,rgba(218,37,29,.12),rgba(5,10,20,.85));
  position:relative;
}
.d-coll-shift::before{
  content:"+";
  position:absolute;inset-inline-start:12px;inset-block-start:var(--pad-card);
  width:18px;height:18px;border-radius:50%;
  font-family:var(--font-display);font-weight:700;font-size:13px;line-height:18px;text-align:center;
  color:var(--white);background:var(--red);
  box-shadow:0 0 14px rgba(218,37,29,.45);
}
.d-coll-shift__badge{
  display:inline-flex;align-items:baseline;gap:10px;flex-wrap:wrap;
}
.d-coll-shift__num{
  font-family:var(--font-display);font-weight:700;
  font-size:clamp(32px,4.5vw,44px);line-height:1;color:var(--red-soft);
}
.d-coll-shift__num span{
  display:inline;
  margin-left:.15em;
  font-family:var(--font-sans);font-size:.42em;font-weight:700;
  letter-spacing:1.4px;text-transform:uppercase;
  color:rgba(245,237,214,.6);vertical-align:baseline;
}
.d-coll-shift h4{
  margin:0;font-family:var(--font-serif-title);font-weight:600;
  font-size:clamp(19px,1.9vw,22px);color:var(--white);line-height:1.32;
}
.d-coll-shift .prose-body{margin:0;max-width:62ch}
.d-coll__stamp{position:relative;z-index:1;margin-top:clamp(18px,2.2vw,24px);padding-top:clamp(20px,2.4vw,26px);border-top:1px solid rgba(108,174,255,.2)}
.d-coll__stamp .prose-body strong{color:var(--white);font-weight:600}

.dispute-voices{margin-top:var(--intra-gap-md)}
.dispute-voices .voices-eyebrow{margin-bottom:18px}
.dvq-grid{display:grid;grid-template-columns:1.15fr 1fr;gap:var(--gap-grid);align-items:stretch}
.dvq-card{position:relative;display:flex;flex-direction:column;min-height:100%;border:1px solid var(--line);border-radius:8px;background:var(--surface-elevated);padding:clamp(26px,3vw,32px) clamp(24px,2.6vw,28px);transition:border-color .3s,transform .3s}
.dvq-card:hover{border-color:rgba(255,204,0,.25);transform:translateY(-2px)}
.dvq-card.is-wide{grid-column:1/-1;background:linear-gradient(155deg,rgba(0,43,91,.25),rgba(8,18,36,.5))}
.dvq-card__kind{font-family:var(--font-sans);font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--blue);margin-bottom:16px}
.dvq-card p{flex:1 1 auto;font-family:var(--font-serif-text);font-style:italic;font-size:clamp(20px,2.2vw,25px);font-weight:500;line-height:1.6;color:rgba(245,237,214,.88);margin:0}
.dvq-card p strong{color:var(--gold);font-style:normal;font-weight:600}
.dvq-card .vq-cite{margin-top:auto;padding-top:clamp(20px,2.4vw,28px)}

/* ---------- 04 Quốc tế phản đối (typography đồng bộ) ---------- */
#opposition .prose-lead,#opposition .prose-body{max-width:62ch}
#opposition .op-voice .prose-body{max-width:none}
.op-voices{margin-top:var(--intra-gap);display:flex;flex-direction:column;gap:var(--gap-card)}
.op-voice{display:grid;grid-template-columns:minmax(112px,132px) 1fr;gap:clamp(18px,2.6vw,28px);padding:clamp(24px,3vw,32px);border:1px solid var(--line);border-radius:8px;background:var(--surface-elevated);align-items:start;transition:border-color .35s}
.op-voice:hover{border-color:rgba(108,174,255,.22)}
.op-voice__yr{font-family:var(--font-display);font-weight:700;font-size:clamp(32px,3.6vw,42px);line-height:1;color:var(--gold)}
.op-voice__yr small{display:block;margin-top:6px;font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(245,237,214,.5)}
.op-voice h4{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(19px,1.8vw,22px);color:var(--white);line-height:1.28;margin:0 0 12px}
.op-voice .prose-body{margin:0 0 16px}
.op-chips{display:flex;flex-wrap:wrap;gap:8px}
.chip{display:inline-block;font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;padding:6px 12px;border-radius:99px;border:1px solid var(--line-2);color:rgba(245,237,214,.7);background:rgba(255,255,255,.04)}
.chip.gold{border-color:rgba(255,204,0,.35);color:rgba(255,220,120,.95);background:rgba(255,204,0,.08)}
.chip.red{border-color:rgba(218,37,29,.35);color:rgba(255,200,190,.95);background:rgba(218,37,29,.08)}
.chip.blue{border-color:rgba(108,174,255,.35);color:rgba(200,225,255,.95);background:rgba(108,174,255,.08)}

/* ---------- 05 Nhân chứng thầm lặng — bản đồ cổ ---------- */
#witnesses{
  background:linear-gradient(180deg,var(--surface-base),#0a0c10);
  --am-lead:clamp(20px,2.1vw,25px);
  --am-year:clamp(22px,2.5vw,34px);
  --am-title:clamp(22px,2.8vw,30px);
  --am-body:clamp(17px,1.28vw,19.5px);
}
#witnesses .prose-lead{max-width:62ch;font-size:var(--am-lead);line-height:1.68;color:rgba(245,237,214,.78)}
#witnesses .prose-lead em{color:var(--gold);font-style:normal;font-weight:600}
.ancient-maps-timeline{margin-top:var(--intra-gap);display:flex;flex-direction:column;gap:0;max-width:960px;margin-left:auto;margin-right:auto}
.ancient-map-entry{display:grid;grid-template-columns:28px minmax(96px,132px) minmax(0,1fr);gap:0 clamp(16px,2.5vw,28px);padding:0 0 clamp(32px,4vw,44px);position:relative}
.ancient-map-entry:last-child{padding-bottom:0}
.ancient-map-entry__rail{grid-column:1;grid-row:1/-1;display:flex;flex-direction:column;align-items:center;padding-top:6px}
.ancient-map-entry__dot{width:12px;height:12px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 4px rgba(255,204,0,.18);flex-shrink:0}
.ancient-map-entry__line{flex:1;width:2px;margin-top:6px;background:linear-gradient(180deg,rgba(255,204,0,.45),rgba(255,204,0,.08));min-height:24px}
.ancient-map-entry__year{grid-column:2;grid-row:1;font-family:var(--font-display);font-weight:700;font-size:var(--am-year);letter-spacing:.05em;color:var(--gold);line-height:1.2;padding-top:4px;align-self:start}
.ancient-map-entry__content{grid-column:3;grid-row:1;min-width:0;border:1px solid rgba(212,175,55,.22);background:linear-gradient(165deg,rgba(12,19,34,.92),rgba(5,10,20,.96));border-radius:8px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,.35)}
.ancient-map-entry__figure{position:relative;margin:0;background:#0a1120;border-bottom:1px solid rgba(212,175,55,.15);min-height:200px}
.ancient-map-entry__zoom{position:relative;display:block;width:100%;margin:0;padding:0;border:none;background:transparent;line-height:0;cursor:none;text-align:left}
.ancient-map-entry__zoom:focus-visible{outline:2px solid rgba(255,204,0,.75);outline-offset:-3px}
.ancient-map-entry__figure img{width:100%;height:auto;max-height:min(72vh,640px);object-fit:contain;object-position:center;background:#0c1018;pointer-events:none;transition:transform .55s cubic-bezier(.25,.46,.45,.94)}
.ancient-map-entry__zoom:hover img,.ancient-map-entry__zoom:focus-visible img{transform:scale(1.02)}
.ancient-map-entry__zoom-hint{position:absolute;top:14px;right:14px;z-index:1;display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;border:1px solid rgba(255,204,0,.38);background:rgba(5,10,20,.72);color:var(--gold);opacity:0;transform:scale(.92);transition:opacity .28s,transform .28s,background .25s;pointer-events:none}
.ancient-map-entry__zoom:hover .ancient-map-entry__zoom-hint,.ancient-map-entry__zoom:focus-visible .ancient-map-entry__zoom-hint{opacity:1;transform:scale(1)}
.ancient-map-entry__zoom:hover .ancient-map-entry__zoom-hint{background:rgba(218,37,29,.45);border-color:var(--gold)}
.ancient-map-entry__title{font-family:var(--font-serif-title);font-weight:700;font-size:var(--am-title);color:var(--white);line-height:1.25;margin:0;padding:clamp(16px,2vw,20px) clamp(16px,2vw,20px) 8px}
.ancient-map-entry__text{padding:0 clamp(16px,2vw,20px) clamp(16px,2vw,20px);max-width:none;font-size:var(--am-body);line-height:1.68;color:rgba(245,237,214,.88)}
.ancient-map-entry__text p{margin:0 0 .65em}
.ancient-map-entry__text p:last-child{margin-bottom:0}
.ancient-map-entry__text strong{color:var(--cream);font-weight:600}
.ancient-map-entry__text em{color:var(--gold);font-style:italic}
.wit-quote{margin:clamp(28px,3.5vw,40px) auto 0;max-width:54ch;text-align:center;font-family:var(--font-serif-text);font-style:italic;font-weight:500;font-size:clamp(22px,2.4vw,28px);line-height:1.45;color:#e7d9a8}
/* Lightbox bản đồ cổ (đồng bộ hoangsa timeline) */
.timeline-lightbox{--lb-pad-y:clamp(14px,2.5vh,22px);--lb-pad-x:clamp(20px,4vw,32px);--lb-cap-h:48px;position:fixed;inset:0;z-index:1280;box-sizing:border-box;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:var(--lb-pad-y) var(--lb-pad-x);background:rgba(2,6,14,.94);backdrop-filter:blur(12px);opacity:0;visibility:hidden;transition:opacity .35s,visibility .35s}
.timeline-lightbox.is-open{opacity:1;visibility:visible}
.timeline-lightbox-viewport{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:0;width:100%;max-height:calc(100dvh - var(--lb-pad-y)*2)}
.timeline-lightbox-frame{flex:1;display:flex;align-items:center;justify-content:center;min-height:0;width:100%;max-height:calc(100dvh - var(--lb-pad-y)*2 - var(--lb-cap-h))}
.timeline-lightbox-frame img{display:block;width:auto;height:auto;max-width:100%;max-height:100%;object-fit:contain;border-radius:3px;box-shadow:0 20px 60px rgba(0,0,0,.55)}
.timeline-lightbox-meta{flex-shrink:0;display:flex;flex-wrap:wrap;align-items:baseline;justify-content:center;gap:6px 14px;padding-top:clamp(8px,1.2vh,12px);max-width:calc(100vw - var(--lb-pad-x)*2);text-align:center}
.timeline-lightbox-index{font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,204,0,.85)}
.timeline-lightbox-era{font-family:var(--font-sans);font-size:clamp(11px,1.4vw,13px);font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:rgba(245,237,214,.62)}
.timeline-lightbox-title{flex:1 1 100%;font-family:var(--font-serif-title);font-size:clamp(15px,2vw,19px);font-weight:600;line-height:1.35;color:var(--cream)}
.timeline-lightbox-close{position:absolute;top:20px;right:20px;z-index:1281;width:44px;height:44px;border:1px solid rgba(255,204,0,.35);background:rgba(5,10,20,.8);color:var(--gold);font-size:24px;line-height:1;cursor:none}
.timeline-lightbox-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:1281;width:48px;height:48px;border:1px solid rgba(255,204,0,.3);background:rgba(5,10,20,.75);color:var(--gold);font-size:20px;cursor:none}
.timeline-lightbox-prev{left:clamp(10px,2vw,20px)}
.timeline-lightbox-next{right:clamp(10px,2vw,20px)}
body.is-lightboxOpen .site-fab,body.is-lightboxOpen #mainNav{pointer-events:none}
@media(orientation:landscape){
  .timeline-lightbox{--lb-cap-h:88px;--lb-pad-x:clamp(56px,9vw,80px)}
  .timeline-lightbox-frame{height:calc(100dvh - var(--lb-pad-y)*2 - var(--lb-cap-h));max-height:calc(100dvh - var(--lb-pad-y)*2 - var(--lb-cap-h))}
}
@media(orientation:portrait){
  .timeline-lightbox{--lb-cap-h:96px}
  .timeline-lightbox-frame{width:calc(100vw - var(--lb-pad-x)*2);max-width:calc(100vw - var(--lb-pad-x)*2);max-height:calc(100dvh - var(--lb-pad-y)*2 - var(--lb-cap-h))}
  .timeline-lightbox-frame img{width:100%;max-width:100%;height:auto;max-height:calc(100dvh - var(--lb-pad-y)*2 - var(--lb-cap-h))}
}
@media(max-width:768px){
  .ancient-map-entry{grid-template-columns:20px 1fr;grid-template-rows:auto auto}
  .ancient-map-entry__rail{grid-column:1;grid-row:1/-1}
  .ancient-map-entry__year{grid-column:2;grid-row:1;margin-bottom:8px;font-size:clamp(20px,5vw,28px)}
  .ancient-map-entry__content{grid-column:1/-1;grid-row:2}
}

/* ---------- 06 Phán quyết PCA (typography đồng bộ) ---------- */
#verdict{background:radial-gradient(88% 52% at 80% 4%,rgba(255,204,0,.09) 0%,transparent 56%),radial-gradient(72% 48% at 12% 4%,rgba(0,80,160,.3) 0%,transparent 60%),linear-gradient(180deg,#040d18 0%,#051422 44%,#060f1a 100%)}
#verdict .prose-lead,#verdict .prose-body{max-width:62ch}
.pca-no3{margin-top:var(--intra-gap);display:grid;grid-template-columns:repeat(3,1fr);gap:var(--gap-grid);align-items:stretch}
.pca-card{position:relative;display:flex;flex-direction:column;border:1px solid var(--line);background:linear-gradient(180deg,rgba(255,255,255,.04),transparent);padding:clamp(28px,3.2vw,36px) clamp(24px,2.6vw,30px);cursor:pointer;transition:border-color .4s,transform .4s;overflow:hidden;border-radius:8px}
.pca-card:hover{border-color:rgba(255,204,0,.4);transform:translateY(-5px)}
.pca-card .khong{font-family:var(--font-display);font-weight:700;font-size:clamp(40px,5vw,60px);color:var(--red);line-height:.9;letter-spacing:1px}
.pca-card h3{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(20px,2vw,24px);color:var(--white);line-height:1.25;margin:12px 0 14px;text-transform:lowercase}
.pca-card h3::first-letter{text-transform:uppercase}
.pca-card .short{flex:1 1 auto;font-family:var(--font-sans);font-size:clamp(17px,1.05vw,19px);font-weight:300;line-height:1.65;color:rgba(245,237,214,.88);margin:0}
.pca-card .quote{max-height:0;overflow:hidden;opacity:0;transition:max-height .55s ease,opacity .5s,margin .5s;margin-top:0}
.pca-card.open .quote{max-height:360px;opacity:1;margin-top:18px}
.pca-card .quote p{margin:0;padding-top:16px;border-top:1px solid var(--line);font-family:var(--font-serif-text);font-style:italic;font-weight:500;font-size:clamp(18px,1.9vw,22px);line-height:1.55;color:rgba(245,237,214,.88)}
.pca-card .more{margin-top:16px;font-family:var(--font-sans);font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gold)}
.pca-extra{margin-top:var(--intra-gap);display:grid;grid-template-columns:repeat(2,1fr);gap:var(--gap-card) var(--gap-grid)}
.pca-extra .ri{display:flex;gap:12px;align-items:flex-start;padding:clamp(18px,2.2vw,22px);border:1px solid var(--line);border-radius:8px;background:rgba(5,10,20,.45)}
.pca-extra .ri .rk{
  color:var(--gold);font-family:var(--font-display);font-weight:700;font-size:clamp(18px,2vw,22px);line-height:1;
  flex:0 0 auto;width:1.35em;min-width:1.35em;text-align:center;padding-top:2px;
}
.pca-extra .ri .prose-body{margin:0;flex:1;min-width:0;max-width:none;font-size:clamp(17px,1.05vw,19px)}
.pca-subtitle{margin-top:var(--intra-gap-md)}
.pca-unclos{margin-top:var(--gap-grid);display:grid;grid-template-columns:repeat(3,1fr);gap:var(--gap-card)}
.pca-zone{background:var(--surface-elevated);border:1px solid rgba(108,174,255,.22);border-radius:8px;padding:clamp(26px,3vw,32px) clamp(22px,2.4vw,28px);text-align:center}
.pca-zone .z{font-family:var(--font-display);font-weight:700;font-size:clamp(36px,4.2vw,48px);color:#8fc2ff;line-height:1;margin-bottom:10px}
.pca-zone h4{font-family:var(--font-serif-title);font-weight:600;font-size:clamp(18px,1.7vw,21px);color:var(--white);line-height:1.28;margin:0 0 10px}
.pca-zone .prose-body{margin:0;font-size:clamp(16px,1.05vw,18px);color:rgba(245,237,214,.8);max-width:28ch;margin-left:auto;margin-right:auto}
.pca-foot{margin-top:clamp(24px,3vw,32px);max-width:68ch}

/* ---------- 07 Kết luận — chủ quyền (gom nội dung, typography đồng bộ) ---------- */
#sovereignty{
  position:relative;
  overflow:hidden;
  background:
    radial-gradient(70% 55% at 18% 8%,rgba(255,204,0,.1) 0%,transparent 58%),
    radial-gradient(62% 48% at 88% 12%,rgba(218,37,29,.06) 0%,transparent 55%),
    linear-gradient(180deg,var(--surface-base) 0%,#0a0e08 46%,#120d06 100%);
}
#sovereignty::before{
  content:'§';
  position:absolute;top:5%;right:6%;
  font-family:var(--font-serif-title);
  font-size:clamp(96px,14vw,180px);
  color:rgba(255,204,0,.04);
  pointer-events:none;line-height:1;z-index:0;
}
#sovereignty .section-inner{position:relative;z-index:1}
#sovereignty .prose-lead,#sovereignty>.section-inner>.prose-lead{max-width:62ch}
#sovereignty .prose-lead em{font-style:italic;color:rgba(245,237,214,.85)}
#sovereignty .prose-lead strong{color:var(--gold);font-weight:600}
#sovereignty .prose-body em{color:var(--red);font-style:normal;font-weight:600}
#sovereignty .prose-body strong{color:var(--gold);font-weight:600}
#sovereignty .section-subtitle{
  font-family:var(--font-serif-title);
  font-size:clamp(22px,2.4vw,30px);
  font-weight:600;
  line-height:1.2;
  color:var(--white);
  letter-spacing:-.01em;
  margin:0;
}
#sovereignty .prose-caption{color:rgba(245,237,214,.5)}
#sovereignty .sov-spine__head .prose-caption,
#sovereignty .sov-balance__label{color:rgba(255,220,120,.58)}
#sovereignty .sov-thesis__label,
#sovereignty .sov-balance__tag,
#sovereignty .sov-next__eyebrow,
#sovereignty .sov-seal__cite{color:rgba(245,237,214,.5)}
#sovereignty .sov-balance__tag--vn{color:rgba(255,220,120,.82)}
#sovereignty .sov-balance__tag--dash{color:rgba(160,200,255,.78)}
#sovereignty .sov-chron__title,
#sovereignty .sov-balance__side h4{
  font-family:var(--font-serif-title);
  font-weight:600;
  font-size:clamp(19px,1.8vw,22px);
  line-height:1.28;
}
#sovereignty .sov-chron__title{color:var(--cream);margin:0 0 10px}
#sovereignty .sov-balance__side--vn h4{color:var(--gold);margin:0 0 14px}
#sovereignty .sov-balance__side--dash h4{color:var(--white);margin:0 0 14px}
#sovereignty .sov-thesis .prose-body,
#sovereignty .sov-chron .prose-body,
#sovereignty .sov-balance__side .prose-body{
  max-width:none;
  margin:0;
  font-size:clamp(18px,1.15vw,20px);
  line-height:1.7;
  color:rgba(245,237,214,.9);
}
#sovereignty .sov-thesis .prose-body{max-width:68ch}
#sovereignty .sov-chron .prose-body{max-width:62ch}
#sovereignty .sov-chron__yr,
#sovereignty .sov-spine__range{
  font-family:var(--font-display);
  font-weight:700;
  text-transform:uppercase;
}
#sovereignty .sov-chron__yr{
  font-size:clamp(16px,1.35vw,18px);
  line-height:1.1;
  letter-spacing:.04em;
}
#sovereignty .sov-spine__range{
  font-size:11px;
  letter-spacing:2px;
  color:rgba(255,220,120,.82);
}
#sovereignty .sov-thesis__locks li{
  font-family:var(--font-sans);
  font-size:clamp(13px,.95vw,14px);
  font-weight:600;
  letter-spacing:.04em;
  color:rgba(245,237,214,.92);
}
#sovereignty .sov-seal__quote{
  font-family:var(--font-serif-text);
  font-style:italic;
  font-weight:500;
  font-size:clamp(20px,2.1vw,25px);
  line-height:1.6;
  color:rgba(245,237,214,.88);
}
#sovereignty .sov-seal__quote strong{color:var(--gold);font-style:normal;font-weight:600}
#sovereignty .sov-next .prose-body{max-width:52ch;color:rgba(245,237,214,.9)}
#sovereignty .sov-next__cta{
  font-family:var(--font-sans);
  font-size:clamp(15px,1vw,16px);
  font-weight:600;
  letter-spacing:.02em;
}
.warm-glow{position:absolute;left:50%;top:22%;transform:translateX(-50%);width:80vw;max-width:960px;height:600px;background:radial-gradient(circle,rgba(255,204,0,.11),transparent 62%);filter:blur(34px);pointer-events:none}

/* — Luận điểm tổng hợp (stamp + 3 khóa) — */
.sov-thesis{
  position:relative;
  margin-top:var(--intra-gap);
  padding:var(--pad-card) clamp(28px,3.6vw,44px);
  border-radius:8px;
  border:1px solid rgba(255,204,0,.22);
  background:linear-gradient(155deg,rgba(255,204,0,.09) 0%,rgba(10,18,34,.96) 32%,rgba(5,10,20,.94) 100%);
  box-shadow:0 32px 72px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.05);
  overflow:hidden;
}
.sov-thesis::before{
  content:'';
  position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--red) 0%,var(--gold) 42%,rgba(255,204,0,.35) 100%);
}
.sov-thesis__glow{position:absolute;top:-40%;right:-6%;width:min(480px,58%);height:130%;background:radial-gradient(circle,rgba(255,204,0,.14),transparent 68%);pointer-events:none}
.sov-thesis__glow--warm{left:-10%;right:auto;top:auto;bottom:-45%;width:min(380px,50%);background:radial-gradient(circle,rgba(218,37,29,.1),transparent 70%)}
.sov-thesis__head{
  position:relative;z-index:1;
  display:flex;align-items:center;gap:14px;
  margin-bottom:clamp(22px,2.8vw,28px);
}
.sov-thesis__badge{
  flex-shrink:0;
  font-family:var(--font-display);
  font-weight:700;
  font-size:clamp(28px,3.2vw,36px);
  line-height:1;
  color:rgba(255,204,0,.22);
  letter-spacing:1px;
}
.sov-thesis__label{color:var(--gold);letter-spacing:2px}
.sov-thesis__rule{
  flex:1;height:3px;border-radius:99px;
  background:repeating-linear-gradient(90deg,var(--gold) 0 10px,transparent 10px 20px);
  opacity:.4;
}
.sov-thesis__locks{
  position:relative;z-index:1;
  display:flex;flex-wrap:wrap;gap:10px;
  margin:0 0 clamp(24px,3vw,32px);padding:0;list-style:none;
}
.sov-thesis__locks li{
  padding:10px 16px;
  border-radius:99px;
  border:1px solid rgba(255,204,0,.3);
  background:rgba(255,204,0,.07);
  box-shadow:0 6px 20px rgba(0,0,0,.22);
}
.sov-thesis__locks li::before{
  content:'';
  display:inline-block;width:5px;height:5px;margin-right:8px;
  border-radius:50%;background:var(--red);
  box-shadow:0 0 8px rgba(218,37,29,.5);
  vertical-align:middle;position:relative;top:-1px;
}
.sov-thesis__body{
  position:relative;z-index:1;
  padding-left:clamp(20px,2.4vw,28px);
  border-left:3px solid rgba(255,204,0,.45);
}
.sov-thesis__body::before{
  content:'“';
  position:absolute;left:-2px;top:-18px;
  font-family:var(--font-serif-title);
  font-size:clamp(56px,6vw,72px);
  line-height:1;
  color:rgba(255,204,0,.12);
  pointer-events:none;
}

/* — Biên niên sổ dọc (sov-spine) — */
.sov-spine{margin-top:var(--intra-gap-md)}
.sov-spine__head{
  display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;
  gap:12px 24px;
  margin-bottom:clamp(26px,3.2vw,36px);
}
.sov-spine__head .prose-caption{margin:0 0 8px}
.sov-spine__range{
  display:inline-flex;align-items:center;gap:10px;
  padding:10px 16px;
  border-radius:99px;
  border:1px solid rgba(255,204,0,.22);
  background:rgba(255,204,0,.06);
}
.sov-spine__range svg{width:14px;height:14px;opacity:.7;flex-shrink:0}

.sov-chronicle{
  --sov-pad-x:clamp(16px,2vw,22px);
  --sov-rail:44px;
  --sov-year:clamp(100px,11vw,120px);
  --sov-gap:clamp(16px,2.2vw,24px);
  position:relative;
  border-radius:8px;
  border:1px solid rgba(255,204,0,.14);
  background:linear-gradient(180deg,rgba(255,204,0,.03) 0%,rgba(4,10,22,.92) 18%);
  box-shadow:0 32px 72px rgba(0,0,0,.42),inset 0 1px 0 rgba(255,255,255,.04);
  overflow:hidden;
}
.sov-chronicle::before{
  content:'';
  position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--gold),rgba(255,204,0,.35) 70%,transparent);
}
.sov-chronicle__axis{
  position:absolute;
  left:calc(var(--sov-pad-x) + var(--sov-rail) / 2 - 1.5px);
  top:28px;bottom:28px;
  width:3px;border-radius:99px;
  background:rgba(255,204,0,.12);
  overflow:hidden;
  z-index:0;
}
.sov-chronicle__fill{
  display:block;width:100%;height:0%;
  background:linear-gradient(180deg,var(--gold),rgba(255,204,0,.45));
  border-radius:inherit;
  transition:height .6s ease;
}
.sov-chron{
  position:relative;z-index:1;
  display:grid;
  grid-template-columns:var(--sov-rail) var(--sov-year) minmax(0,1fr);
  gap:0 var(--sov-gap);
  align-items:center;
  padding:clamp(22px,2.6vw,28px) clamp(22px,2.6vw,28px) clamp(22px,2.6vw,28px) var(--sov-pad-x);
  border-top:1px solid rgba(255,255,255,.06);
  transition:background .4s;
}
.sov-chron:first-child{border-top:0}
.sov-chron[data-cursor]{cursor:none}
.sov-chron:hover,.sov-chron.is-active{
  background:linear-gradient(90deg,rgba(255,204,0,.07),transparent 68%);
}
.sov-chron--now .sov-chron__yr{
  color:var(--white);
  background:linear-gradient(135deg,rgba(255,204,0,.35),rgba(218,37,29,.2));
  border-color:rgba(255,204,0,.5);
  box-shadow:0 8px 28px rgba(255,204,0,.15);
}
.sov-chron__marker{
  grid-column:1;
  display:flex;
  align-items:center;
  justify-content:center;
  align-self:center;
  z-index:2;
}
.sov-chron__dot{
  display:block;width:14px;height:14px;
  border-radius:50%;
  border:3px solid var(--surface-base);
  background:var(--gold);
  box-shadow:0 0 0 2px rgba(255,204,0,.35),0 0 16px rgba(255,204,0,.4);
  transition:transform .35s,box-shadow .35s;
}
.sov-chron:hover .sov-chron__dot,.sov-chron.is-active .sov-chron__dot{
  transform:scale(1.25);
  box-shadow:0 0 0 3px rgba(255,204,0,.5),0 0 24px rgba(255,204,0,.55);
}
.sov-chron__yr{
  grid-column:2;
  display:flex;align-items:center;justify-content:center;
  align-self:center;
  min-height:52px;
  padding:10px 8px;
  text-align:center;
  color:var(--gold);
  border:1px solid rgba(255,204,0,.22);
  border-radius:8px;
  background:rgba(0,8,18,.55);
}
.sov-chron__main{grid-column:3}
.sov-chron__main{
  align-self:center;
  min-width:0;
  padding:clamp(14px,1.8vw,18px) clamp(18px,2.2vw,24px);
  border-radius:8px;
  border:1px solid transparent;
  transition:border-color .35s,box-shadow .35s;
}
.sov-chron:hover .sov-chron__main,.sov-chron.is-active .sov-chron__main{
  border-color:rgba(255,204,0,.14);
  background:rgba(255,255,255,.02);
  box-shadow:0 12px 32px rgba(0,0,0,.2);
}
/* — Cân bằng đối chiếu (sov-balance) — */
.sov-balance{margin-top:var(--intra-gap)}
.sov-balance__label{margin:0 0 clamp(20px,2.6vw,28px);text-align:center}
.sov-balance__stage{
  position:relative;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:var(--gap-grid);
  padding-top:8px;
}
.sov-balance__medallion{
  position:absolute;
  left:50%;top:50%;
  transform:translate(-50%,-50%);
  z-index:3;
  width:clamp(56px,6vw,72px);
  height:clamp(56px,6vw,72px);
  display:flex;align-items:center;justify-content:center;
  border-radius:50%;
  border:2px solid rgba(255,204,0,.45);
  background:radial-gradient(circle at 35% 30%,#1a2840,#050a14 68%);
  box-shadow:0 0 0 6px rgba(5,10,20,.9),0 16px 40px rgba(0,0,0,.5),0 0 32px rgba(255,204,0,.12);
  font-size:clamp(26px,3vw,32px);
  line-height:1;
  pointer-events:none;
}
.sov-balance__side{
  position:relative;
  display:flex;flex-direction:column;
  min-height:100%;
  padding:clamp(28px,3.2vw,36px) clamp(26px,2.8vw,32px);
  border-radius:8px;
  overflow:hidden;
  transition:transform .4s,box-shadow .4s,border-color .4s;
}
.sov-balance__side[data-cursor]{cursor:none}
.sov-balance__side::before{
  content:'';
  position:absolute;top:0;left:0;right:0;height:4px;
}
.sov-balance__side--vn{
  border:1px solid rgba(255,204,0,.28);
  background:
    radial-gradient(90% 70% at 0% 0%,rgba(255,204,0,.14),transparent 55%),
    linear-gradient(165deg,rgba(12,20,36,.95),rgba(5,10,20,.98));
  box-shadow:0 20px 48px rgba(0,0,0,.35);
}
.sov-balance__side--vn::before{background:linear-gradient(90deg,var(--gold),rgba(255,204,0,.4))}
.sov-balance__side--vn:hover{
  transform:translateY(-4px);
  border-color:rgba(255,204,0,.45);
  box-shadow:0 28px 56px rgba(0,0,0,.4),0 0 40px rgba(255,204,0,.08);
}
.sov-balance__side--dash{
  border:1px solid rgba(108,174,255,.22);
  background:
    radial-gradient(90% 70% at 100% 0%,rgba(108,174,255,.1),transparent 55%),
    linear-gradient(195deg,rgba(8,16,32,.95),rgba(5,10,20,.98));
  box-shadow:0 20px 48px rgba(0,0,0,.35);
}
.sov-balance__side--dash::before{
  background:repeating-linear-gradient(90deg,rgba(108,174,255,.7) 0 8px,rgba(108,174,255,.2) 8px 16px);
}
.sov-balance__side--dash:hover{
  transform:translateY(-4px);
  border-color:rgba(108,174,255,.38);
  box-shadow:0 28px 56px rgba(0,0,0,.4),0 0 28px rgba(108,174,255,.06);
}
.sov-balance__cap{
  display:flex;align-items:center;gap:12px;
  margin-bottom:clamp(18px,2.2vw,22px);
}
.sov-balance__glyph{
  flex-shrink:0;
  width:40px;height:40px;
  display:flex;align-items:center;justify-content:center;
  border-radius:8px;
  font-size:20px;line-height:1;
}
.sov-balance__side--vn .sov-balance__glyph{
  color:var(--gold);
  border:1px solid rgba(255,204,0,.35);
  background:rgba(255,204,0,.1);
}
.sov-balance__side--dash .sov-balance__glyph{
  font-family:var(--font-display);
  font-weight:700;
  font-size:11px;
  letter-spacing:1px;
  color:rgba(160,200,255,.95);
  border:1px solid rgba(108,174,255,.35);
  background:rgba(108,174,255,.08);
}
.sov-balance__side--dash .sov-balance__glyph svg{width:22px;height:22px}
.sov-balance__side .prose-body{flex:1 1 auto}

/* — Con dấu lập trường (seal) — */
.sov-seal{
  margin:clamp(52px,6.5vw,68px) auto 0;
  max-width:768px;
  padding:0;
  border:0;
}
.sov-seal__frame{
  position:relative;
  padding:clamp(36px,4.2vw,52px) clamp(32px,3.8vw,48px);
  border-radius:8px;
  text-align:center;
  border:1px solid rgba(255,204,0,.28);
  background:
    radial-gradient(120% 80% at 50% 0%,rgba(255,204,0,.12),transparent 55%),
    linear-gradient(180deg,rgba(12,22,40,.95),rgba(5,10,20,.98));
  box-shadow:0 28px 64px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.06);
  overflow:hidden;
}
.sov-seal__orbit{
  position:absolute;inset:14px;
  border-radius:10px;
  border:1px dashed rgba(255,204,0,.2);
  pointer-events:none;
  animation:sovSealPulse 5s ease-in-out infinite alternate;
}
@keyframes sovSealPulse{to{border-color:rgba(255,204,0,.38);opacity:.85}}
.sov-seal__corners{
  position:absolute;inset:20px;
  pointer-events:none;
}
.sov-seal__corners span{
  position:absolute;width:18px;height:18px;
  border-color:rgba(255,204,0,.45);
  border-style:solid;
  border-width:0;
}
.sov-seal__corners span:nth-child(1){top:0;left:0;border-top-width:2px;border-left-width:2px}
.sov-seal__corners span:nth-child(2){top:0;right:0;border-top-width:2px;border-right-width:2px}
.sov-seal__corners span:nth-child(3){bottom:0;left:0;border-bottom-width:2px;border-left-width:2px}
.sov-seal__corners span:nth-child(4){bottom:0;right:0;border-bottom-width:2px;border-right-width:2px}
.sov-seal__quote{
  position:relative;z-index:1;
  margin:0 auto;
  padding:0;
  border:0;
  background:transparent;
  max-width:54ch;
}
.sov-seal__quote::before{
  content:'“';
  display:block;
  font-family:var(--font-serif-title);
  font-style:normal;
  font-size:clamp(48px,5vw,64px);
  line-height:.85;
  color:rgba(255,204,0,.25);
  margin-bottom:8px;
}
.sov-seal__cite{
  position:relative;z-index:1;
  display:block;
  margin-top:clamp(22px,2.6vw,28px);
  padding-top:clamp(18px,2.2vw,22px);
  border-top:1px solid rgba(255,204,0,.16);
}
@media(prefers-reduced-motion:reduce){
  .sov-seal__orbit{animation:none}
  .sov-balance__side--vn:hover,.sov-balance__side--dash:hover{transform:none}
  .sov-chronicle__fill{transition:none}
}

.sov-next{
  margin-top:var(--intra-gap-md);
  display:grid;
  grid-template-columns:1fr auto;
  gap:clamp(20px,3vw,32px);
  align-items:center;
  padding:clamp(28px,3.2vw,36px) clamp(28px,3.4vw,40px);
  border:1px solid rgba(255,204,0,.28);
  border-radius:8px;
  background:linear-gradient(105deg,rgba(255,204,0,.08) 0%,rgba(0,15,35,.5) 48%,rgba(5,10,24,.85) 100%);
  box-shadow:0 24px 64px rgba(0,0,0,.35);
}
.sov-next__eyebrow{margin:0 0 10px;color:rgba(255,220,120,.75)}
.sov-next .section-subtitle{margin-bottom:12px}
.sov-next__cta{
  display:inline-flex;align-items:center;gap:10px;
  color:var(--dark);
  background:var(--gold);
  padding:14px 22px;
  border-radius:8px;
  white-space:nowrap;
  transition:transform .3s,box-shadow .3s,background .3s;
}
.sov-next__cta:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 32px rgba(255,204,0,.28);
  background:#ffe566;
}
.sov-next__cta svg{width:18px;height:18px;flex-shrink:0}

/* ---------- Footer — Biển Đông sâu (ocean trench) ---------- */
.site-foot{
  position:relative;
  overflow:hidden;
  background:
    radial-gradient(120% 80% at 50% -20%,rgba(108,174,255,.14) 0%,transparent 55%),
    radial-gradient(80% 60% at 12% 100%,rgba(0,43,91,.55) 0%,transparent 50%),
    radial-gradient(70% 50% at 92% 80%,rgba(218,37,29,.06) 0%,transparent 45%),
    linear-gradient(180deg,#061428 0%,#04101f 38%,#020a14 72%,#000511 100%);
  border-top:1px solid rgba(108,174,255,.18);
  padding:0 0 clamp(52px,6vw,72px);
}
.site-foot__inner{padding-top:clamp(72px,9vw,100px)!important;padding-bottom:clamp(12px,2vw,20px)!important}
#footer .prose-caption{color:rgba(143,194,255,.55)}
#footer .prose-body{color:rgba(200,225,255,.82);font-size:clamp(17px,1.05vw,19px);line-height:1.68}
#footer .prose-body strong{color:var(--gold);font-weight:600}
#footer a[data-cursor]{cursor:none}
#footer .foot-trench__legal a[data-cursor]{cursor:none}

.foot-waves{
  position:absolute;left:0;right:0;top:0;
  height:clamp(72px,10vw,110px);
  pointer-events:none;z-index:1;
  color:rgba(108,174,255,.35);
}
.foot-waves svg{width:100%;height:100%;display:block}
.foot-waves__layer{animation:footWaveDrift 14s ease-in-out infinite alternate}
.foot-waves__layer--2{animation-duration:18s;animation-direction:alternate-reverse;opacity:.65}
@keyframes footWaveDrift{to{transform:translateX(-2.5%)}}

.foot-void{
  position:absolute;inset:0;
  pointer-events:none;z-index:0;opacity:.07;
}
.foot-void svg{position:absolute;right:-8%;bottom:8%;width:min(520px,70vw);height:auto;transform:rotate(-8deg)}

.foot-crest{
  position:relative;z-index:2;
  text-align:center;
  max-width:768px;
  margin:0 auto clamp(40px,5vw,52px);
  padding:clamp(32px,4vw,44px) clamp(24px,3vw,32px);
  border:1px solid rgba(108,174,255,.22);
  border-radius:8px;
  background:linear-gradient(180deg,rgba(0,43,91,.45) 0%,rgba(2,10,22,.75) 100%);
  box-shadow:0 28px 64px rgba(0,0,0,.45),inset 0 1px 0 rgba(108,174,255,.12);
}
.foot-crest::before{
  content:'';
  position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,transparent,rgba(108,174,255,.7) 20%,var(--gold) 50%,rgba(108,174,255,.5) 80%,transparent);
}
.foot-crest__eyebrow{margin:0 0 16px;color:rgba(143,194,255,.7)}
.foot-crest__quote{
  margin:0;
  font-family:var(--font-serif-text);
  font-style:italic;
  font-weight:500;
  font-size:clamp(22px,2.8vw,36px);
  line-height:1.45;
  color:rgba(230,240,255,.92);
  max-width:28ch;
  margin-left:auto;margin-right:auto;
}
.foot-crest__quote b{color:var(--gold);font-style:normal;font-weight:600}
.foot-crest__sub{
  margin:clamp(18px,2.2vw,24px) 0 0;
  font-family:var(--font-sans);
  font-size:clamp(14px,.95vw,15px);
  font-weight:300;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:rgba(143,194,255,.45);
}

.foot-navstrip{
  position:relative;z-index:2;
  display:flex;flex-wrap:wrap;justify-content:center;gap:12px;
  margin-bottom:clamp(48px,6vw,64px);
}
.foot-btn{
  display:inline-flex;align-items:center;gap:10px;
  font-family:var(--font-sans);
  font-size:12px;font-weight:600;
  letter-spacing:2px;text-transform:uppercase;
  padding:14px 26px;
  border-radius:8px;
  transition:transform .3s,box-shadow .3s,border-color .3s,background .3s;
}
.foot-btn--gold{
  color:var(--dark);
  background:linear-gradient(180deg,#ffe566,var(--gold));
  border:1px solid rgba(255,204,0,.5);
  box-shadow:0 10px 28px rgba(255,204,0,.18);
}
.foot-btn--gold:hover{transform:translateY(-2px);box-shadow:0 14px 36px rgba(255,204,0,.28)}
.foot-btn--sea{
  color:rgba(200,225,255,.92);
  background:rgba(0,43,91,.35);
  border:1px solid rgba(108,174,255,.4);
}
.foot-btn--sea:hover{
  transform:translateY(-2px);
  border-color:rgba(108,174,255,.65);
  background:rgba(0,60,120,.45);
  box-shadow:0 12px 32px rgba(0,80,160,.25);
}
.foot-btn svg{width:16px;height:16px;flex-shrink:0;opacity:.85}

.foot-deck{
  position:relative;z-index:2;
  display:grid;
  grid-template-columns:1.35fr 1fr 1fr;
  gap:var(--gap-grid);
}
.foot-panel{
  padding:clamp(26px,3vw,32px) clamp(24px,2.6vw,28px);
  border-radius:8px;
  border:1px solid rgba(108,174,255,.14);
  background:linear-gradient(165deg,rgba(0,31,63,.55) 0%,rgba(2,10,22,.85) 100%);
  box-shadow:0 16px 40px rgba(0,0,0,.32);
  transition:border-color .35s,transform .35s;
}
.foot-panel:hover{border-color:rgba(108,174,255,.28);transform:translateY(-3px)}
.foot-panel__head{
  display:flex;align-items:center;gap:12px;
  margin-bottom:clamp(16px,2vw,20px);
  padding-bottom:14px;
  border-bottom:1px solid rgba(108,174,255,.12);
}
.foot-panel__icon{
  flex-shrink:0;width:36px;height:36px;
  display:flex;align-items:center;justify-content:center;
  border-radius:8px;
  border:1px solid rgba(108,174,255,.25);
  background:rgba(108,174,255,.08);
  color:rgba(143,194,255,.9);
  font-size:18px;line-height:1;
}
.foot-panel__title{
  font-family:var(--font-display);
  font-weight:700;
  font-size:11px;
  letter-spacing:2px;
  text-transform:uppercase;
  color:rgba(143,194,255,.75);
  margin:0;
}
.foot-brand__eyebrow{
  margin:0 0 12px;
  font-family:var(--font-sans);
  font-size:10px;font-weight:600;
  letter-spacing:2.2px;text-transform:uppercase;
  color:rgba(255,220,120,.82);
}
.foot-brand{
  font-family:var(--font-display);
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.05em;
  font-size:clamp(17px,1.9vw,20px);
  display:inline-flex;align-items:center;gap:.55em;
  margin-bottom:14px;
}
.foot-brand .dashes{display:inline-flex;gap:3px;align-items:center}
.foot-brand .dashes i{width:8px;height:2px;background:var(--red);display:block;transition:.3s}
.foot-panel--brand:hover .foot-brand .dashes i{background:var(--gold)}
.foot-brand .b-gold{color:var(--gold);text-shadow:0 0 14px rgba(255,204,0,.22)}
.foot-panel--brand .prose-body{max-width:none;margin:0;line-height:1.68}
.foot-panel--brand .prose-body strong{color:var(--gold);font-weight:600}
.foot-brand__badges{
  display:flex;flex-wrap:wrap;gap:8px 10px;
  margin-top:clamp(18px,2.4vw,24px);
  padding-top:clamp(16px,2vw,20px);
  border-top:1px solid rgba(108,174,255,.14);
}
.foot-brand__chip{
  font-family:var(--font-sans);
  font-size:clamp(10px,.88vw,11.5px);
  font-weight:600;
  letter-spacing:.04em;
  line-height:1.35;
  text-transform:none;
  padding:8px 14px;
  border-radius:99px;
  border:1px solid rgba(108,174,255,.34);
  color:rgba(210,228,255,.88);
  background:rgba(0,31,63,.52);
}
.foot-brand__chip--gold{
  border-color:rgba(255,204,0,.44);
  color:rgba(255,232,150,.96);
  background:rgba(255,204,0,.1);
}
.foot-brand__chip--soft{
  border-color:rgba(108,174,255,.22);
  color:rgba(185,210,245,.78);
  background:rgba(0,20,40,.35);
  font-weight:500;
}
.foot-links{list-style:none;display:flex;flex-direction:column;gap:4px;margin:0;padding:0}
.foot-links a{
  display:flex;align-items:center;gap:10px;
  padding:9px 10px;
  margin:0 -10px;
  border-radius:6px;
  font-family:var(--font-sans);
  font-size:clamp(15px,.95vw,16px);
  font-weight:300;
  line-height:1.45;
  color:rgba(200,225,255,.78);
  transition:color .3s,background .3s,padding-left .3s;
}
.foot-links a::before{
  content:'';
  width:5px;height:5px;border-radius:50%;
  background:rgba(108,174,255,.45);
  flex-shrink:0;
  transition:background .3s,box-shadow .3s;
}
.foot-links a:hover{
  color:var(--gold);
  background:rgba(108,174,255,.08);
  padding-left:14px;
}
.foot-links a:hover::before{background:var(--gold);box-shadow:0 0 10px rgba(255,204,0,.4)}
.foot-links a.is-ext::after{
  content:'↗';
  margin-left:auto;
  font-size:11px;
  opacity:.55;
  color:rgba(143,194,255,.8);
}

.foot-trench{
  position:relative;z-index:2;
  margin-top:clamp(40px,5vw,52px);
  padding-top:clamp(22px,3vw,28px);
  border-top:1px solid rgba(108,174,255,.16);
}
.foot-trench__bar{display:block}
.foot-trench__legal{
  flex:1 1 300px;
  margin:0;
  font-family:var(--font-sans);
  font-size:clamp(12px,1vw,13px);
  font-weight:300;
  line-height:1.65;
  letter-spacing:.02em;
  color:rgba(200,225,255,.8);
  max-width:780px;
}
.foot-trench__legal strong{color:rgba(235,244,255,.94);font-weight:500}
.foot-trench__legal a{
  color:var(--gold);
  text-decoration:none;
  border-bottom:1px solid rgba(255,204,0,.28);
  transition:color .25s,border-color .25s;
}
.foot-trench__legal a:hover{color:#fff;border-bottom-color:var(--gold)}
.foot-trench__note{
  display:block;
  margin-top:8px;
  font-size:clamp(11px,.9vw,12px);
  font-style:italic;
  line-height:1.55;
  color:rgba(175,205,240,.72);
}
@media(prefers-reduced-motion:reduce){
  .foot-waves__layer{animation:none}
  .foot-panel:hover{transform:none}
  .foot-btn--gold:hover,.foot-btn--sea:hover{transform:none}
}

@media(prefers-reduced-motion:reduce){
  *{animation:none !important;transition:none !important}
  .reveal{opacity:1;transform:none}
  .cursor,.cursor-ring{display:none}
  body{cursor:auto}
  .hero-title .l,.hero-label,.hero-sub,.hero-actions,.hero-divider,.scroll-indicator,.hero-fact{opacity:1 !important;transform:none !important}
  .hero-title .ask::after{width:94% !important}
}

/* ========== RESPONSIVE — phải đặt CUỐI file (sau mọi rule component) ========== */
@media(max-width:1199px){
  .section-title{font-size:clamp(32px,4.6vw,58px)}
  .section-subtitle{font-size:clamp(21px,2.2vw,28px)}
  .hero-title{font-size:clamp(42px,7.6vw,96px)}
  .warm-glow{height:480px;max-width:100%}
  #sovereignty::before{font-size:clamp(80px,12vw,140px);right:4%}
}
@media(max-width:1023px){
  nav.scrolled{padding:8px 16px}
  .origin-record{margin-top:var(--intra-gap-md);padding:var(--pad-card)}
  .orig-banner{grid-template-columns:minmax(108px,auto) 1fr;gap:16px 24px;padding:var(--pad-card)}
  .dispute-thesis .d-pillar-head{margin-bottom:var(--gap-grid)}
}
@media(max-width:990px){
  .section-inner{overflow-x:clip}
  .def-feature,.ancient-maps-timeline,.pca-no3,.pca-extra,.pca-unclos,.foot-deck,.dvq-grid,.d-pillar-grid,.stat-band,.orig-banner,.o-answer,.orig-row,.op-voice,.d-issue,.d-coll__rule,.d-coll-dual,.d-coll-shift,.sov-balance__stage,.sov-next{min-width:0}
  .def-feature{grid-template-columns:1fr !important;gap:var(--gap-grid)}
  .def-figure-sticky{position:relative !important;top:auto !important}
  .def-figure-sticky::before{display:none}
  .pca-no3{grid-template-columns:1fr !important}
  .pca-extra{grid-template-columns:1fr !important}
  .pca-unclos{grid-template-columns:1fr !important}
  .foot-deck{grid-template-columns:1fr !important}
  .dvq-grid{grid-template-columns:1fr !important}
  .site-foot__inner{padding-top:clamp(64px,8vw,88px)!important}
  #what .prose-lead,#origin .prose-lead,#dispute .prose-lead,#opposition .prose-lead,#witnesses .prose-lead,#verdict .prose-lead,#sovereignty .prose-lead,.prose-body,.prose-lead{max-width:100%}
  .orig-banner{grid-template-columns:1fr !important;gap:var(--gap-grid);padding:var(--pad-card)}
  .orig-banner__unit{max-width:none}
  .orig-banner__timeline{flex-wrap:wrap;gap:var(--stack-md)}
  .o-answer{grid-template-columns:1fr !important}
  .o-answer__q{border-inline-end:0;border-bottom:1px solid var(--line)}
  .o-answer__q h4{max-width:none}
  .orig-row{grid-template-columns:1fr !important}
  .d-pillar-grid{grid-template-columns:1fr !important}
  .d-pillar{
    grid-template-columns:1fr !important;
    gap:12px;
    padding:var(--pad-card);
    overflow:hidden;
  }
  .d-pillar__idx,
  .d-issue__num,
  .o-answer__num{
    position:absolute;
    inset-inline-end:6px;
    inset-block-start:4px;
    inset-block-end:auto;
    padding:0;
    margin:0;
    font-family:var(--font-display);
    font-weight:700;
    font-size:clamp(56px,16vw,88px);
    line-height:.85;
    letter-spacing:-2px;
    text-align:end;
    background:none;
    border:none;
    border-radius:0;
    opacity:1;
    pointer-events:none;
    user-select:none;
    z-index:0;
  }
  .d-pillar__idx{color:rgba(255,204,0,.06)}
  .d-issue__num{color:rgba(245,237,214,.07)}
  .d-issue.is-warn .d-issue__num{color:rgba(218,37,29,.09)}
  .o-answer__num{color:rgba(255,204,0,.055)}
  .d-pillar__body,
  .d-issue > *:not(.d-issue__num),
  .o-answer__q > *:not(.o-answer__num),
  .o-answer__a{position:relative;z-index:1}
  .d-issue{
    grid-template-columns:1fr !important;
    gap:12px;
    padding:var(--pad-card);
    overflow:hidden;
  }
  .o-answer__num{
    display:block;
    inset-inline-end:-2px;
    inset-block-start:-6px;
    font-size:clamp(72px,20vw,100px);
  }
  .d-coll{padding:var(--pad-card)}
  .d-coll__rule{grid-template-columns:1fr !important}
  .d-coll-dual{grid-template-columns:1fr !important}
  .d-coll__meter-cap,.d-coll__meter-cap span{text-align:left}
  .d-coll-case,.d-coll-shift{padding-inline-start:var(--pad-card)}
  .d-coll-case::after,.d-coll-shift::before{display:none}
  .d-coll-shift{grid-template-columns:1fr !important}
  .op-voice{grid-template-columns:1fr !important;gap:var(--stack-md)}
  .op-voice__yr small{display:inline}
  .sov-spine__head{flex-direction:column;align-items:flex-start}
  .sov-chronicle{--sov-year:clamp(72px,12vw,96px)}
  .sov-balance__stage{display:flex !important;flex-direction:column;grid-template-columns:none !important;gap:var(--gap-grid);padding-top:0}
  .sov-balance__medallion{position:relative !important;left:auto !important;top:auto !important;transform:none !important;margin:4px auto 8px;order:2}
  .sov-balance__side--vn{order:1}
  .sov-balance__side--dash{order:3}
  .sov-next{grid-template-columns:1fr !important;gap:var(--gap-grid)}
  .sov-next__cta{align-self:stretch;justify-content:center}
}
@media(max-width:768px){
  .vignette{box-shadow:inset 0 0 96px 20px rgba(0,0,0,.38)}
  .section-title{font-size:clamp(28px,7vw,48px)}
  .section-subtitle{font-size:clamp(20px,4.5vw,26px)}
  .prose-lead{font-size:clamp(20px,2.8vw,23px)}
  .prose-quote{padding:18px 20px;font-size:clamp(18px,2.5vw,24px)}
  .voices-eyebrow{font-size:14px;margin-bottom:var(--gap-grid)}
  .voices-eyebrow::before{width:24px}
  #hero{
    height:auto;
    min-height:0;
    max-height:none;
    align-items:center;
    justify-content:center;
    box-sizing:border-box;
    padding-top:calc(var(--nav-offset) + env(safe-area-inset-top,0px) + clamp(12px,2.8vh,18px) + var(--hero-pad-extra));
    padding-bottom:calc(clamp(20px,4.5vh,32px) + var(--hero-pad-extra));
    padding-left:var(--section-x);
    padding-right:var(--section-x);
  }
  .hero-bg{background-position:center 38%}
  .hero-content{width:100%;padding-top:0;padding-bottom:clamp(8px,2vw,14px)}
  .hero-label{margin-bottom:12px}
  .hero-title{margin-bottom:8px}
  .hero-divider{margin:14px auto}
  .hero-sub{margin-bottom:18px;font-size:clamp(17px,2.4vw,22px)}
  .hero-actions{gap:10px}
  .wave-container{height:24%}
  .island-silhouette{height:56px;opacity:.55}
  .cta{padding:14px 28px;font-size:12px;letter-spacing:1.5px}
  .stat-band{grid-template-columns:repeat(2,1fr) !important}
  .stat-cell{padding:22px 16px}
  .stat-num{font-size:clamp(28px,8vw,44px)}
  .stat-label{font-size:11px}
  .fig-cap{padding:28px 16px 14px}
  .mile{padding-bottom:32px}
  .origin-record{margin-top:var(--intra-gap)}
  .orig-verdict__head{margin-bottom:var(--intra-gap-md)}
  .o-answer__q{padding-top:clamp(20px,4vw,26px)}
  .orig-stamp__frame{padding:var(--pad-card)}
  .orig-row{padding:var(--pad-card-sm) var(--pad-card)}
  .op-voice{padding:var(--pad-card)}
  .op-voice__yr{display:flex;flex-wrap:wrap;align-items:baseline;gap:8px 12px}
  .op-voice__yr small{margin-top:0}
  .d-coll__stack{gap:var(--gap-card)}
  .d-coll-case::after,.d-coll-shift::before{display:none !important}
  .d-coll-case,.d-coll-shift{padding-inline-start:var(--pad-card) !important}
  .d-coll-meter__labels{flex-direction:column;align-items:flex-start;gap:4px}
  .d-coll-meter__labels .end{text-align:start}
  .d-coll-meter__legend{font-size:14px;line-height:1.5}
  .d-coll-viz__hero{flex-direction:row;flex-wrap:wrap;align-items:baseline;gap:6px 12px}
  .dispute-voices .dvq-card.is-wide{grid-column:auto}
  .dvq-card{padding:var(--pad-card)}
  .wit-quote{margin-top:var(--intra-gap);padding:0;font-size:clamp(20px,5vw,26px)}
  .lens{width:120px;height:120px}
  .pca-card{padding:var(--pad-card)}
  .pca-card:hover{transform:none}
  .pca-extra .ri{
    flex-direction:row;
    align-items:flex-start;
    gap:10px;
    padding:14px 16px;
  }
  .pca-extra .ri .rk{
    width:26px;min-width:26px;height:26px;
    display:inline-flex;align-items:center;justify-content:center;
    padding:0;font-size:15px;
    border-radius:6px;
    background:rgba(255,204,0,.12);
    border:1px solid rgba(255,204,0,.22);
  }
  .pca-extra .ri .prose-body{flex:1;min-width:0}
  .pca-zone{padding:var(--pad-card)}
  .pca-zone .prose-body{max-width:none}
  .sov-thesis{padding:var(--pad-card)}
  .sov-thesis__head{flex-wrap:wrap;gap:var(--gap-card)}
  .sov-thesis__body{padding-left:16px}
  .sov-chronicle{--sov-year:72px}
  .sov-chron{padding:var(--pad-card-sm) var(--pad-card-sm) var(--pad-card-sm) var(--sov-pad-x)}
  .sov-balance__side{padding:var(--pad-card)}
  .sov-seal{margin-top:var(--intra-gap)}
  .sov-seal__frame{padding:var(--pad-card)}
  .sov-next{text-align:left;padding:var(--pad-card)}
  .sov-next__cta{width:100%}
  .foot-navstrip{gap:var(--stack-md);margin-bottom:var(--intra-gap-md)}
  .foot-btn{padding:12px 20px}
  .foot-panel{padding:var(--pad-card)}
  .foot-crest{margin-bottom:var(--intra-gap-md)}
}
@media(max-width:567px){
  :root{--nav-chip-pad-icon:5px;--nav-chip-pad-text:7px}
  .vignette{box-shadow:inset 0 0 64px 12px rgba(0,0,0,.28)}
  .section-title{font-size:clamp(26px,8.5vw,36px);letter-spacing:0}
  .section-subtitle{font-size:clamp(18px,5vw,22px)}
  .prose-lead{font-size:clamp(18px,4.8vw,21px)}
  .prose-body{font-size:clamp(17px,4.2vw,19px)}
  .prose-quote{padding:16px 18px;font-size:clamp(17px,4.5vw,20px)}
  .hero-fact{display:none !important}
  #hero{
    padding-top:calc(var(--nav-offset) + env(safe-area-inset-top,0px) + 10px + var(--hero-pad-extra));
    padding-bottom:calc(clamp(18px,4vh,28px) + var(--hero-pad-extra));
  }
  .hero-label{margin-bottom:10px}
  .hero-title{margin-bottom:6px}
  .hero-divider{margin:12px auto}
  .hero-sub{margin-bottom:16px}
  .hero-actions{gap:8px}
  .wave-container{height:20%}
  .island-silhouette{height:44px;opacity:.5}
  .hero-label{font-size:11px;letter-spacing:2px;margin-bottom:10px}
  .hero-sub{font-size:clamp(16px,4.5vw,20px);margin-bottom:16px}
  .hero-actions{flex-direction:column;width:100%;max-width:min(320px,100%)}
  .hero-title{font-size:clamp(36px,11vw,52px)}
  .cta{width:100%;justify-content:center;padding:14px 22px}
  .scroll-indicator{display:none}
  .island-silhouette{width:95%}
  .stat-band{grid-template-columns:repeat(2,1fr) !important}
  .stat-cell{padding:16px 12px}
  .stat-num{font-size:clamp(24px,7vw,36px)}
  .stat-label{font-size:10px;line-height:1.35}
  .def-feature{gap:var(--gap-grid)}
  .def-item{gap:12px;padding:14px 0}
  .def-item .idx{width:30px;height:30px;font-size:13px}
  .fig-legend{display:none}
  .fig-index{top:14px;left:14px;font-size:10px;padding:5px 9px}
  .fig-cap{font-size:11.5px;padding:24px 14px 12px}
  .mile{grid-template-columns:14px 1fr;gap:0 12px;padding-bottom:26px}
  .mile__head{gap:10px;flex-direction:column;align-items:flex-start}
  .origin-record{padding:var(--pad-card-sm)}
  .orig-banner{padding:var(--pad-card-sm);margin-bottom:var(--gap-grid)}
  .orig-banner__num{font-size:clamp(44px,12vw,58px)}
  .orig-banner__timeline span{font-size:12px}
  .orig-stamp__chips{flex-direction:column;align-items:flex-start}
  .orig-stamp__chips li{width:100%}
  .orig-stamp__head{flex-wrap:wrap}
  .o-answer__q{padding:var(--pad-card-sm)}
  .o-answer__a{padding:var(--pad-card-sm)}
  .o-answer__q h4{font-size:clamp(18px,5vw,22px)}
  .d-pillar{padding:var(--pad-card-sm)}
  .d-issue{padding:var(--pad-card-sm)}
  .d-coll{padding:var(--pad-card-sm)}
  .d-coll__title{font-size:clamp(20px,5.5vw,26px)}
  .d-coll__intro{font-size:clamp(18px,4.8vw,22px)}
  .d-coll-shift{padding:var(--pad-card-sm)}
  .d-coll-case,.d-coll-shift{padding-inline-start:var(--pad-card-sm) !important}
  .d-coll-viz__num{font-size:clamp(34px,9vw,44px)}
  .d-coll-dual__num{font-size:clamp(30px,8vw,40px)}
  .op-voice__yr{font-size:clamp(26px,7vw,36px)}
  .lens{width:100px;height:100px}
  .pca-card .khong{font-size:clamp(34px,10vw,48px)}
  .pca-foot{margin-top:var(--gap-grid)}
  .sov-thesis__locks{flex-direction:column;align-items:flex-start}
  .sov-thesis__locks li{width:100%}
  .sov-chronicle{--sov-pad-x:12px;--sov-rail:32px;--sov-year:auto}
  .sov-chron{
    grid-template-columns:var(--sov-rail) minmax(0,1fr) !important;
    grid-template-areas:"marker year" "marker main" !important;
    gap:var(--stack-sm) var(--gap-card);
    align-items:start;
    padding:var(--pad-card-sm) var(--pad-card-sm) var(--pad-card-sm) var(--sov-pad-x);
  }
  .sov-chron__marker{grid-area:marker !important;grid-column:auto !important;align-self:center}
  .sov-chron__yr{grid-area:year !important;grid-column:auto !important;min-height:auto;width:100%;justify-content:flex-start;padding:8px 12px;font-size:14px}
  .sov-chron__main{grid-area:main !important;grid-column:auto !important;padding:var(--pad-card-sm)}
  .sov-spine__range{width:100%;justify-content:center}
  .sov-seal__frame{padding:var(--pad-card-sm)}
  .sov-next__cta{white-space:normal;text-align:center;line-height:1.35}
  #sovereignty::before{display:none}
  .warm-glow{width:100%;height:280px;top:18%}
  .site-foot__inner{padding-top:clamp(56px,10vw,72px)!important}
  .foot-crest{padding:var(--pad-card-sm);margin-bottom:var(--intra-gap)}
  .foot-crest__quote{font-size:clamp(20px,5.5vw,28px);max-width:none}
  .foot-navstrip{flex-direction:column;align-items:stretch;margin-bottom:var(--intra-gap)}
  .foot-btn{width:100%;justify-content:center;padding:12px 18px;font-size:11px;letter-spacing:1.5px}
  .foot-trench__legal{max-width:none}
  .foot-brand__chip{flex:1 1 auto;max-width:100%}
  .foot-void svg{right:-20%;width:min(360px,95vw)}
  .divider-coast{height:56px;margin-top:-56px}
}
</style>
</head>
<body>

<!-- Loader -->
<div id="loader">
  <span class="brand-mark brand-mark--loader" aria-hidden="true">
    @include('landing.partials.brand-mark')
  </span>
  <div class="loader-mark">{!! t('loader_sub') !!}</div>
</div>

<div class="grain-fixed"></div>
<div class="vignette"></div>
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>
<script id="landing-config" type="application/json">{!! json_encode($landingScriptConfig, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}</script>
@include('landing.partials.nav')

<!-- ============ HERO ============ -->
<section id="hero" data-index="{!! t('hero_index') !!}">
  <div class="hero-bg"></div>
  <div class="hero-stars"></div>
  <div class="hero-grid"></div>
  <div class="light-leak leak-red" style="top:20%;left:12%"></div>
  <div class="light-leak leak-blue" style="top:26%;right:14%"></div>
  <div class="light-leak leak-gold" style="bottom:22%;left:50%;transform:translateX(-50%)"></div>

  <div class="wave-container" data-parallax="0.3">
    <div class="wave wave1"><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,100 C200,40 400,160 600,100 C800,40 1000,160 1200,100 L1200,200 L0,200 Z" fill="rgba(0,50,120,.25)"/></svg><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,100 C200,40 400,160 600,100 C800,40 1000,160 1200,100 L1200,200 L0,200 Z" fill="rgba(0,50,120,.25)"/></svg></div>
    <div class="wave wave2"><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,120 C150,60 350,180 600,120 C850,60 1050,180 1200,120 L1200,200 L0,200 Z" fill="rgba(0,30,80,.3)"/></svg><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,120 C150,60 350,180 600,120 C850,60 1050,180 1200,120 L1200,200 L0,200 Z" fill="rgba(0,30,80,.3)"/></svg></div>
    <div class="wave wave3"><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,140 C300,80 600,180 900,120 C1050,90 1150,160 1200,140 L1200,200 L0,200 Z" fill="rgba(5,15,35,.5)"/></svg><svg viewBox="0 0 1200 200" preserveAspectRatio="none"><path d="M0,140 C300,80 600,180 900,120 C1050,90 1150,160 1200,140 L1200,200 L0,200 Z" fill="rgba(5,15,35,.5)"/></svg></div>
  </div>

  <svg class="island-silhouette" viewBox="0 0 600 100" aria-hidden="true">
    <path d="M40,80 C80,48 120,26 160,42 C182,50 202,36 232,42 C262,48 282,30 312,36 C342,42 362,58 402,52 C432,47 462,62 502,56 C524,53 544,62 564,58 L564,100 L40,100 Z" fill="rgba(0,60,120,.4)"/>
    <text x="300" y="24" font-family="serif" font-size="18" fill="rgba(255,204,0,.32)" text-anchor="middle">★</text>
  </svg>

  <div class="hero-fact">
    <svg class="ic" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><line x1="5.6" y1="5.6" x2="18.4" y2="18.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    <span>{!! t('hero_fact') !!}</span>
  </div>

  <div class="hero-content">
    <div class="hero-label">{!! t('hero_label') !!}</div>
    <h1 class="hero-title"><span class="l l1">{!! t('hero_title_line1') !!}</span><span class="l l2"><span class="ask">{!! t('hero_title_line2') !!}</span></span></h1>
    <div class="hero-divider"></div>
    <p class="hero-sub">{!! t('hero_sub') !!}</p>
    <div class="hero-actions">
      <a href="#what" class="cta cta-gold" data-cursor><span>{!! t('hero_cta_primary') !!}</span></a>
      <a href="#sovereignty" class="cta cta-ghost" data-cursor>{!! t('hero_cta_secondary') !!}</a>
    </div>
  </div>

  <div class="scroll-indicator"><div class="scroll-line"></div><span>{!! t('hero_scroll') !!}</span></div>
</section>

<!-- ============ 01 — ĐỊNH NGHĨA ============ -->
<section id="what" data-index="{!! t('what_label') !!}">
  <div class="light-leak leak-gold" style="top:8%;left:-4%"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">01</span>{!! t('what_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('what_title') !!}<br><span class="accent">{!! t('what_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('what_lead') !!}</p>

    <div class="def-feature">
      <div class="def-figure-wrap reveal reveal-delay-2">
        <div class="def-figure-sticky" id="defFigureSticky">
          <figure class="def-figure">
            <span class="def-figure__frame" aria-hidden="true"></span>
            <span class="corner tl" aria-hidden="true"></span><span class="corner tr" aria-hidden="true"></span><span class="corner bl" aria-hidden="true"></span><span class="corner br" aria-hidden="true"></span>
            <span class="fig-index">{!! t('what_fig_index') !!}</span>
            <span class="fig-legend"><span class="dash"></span>{!! t('what_fig_legend') !!}</span>
            <img src="/storage/images/dinh-nghia-duong-luoi-bo-nine-dash-line.png" width="819" height="655" loading="lazy" decoding="async" alt="{{ te('what_fig_alt') }}">
            <figcaption class="fig-cap">{!! t('what_fig_cap') !!}</figcaption>
          </figure>
        </div>
      </div>

      <ol class="def-list reveal reveal-delay-3" id="defList">
        <li class="def-item"><span class="idx">01</span><div><h4>{!! t('what_item1_title') !!}</h4><p>{!! t('what_item1_body') !!}</p></div></li>
        <li class="def-item"><span class="idx">02</span><div><h4>{!! t('what_item2_title') !!}</h4><p>{!! t('what_item2_body') !!}</p></div></li>
        <li class="def-item"><span class="idx">03</span><div><h4>{!! t('what_item3_title') !!}</h4><p>{!! t('what_item3_body') !!}</p></div></li>
        <li class="def-item"><span class="idx">04</span><div><h4>{!! t('what_item4_title') !!}</h4><p>{!! t('what_item4_body') !!}</p></div></li>
      </ol>
    </div>

    <div class="stat-band reveal">
      <div class="stat-cell"><div class="stat-num red" data-count="75" data-suffix="%" data-prefix="~">~0%</div><div class="stat-label">{!! t('what_stat1_label') !!}</div></div>
      <div class="stat-cell"><div class="stat-num" data-count="4">0</div><div class="stat-label">{!! t('what_stat2_label') !!}</div></div>
      <div class="stat-cell"><div class="stat-num" data-count="5" data-suffix="%" data-prefix="~">~0%</div><div class="stat-label">{!! t('what_stat3_label') !!}</div></div>
      <div class="stat-cell"><div class="stat-num" data-count="0">0</div><div class="stat-label">{!! t('what_stat4_label') !!}</div></div>
    </div>
  </div>
</section>

<!-- ============ 02 — NGUỒN GỐC ============ -->
<section id="origin" data-index="{!! t('origin_index') !!}">
  <div class="light-leak leak-gold" style="top:16%;right:6%"></div>
  <div class="light-leak leak-blue" style="bottom:12%;left:4%"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">02</span>{!! t('origin_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('origin_title') !!} <span class="accent">{!! t('origin_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('origin_lead') !!}</p>

    <div class="origin-tl">
      <article class="mile reveal">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile1_year') !!}</span><span class="mile__tag">{!! t('origin_mile1_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile1_title') !!}</h3>
          <p>{!! t('origin_mile1_body') !!}</p>
        </div>
      </article>

      <article class="mile reveal is-key">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile2_year') !!}</span><span class="mile__tag">{!! t('origin_mile2_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile2_title') !!}</h3>
          <div class="dash-marks" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><span class="n">{!! t('origin_mile2_dash_label') !!}</span></div>
          <p>{!! t('origin_mile2_body') !!}</p>
        </div>
      </article>

      <article class="mile reveal is-key">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile3_year') !!}</span><span class="mile__tag">{!! t('origin_mile3_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile3_title') !!}</h3>
          <div class="dash-marks" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i class="muted"></i><i class="muted"></i><span class="n">{!! t('origin_mile3_dash_label') !!}</span></div>
          <p>{!! t('origin_mile3_body') !!}</p>
        </div>
      </article>

      <article class="mile reveal">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile4_year') !!}</span><span class="mile__tag">{!! t('origin_mile4_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile4_title') !!}</h3>
          <p>{!! t('origin_mile4_body') !!}</p>
        </div>
      </article>

      <article class="mile reveal">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile5_year') !!}</span><span class="mile__tag">{!! t('origin_mile5_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile5_title') !!}</h3>
          <div class="dash-marks" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><span class="n">{!! t('origin_mile5_dash_label') !!}</span></div>
          <p>{!! t('origin_mile5_body') !!}</p>
        </div>
      </article>

      <article class="mile reveal is-verdict">
        <div class="mile__rail"><span class="mile__dot"></span></div>
        <div class="mile__body">
          <div class="mile__head"><span class="mile__year">{!! t('origin_mile6_year') !!}</span><span class="mile__tag">{!! t('origin_mile6_tag') !!}</span></div>
          <h3 class="mile__title">{!! t('origin_mile6_title') !!}</h3>
          <p>{!! t('origin_mile6_body') !!}</p>
        </div>
      </article>
    </div>

    <div class="origin-record reveal">
      <div class="voices-eyebrow">{!! t('origin_record_eyebrow') !!}</div>
      <p class="prose-lead">{!! t('origin_record_lead') !!}</p>

      <div class="orig-banner reveal reveal-delay-1">
        <span class="orig-banner__glow" aria-hidden="true"></span>
        <div class="orig-banner__stat">
          <div class="orig-banner__stat-inner">
            <span class="orig-banner__num">{!! t('origin_banner_num') !!}</span>
            <span class="orig-banner__unit">{!! t('origin_banner_unit') !!}</span>
          </div>
        </div>
        <div class="orig-banner__body">
          <p class="orig-banner__tag">{!! t('origin_banner_tag') !!}</p>
          <p class="prose-body">{!! t('origin_banner_body') !!}</p>
        </div>
        <div class="orig-banner__timeline" aria-hidden="true">
          <span>{!! t('origin_banner_tl_start') !!}</span>
          <span class="dash-track"></span>
          <span class="mid">{!! t('origin_banner_tl_mid') !!}</span>
          <span class="dash-track"></span>
          <span>{!! t('origin_banner_tl_end') !!}</span>
        </div>
      </div>

      <div class="orig-ledger">
        <div class="orig-row">
          <span class="orig-yr">{!! t('origin_row1_year') !!}</span>
          <div>
            <h4>{!! t('origin_row1_title') !!}</h4>
            <p class="prose-body">{!! t('origin_row1_body') !!}</p>
          </div>
        </div>
        <div class="orig-row">
          <span class="orig-yr">{!! t('origin_row2_year') !!}</span>
          <div>
            <h4>{!! t('origin_row2_title') !!}</h4>
            <p class="prose-body">{!! t('origin_row2_body') !!}</p>
          </div>
        </div>
        <div class="orig-row">
          <span class="orig-yr">{!! t('origin_row3_year') !!}</span>
          <div>
            <h4>{!! t('origin_row3_title') !!}</h4>
            <p class="prose-body">{!! t('origin_row3_body') !!}</p>
          </div>
        </div>
        <div class="orig-row">
          <span class="orig-yr">{!! t('origin_row4_year') !!}</span>
          <div>
            <h4>{!! t('origin_row4_title') !!}</h4>
            <p class="prose-body">{!! t('origin_row4_body') !!}</p>
          </div>
        </div>
        <div class="orig-row">
          <span class="orig-yr">{!! t('origin_row5_year') !!}</span>
          <div>
            <h4>{!! t('origin_row5_title') !!}</h4>
            <p class="prose-body">{!! t('origin_row5_body') !!}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="orig-verdict reveal">
      <header class="orig-verdict__head">
        <div class="voices-eyebrow">{!! t('origin_verdict_eyebrow') !!}</div>
        <h3 class="orig-verdict__title">{!! t('origin_verdict_title') !!}</h3>
        <p class="orig-verdict__intro">{!! t('origin_verdict_intro') !!}</p>
      </header>

      <div class="orig-verdict__stack">
        <article class="o-answer reveal">
          <div class="o-answer__q">
            <span class="o-answer__num" aria-hidden="true">01</span>
            <span class="o-answer__label">{!! t('origin_q1_label') !!}</span>
            <h4>{!! t('origin_q1_title') !!}</h4>
          </div>
          <div class="o-answer__a">
            <p class="prose-body">{!! t('origin_q1_body') !!}</p>
            <div class="o-answer__fact"><span class="o-answer__fact-tag">{!! t('origin_q1_fact_tag') !!}</span> {!! t('origin_q1_fact') !!}</div>
          </div>
        </article>

        <article class="o-answer reveal reveal-delay-1">
          <div class="o-answer__q">
            <span class="o-answer__num" aria-hidden="true">02</span>
            <span class="o-answer__label">{!! t('origin_q2_label') !!}</span>
            <h4>{!! t('origin_q2_title') !!}</h4>
          </div>
          <div class="o-answer__a">
            <p class="prose-body">{!! t('origin_q2_body') !!}</p>
            <div class="o-answer__fact"><span class="o-answer__fact-tag">{!! t('origin_q2_fact_tag') !!}</span> {!! t('origin_q2_fact') !!}</div>
          </div>
        </article>

        <article class="o-answer reveal reveal-delay-2">
          <div class="o-answer__q">
            <span class="o-answer__num" aria-hidden="true">03</span>
            <span class="o-answer__label">{!! t('origin_q3_label') !!}</span>
            <h4>{!! t('origin_q3_title') !!}</h4>
          </div>
          <div class="o-answer__a">
            <p class="prose-body">{!! t('origin_q3_body') !!}</p>
            <div class="o-answer__fact"><span class="o-answer__fact-tag">{!! t('origin_q3_fact_tag') !!}</span> {!! t('origin_q3_fact') !!}</div>
          </div>
        </article>
      </div>

      <div class="orig-verdict__stamp reveal reveal-delay-3">
        <div class="orig-stamp__frame">
          <span class="orig-stamp__glow" aria-hidden="true"></span>
          <span class="orig-stamp__glow orig-stamp__glow--red" aria-hidden="true"></span>
          <header class="orig-stamp__head">
            <span class="orig-stamp__dot" aria-hidden="true"></span>
            <span class="orig-stamp__label">{!! t('origin_stamp_label') !!}</span>
            <span class="orig-stamp__rule" aria-hidden="true"></span>
          </header>
          <p class="orig-stamp__lede">{!! t('origin_stamp_lede') !!}</p>
          <ul class="orig-stamp__chips">
            <li>{!! t('origin_stamp_chip1') !!}</li>
            <li>{!! t('origin_stamp_chip2') !!}</li>
            <li>{!! t('origin_stamp_chip3') !!}</li>
          </ul>
          <p class="orig-stamp__foot">{!! t('origin_stamp_foot') !!}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ 03 — VÌ SAO GÂY TRANH CÃI ============ -->
<section id="dispute" data-index="{!! t('dispute_index') !!}">
  <div class="light-leak leak-red" style="top:14%;right:-4%"></div>
  <div class="light-leak leak-blue" style="bottom:10%;left:-3%"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">03</span>{!! t('dispute_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('dispute_title') !!} <span class="red-accent">{!! t('dispute_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('dispute_lead') !!}</p>

    <div class="dispute-thesis reveal reveal-delay-2">
      <header class="d-pillar-head">
        <p class="d-pillar-head__eyebrow">{!! t('dispute_pillar_head_eyebrow') !!}</p>
        <p class="d-pillar-head__text prose-body">{!! t('dispute_pillar_head_text') !!}</p>
      </header>
      <div class="d-pillar-grid">
        <article class="d-pillar">
          <span class="d-pillar__idx" aria-hidden="true">01</span>
          <div class="d-pillar__body">
            <span class="d-pillar__tag">{!! t('dispute_pillar1_tag') !!}</span>
            <h4>{!! t('dispute_pillar1_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_pillar1_body') !!}</p>
          </div>
        </article>
        <article class="d-pillar">
          <span class="d-pillar__idx" aria-hidden="true">02</span>
          <div class="d-pillar__body">
            <span class="d-pillar__tag">{!! t('dispute_pillar2_tag') !!}</span>
            <h4>{!! t('dispute_pillar2_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_pillar2_body') !!}</p>
          </div>
        </article>
        <article class="d-pillar">
          <span class="d-pillar__idx" aria-hidden="true">03</span>
          <div class="d-pillar__body">
            <span class="d-pillar__tag">{!! t('dispute_pillar3_tag') !!}</span>
            <h4>{!! t('dispute_pillar3_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_pillar3_body') !!}</p>
          </div>
        </article>
        <article class="d-pillar is-geo">
          <span class="d-pillar__idx" aria-hidden="true">04</span>
          <div class="d-pillar__body">
            <span class="d-pillar__tag">{!! t('dispute_pillar4_tag') !!}</span>
            <h4>{!! t('dispute_pillar4_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_pillar4_body') !!}</p>
          </div>
        </article>
      </div>
    </div>

    <div class="dispute-ledger">
      <article class="d-issue reveal is-warn">
        <div class="d-issue__num">01</div>
        <div>
          <div class="d-issue__tag">{!! t('dispute_issue1_tag') !!}</div>
          <h3>{!! t('dispute_issue1_title') !!}</h3>
          <p class="prose-body">{!! t('dispute_issue1_body') !!}</p>
          <div class="d-fact">{!! t('dispute_issue1_fact') !!}</div>
        </div>
      </article>

      <article class="d-issue reveal">
        <div class="d-issue__num">02</div>
        <div>
          <div class="d-issue__tag">{!! t('dispute_issue2_tag') !!}</div>
          <h3>{!! t('dispute_issue2_title') !!}</h3>
          <p class="prose-body">{!! t('dispute_issue2_body') !!}</p>
          <div class="d-fact">{!! t('dispute_issue2_fact') !!}</div>
          <div class="d-chips"><span class="d-chip red">{!! t('dispute_issue2_chip1') !!}</span><span class="d-chip">{!! t('dispute_issue2_chip2') !!}</span></div>
        </div>
      </article>

      <article class="d-issue reveal is-warn">
        <div class="d-issue__num">03</div>
        <div>
          <div class="d-issue__tag">{!! t('dispute_issue3_tag') !!}</div>
          <h3>{!! t('dispute_issue3_title') !!}</h3>
          <p class="prose-body">{!! t('dispute_issue3_body') !!}</p>
          <div class="d-fact">{!! t('dispute_issue3_fact') !!}</div>
        </div>
      </article>

      <article class="d-issue reveal">
        <div class="d-issue__num">04</div>
        <div>
          <div class="d-issue__tag">{!! t('dispute_issue4_tag') !!}</div>
          <h3>{!! t('dispute_issue4_title') !!}</h3>
          <p class="prose-body">{!! t('dispute_issue4_body') !!}</p>
          <div class="d-fact">{!! t('dispute_issue4_fact') !!}</div>
          <div class="d-chips"><span class="d-chip">{!! t('dispute_issue4_chip1') !!}</span><span class="d-chip">{!! t('dispute_issue4_chip2') !!}</span><span class="d-chip red">{!! t('dispute_issue4_chip3') !!}</span></div>
        </div>
      </article>

      <article class="d-issue reveal is-warn">
        <div class="d-issue__num">05</div>
        <div>
          <div class="d-issue__tag">{!! t('dispute_issue5_tag') !!}</div>
          <h3>{!! t('dispute_issue5_title') !!}</h3>
          <p class="prose-body">{!! t('dispute_issue5_body') !!}</p>
          <div class="d-fact">{!! t('dispute_issue5_fact') !!}</div>
          <div class="d-chips"><span class="d-chip red">{!! t('dispute_issue5_chip1') !!}</span><span class="d-chip">{!! t('dispute_issue5_chip2') !!}</span><span class="d-chip">{!! t('dispute_issue5_chip3') !!}</span></div>
        </div>
      </article>
    </div>

    <div class="dispute-collision reveal">
      <div class="d-coll">
        <span class="d-coll__glow" aria-hidden="true"></span>

        <header class="d-coll__head">
          <div class="voices-eyebrow">{!! t('dispute_coll_eyebrow') !!}</div>
          <h3 class="d-coll__title">{!! t('dispute_coll_title') !!}</h3>
          <p class="d-coll__intro">{!! t('dispute_coll_intro') !!}</p>
        </header>

        <aside class="d-coll__rule">
          <div>
            <h4>{!! t('dispute_coll_rule_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_coll_rule_body') !!}</p>
          </div>
          <div class="d-coll__meter-side">
            <div class="d-coll__meter-cap">{!! t('dispute_coll_meter_cap') !!}<span>{!! t('dispute_coll_meter_cap_unit') !!}</span></div>
            <div class="d-coll__meter-ref" aria-hidden="true"></div>
          </div>
        </aside>

        <div class="d-coll__stack">
          <article class="d-coll-case reveal">
            <header class="d-coll-case__head">
              <span class="d-coll-case__tag">{!! t('dispute_case1_tag') !!}</span>
              <h4>{!! t('dispute_case1_title') !!}</h4>
            </header>
            <p class="d-coll-case__body prose-body">{!! t('dispute_case1_body') !!}</p>
            <div class="d-coll-viz" aria-label="{!! t('dispute_case1_viz_aria') !!}">
              <div class="d-coll-viz__hero">
                <span class="d-coll-viz__num">{!! t('dispute_case1_num') !!}</span>
                <span class="d-coll-viz__unit">{!! t('dispute_case1_unit') !!}</span>
              </div>
              <div class="d-coll-meter">
                <div class="d-coll-meter__labels"><span>{!! t('dispute_case1_meter_start') !!}</span><span class="end">{!! t('dispute_case1_meter_end') !!}</span></div>
                <div class="d-coll-meter__track"><i class="d-coll-meter__fill" data-w="61.5"></i><span class="d-coll-meter__cap" aria-hidden="true" title="Mốc 200 hải lý"></span></div>
                <p class="d-coll-meter__legend">{!! t('dispute_case1_legend') !!}</p>
              </div>
            </div>
            <p class="d-coll-case__insight">{!! t('dispute_case1_insight') !!}</p>
          </article>

          <article class="d-coll-case reveal reveal-delay-1">
            <header class="d-coll-case__head">
              <span class="d-coll-case__tag">{!! t('dispute_case2_tag') !!}</span>
              <h4>{!! t('dispute_case2_title') !!}</h4>
            </header>
            <p class="d-coll-case__body prose-body">{!! t('dispute_case2_body') !!}</p>
            <div class="d-coll-viz" aria-label="{!! t('dispute_case2_viz_aria') !!}">
              <div class="d-coll-viz__hero">
                <span class="d-coll-viz__num">{!! t('dispute_case2_num') !!}</span>
                <span class="d-coll-viz__unit">{!! t('dispute_case2_unit') !!}</span>
              </div>
              <div class="d-coll-meter">
                <div class="d-coll-meter__labels"><span>{!! t('dispute_case2_meter_start') !!}</span><span class="end">{!! t('dispute_case2_meter_end') !!}</span></div>
                <div class="d-coll-meter__track"><i class="d-coll-meter__fill" data-w="62"></i><span class="d-coll-meter__cap" aria-hidden="true" title="Mốc 200 hải lý"></span></div>
                <p class="d-coll-meter__legend">{!! t('dispute_case2_legend') !!}</p>
              </div>
            </div>
            <p class="d-coll-case__insight">{!! t('dispute_case2_insight') !!}</p>
          </article>

          <article class="d-coll-case is-map reveal reveal-delay-2">
            <header class="d-coll-case__head">
              <span class="d-coll-case__tag">{!! t('dispute_case3_tag') !!}</span>
              <h4>{!! t('dispute_case3_title') !!}</h4>
            </header>
            <p class="d-coll-case__body prose-body">{!! t('dispute_case3_body') !!}</p>
            <div class="d-coll-viz" aria-label="{!! t('dispute_case3_viz_aria') !!}">
              <div class="d-coll-dual" role="group">
                <div class="d-coll-dual__card">
                  <p class="d-coll-dual__lbl">{!! t('dispute_case3_dual_map_lbl') !!}</p>
                  <p class="d-coll-dual__num">{!! t('dispute_case3_dual_map_num') !!}</p>
                  <p class="d-coll-dual__sub">{!! t('dispute_case3_dual_map_sub') !!}</p>
                </div>
                <div class="d-coll-dual__card is-real">
                  <p class="d-coll-dual__lbl">{!! t('dispute_case3_dual_real_lbl') !!}</p>
                  <p class="d-coll-dual__num">{!! t('dispute_case3_dual_real_num') !!}</p>
                  <p class="d-coll-dual__sub">{!! t('dispute_case3_dual_real_sub') !!}</p>
                </div>
              </div>
              <p class="d-coll-viz__note">{!! t('dispute_case3_note') !!}</p>
            </div>
            <p class="d-coll-case__insight">{!! t('dispute_case3_insight') !!}</p>
          </article>

          <aside class="d-coll-shift reveal reveal-delay-3">
            <div class="d-coll-shift__badge">
              <span class="d-coll-shift__num">{!! t('dispute_coll_shift_badge') !!} <span>{!! t('dispute_coll_shift_badge_unit') !!}</span></span>
            </div>
            <h4>{!! t('dispute_coll_shift_title') !!}</h4>
            <p class="prose-body">{!! t('dispute_coll_shift_body') !!}</p>
          </aside>
        </div>

        <footer class="d-coll__stamp">
          <p class="prose-body">{!! t('dispute_coll_stamp') !!}</p>
        </footer>
      </div>
    </div>

    <div class="dispute-voices reveal">
      <div class="voices-eyebrow">{!! t('dispute_voices_eyebrow') !!}</div>
      <div class="dvq-grid">
        <figure class="dvq-card is-wide">
          <div class="dvq-card__kind">{!! t('dispute_voice1_kind') !!}</div>
          <p>{!! t('dispute_voice1_body') !!}</p>
          <figcaption class="vq-cite">
            <span class="vq-cite__badge"><img src="https://flagcdn.com/w40/us.png" width="34" height="34" alt="{!! t('dispute_voice1_flag_alt') !!}" loading="lazy" decoding="async"></span>
            <span class="vq-cite__who"><span class="vq-cite__name">{!! t('dispute_voice1_name') !!}</span><span class="vq-cite__role">{!! t('dispute_voice1_role') !!}</span></span>
          </figcaption>
        </figure>
        <figure class="dvq-card">
          <div class="dvq-card__kind">{!! t('dispute_voice2_kind') !!}</div>
          <p>{!! t('dispute_voice2_body') !!}</p>
          <figcaption class="vq-cite">
            <span class="vq-cite__badge"><img src="https://flagcdn.com/w40/cn.png" width="34" height="34" alt="{!! t('dispute_voice2_flag_alt') !!}" loading="lazy" decoding="async"></span>
            <span class="vq-cite__who"><span class="vq-cite__name">{!! t('dispute_voice2_name') !!}</span><span class="vq-cite__role">{!! t('dispute_voice2_role') !!}</span></span>
          </figcaption>
        </figure>
        <figure class="dvq-card">
          <div class="dvq-card__kind">{!! t('dispute_voice3_kind') !!}</div>
          <p>{!! t('dispute_voice3_body') !!}</p>
          <figcaption class="vq-cite">
            <span class="vq-cite__badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v18M6 21h12M12 6l-6.5 2 2.6 4.6a2.7 2.7 0 01-5.2 0L11.5 8M12 6l6.5 2-2.6 4.6a2.7 2.7 0 005.2 0L18.5 8"/></svg></span>
            <span class="vq-cite__who"><span class="vq-cite__name">{!! t('dispute_voice3_name') !!}</span><span class="vq-cite__role">{!! t('dispute_voice3_role') !!}</span></span>
          </figcaption>
        </figure>
      </div>
    </div>
  </div>
</section>

<!-- ============ 04 — QUỐC TẾ PHẢN ĐỐI ============ -->
<section id="opposition" data-index="{!! t('opposition_index') !!}">
  <div class="light-leak leak-blue" style="top:12%;left:-4%"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">04</span>{!! t('opposition_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('opposition_title') !!} <span class="accent">{!! t('opposition_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('opposition_lead') !!}</p>

    <div class="op-voices">
      <article class="op-voice reveal">
        <div class="op-voice__yr">{!! t('opposition_voice1_year') !!}<small>{!! t('opposition_voice1_date') !!}</small></div>
        <div>
          <h4>{!! t('opposition_voice1_title') !!}</h4>
          <p class="prose-body">{!! t('opposition_voice1_body') !!}</p>
          <div class="op-chips"><span class="chip red">{!! t('opposition_voice1_chip1') !!}</span><span class="chip">{!! t('opposition_voice1_chip2') !!}</span></div>
        </div>
      </article>
      <article class="op-voice reveal">
        <div class="op-voice__yr">{!! t('opposition_voice2_year') !!}<small>{!! t('opposition_voice2_date') !!}</small></div>
        <div>
          <h4>{!! t('opposition_voice2_title') !!}</h4>
          <p class="prose-body">{!! t('opposition_voice2_body') !!}</p>
          <div class="op-chips"><span class="chip blue">{!! t('opposition_voice2_chip1') !!}</span></div>
        </div>
      </article>
      <article class="op-voice reveal">
        <div class="op-voice__yr">{!! t('opposition_voice3_year') !!}<small>{!! t('opposition_voice3_date') !!}</small></div>
        <div>
          <h4>{!! t('opposition_voice3_title') !!}</h4>
          <p class="prose-body">{!! t('opposition_voice3_body') !!}</p>
          <div class="op-chips"><span class="chip gold">{!! t('opposition_voice3_chip1') !!}</span><span class="chip">{!! t('opposition_voice3_chip2') !!}</span></div>
        </div>
      </article>
      <article class="op-voice reveal">
        <div class="op-voice__yr">{!! t('opposition_voice4_year') !!}</div>
        <div>
          <h4>{!! t('opposition_voice4_title') !!}</h4>
          <p class="prose-body">{!! t('opposition_voice4_body') !!}</p>
          <div class="op-chips"><span class="chip">{!! t('opposition_voice4_chip1') !!}</span></div>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ============ 05 — NHÂN CHỨNG THẦM LẶNG ============ -->
<section id="witnesses" data-index="{!! t('witnesses_index') !!}">
  <div class="light-leak leak-gold" style="top:10%;right:-4%"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">05</span>{!! t('witnesses_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('witnesses_title') !!} <span class="accent">{!! t('witnesses_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('witnesses_lead') !!}</p>

    @include('landing.partials.ancient-maps-timeline', ['ancientMapsLangPrefix' => 'witnesses'])

    <p class="wit-quote reveal">{!! t('witnesses_quote') !!}</p>
  </div>
</section>

<!-- ============ 06 — PCA 2016 ============ -->
<section id="verdict" data-index="{!! t('verdict_index') !!}">
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">06</span>{!! t('verdict_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('verdict_title') !!} <span class="accent">{!! t('verdict_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('verdict_lead') !!}</p>

    <div class="pca-no3">
      <article class="pca-card no-card reveal" data-cursor>
        <div class="khong">{!! t('verdict_card1_khong') !!}</div>
        <h3>{!! t('verdict_card1_title') !!}</h3>
        <p class="short prose-body">{!! t('verdict_card1_short') !!}</p>
        <div class="quote"><p>{!! t('verdict_card1_quote') !!}</p></div>
        <div class="more">{!! t('verdict_card1_more') !!}</div>
      </article>
      <article class="pca-card no-card reveal reveal-delay-1" data-cursor>
        <div class="khong">{!! t('verdict_card2_khong') !!}</div>
        <h3>{!! t('verdict_card2_title') !!}</h3>
        <p class="short prose-body">{!! t('verdict_card2_short') !!}</p>
        <div class="quote"><p>{!! t('verdict_card2_quote') !!}</p></div>
        <div class="more">{!! t('verdict_card2_more') !!}</div>
      </article>
      <article class="pca-card no-card reveal reveal-delay-2" data-cursor>
        <div class="khong">{!! t('verdict_card3_khong') !!}</div>
        <h3>{!! t('verdict_card3_title') !!}</h3>
        <p class="short prose-body">{!! t('verdict_card3_short') !!}</p>
        <div class="quote"><p>{!! t('verdict_card3_quote') !!}</p></div>
        <div class="more">{!! t('verdict_card3_more') !!}</div>
      </article>
    </div>

    <div class="pca-extra reveal">
      <div class="ri"><span class="rk">+</span><p class="prose-body">{!! t('verdict_extra1') !!}</p></div>
      <div class="ri"><span class="rk">+</span><p class="prose-body">{!! t('verdict_extra2') !!}</p></div>
      <div class="ri"><span class="rk">+</span><p class="prose-body">{!! t('verdict_extra3') !!}</p></div>
      <div class="ri"><span class="rk">+</span><p class="prose-body">{!! t('verdict_extra4') !!}</p></div>
    </div>

    <h3 class="section-subtitle pca-subtitle reveal">{!! t('verdict_subtitle') !!}</h3>
    <p class="prose-body reveal" style="margin-top:14px">{!! t('verdict_subtitle_body') !!}</p>

    <div class="pca-unclos">
      <div class="pca-zone reveal"><div class="z">{!! t('verdict_zone1_num') !!}</div><h4>{!! t('verdict_zone1_title') !!}</h4><p class="prose-body">{!! t('verdict_zone1_body') !!}</p></div>
      <div class="pca-zone reveal reveal-delay-1"><div class="z">{!! t('verdict_zone2_num') !!}</div><h4>{!! t('verdict_zone2_title') !!}</h4><p class="prose-body">{!! t('verdict_zone2_body') !!}</p></div>
      <div class="pca-zone reveal reveal-delay-2"><div class="z">{!! t('verdict_zone3_num') !!}</div><h4>{!! t('verdict_zone3_title') !!}</h4><p class="prose-body">{!! t('verdict_zone3_body') !!}</p></div>
    </div>
    <p class="prose-body pca-foot reveal">{!! t('verdict_foot') !!}</p>
  </div>
</section>

<!-- ============ 07 — KẾT LUẬN: HOÀNG SA – TRƯỜNG SA ============ -->
<section id="sovereignty" data-index="{!! t('sovereignty_index') !!}">
  <div class="warm-glow" aria-hidden="true"></div>
  <div class="section-inner">
    <div class="section-label reveal"><span class="lnum">07</span>{!! t('sovereignty_label') !!}</div>
    <h2 class="section-title reveal reveal-delay-1">{!! t('sovereignty_title') !!} <span class="accent">{!! t('sovereignty_title_accent') !!}</span></h2>
    <p class="prose-lead reveal reveal-delay-2">{!! t('sovereignty_lead') !!}</p>

    <div class="sov-thesis reveal">
      <div class="sov-thesis__glow" aria-hidden="true"></div>
      <div class="sov-thesis__glow sov-thesis__glow--warm" aria-hidden="true"></div>
      <header class="sov-thesis__head">
        <span class="sov-thesis__badge" aria-hidden="true">§</span>
        <span class="sov-thesis__label prose-caption">{!! t('sovereignty_thesis_label') !!}</span>
        <span class="sov-thesis__rule" aria-hidden="true"></span>
      </header>
      <ul class="sov-thesis__locks" aria-label="Ba điểm đã rõ từ các mục trước">
        <li>{!! t('sovereignty_thesis_lock1') !!}</li>
        <li>{!! t('sovereignty_thesis_lock2') !!}</li>
        <li>{!! t('sovereignty_thesis_lock3') !!}</li>
      </ul>
      <p class="sov-thesis__body prose-body">{!! t('sovereignty_thesis_body') !!}</p>
    </div>

    <div class="sov-spine reveal">
      <header class="sov-spine__head">
        <div>
          <p class="prose-caption">{!! t('sovereignty_spine_caption') !!}</p>
          <h3 class="section-subtitle">{!! t('sovereignty_spine_title') !!}</h3>
        </div>
        <span class="sov-spine__range">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M14 7l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          {!! t('sovereignty_spine_range') !!}
        </span>
      </header>
      <div class="sov-chronicle" id="sovChronicle" role="list">
        <div class="sov-chronicle__axis" aria-hidden="true"><span class="sov-chronicle__fill" id="sovChronFill"></span></div>
        <article class="sov-chron reveal" role="listitem" data-cursor>
          <div class="sov-chron__marker" aria-hidden="true"><span class="sov-chron__dot"></span></div>
          <time class="sov-chron__yr" datetime="1600">{!! t('sovereignty_chron1_year') !!}</time>
          <div class="sov-chron__main">
            <h4 class="sov-chron__title">{!! t('sovereignty_chron1_title') !!}</h4>
            <p class="prose-body">{!! t('sovereignty_chron1_body') !!}</p>
          </div>
        </article>
        <article class="sov-chron reveal reveal-delay-1" role="listitem" data-cursor>
          <div class="sov-chron__marker" aria-hidden="true"><span class="sov-chron__dot"></span></div>
          <time class="sov-chron__yr" datetime="1816">{!! t('sovereignty_chron2_year') !!}</time>
          <div class="sov-chron__main">
            <h4 class="sov-chron__title">{!! t('sovereignty_chron2_title') !!}</h4>
            <p class="prose-body">{!! t('sovereignty_chron2_body') !!}</p>
          </div>
        </article>
        <article class="sov-chron reveal reveal-delay-1" role="listitem" data-cursor>
          <div class="sov-chron__marker" aria-hidden="true"><span class="sov-chron__dot"></span></div>
          <time class="sov-chron__yr" datetime="1834">{!! t('sovereignty_chron3_year') !!}</time>
          <div class="sov-chron__main">
            <h4 class="sov-chron__title">{!! t('sovereignty_chron3_title') !!}</h4>
            <p class="prose-body">{!! t('sovereignty_chron3_body') !!}</p>
          </div>
        </article>
        <article class="sov-chron reveal reveal-delay-2" role="listitem" data-cursor>
          <div class="sov-chron__marker" aria-hidden="true"><span class="sov-chron__dot"></span></div>
          <time class="sov-chron__yr" datetime="1932">{!! t('sovereignty_chron4_year') !!}</time>
          <div class="sov-chron__main">
            <h4 class="sov-chron__title">{!! t('sovereignty_chron4_title') !!}</h4>
            <p class="prose-body">{!! t('sovereignty_chron4_body') !!}</p>
          </div>
        </article>
        <article class="sov-chron sov-chron--now reveal reveal-delay-2" role="listitem" data-cursor>
          <div class="sov-chron__marker" aria-hidden="true"><span class="sov-chron__dot"></span></div>
          <time class="sov-chron__yr">{!! t('sovereignty_chron5_year') !!}</time>
          <div class="sov-chron__main">
            <h4 class="sov-chron__title">{!! t('sovereignty_chron5_title') !!}</h4>
            <p class="prose-body">{!! t('sovereignty_chron5_body') !!}</p>
          </div>
        </article>
      </div>
    </div>

    <div class="sov-balance reveal">
      <p class="sov-balance__label prose-caption">{!! t('sovereignty_balance_label') !!}</p>
      <div class="sov-balance__stage">
        <div class="sov-balance__medallion" title="{!! t('sovereignty_balance_medallion_title') !!}" aria-hidden="true">⚖</div>
        <article class="sov-balance__side sov-balance__side--vn" data-cursor>
          <div class="sov-balance__cap">
            <span class="sov-balance__glyph" aria-hidden="true">★</span>
            <span class="sov-balance__tag sov-balance__tag--vn prose-caption">{!! t('sovereignty_vn_tag') !!}</span>
          </div>
          <h4>{!! t('sovereignty_vn_title') !!}</h4>
          <p class="prose-body">{!! t('sovereignty_vn_body') !!}</p>
        </article>
        <article class="sov-balance__side sov-balance__side--dash" data-cursor>
          <div class="sov-balance__cap">
            <span class="sov-balance__glyph" aria-hidden="true">
              <svg viewBox="0 0 48 16" fill="none" aria-hidden="true"><path d="M4 8h40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="6 8"/></svg>
            </span>
            <span class="sov-balance__tag sov-balance__tag--dash prose-caption">{!! t('sovereignty_dash_tag') !!}</span>
          </div>
          <h4>{!! t('sovereignty_dash_title') !!}</h4>
          <p class="prose-body">{!! t('sovereignty_dash_body') !!}</p>
        </article>
      </div>
    </div>

    <figure class="sov-seal reveal">
      <div class="sov-seal__frame">
        <div class="sov-seal__orbit" aria-hidden="true"></div>
        <div class="sov-seal__corners" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
        <blockquote class="sov-seal__quote">
          {!! t('sovereignty_seal_quote') !!}
        </blockquote>
        <figcaption class="sov-seal__cite prose-caption">{!! t('sovereignty_seal_cite') !!}</figcaption>
      </div>
    </figure>

    <aside class="sov-next reveal" aria-labelledby="sov-next-title">
      <div>
        <p class="sov-next__eyebrow prose-caption">{!! t('sovereignty_next_eyebrow') !!}</p>
        <h3 id="sov-next-title" class="section-subtitle">{!! t('sovereignty_next_title') !!}</h3>
        <p class="prose-body">{!! t('sovereignty_next_body') !!}</p>
      </div>
      <a class="sov-next__cta" href="{{ $ecosystemParacelUrl }}" data-cursor rel="noopener">
        <span>{!! t('sovereignty_next_cta') !!}</span>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h12M13 7l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </aside>
  </div>
</section>

<!-- ============ FOOTER — Biển Đông sâu ============ -->
<footer id="footer" class="site-foot" data-index="{!! t('footer_index') !!}">
  <div class="foot-waves" aria-hidden="true">
    <svg viewBox="0 0 1440 120" preserveAspectRatio="none" role="presentation">
      <g class="foot-waves__layer" fill="currentColor" opacity=".55">
        <path d="M0,64 C240,20 480,100 720,56 C960,12 1200,88 1440,48 L1440,120 L0,120 Z"/>
      </g>
      <g class="foot-waves__layer foot-waves__layer--2" fill="currentColor" opacity=".35">
        <path d="M0,80 C320,40 640,96 960,60 C1200,32 1320,72 1440,68 L1440,120 L0,120 Z"/>
      </g>
    </svg>
  </div>
  <div class="foot-void" aria-hidden="true">
    <svg viewBox="0 0 400 200" fill="none">
      <path d="M30,40 Q120,20 200,55 Q280,95 360,70 Q370,110 320,130 Q200,160 80,120 Q40,90 30,40Z" stroke="#DA251D" stroke-width="2.5" stroke-dasharray="10 14" opacity=".9"/>
      <path d="M55,35 L345,145" stroke="#6caeff" stroke-width="3" stroke-linecap="round"/>
      <path d="M345,35 L55,145" stroke="#6caeff" stroke-width="3" stroke-linecap="round"/>
    </svg>
  </div>

  <div class="section-inner site-foot__inner">
    <div class="foot-crest reveal">
      <p class="foot-crest__eyebrow prose-caption">{!! t('footer_crest_eyebrow') !!}</p>
      <blockquote class="foot-crest__quote">{!! t('footer_crest_quote') !!}</blockquote>
      <p class="foot-crest__sub">{!! t('footer_crest_sub') !!}</p>
    </div>

    <nav class="foot-navstrip reveal" aria-label="{!! t('footer_nav_aria') !!}">
      <a href="#hero" class="foot-btn foot-btn--gold" data-cursor>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 19V5M7 10l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>{!! t('footer_btn_top') !!}</span>
      </a>
      <a href="#verdict" class="foot-btn foot-btn--sea" data-cursor>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>{!! t('footer_btn_verdict') !!}</span>
      </a>
      <a href="{{ $ecosystemParacelUrl }}" class="foot-btn foot-btn--sea" data-cursor rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h12M13 7l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>{!! t('footer_btn_paracel') !!}</span>
      </a>
    </nav>

    <div class="foot-deck">
      <div class="foot-panel foot-panel--brand reveal" data-cursor>
        <p class="foot-brand__eyebrow">{!! t('footer_brand_eyebrow') !!}</p>
        <div class="foot-brand"><span class="dashes" aria-hidden="true"><i></i><i></i><i></i></span><span class="b-gold">{!! t('footer_brand_gold') !!}</span></div>
        <p class="prose-body">{!! t('footer_brand_body') !!}</p>
        <div class="foot-brand__badges" role="list" aria-label="{!! te('footer_badges_aria') !!}">
          <span class="foot-brand__chip foot-brand__chip--gold" role="listitem">{!! t('footer_chip1') !!}</span>
          <span class="foot-brand__chip foot-brand__chip--gold" role="listitem">{!! t('footer_chip2') !!}</span>
          <span class="foot-brand__chip" role="listitem">{!! t('footer_chip3') !!}</span>
          <span class="foot-brand__chip" role="listitem">{!! t('footer_chip4') !!}</span>
          <span class="foot-brand__chip" role="listitem">{!! t('footer_chip5') !!}</span>
          <span class="foot-brand__chip foot-brand__chip--soft" role="listitem">{!! t('footer_chip6') !!}</span>
        </div>
      </div>
      <div class="foot-panel reveal reveal-delay-1">
        <div class="foot-panel__head">
          <span class="foot-panel__icon" aria-hidden="true">§</span>
          <h2 class="foot-panel__title">{!! t('footer_refs_title') !!}</h2>
        </div>
        <ul class="foot-links">
          <li><a href="#" onclick="return false" data-cursor>{!! t('footer_ref1') !!}</a></li>
          <li><a href="#" onclick="return false" data-cursor>{!! t('footer_ref2') !!}</a></li>
          <li><a href="#" onclick="return false" data-cursor>{!! t('footer_ref3') !!}</a></li>
          <li><a href="#" onclick="return false" data-cursor>{!! t('footer_ref4') !!}</a></li>
        </ul>
      </div>
      <div class="foot-panel reveal reveal-delay-2">
        <div class="foot-panel__head">
          <span class="foot-panel__icon" aria-hidden="true">⌖</span>
          <h2 class="foot-panel__title">{!! t('footer_toc_title') !!}</h2>
        </div>
        <ul class="foot-links">
          <li><a href="#what" data-cursor>{!! t('footer_toc1') !!}</a></li>
          <li><a href="#origin" data-cursor>{!! t('footer_toc2') !!}</a></li>
          <li><a href="#dispute" data-cursor>{!! t('footer_toc3') !!}</a></li>
          <li><a href="#opposition" data-cursor>{!! t('footer_toc4') !!}</a></li>
          <li><a href="#witnesses" data-cursor>{!! t('footer_toc5') !!}</a></li>
          <li><a href="#verdict" data-cursor>{!! t('footer_toc6') !!}</a></li>
          <li><a href="#sovereignty" data-cursor>{!! t('footer_toc7') !!}</a></li>
        </ul>
      </div>
    </div>

    <div class="foot-trench reveal">
      <div class="foot-trench__bar">
        <p class="foot-trench__legal">
          {!! t('footer_copy', ['year' => date('Y')]) !!}
          <span class="foot-trench__note">{!! t('footer_copy_disclaimer') !!}</span>
        </p>
      </div>
    </div>
  </div>
</footer>

@include('landing.partials.ancient-map-lightbox')
@include('landing.partials.fab')

<script>
(function(){
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Loader */
  setTimeout(function(){ var l=document.getElementById('loader'); if(l) l.classList.add('done'); }, 2400);
  window.addEventListener('load', function(){ setTimeout(function(){ var l=document.getElementById('loader'); if(l) l.classList.add('done'); }, 600); });

  /* Cursor */
  if(!reduce){
    var cur=document.getElementById('cursor'), ring=document.getElementById('cursorRing');
    var mx=innerWidth/2,my=innerHeight/2,rx=mx,ry=my;
    addEventListener('mousemove',function(e){mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px';});
    (function loop(){rx+=(mx-rx)*.2;ry+=(my-ry)*.2;ring.style.left=rx+'px';ring.style.top=ry+'px';requestAnimationFrame(loop);})();
    document.addEventListener('mouseover',function(e){
      var lens=e.target.closest('[data-lens]');
      ring.classList.toggle('lens', !!lens);
      if(e.target.closest('[data-cursor],a,button,.no-card,.pca-card,.dvq-card,.d-pillar,.d-coll-case,.op-voice,.ancient-map-entry__zoom,.timeline-lightbox-close,.timeline-lightbox-nav,.sov-chron,.sov-balance__side,.sov-next,.foot-panel,.foot-btn,.site-fab-btn')&&!lens) cur.classList.add('expand');
      else cur.classList.remove('expand');
    });
  }

  /* ===== Nav: scroll progress + active step + compact (ported from hoangsa.dev) ===== */
  var nav=document.getElementById('mainNav');
  var navLogo=document.getElementById('navLogo');
  function syncNavOffset(){
    if(!nav) return;
    var h=Math.ceil(nav.getBoundingClientRect().height);
    if(h>0) document.documentElement.style.setProperty('--nav-offset',h+'px');
  }
  syncNavOffset();
  if(nav&&typeof ResizeObserver!=='undefined'){
    var navOffsetRo=new ResizeObserver(syncNavOffset);
    navOffsetRo.observe(nav);
  }else{
    window.addEventListener('resize',syncNavOffset,{passive:true});
  }
  var navRail=document.getElementById('navRail');
  var navRailSteps=navRail?navRail.querySelector('.nav-rail-steps'):null;
  var navRailTrack=navRailSteps?navRailSteps.querySelector('.nav-rail-track'):null;
  var navRailProgress=document.getElementById('navRailProgress');
  var navSteps=[].slice.call(document.querySelectorAll('.nav-step'));
  var SCROLL_MARKER_RATIO=0.32;
  var reduceMo=matchMedia('(prefers-reduced-motion: reduce)');

  function getScrollMarkerY(){ return pageYOffset + innerHeight*SCROLL_MARKER_RATIO; }
  function getSectionTop(el){ return el.getBoundingClientRect().top + pageYOffset; }

  function measureNavRail(){
    if(!navRailSteps||!navSteps.length) return null;
    var centers=navSteps.map(function(step){
      var dot=step.querySelector('.nav-step-dot');
      if(!dot) return 0;
      return step.offsetLeft + dot.offsetLeft + dot.offsetWidth/2;
    });
    var trackStart=centers[0], trackEnd=centers[centers.length-1];
    return {centers:centers,trackStart:trackStart,trackEnd:trackEnd,trackWidth:Math.max(0,trackEnd-trackStart)};
  }
  function layoutNavRailTrack(){
    if(!navRailTrack||!navRailProgress) return null;
    var m=measureNavRail();
    if(!m||m.trackWidth<=0) return null;
    navRailTrack.style.left=m.trackStart+'px';
    navRailTrack.style.width=m.trackWidth+'px';
    navRailProgress.style.left=m.trackStart+'px';
    return m;
  }
  function getActiveNavStepIndex(){
    var marker=getScrollMarkerY(), idx=-1;
    navSteps.forEach(function(step,i){
      var el=document.getElementById(step.dataset.section);
      if(el && marker>=getSectionTop(el)) idx=i;
    });
    return idx;
  }
  function getNavRailProgressWidthPx(m){
    var activeIdx=getActiveNavStepIndex();
    if(activeIdx<0||m.trackWidth<=0) return 0;
    var rel=m.centers.map(function(c){ return c-m.trackStart; });
    var marker=getScrollMarkerY(), end=rel[activeIdx];
    if(activeIdx<rel.length-1){
      var el=document.getElementById(navSteps[activeIdx].dataset.section);
      var nextEl=document.getElementById(navSteps[activeIdx+1].dataset.section);
      if(el&&nextEl){
        var start=getSectionTop(el), nextTop=getSectionTop(nextEl);
        var t=nextTop>start?Math.min(1,Math.max(0,(marker-start)/(nextTop-start))):0;
        end=rel[activeIdx]+t*(rel[activeIdx+1]-rel[activeIdx]);
      }
    } else {
      var el2=document.getElementById(navSteps[activeIdx].dataset.section);
      if(el2){
        var start2=getSectionTop(el2), bottom=start2+el2.offsetHeight;
        var t2=Math.min(1,Math.max(0,(marker-start2)/Math.max(1,bottom-start2)));
        end=rel[activeIdx]+t2*(m.trackWidth-rel[activeIdx]);
      }
    }
    return Math.min(m.trackWidth,Math.max(0,end));
  }

  /* center the active step when rail overflows horizontally */
  var lastMilestone=-2, railScrollRaf=0;
  function canScrollRail(){ return navRail && navRail.scrollWidth>navRail.clientWidth+1; }
  function scrollRailToActive(idx){
    if(!canScrollRail()) return;
    if(idx<0){ navRail.scrollTo({left:0,behavior:reduceMo.matches?'auto':'smooth'}); return; }
    var step=navSteps[idx]; if(!step) return;
    var maxScroll=Math.max(0,navRail.scrollWidth-navRail.clientWidth);
    var center=step.offsetLeft+step.offsetWidth/2;
    var left=Math.max(0,Math.min(center-navRail.clientWidth/2,maxScroll));
    if(Math.abs(navRail.scrollLeft-left)>3) navRail.scrollTo({left:left,behavior:reduceMo.matches?'auto':'smooth'});
  }
  function syncMilestone(idx){
    if(idx===lastMilestone) return;
    lastMilestone=idx;
    if(railScrollRaf) cancelAnimationFrame(railScrollRaf);
    railScrollRaf=requestAnimationFrame(function(){ railScrollRaf=0; scrollRailToActive(idx); });
  }

  /* mobile/tablet: hide logo+actions on scroll down, show on scroll up */
  var navLastY=pageYOffset, navCompactRaf=0, navCompactHidden=false;
  var NAV_COMPACT_MQ=matchMedia('(max-width: 1024px)');
  function updateNavCompact(){
    if(!nav) return;
    if(!NAV_COMPACT_MQ.matches){ navCompactHidden=false; nav.classList.remove('is-nav-compact'); navLastY=pageYOffset; return; }
    var y=pageYOffset, delta=y-navLastY;
    if(y<56) navCompactHidden=false;
    else if(delta>3 && y>72) navCompactHidden=true;
    else if(delta<-3) navCompactHidden=false;
    nav.classList.toggle('is-nav-compact',navCompactHidden);
    navLastY=y;
    syncNavOffset();
  }
  NAV_COMPACT_MQ.addEventListener('change',function(){ updateNavCompact(); syncNavOffset(); });

  function updateScrollUi(){
    var m=layoutNavRailTrack()||measureNavRail();
    if(navRailProgress&&m) navRailProgress.style.width=getNavRailProgressWidthPx(m)+'px';
    var activeIdx=getActiveNavStepIndex();
    if(navLogo) navLogo.classList.toggle('is-active',activeIdx<0);
    navSteps.forEach(function(step,i){
      step.classList.remove('is-active','is-passed');
      if(i===activeIdx) step.classList.add('is-active');
      else if(i<activeIdx) step.classList.add('is-passed');
    });
    syncMilestone(activeIdx);
    if(nav) nav.classList.toggle('scrolled', pageYOffset>60);
    if(!navCompactRaf){ navCompactRaf=requestAnimationFrame(function(){ updateNavCompact(); navCompactRaf=0; }); }
  }
  navSteps.forEach(function(step,i){
    step.addEventListener('click',function(){ lastMilestone=i-1; requestAnimationFrame(function(){ scrollRailToActive(i); lastMilestone=i; }); });
  });
  addEventListener('scroll',updateScrollUi,{passive:true});
  addEventListener('resize',updateScrollUi,{passive:true});
  if(navRail) navRail.addEventListener('scroll',updateScrollUi,{passive:true});
  addEventListener('load',function(){ updateScrollUi(); syncNavOffset(); });
  updateScrollUi();
  requestAnimationFrame(function(){ requestAnimationFrame(syncNavOffset); });

  /* ===== Ambient ocean sound (WebAudio, no external file) ===== */
  (function(){
    var btn=document.getElementById('soundBtn'); if(!btn) return;
    var ctx=null, nodes=null, playing=false;
    function build(){
      var AC=window.AudioContext||window.webkitAudioContext; if(!AC) return null;
      ctx=new AC();
      var bufSize=2*ctx.sampleRate, noiseBuf=ctx.createBuffer(1,bufSize,ctx.sampleRate), out=noiseBuf.getChannelData(0);
      var last=0;
      for(var i=0;i<bufSize;i++){ var w=Math.random()*2-1; last=(last+0.02*w)/1.02; out[i]=last*3.2; }
      var src=ctx.createBufferSource(); src.buffer=noiseBuf; src.loop=true;
      var lp=ctx.createBiquadFilter(); lp.type='lowpass'; lp.frequency.value=620;
      var gain=ctx.createGain(); gain.gain.value=0;
      /* slow swell to mimic waves */
      var lfo=ctx.createOscillator(); lfo.frequency.value=0.12;
      var lfoGain=ctx.createGain(); lfoGain.gain.value=0.05;
      lfo.connect(lfoGain); lfoGain.connect(gain.gain);
      src.connect(lp); lp.connect(gain); gain.connect(ctx.destination);
      src.start(0); lfo.start(0);
      return {gain:gain};
    }
    btn.addEventListener('click',function(){
      if(!ctx){ nodes=build(); if(!nodes) return; }
      if(ctx.state==='suspended') ctx.resume();
      playing=!playing;
      btn.classList.toggle('is-playing',playing);
      btn.setAttribute('aria-pressed',playing?'true':'false');
      btn.setAttribute('aria-label',playing?'Tắt âm thanh nền':'Bật âm thanh nền');
      var now=ctx.currentTime;
      nodes.gain.gain.cancelScheduledValues(now);
      nodes.gain.gain.linearRampToValueAtTime(playing?0.16:0, now+ (playing?1.2:0.5));
    });
  })();

  /* Reveal — welcome-reveal.js (Vite) */

  /* Counters */
  var cio=new IntersectionObserver(function(en){
    en.forEach(function(e){
      if(!e.isIntersecting) return;
      var el=e.target, target=+el.dataset.count, suf=el.dataset.suffix||'', pre=el.dataset.prefix||'';
      var o={v:0};
      var s=performance.now(), dur=1500;
      (function tick(t){ var p=Math.min(1,(t-s)/dur); el.textContent=pre+Math.floor(p*target)+suf; if(p<1) requestAnimationFrame(tick); })(s);
      cio.unobserve(el);
    });
  },{threshold:0.5});
  document.querySelectorAll('[data-count]').forEach(function(el){ cio.observe(el); });

  /* Distance bars */
  var bio=new IntersectionObserver(function(en){
    en.forEach(function(e){ if(e.isIntersecting){ var i=e.target.querySelector('i[data-w]'); if(i) i.style.width=i.dataset.w+'%'; bio.unobserve(e.target); } });
  },{threshold:0.3});
  /* Định nghĩa: bản đồ sticky + highlight khi đọc list bên phải */
  (function(){
    var sticky=document.getElementById('defFigureSticky');
    var list=document.getElementById('defList');
    if(!sticky||!list) return;
    var mq=window.matchMedia('(min-width:921px)');
    var io=new IntersectionObserver(function(en){
      if(!mq.matches){ sticky.classList.remove('is-pinned'); return; }
      sticky.classList.toggle('is-pinned',en[0].isIntersecting);
    },{rootMargin:'-96px 0px -28% 0px',threshold:0});
    io.observe(list);
    mq.addEventListener('change',function(){ if(!mq.matches) sticky.classList.remove('is-pinned'); });
  })();

  document.querySelectorAll('.d-coll-case').forEach(function(r){ bio.observe(r); });

  /* Sovereignty chronicle: active row + progress fill */
  (function(){
    var chronicle=document.getElementById('sovChronicle');
    var fill=document.getElementById('sovChronFill');
    var chrons=chronicle?[].slice.call(chronicle.querySelectorAll('.sov-chron')):[];
    if(!chrons.length) return;
    function updateProgress(){
      if(!fill||!chronicle) return;
      var active=-1;
      chrons.forEach(function(c,i){ if(c.classList.contains('is-active')) active=i; });
      if(active<0) active=0;
      var pct=((active+1)/chrons.length)*100;
      fill.style.height=pct+'%';
    }
    var sovIo=new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting) e.target.classList.add('is-active');
        else e.target.classList.remove('is-active');
      });
      updateProgress();
    },{rootMargin:'-18% 0px -58% 0px',threshold:0.2});
    chrons.forEach(function(c){ sovIo.observe(c); });
    updateProgress();
  })();

  /* PCA cards toggle */
  document.querySelectorAll('.no-card').forEach(function(c){ c.addEventListener('click',function(){ c.classList.toggle('open'); }); });

  /* Ancient map magnifier */
  document.querySelectorAll('[data-lens]').forEach(function(frame){
    var lens=frame.querySelector('.lens'), inner=lens.querySelector('.lens-inner'), content=frame.querySelector('[data-lenscontent]');
    var ZOOM=2.4, size=150;
    inner.innerHTML=content.innerHTML;
    function place(e){
      var r=frame.getBoundingClientRect(), x=e.clientX-r.left, y=e.clientY-r.top;
      if(x<0||y<0||x>r.width||y>r.height){ lens.classList.remove('show'); return; }
      lens.classList.add('show');
      lens.style.left=(x-size/2)+'px'; lens.style.top=(y-size/2)+'px';
      inner.style.width=(r.width*ZOOM)+'px'; inner.style.height=(r.height*ZOOM)+'px';
      inner.style.left=(-x*ZOOM+size/2)+'px'; inner.style.top=(-y*ZOOM+size/2)+'px';
    }
    frame.addEventListener('mousemove',place);
    frame.addEventListener('mouseleave',function(){ lens.classList.remove('show'); });
  });

  /* GSAP scrollytelling (progressive enhancement) */
  window.addEventListener('load', function(){
    if(reduce || typeof gsap==='undefined' || typeof ScrollTrigger==='undefined') return;
    gsap.registerPlugin(ScrollTrigger);
    // hero parallax
    gsap.to('.hero-content',{yPercent:26,ease:'none',scrollTrigger:{trigger:'#hero',start:'top top',end:'bottom top',scrub:true,invalidateOnRefresh:true}});
    gsap.to('.hero-stars',{yPercent:-12,ease:'none',scrollTrigger:{trigger:'#hero',start:'top top',end:'bottom top',scrub:true,invalidateOnRefresh:true}});
  });
})();
</script>
</body>
</html>
