(function(){
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Language Switcher */
  var currentLang = 'vi';
  var langToggleBtn = document.getElementById('langToggleBtn');
  var titles = {
    vi: "Đường Lưỡi Bò là gì? — Sự thật pháp lý & lịch sử | ninedashline.dev",
    en: "What is the Nine-Dash Line? — Legal & Historical Truth | ninedashline.dev"
  };
  var descriptions = {
    vi: "Đường Lưỡi Bò là gì, xuất hiện từ đâu, vì sao gây tranh cãi và vì sao Tòa Trọng tài 2016 bác bỏ — giải thích bằng luật pháp quốc tế và sự thật khách quan.",
    en: "What is the Nine-Dash Line, where did it come from, why is it controversial, and why did the 2016 Arbitral Tribunal reject it — explained through international law and objective facts."
  };

  function setLanguage(lang) {
    currentLang = lang;
    document.documentElement.setAttribute('lang', lang);
    document.title = titles[lang];
    var descMeta = document.querySelector('meta[name="description"]');
    if(descMeta) descMeta.setAttribute('content', descriptions[lang]);
    
    // Update button text active state representation
    if(lang === 'vi') {
      langToggleBtn.innerHTML = '<span>VI</span> | <span style="opacity: 0.5;">EN</span>';
    } else {
      langToggleBtn.innerHTML = '<span style="opacity: 0.5;">VI</span> | <span>EN</span>';
    }
  }

  langToggleBtn.addEventListener('click', function(){
    setLanguage(currentLang === 'vi' ? 'en' : 'vi');
  });

  /* Web Audio API Wave Synthesizer */
  var audioCtx = null;
  var waveNode = null;
  var isAudioPlaying = false;
  var soundToggleBtn = document.getElementById('soundToggleBtn');
  var audioToast = document.getElementById('audioToast');
  var toastAcceptBtn = document.getElementById('toastAcceptBtn');
  var toastCloseBtn = document.getElementById('toastCloseBtn');

  function initAudioContext() {
    if (!audioCtx) {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }
  }

  function startWaveSynthesis() {
    initAudioContext();
    if (audioCtx.state === 'suspended') {
      audioCtx.resume();
    }

    // Synthesis buffer size
    var bufferSize = 4 * audioCtx.sampleRate;
    var noiseBuffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
    var output = noiseBuffer.getChannelData(0);
    for (var i = 0; i < bufferSize; i++) {
      output[i] = Math.random() * 2 - 1;
    }

    var whiteNoise = audioCtx.createBufferSource();
    whiteNoise.buffer = noiseBuffer;
    whiteNoise.loop = true;

    // Filter to simulate low frequency rumbling waves
    var filter = audioCtx.createBiquadFilter();
    filter.type = 'lowpass';
    filter.frequency.value = 350;

    var gainNode = audioCtx.createGain();
    gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);

    whiteNoise.connect(filter);
    filter.connect(gainNode);
    gainNode.connect(audioCtx.destination);
    whiteNoise.start();

    // LFO Oscillator to shape wave intervals (approx 10-12s)
    var lfo = audioCtx.createOscillator();
    lfo.frequency.value = 0.08;

    var lfoFilterGain = audioCtx.createGain();
    lfoFilterGain.gain.value = 220;

    lfo.connect(lfoFilterGain);
    lfoFilterGain.connect(filter.frequency);

    var lfoVolGain = audioCtx.createGain();
    lfoVolGain.gain.value = 0.05;

    lfo.connect(lfoVolGain);
    lfoVolGain.connect(gainNode.gain);

    lfo.start();

    waveNode = {
      whiteNoise: whiteNoise,
      filter: filter,
      gainNode: gainNode,
      lfo: lfo,
      lfoFilterGain: lfoFilterGain,
      lfoVolGain: lfoVolGain
    };
  }

  function stopWaveSynthesis() {
    if (waveNode) {
      try {
        waveNode.whiteNoise.stop();
        waveNode.lfo.stop();
      } catch(e){}
      waveNode = null;
    }
  }

  function toggleAudio(state) {
    if (state === undefined) state = !isAudioPlaying;
    
    if (state) {
      try {
        startWaveSynthesis();
        isAudioPlaying = true;
        soundToggleBtn.classList.add('active');
      } catch (e) {
        console.error("Audio Context initialization failed: ", e);
      }
    } else {
      stopWaveSynthesis();
      isAudioPlaying = false;
      soundToggleBtn.classList.remove('active');
    }
  }

  soundToggleBtn.addEventListener('click', function(){
    toggleAudio();
  });

  // Prompt user after 3 seconds for audio
  setTimeout(function(){
    audioToast.classList.add('show');
  }, 2500);

  toastAcceptBtn.addEventListener('click', function(){
    toggleAudio(true);
    audioToast.classList.remove('show');
  });

  toastCloseBtn.addEventListener('click', function(){
    audioToast.classList.remove('show');
  });

  /* Loader */
  var bar = document.getElementById('loaderBar'), p = 0;
  var li = setInterval(function(){
    p = Math.min(100, p + Math.random()*22);
    bar.style.width = p + '%';
    if(p>=100){ 
      clearInterval(li); 
      setTimeout(function(){ 
        document.getElementById('loader').classList.add('done'); 
      }, 300); 
    }
  }, 80);

  /* Cursor */
  if(!reduce){
    var cur = document.getElementById('cursor'), ring = document.getElementById('cursorRing');
    var mx=innerWidth/2,my=innerHeight/2,rx=mx,ry=my;
    addEventListener('mousemove',function(e){ mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'; });
    (function loop(){ rx+=(mx-rx)*.2; ry+=(my-ry)*.2; ring.style.left=rx+'px'; ring.style.top=ry+'px'; requestAnimationFrame(loop); })();
    document.addEventListener('mouseover',function(e){
      if(e.target.closest('[data-cursor],a,button,.tl-step,.reason-card,.ruling-row')) cur.classList.add('expand');
      else cur.classList.remove('expand');
    });
  }

  /* Nav + progress */
  var nav = document.getElementById('nav'), progress = document.getElementById('progress');
  var secs = [].slice.call(document.querySelectorAll('section[id],footer[id]'));
  var links = [].slice.call(document.querySelectorAll('.nav-links a'));
  function onScroll(){
    var st = scrollY, h = document.documentElement.scrollHeight - innerHeight;
    progress.style.width = (st/h*100) + '%';
    nav.classList.toggle('scrolled', st>40);
    var cur = 0;
    secs.forEach(function(s,i){ if(s.offsetTop - innerHeight*0.4 <= st) cur = i; });
    var id = secs[cur] ? secs[cur].id : '';
    links.forEach(function(a){ 
      var href = a.getAttribute('href');
      a.classList.toggle('active', href === '#'+id || (id === 'museum' && href === '#origin')); 
    });
  }
  addEventListener('scroll', onScroll, {passive:true}); onScroll();

  /* Reveal — footer/copyright cuối trang (tránh rootMargin âm phía dưới) */
  (function(){
    var revealEls = document.querySelectorAll('.reveal');
    if(!revealEls.length) return;
    var markVisible = function(el){ el.classList.add('visible'); };
    if(reduce || !('IntersectionObserver' in window)){
      revealEls.forEach(markVisible);
      return;
    }
    var revealInViewport = function(slackTop, slackBottom){
      slackTop = slackTop || 100; slackBottom = slackBottom || 120;
      var vh = window.innerHeight || document.documentElement.clientHeight;
      revealEls.forEach(function(el){
        if(el.classList.contains('visible')) return;
        var rect = el.getBoundingClientRect();
        if(rect.top < vh + slackBottom && rect.bottom > -slackTop) markVisible(el);
      });
    };
    var revealIfPageBottom = function(){
      var doc = document.documentElement;
      if(window.scrollY + window.innerHeight < doc.scrollHeight - 32) return;
      revealEls.forEach(markVisible);
    };
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(en){
        if(!en.isIntersecting) return;
        markVisible(en.target);
        io.unobserve(en.target);
      });
    }, {threshold:[0,0.06,0.12], rootMargin:'80px 0px 18% 0px'});
    revealEls.forEach(function(el){ io.observe(el); });
    revealInViewport();
    requestAnimationFrame(function(){ revealInViewport(); revealIfPageBottom(); });
    var scrollRaf = 0;
    var onScrollReveal = function(){
      if(scrollRaf) return;
      scrollRaf = requestAnimationFrame(function(){
        scrollRaf = 0;
        revealInViewport();
        revealIfPageBottom();
      });
    };
    window.addEventListener('scroll', onScrollReveal, {passive:true});
    window.addEventListener('resize', onScrollReveal, {passive:true});
  })();

  /* Distance bars animate on reveal */
  var bio = new IntersectionObserver(function(entries){
    entries.forEach(function(en){
      if(en.isIntersecting){ var i = en.target.querySelector('i[data-w]'); if(i) i.style.width = i.dataset.w + '%'; bio.unobserve(en.target); }
    });
  }, {threshold:0.3});
  document.querySelectorAll('.dist-row').forEach(function(r){ bio.observe(r); });

  /* Origin: Dash map segments toggle */
  var dashGroup = document.getElementById('dashGroup');
  var dashLabel = document.getElementById('dashCountLabel');
  
  // Coordinates representing the segments of the U-line
  // 1-9: main U-shape segments, 10-11: Gulf of Tonkin (1948 only), 12: East of Taiwan (2104 vertical map only)
  var segments = [
    // Dashes 1 to 9 (Common Main)
    {id: 1, p: [[235,90],[258,108]]},
    {id: 2, p: [[275,118],[300,140]]},
    {id: 3, p: [[308,160],[318,190]]},
    {id: 4, p: [[318,210],[312,240]]},
    {id: 5, p: [[308,255],[298,285]]},
    {id: 6, p: [[294,310],[272,330]]},
    {id: 7, p: [[255,335],[225,345]]},
    {id: 8, p: [[210,345],[180,348]]},
    {id: 9, p: [[165,340],[145,325]]},
    // Dashes 10 and 11 (Gulf of Tonkin)
    {id: 10, p: [[150,90],[175,85]]},
    {id: 11, p: [[195,78],[215,82]]},
    // Dash 12 (East of Taiwan - 2014)
    {id: 12, p: [[325,120],[335,95]]}
  ];

  function drawDashes(yearCount) {
    if(!dashGroup) return;
    dashGroup.innerHTML='';
    
    segments.forEach(function(seg){
      var show = false;
      if (yearCount === 11) {
        // 1948 shows dashes 1 to 11, excludes 12
        if (seg.id <= 11) show = true;
      } else if (yearCount === 9) {
        // 1953 shows dashes 1 to 9, excludes 10, 11, 12
        if (seg.id <= 9) show = true;
      } else if (yearCount === 10) {
        // 2014 shows dashes 1 to 9, plus dash 12 (Taiwan). Excludes Tonkin 10, 11
        if (seg.id <= 9 || seg.id === 12) show = true;
      }
      
      if (show) {
        var l = document.createElementNS('http://www.w3.org/2000/svg','line');
        l.setAttribute('x1', seg.p[0][0]); l.setAttribute('y1', seg.p[0][1]);
        l.setAttribute('x2', seg.p[1][0]); l.setAttribute('y2', seg.p[1][1]);
        l.style.filter = 'drop-shadow(0 0 5px rgba(218,37,29,.85))';
        dashGroup.appendChild(l);
      }
    });
  }

  // Draw 11 dashes initially (1948)
  drawDashes(11);

  document.querySelectorAll('.tl-step').forEach(function(step){
    function act(){
      document.querySelectorAll('.tl-step').forEach(function(s){ s.classList.remove('active'); });
      step.classList.add('active');
      
      var count = +step.dataset.dashes;
      drawDashes(count);
      
      if(dashLabel) {
        var text = currentLang === 'vi' ? step.dataset.countVi : step.dataset.countEn;
        dashLabel.textContent = text;
      }
    }
    step.addEventListener('mouseenter', act);
    step.addEventListener('click', act);
  });

  // Watch for language change to update active timeline label instantly
  langToggleBtn.addEventListener('click', function(){
    var activeStep = document.querySelector('.tl-step.active');
    if(activeStep && dashLabel) {
      dashLabel.textContent = currentLang === 'vi' ? activeStep.dataset.countVi : activeStep.dataset.countEn;
    }
  });

  /* Verdict Expandable Rulings */
  document.querySelectorAll('.ruling-row').forEach(function(row) {
    row.addEventListener('click', function() {
      var active = row.classList.contains('active');
      document.querySelectorAll('.ruling-row').forEach(function(r) { r.classList.remove('active'); });
      if (!active) {
        row.classList.add('active');
      }
    });
  });

  /* Digital Museum Map Magnifier Glass */
  document.querySelectorAll('.museum-img-container').forEach(function(container) {
    var img = container.querySelector('img');
    var glass = container.querySelector('.magnifier-glass');
    
    container.addEventListener('mousemove', function(e) {
      var rect = container.getBoundingClientRect();
      var x = e.clientX - rect.left;
      var y = e.clientY - rect.top;
      
      // Position magnifier glass
      glass.style.left = (x - 60) + 'px';
      glass.style.top = (y - 60) + 'px';
      
      // Calculate background zoom coordinates
      var px = (x / rect.width) * 100;
      var py = (y / rect.height) * 100;
      
      // Set magnifier background position
      glass.style.backgroundPosition = px + '% ' + py + '%';
    });
  });

  /* Chatbot Interface Logic */
  var chatWidget = document.getElementById('chatWidget');
  var chatBtn = document.getElementById('chatBtn');
  var chatPanel = document.getElementById('chatPanel');
  var chatMsgs = document.getElementById('chatMsgs');
  var chatInput = document.getElementById('chatInput');
  var chatInputEn = document.getElementById('chatInputEn');
  var chatSendBtn = document.getElementById('chatSendBtn');

  chatBtn.addEventListener('click', function() {
    var open = chatPanel.classList.toggle('open');
    chatBtn.classList.toggle('active', open);
    if(open) {
      setTimeout(function(){
        if(currentLang === 'vi') chatInput.focus();
        else chatInputEn.focus();
      }, 300);
    }
  });

  function appendMessage(text, sender) {
    var m = document.createElement('div');
    m.className = 'chat-msg ' + sender;
    m.textContent = text;
    chatMsgs.appendChild(m);
    chatMsgs.scrollTop = chatMsgs.scrollHeight;
  }

  function simulateTypewriter(text, sender) {
    var m = document.createElement('div');
    m.className = 'chat-msg ' + sender;
    chatMsgs.appendChild(m);
    
    var idx = 0;
    function type() {
      if(idx < text.length) {
        m.textContent += text.charAt(idx);
        idx++;
        chatMsgs.scrollTop = chatMsgs.scrollHeight;
        setTimeout(type, 10);
      }
    }
    type();
  }

  async function handleSend(text) {
    if (!text.trim()) return;
    appendMessage(text, 'user');
    
    // Clear inputs
    chatInput.value = '';
    chatInputEn.value = '';

    // Show temporary bot loading
    var loadMsg = document.createElement('div');
    loadMsg.className = 'chat-msg bot';
    loadMsg.innerHTML = '<span style="opacity:0.6">...</span>';
    chatMsgs.appendChild(loadMsg);
    chatMsgs.scrollTop = chatMsgs.scrollHeight;

    try {
      var context = 'general';
      if (text.toLowerCase().includes('unclos') || text.toLowerCase().includes('công ước')) context = 'unclos';
      else if (text.toLowerCase().includes('hoàng sa') || text.toLowerCase().includes('trường sa') || text.toLowerCase().includes('chủ quyền')) context = 'geography';
      else if (text.toLowerCase().includes('lịch sử') || text.toLowerCase().includes('nguồn gốc') || text.toLowerCase().includes('1948')) context = 'timeline';

      var response = await fetch('/api/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          message: text,
          context: context
        })
      });
      
      chatMsgs.removeChild(loadMsg);

      if (response.ok) {
        var data = await response.json();
        simulateTypewriter(data.reply, 'bot');
      } else {
        simulateTypewriter(currentLang === 'vi' ? 'Xin lỗi, trợ lý gặp sự cố kết nối.' : 'Sorry, the assistant encountered a connection issue.', 'bot');
      }
    } catch(e) {
      chatMsgs.removeChild(loadMsg);
      simulateTypewriter(currentLang === 'vi' ? 'Lỗi kết nối máy chủ.' : 'Server connection error.', 'bot');
    }
  }

  chatSendBtn.addEventListener('click', function() {
    var val = currentLang === 'vi' ? chatInput.value : chatInputEn.value;
    handleSend(val);
  });

  chatInput.addEventListener('keypress', function(e) {
    if(e.key === 'Enter') handleSend(chatInput.value);
  });
  chatInputEn.addEventListener('keypress', function(e) {
    if(e.key === 'Enter') handleSend(chatInputEn.value);
  });

  document.querySelectorAll('.suggest-chip').forEach(function(chip) {
    chip.addEventListener('click', function() {
      handleSend(chip.dataset.query);
    });
  });

})();