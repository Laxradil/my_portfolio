import './bootstrap';

/* =====================================================================
   1. WEB AUDIO API RETRO MICRO-SFX SYNTHESIZER (MUTED BY DEFAULT)
   ===================================================================== */
class RetroAudioEngine {
    constructor() {
        this.enabled = false;
        this.ctx = null;
    }

    toggle() {
        this.enabled = !this.enabled;
        if (this.enabled && !this.ctx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            this.ctx = new AudioCtx();
        }
        if (this.enabled && this.ctx.state === 'suspended') {
            this.ctx.resume();
        }
        if (this.enabled) {
            this.playToggleOn();
        }
        return this.enabled;
    }

    playTone(freq, duration, type = 'triangle', volume = 0.035, slideTo = null) {
        if (!this.enabled || !this.ctx) return;
        const now = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = type;
        osc.frequency.setValueAtTime(freq, now);
        if (slideTo) {
            osc.frequency.exponentialRampToValueAtTime(slideTo, now + duration);
        }

        gain.gain.setValueAtTime(volume, now);
        gain.gain.exponentialRampToValueAtTime(0.0001, now + duration);

        osc.connect(gain);
        gain.connect(this.ctx.destination);
        osc.start(now);
        osc.stop(now + duration);
    }

    playStep() { this.playTone(135 + Math.random() * 25, 0.035, 'triangle', 0.018, 70); }
    playJump() { this.playTone(210, 0.14, 'square', 0.022, 520); }
    playLand() { this.playTone(120, 0.07, 'sine', 0.04, 48); }
    playKeyClick() { this.playTone(680, 0.025, 'triangle', 0.02, 240); }
    playToggleOn() { this.playTone(440, 0.06, 'square', 0.02, 660); }
}

const sfx = new RetroAudioEngine();

/* =====================================================================
   3. DOM-SPRITE COUPLING PHYSICS & SVG LIMB ANIMATION ENGINE
   ===================================================================== */
const buddyEl = document.getElementById('buddy-entity');
const buddyVisual = document.getElementById('buddy-visual');
const speechEl = document.getElementById('buddy-speech');
const speechTextEl = document.getElementById('buddy-speech-text');
const legLeft = document.getElementById('buddy-leg-left');
const legRight = document.getElementById('buddy-leg-right');
const armFront = document.getElementById('buddy-arm-front');
const armBack = document.getElementById('buddy-arm-back');

