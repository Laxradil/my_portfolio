/*
 * avatar.js - interactive pixel avatar for nav buttons
 *
 * Files needed (put in Laravel public/img/):
 *   avatar_sheet.png, avatar_sheet.json
 *
 * Blade usage:
 *   <a href="#projects" data-avatar-nav>Projects</a>
 *   <script src="{{ asset('js/avatar.js') }}"></script>
 *   <script>Avatar.init({ sheet: "{{ asset('img/avatar_sheet.png') }}",
 *                         meta:  "{{ asset('img/avatar_sheet.json') }}" });</script>
 *
 * Flow on click: walk under the button -> crouch -> jump (rise/apex/fall)
 * -> land on the button -> go to the section -> drop to the floor -> idle.
 *
 * Positioning is by FEET (x = center, y = baseline), never by image size,
 * and the floor is a hard clamp, so the sprite can't fall through it.
 */
(function (global) {
  'use strict';

  var DEFAULTS = {
    sheet: '/img/avatar_sheet.png',
    meta: '/img/avatar_sheet.json',
    selector: 'a[data-avatar-nav], button[data-avatar-nav]',
    scale: 1,            // size multiplier
    floorOffset: 8,      // px above the bottom of the viewport
    startX: 80,          // starting x (feet center)
    walkSpeed: 240,      // px/s
    jumpTime: 0.75,      // seconds in the air going up to the button
    jumpExtra: 70,       // extra apex height above the higher point (px)
    gravity: 2200,       // px/s^2 for dropping back down
    stayTime: 600,       // ms to stay on the button before dropping
    scrollDelay: 250     // ms after landing before navigating
  };

  // fallback if the JSON can't be loaded
  var FALLBACK = { cellWidth: 133, cellHeight: 158, footPadding: 4 };
  var ROW = { idle: 0, walk: 1, jump: 2, air: 3, land: 4, front: 5 };
  var COUNT = { idle: 4, walk: 6, jump: 3, air: 2, land: 2, front: 2 };
  var FPS = { idle: 5, walk: 10, land: 8, front: 3 };

  var cfg, meta, el;
  var CW, CH, PAD;
  var s = {
    mode: 'idle',    // idle | walk | crouch | jump | land | perched | drop
    x: 0, y: 0,      // feet position (viewport px)
    vy: 0,
    face: 1,         // 1 = right, -1 = left
    t: 0,            // time in current mode
    anim: 'idle', frame: 0, animT: 0,
    target: null,    // { btn, x, y, done }
    jump: null,
    idleFor: 0, frontLeft: 0
  };
  var last = 0, running = false;

  function floorY() { return global.innerHeight - cfg.floorOffset; }

  function setFrame(anim, i) {
    el.style.backgroundPosition = '-' + (i * CW) + 'px -' + (ROW[anim] * CH) + 'px';
  }

  function play(anim, dt, loop) {
    if (s.anim !== anim) { s.anim = anim; s.frame = 0; s.animT = 0; }
    s.animT += dt;
    var step = 1 / (FPS[anim] || 8);
    while (s.animT >= step) {
      s.animT -= step;
      s.frame++;
      if (s.frame >= COUNT[anim]) s.frame = loop === false ? COUNT[anim] - 1 : 0;
    }
    setFrame(anim, s.frame);
  }

  function still(anim, i) { s.anim = anim; s.frame = i; setFrame(anim, i); }

  function setMode(m) { s.mode = m; s.t = 0; }

  function render() {
    // transform-origin is the feet point, so scale/flip happen around the feet
    el.style.transform = 'translate(' + Math.round(s.x - CW / 2) + 'px,' +
      Math.round(s.y - (CH - PAD)) + 'px) scale(' + (s.face * cfg.scale) + ',' + cfg.scale + ')';
  }

  function rectOf(btn) {
    var r = btn.getBoundingClientRect();
    return { x: r.left + r.width / 2, y: r.top, visible: r.bottom > 0 && r.top < global.innerHeight };
  }

  /* ---------- state updates ---------- */
  function update(dt) {
    s.t += dt;

    if (s.mode === 'idle') {
      s.y = floorY();
      s.idleFor += dt;
      if (s.frontLeft > 0) {          // occasionally face the viewer
        s.frontLeft -= dt;
        play('front', dt);
      } else {
        play('idle', dt);
        if (s.idleFor > 5) { s.idleFor = 0; s.frontLeft = 1.4; }
      }
    }

    else if (s.mode === 'walk') {
      var dx = s.target.x - s.x;
      var step = cfg.walkSpeed * dt;
      s.face = dx >= 0 ? 1 : -1;
      if (Math.abs(dx) <= step) {
        s.x = s.target.x;
        setMode('crouch');
      } else {
        s.x += Math.sign(dx) * step;
      }
      s.y = floorY();
      play('walk', dt);
    }

    else if (s.mode === 'crouch') {
      s.y = floorY();
      still('jump', 0);               // anticipation frame
      if (s.t >= 0.14) startJump();
    }

    else if (s.mode === 'jump') {
      var j = s.jump;
      var p = Math.min(1, s.t / j.T);
      // live target so a moving/sticky button is still hit
      var tg = rectOf(s.target.btn);
      j.y1 = tg.visible ? tg.y : floorY();
      s.x = j.x0 + (j.x1 - j.x0) * p;
      s.y = j.y0 + (j.y1 - j.y0) * p - 4 * j.h * p * (1 - p);
      var vy = (j.y1 - j.y0) - 4 * j.h * (1 - 2 * p);   // dy/dp: <0 means rising
      if (p < 0.15) still('jump', 1);
      else if (vy < -40) still('jump', 2);
      else if (Math.abs(vy) <= 40) still('air', 0);
      else still('air', 1);
      if (p >= 1) {
        s.y = j.y1;
        setMode('land');
        s.anim = 'land'; s.frame = 0; s.animT = 0;
      }
    }

    else if (s.mode === 'land') {
      var tg2 = rectOf(s.target.btn);
      s.y = tg2.visible ? tg2.y : floorY();
      play('land', dt, false);
      if (s.t >= 0.24) {
        setMode('perched');
        var tgt = s.target;
        setTimeout(function () { if (tgt.done) tgt.done(); }, cfg.scrollDelay);
      }
    }

    else if (s.mode === 'perched') {
      var tg3 = rectOf(s.target.btn);
      if (!tg3.visible) { startDrop(); }          // button scrolled away
      else {
        s.x = tg3.x; s.y = tg3.y;                 // stay glued to the button
        play('idle', dt);
        if (s.t * 1000 >= cfg.stayTime + cfg.scrollDelay) startDrop();
      }
    }

    else if (s.mode === 'drop') {
      s.vy += cfg.gravity * dt;
      s.y += Math.min(s.vy, 1500) * dt;           // capped so it can't skip the floor
      still('air', s.vy < 200 ? 0 : 1);
      if (s.y >= floorY()) {
        s.y = floorY(); s.vy = 0;
        setMode('landfloor');
        s.anim = 'land'; s.frame = 0; s.animT = 0;
      }
    }

    else if (s.mode === 'landfloor') {
      s.y = floorY();
      play('land', dt, false);
      if (s.t >= 0.24) { s.target = null; s.idleFor = 0; setMode('idle'); }
    }

    // hard safety: never below the floor, never off-screen sideways
    if (s.mode === 'idle' || s.mode === 'walk' || s.mode === 'crouch' || s.mode === 'landfloor') {
      s.y = floorY();
    }
    s.y = Math.min(s.y, floorY());
    s.x = Math.max(CW * cfg.scale / 2, Math.min(global.innerWidth - CW * cfg.scale / 2, s.x));
  }

  function startJump() {
    var tg = rectOf(s.target.btn);
    var y0 = s.y, y1 = tg.visible ? tg.y : floorY();
    s.jump = {
      x0: s.x, x1: tg.x, y0: y0, y1: y1,
      h: Math.max(30, cfg.jumpExtra),
      T: cfg.jumpTime
    };
    s.face = tg.x >= s.x ? 1 : -1;
    setMode('jump');
  }

  function startDrop() { s.vy = 0; setMode('drop'); }

  /* ---------- public: go to a button ---------- */
  function goTo(btn, done) {
    if (s.mode !== 'idle') return false;          // ignore clicks while busy
    var tg = rectOf(btn);
    s.target = { btn: btn, x: tg.x, done: done };
    s.idleFor = 0; s.frontLeft = 0;
    setMode(Math.abs(tg.x - s.x) < 6 ? 'crouch' : 'walk');
    return true;
  }

  function onClick(e) {
    var btn = e.target.closest(cfg.selector);
    if (!btn) return;
    e.preventDefault();                           // stop instant navigation
    var href = btn.getAttribute('href');
    var started = goTo(btn, function () {
      if (!href) return;
      if (href.charAt(0) === '#') {
        var sec = document.querySelector(href);
        if (sec) sec.scrollIntoView({ behavior: 'smooth' });
        history.replaceState(null, '', href);
      } else {
        global.location.href = href;              // normal Laravel route
      }
    });
    if (!started) return;
  }

  function tick(now) {
    var dt = Math.min(0.05, (now - last) / 1000); // clamp: no huge steps after tab switch
    last = now;
    update(dt);
    render();
    global.requestAnimationFrame(tick);
  }

  function boot() {
    CW = meta.cellWidth; CH = meta.cellHeight; PAD = meta.footPadding || 4;
    el = document.createElement('div');
    el.id = 'site-avatar';
    el.style.cssText =
      'position:fixed;left:0;top:0;z-index:9999;pointer-events:none;' +
      'width:' + CW + 'px;height:' + CH + 'px;' +
      'background:url("' + cfg.sheet + '") 0 0 no-repeat;' +
      'image-rendering:pixelated;will-change:transform;' +
      'transform-origin:' + (CW / 2) + 'px ' + (CH - PAD) + 'px;';
    document.body.appendChild(el);

    s.x = cfg.startX; s.y = floorY();
    still('idle', 0);
    render();

    document.addEventListener('click', onClick);
    global.addEventListener('resize', function () {
      if (s.mode === 'idle') s.y = floorY();
    });
    if (!running) { running = true; last = performance.now(); global.requestAnimationFrame(tick); }
  }

  global.Avatar = {
    init: function (opts) {
      cfg = Object.assign({}, DEFAULTS, opts || {});
      fetch(cfg.meta).then(function (r) { return r.json(); })
        .catch(function () { return FALLBACK; })
        .then(function (m) { meta = m; boot(); });
    },
    goTo: goTo
  };
})(window);