if (buddyEl && buddyVisual) {
    const platforms = Array.from(document.querySelectorAll('[data-platform]'));

    const buddy = {
        x: 240,
        y: 120,
        vx: 0,
        vy: 0,
        width: 44,
        height: 56,
        facing: 1,
        grounded: false,
        currentPlatform: null,
        state: 'FALLING',
        walkCycle: 0,
        stepSoundCooldown: 0,
        idleTimer: 0,
        aiPatrol: true,
        targetPlatform: null,
        targetX: null,
        aiDecisionTimer: 1.5
    };

    const keys = { left: false, right: false };
    const gravity = 1380;

    const triggerJump = () => {
        if (!buddy.grounded) return;
        const targetTop = buddy.targetPlatform?.getBoundingClientRect().top;
        const requiredRise = targetTop === undefined
            ? 0
            : Math.max(0, buddy.y + buddy.height - targetTop + 20);
        buddy.vy = -Math.max(560, Math.sqrt(2 * gravity * requiredRise));
        buddy.grounded = false;
        buddy.currentPlatform = null;
        buddy.idleTimer = 0;
        speechEl?.classList.remove('visible');
        sfx.playJump();
    };

    const showSpeech = (text) => {
        if (!text || !speechEl || !speechTextEl) return;
        speechTextEl.textContent = text;
        speechEl.classList.add('visible');
    };

    const hideSpeech = () => speechEl?.classList.remove('visible');

    const compressPlatform = (platformEl, footViewportX, impactSpeed) => {
        const rect = platformEl.getBoundingClientRect();
        const relX = (footViewportX - rect.left) / Math.max(rect.width, 1);
        const tiltDeg = (relX - 0.5) * 2.4;
        const pushPx = Math.min(Math.max(impactSpeed * 0.008, 2.5), 5.5);

        platformEl.style.setProperty('--spring-y', `${pushPx.toFixed(1)}px`);
        platformEl.style.setProperty('--spring-rot', `${tiltDeg.toFixed(2)}deg`);
        sfx.playLand();

        setTimeout(() => {
            platformEl.style.setProperty('--spring-y', '0px');
            platformEl.style.setProperty('--spring-rot', '0deg');
        }, 130);
    };

    const sendBuddyToPlatform = (platEl, customClientX = null) => {
        if (!platEl) return;
        const rect = platEl.getBoundingClientRect();
        buddy.targetPlatform = platEl;
        buddy.targetX = customClientX !== null
            ? Math.min(Math.max(customClientX, rect.left + 26), rect.right - 26)
            : rect.left + rect.width * (0.25 + Math.random() * 0.5);
        buddy.idleTimer = 0;
        hideSpeech();

        if (rect.top < buddy.y + buddy.height - 10 && buddy.grounded) {
            triggerJump();
        }
    };

    platforms.forEach((plat) => {
        plat.addEventListener('click', (e) => {
            if (e.target.closest('button') || e.target.closest('a')) return;
            sendBuddyToPlatform(plat, e.clientX);
        });
    });

    window.addEventListener('keydown', (e) => {
        if (e.target.matches('input, textarea')) return;
        const k = e.key.toLowerCase();
        if (k === 'a' || e.key === 'ArrowLeft') { keys.left = true; buddy.targetPlatform = null; }
        if (k === 'd' || e.key === 'ArrowRight') { keys.right = true; buddy.targetPlatform = null; }
        if (k === 'w' || e.key === 'ArrowUp' || e.code === 'Space') {
            if (e.code === 'Space' && document.activeElement === document.body) e.preventDefault();
            triggerJump();
        }
        if (k === 'g') toggleXrayMode();
    });

    window.addEventListener('keyup', (e) => {
        const k = e.key.toLowerCase();
        if (k === 'a' || e.key === 'ArrowLeft') keys.left = false;
        if (k === 'd' || e.key === 'ArrowRight') keys.right = false;
    });

    let lastFrameTime = performance.now();
    let fpsFrames = 0;
    let fpsLastCheck = performance.now();

    const hudFps = document.getElementById('hud-fps');
    const hudX = document.getElementById('hud-x');
    const hudY = document.getElementById('hud-y');
    const hudVx = document.getElementById('hud-vx');
    const hudVy = document.getElementById('hud-vy');
    const hudState = document.getElementById('hud-state');
    const hudPlatform = document.getElementById('hud-platform');

    function stepPhysics(now) {
        const dt = Math.min((now - lastFrameTime) / 1000, 0.05);
        lastFrameTime = now;

        fpsFrames++;
        if (now - fpsLastCheck >= 500) {
            const fps = Math.round((fpsFrames * 1000) / (now - fpsLastCheck));
            if (hudFps) hudFps.textContent = `${fps} FPS`;
            fpsFrames = 0;
            fpsLastCheck = now;
        }

        const prevBottom = buddy.y + buddy.height;
        const moveSpeed = 235;
        let moveDir = 0;
        if (keys.left) moveDir -= 1;
        if (keys.right) moveDir += 1;

        if (moveDir === 0 && buddy.targetPlatform) {
            const tRect = buddy.targetPlatform.getBoundingClientRect();
            const desiredX = buddy.targetX ?? (tRect.left + tRect.width * 0.5);
            const centerX = buddy.x + buddy.width * 0.5;
            const diffX = desiredX - centerX;

            if (Math.abs(diffX) > 10) {
                moveDir = Math.sign(diffX);
                if (buddy.grounded && tRect.top < buddy.y + buddy.height - 16 && Math.abs(diffX) < 220) {
                    triggerJump();
                }
            } else if (buddy.grounded && buddy.currentPlatform === buddy.targetPlatform) {
                buddy.targetPlatform = null;
            }
        } else if (moveDir === 0 && buddy.aiPatrol) {
            buddy.aiDecisionTimer -= dt;
            if (buddy.aiDecisionTimer <= 0) {
                buddy.aiDecisionTimer = 3.2 + Math.random() * 2.5;
                const visiblePlats = platforms.filter((p) => {
                    const r = p.getBoundingClientRect();
                    return r.top > 70 && r.top < window.innerHeight - 40 && r.width > 80;
                });
                const pool = visiblePlats.length ? visiblePlats : platforms;
                const randomPlat = pool[Math.floor(Math.random() * pool.length)];
                if (randomPlat) sendBuddyToPlatform(randomPlat);
            }
        }

        if (moveDir !== 0) {
            buddy.vx = moveDir * moveSpeed;
            buddy.facing = moveDir;
            buddy.idleTimer = 0;
            hideSpeech();
        } else {
            buddy.vx *= 0.78;
            if (Math.abs(buddy.vx) < 4) buddy.vx = 0;
        }

        buddy.x += buddy.vx * dt;
        const maxX = window.innerWidth - buddy.width - 8;
        if (buddy.x < 8) { buddy.x = 8; buddy.vx = 0; }
        if (buddy.x > maxX) { buddy.x = maxX; buddy.vx = 0; }

        buddy.vy += gravity * dt;
        buddy.y += buddy.vy * dt;

        let landedPlatform = null;
        const footX = buddy.x + buddy.width * 0.5;
        const newBottom = buddy.y + buddy.height;

        if (buddy.vy >= 0) {
            for (const plat of platforms) {
                const rect = plat.getBoundingClientRect();
                if (rect.bottom < 0 || rect.top > window.innerHeight || rect.width < 40) continue;

                const withinX = footX >= rect.left + 8 && footX <= rect.right - 8;
                const crossedTop = prevBottom <= rect.top + 12 && newBottom >= rect.top;

                if (withinX && crossedTop) {
                    landedPlatform = plat;
                    buddy.y = rect.top - buddy.height;
                    if (!buddy.grounded) compressPlatform(plat, footX, buddy.vy);
                    buddy.vy = 0;
                    buddy.grounded = true;
                    buddy.currentPlatform = plat;
                    break;
                }
            }
        }

        const floorY = window.innerHeight - 14;
        if (!landedPlatform && newBottom >= floorY) {
            buddy.y = floorY - buddy.height;
            buddy.vy = 0;
            buddy.grounded = true;
            buddy.currentPlatform = null;
        } else if (!landedPlatform) {
            buddy.grounded = false;
            buddy.currentPlatform = null;
        }

        platforms.forEach((plat) => {
            const rect = plat.getBoundingClientRect();
            const localX = footX - rect.left;
            const distY = Math.abs((buddy.y + buddy.height) - rect.top);
            const withinHoriz = footX >= rect.left - 30 && footX <= rect.right + 30;

            if (withinHoriz && distY < 90) {
                const intensity = Math.max(0, 1 - distY / 90);
                plat.style.setProperty('--foot-x', `${localX.toFixed(1)}px`);
                plat.style.setProperty('--foot-intensity', intensity.toFixed(2));
            } else {
                plat.style.setProperty('--foot-intensity', '0');
            }
        });

        if (!buddy.grounded) {
            buddy.state = buddy.vy < 0 ? 'JUMPING' : 'FALLING';
            legLeft.style.transform = 'translateY(-1.5px)';
            legRight.style.transform = 'translateY(1px)';
            armFront.style.transform = 'rotate(-28deg)';
            armBack.style.transform = 'rotate(22deg)';
        } else if (Math.abs(buddy.vx) > 15) {
            buddy.state = 'WALKING';
            buddy.walkCycle += dt * 16;
            const swing = Math.sin(buddy.walkCycle);
            legLeft.style.transform = `translateY(${(swing * -2.2).toFixed(1)}px)`;
            legRight.style.transform = `translateY(${(swing * 2.2).toFixed(1)}px)`;
            armFront.style.transform = `rotate(${(swing * 18).toFixed(1)}deg)`;
            armBack.style.transform = `rotate(${(-swing * 18).toFixed(1)}deg)`;

            buddy.stepSoundCooldown -= dt;
            if (buddy.stepSoundCooldown <= 0) {
                sfx.playStep();
                buddy.stepSoundCooldown = 0.22;
            }
        } else {
            buddy.state = 'IDLE';
            const breathe = Math.sin(now * 0.004) * 0.8;
            legLeft.style.transform = 'translateY(0px)';
            legRight.style.transform = 'translateY(0px)';
            armFront.style.transform = `translateY(${breathe.toFixed(1)}px)`;
            armBack.style.transform = `translateY(${breathe.toFixed(1)}px)`;

            buddy.idleTimer += dt;
            if (buddy.idleTimer > 1.1 && buddy.currentPlatform) {
                const msg = buddy.currentPlatform.getAttribute('data-speech') || 'SYSTEM READY!';
                showSpeech(msg);
            }
        }

        buddyEl.style.transform = `translate3d(${buddy.x.toFixed(1)}px, ${buddy.y.toFixed(1)}px, 0)`;
        buddyVisual.style.transform = `scaleX(${buddy.facing})`;

        if (document.body.classList.contains('xray-mode')) {
            if (hudX) hudX.textContent = Math.round(buddy.x);
            if (hudY) hudY.textContent = Math.round(buddy.y);
            if (hudVx) hudVx.textContent = buddy.vx.toFixed(1);
            if (hudVy) hudVy.textContent = buddy.vy.toFixed(1);
            if (hudState) hudState.textContent = buddy.state;
            if (hudPlatform) {
                hudPlatform.textContent = buddy.currentPlatform
                    ? (buddy.currentPlatform.getAttribute('data-platform-name') || 'PLATFORM')
                    : 'AIRBORNE';
            }
        }

        requestAnimationFrame(stepPhysics);
    }

    const btnXray = document.getElementById('btn-xray');
    const xrayLabel = document.getElementById('xray-label');
    function toggleXrayMode() {
        const isOn = document.body.classList.toggle('xray-mode');
        if (xrayLabel) xrayLabel.textContent = isOn ? 'ON' : 'OFF';
        btnXray?.classList.toggle('active', isOn);
        sfx.playKeyClick();
    }
    btnXray?.addEventListener('click', toggleXrayMode);

    const btnPatrol = document.getElementById('btn-patrol');
    const patrolLabel = document.getElementById('patrol-label');
    btnPatrol?.addEventListener('click', () => {
        buddy.aiPatrol = !buddy.aiPatrol;
        if (patrolLabel) patrolLabel.textContent = buddy.aiPatrol ? 'ON' : 'OFF';
        btnPatrol.classList.toggle('active', buddy.aiPatrol);
        if (!buddy.aiPatrol) buddy.targetPlatform = null;
        sfx.playKeyClick();
    });

    const btnSfx = document.getElementById('btn-sfx');
    const sfxLabel = document.getElementById('sfx-label');
    btnSfx?.addEventListener('click', () => {
        const active = sfx.toggle();
        if (sfxLabel) sfxLabel.textContent = active ? 'ON' : 'OFF';
        btnSfx.classList.toggle('active', active);
    });

    const firstPlat = platforms[0];
    if (firstPlat) {
        const rect = firstPlat.getBoundingClientRect();
        buddy.x = rect.left + 64;
        buddy.y = Math.max(80, rect.top - buddy.height - 20);
    }
    requestAnimationFrame(stepPhysics);
}
