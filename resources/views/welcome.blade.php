<!DOCTYPE html><html lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>Fhilip.dev | Software Engineer &amp; Student Portfolio</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@400;500;600&amp;display=swap" rel="stylesheet">
<style>
:root {
  --bg-deep: #050d18;
  --bg-surface: rgba(10, 26, 44, 0.7);
  --bg-surface-elevated: rgba(15, 38, 64, 0.85);
  --border-subtle: rgba(47, 227, 201, 0.12);
  --border-focus: rgba(47, 227, 201, 0.4);
  --text-main: #f0f6fc;
  --text-muted: #8faec4;
  --text-dim: #5c7891;
  --cyan-accent: #2fe3c9;
  --cyan-glow: rgba(47, 227, 201, 0.25);
  --blue-accent: #38bdf8;
  --indigo-glow: rgba(99, 102, 241, 0.2);
  --sans: 'Plus Jakarta Sans', system-ui, sans-serif;
  --mono: 'JetBrains Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  background-color: var(--bg-deep);
  color: var(--text-main);
  font-family: var(--sans);
  line-height: 1.6;
  overflow-x: hidden;
  position: relative;
  min-height: 100vh;
}

/* Ambient Radial Lighting */
.bg-ambient {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}
.glow-orb-1 {
  position: absolute;
  top: -150px;
  right: 10%;
  width: 650px;
  height: 650px;
  background: radial-gradient(circle, rgba(47, 227, 201, 0.14) 0%, rgba(56, 189, 248, 0.04) 50%, transparent 75%);
  filter: blur(80px);
}
.glow-orb-2 {
  position: absolute;
  top: 40%;
  left: -200px;
  width: 700px;
  height: 700px;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, rgba(47, 227, 201, 0.04) 50%, transparent 75%);
  filter: blur(90px);
}
.grid-overlay {
  position: absolute;
  inset: 0;
  background-image: 
    linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
  background-size: 56px 56px;
  mask-image: radial-gradient(ellipse 90% 70% at 50% 30%, #000 40%, transparent 95%);
}

.wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 28px;
  position: relative;
  z-index: 1;
}

.mono { font-family: var(--mono); }

/* NAV */
header {
  border-bottom: 1px solid var(--border-subtle);
  background: rgba(5, 13, 24, 0.82);
  backdrop-filter: blur(16px);
  position: sticky;
  top: 0;
  z-index: 50;
  transition: all 0.3s ease;
}
nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 72px;
}
.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  font-size: 1.1rem;
  letter-spacing: -0.01em;
  text-decoration: none;
  color: var(--text-main);
}
.logo-tag {
  background: rgba(47, 227, 201, 0.12);
  border: 1px solid rgba(47, 227, 201, 0.3);
  color: var(--cyan-accent);
  padding: 3px 8px;
  border-radius: 6px;
  font-family: var(--mono);
  font-size: 0.8rem;
  font-weight: 600;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 36px;
}
.nav-links a {
  text-decoration: none;
  color: var(--text-muted);
  font-size: 0.92rem;
  font-weight: 500;
  transition: color 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}
.nav-links a:hover, .nav-links a.active {
  color: var(--cyan-accent);
}
.nav-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(47, 227, 201, 0.08);
  border: 1px solid rgba(47, 227, 201, 0.25);
  padding: 6px 14px;
  border-radius: 9999px;
  font-size: 0.76rem;
  font-family: var(--mono);
  color: var(--cyan-accent);
}
.status-dot {
  width: 7px;
  height: 7px;
  background: var(--cyan-accent);
  border-radius: 50%;
  box-shadow: 0 0 10px var(--cyan-accent);
  animation: pulse-dot 2s infinite ease-in-out;
}
@keyframes pulse-dot {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.4; transform: scale(0.85); }
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 22px;
  border-radius: 8px;
  font-size: 0.88rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-primary {
  background: linear-gradient(135deg, #2fe3c9 0%, #15bfa8 100%);
  color: #031c18;
  box-shadow: 0 4px 20px rgba(47, 227, 201, 0.35);
  border: 1px solid #44f8df;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(47, 227, 201, 0.5);
}
.btn-outline {
  background: rgba(10, 26, 44, 0.6);
  border: 1px solid var(--border-focus);
  color: var(--cyan-accent);
  backdrop-filter: blur(8px);
}
.btn-outline:hover {
  background: rgba(47, 227, 201, 0.12);
  border-color: var(--cyan-accent);
  transform: translateY(-2px);
}
.btn-ghost {
  color: var(--text-muted);
  border: 1px solid rgba(255, 255, 255, 0.08);
  background: rgba(255, 255, 255, 0.03);
}
.btn-ghost:hover {
  color: var(--text-main);
  background: rgba(255, 255, 255, 0.07);
  border-color: rgba(255, 255, 255, 0.18);
}

/* ==========================================================================
   REDESIGNED HERO SECTION
   ========================================================================== */
.hero {
  padding: 92px 0 96px;
  position: relative;
}
.hero-layout {
  display: grid;
  grid-template-columns: 1.25fr 1fr;
  gap: 56px;
  align-items: center;
}
.hero-badge-wrap {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: rgba(15, 38, 64, 0.7);
  border: 1px solid rgba(47, 227, 201, 0.2);
  padding: 6px 14px;
  border-radius: 9999px;
  margin-bottom: 24px;
  backdrop-filter: blur(12px);
}
.hero-badge-wrap span {
  font-family: var(--mono);
  font-size: 0.78rem;
  color: var(--cyan-accent);
  font-weight: 500;
}
.hero h1 {
  font-size: clamp(2.4rem, 4.8vw, 3.8rem);
  font-weight: 800;
  line-height: 1.1;
  letter-spacing: -0.03em;
  margin-bottom: 18px;
}
.hero h1 .gradient-text {
  background: linear-gradient(135deg, #ffffff 30%, #a2ecdf 70%, #2fe3c9 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.hero h1 .accent-cursor {
  color: var(--cyan-accent);
  font-family: var(--mono);
  font-weight: 400;
  animation: blink 1.2s infinite;
}
@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

.hero-subtitle {
  font-size: clamp(1.15rem, 2.2vw, 1.45rem);
  font-weight: 500;
  color: var(--text-muted);
  line-height: 1.35;
  margin-bottom: 20px;
}
.hero-subtitle strong {
  color: var(--text-main);
  font-weight: 600;
}
.hero-description {
  color: var(--text-dim);
  font-size: 1.02rem;
  max-width: 520px;
  line-height: 1.7;
  margin-bottom: 34px;
}

.hero-actions {
  display: flex;
  gap: 16px;
  align-items: center;
  flex-wrap: wrap;
  margin-bottom: 40px;
}

/* Stack Ticker / Micro-Pills */
.hero-stack {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  padding-top: 24px;
}
.hero-stack-title {
  font-family: var(--mono);
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--text-dim);
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.hero-stack-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.stack-chip {
  font-family: var(--mono);
  font-size: 0.75rem;
  color: var(--text-muted);
  background: rgba(15, 38, 64, 0.55);
  border: 1px solid rgba(255, 255, 255, 0.08);
  padding: 4px 10px;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}
.stack-chip:hover {
  border-color: var(--border-focus);
  color: var(--cyan-accent);
  background: rgba(47, 227, 201, 0.08);
}
.stack-chip svg {
  width: 13px;
  height: 13px;
  fill: currentColor;
}

/* Hero Right Column: Developer Terminal + Holographic Profile */
.hero-visual-card {
  position: relative;
}
.terminal-window {
  background: rgba(10, 24, 40, 0.88);
  border: 1px solid rgba(47, 227, 201, 0.22);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 
    0 20px 50px -10px rgba(0, 0, 0, 0.7),
    0 0 40px rgba(47, 227, 201, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
}
.terminal-header {
  background: rgba(6, 17, 30, 0.95);
  padding: 12px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid rgba(47, 227, 201, 0.14);
}
.terminal-dots {
  display: flex;
  gap: 7px;
}
.tdot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}
.tdot.red { background: #ff5f56; }
.tdot.yellow { background: #ffbd2e; }
.tdot.green { background: #27c93f; }
.terminal-title {
  font-family: var(--mono);
  font-size: 0.74rem;
  color: var(--text-dim);
  display: flex;
  align-items: center;
  gap: 6px;
}
.terminal-body {
  padding: 24px;
}

/* User Profile Badge Inside Terminal */
.terminal-profile-row {
  display: flex;
  align-items: center;
  gap: 18px;
  padding-bottom: 20px;
  margin-bottom: 18px;
  border-bottom: 1px dashed rgba(47, 227, 201, 0.15);
}
.profile-avatar-frame {
  width: 76px;
  height: 76px;
  border-radius: 50%;
  position: relative;
  padding: 3px;
  background: linear-gradient(135deg, var(--cyan-accent), #38bdf8, transparent);
  box-shadow: 0 0 24px rgba(47, 227, 201, 0.35);
  flex-shrink: 0;
}
.profile-avatar-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
  background: #081a2b;
}
.profile-details h4 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #fff;
  letter-spacing: -0.01em;
}
.profile-details p {
  font-family: var(--mono);
  font-size: 0.76rem;
  color: var(--cyan-accent);
  margin-top: 2px;
}
.profile-details .sub {
  font-size: 0.8rem;
  color: var(--text-dim);
  margin-top: 4px;
}

.code-block {
  font-family: var(--mono);
  font-size: 0.8rem;
  line-height: 1.7;
}
.c-kw { color: #f472b6; }
.c-fn { color: #38bdf8; }
.c-str { color: #a5f3fc; }
.c-prop { color: #93c5fd; }
.c-num { color: #fcd34d; }
.c-com { color: #47657d; font-style: italic; }

.floating-stat-badge {
  position: absolute;
  bottom: -20px;
  right: -16px;
  background: rgba(7, 20, 34, 0.95);
  border: 1px solid rgba(47, 227, 201, 0.35);
  padding: 12px 18px;
  border-radius: 12px;
  box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6), 0 0 25px rgba(47, 227, 201, 0.2);
  display: flex;
  align-items: center;
  gap: 12px;
  backdrop-filter: blur(12px);
}
.stat-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: rgba(47, 227, 201, 0.12);
  color: var(--cyan-accent);
  display: grid;
  place-items: center;
}
.stat-info b {
  font-size: 1.05rem;
  color: #fff;
  display: block;
}
.stat-info span {
  font-family: var(--mono);
  font-size: 0.7rem;
  color: var(--text-dim);
}

/* ==========================================================================
   REDESIGNED PROJECT SHOWCASE (BENTO / DYNAMIC FEATURE CARDS)
   ========================================================================== */
section {
  padding: 96px 0;
  position: relative;
}
.section-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 48px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  padding-bottom: 24px;
}
.section-label {
  font-family: var(--mono);
  font-size: 0.8rem;
  color: var(--cyan-accent);
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-title {
  font-size: clamp(1.8rem, 3.2vw, 2.5rem);
  font-weight: 800;
  letter-spacing: -0.02em;
  color: #fff;
}
.section-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Bento Grid System for Projects */
.projects-bento {
  display: grid;
  grid-template-columns: 1fr;
  gap: 36px;
}

/* Featured Hero Project Card */
.project-card {
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(16px);
}
.project-card:hover {
  transform: translateY(-4px);
  border-color: var(--border-focus);
  box-shadow: 
    0 24px 50px -12px rgba(0, 0, 0, 0.6),
    0 0 40px rgba(47, 227, 201, 0.12);
}

/* Feature Top Accent Highlight line */
.project-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgba(47, 227, 201, 0.6), transparent);
  opacity: 0;
  transition: opacity 0.3s;
}
.project-card:hover::before {
  opacity: 1;
}

/* Layout for Featured #1 */
.project-card.featured {
  display: grid;
  grid-template-columns: 1.15fr 1fr;
}

/* Browser Mockup Visual Preview */
.project-visual {
  background: #061524;
  padding: 32px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  overflow: hidden;
  border-right: 1px solid var(--border-subtle);
}
.mockup-window {
  background: #081d31;
  border: 1px solid rgba(47, 227, 201, 0.2);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 18px 36px rgba(0, 0, 0, 0.5);
  position: relative;
}
.mockup-bar {
  background: #061423;
  padding: 8px 14px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.mockup-address {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 4px;
  padding: 2px 10px;
  font-family: var(--mono);
  font-size: 0.68rem;
  color: var(--text-dim);
  flex-grow: 1;
  text-align: center;
}
.mockup-content {
  aspect-ratio: 16 / 10;
  position: relative;
  background: #0a2035;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

/* Custom Vector UI Representation for QR Attendance */
.qr-ui-canvas {
  width: 100%;
  height: 100%;
  background: #071726;
  padding: 20px;
  display: grid;
  grid-template-columns: 80px 1fr;
  gap: 16px;
}
.qr-sidebar {
  background: rgba(14, 37, 61, 0.6);
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.05);
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.qr-nav-item {
  height: 14px;
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.06);
}
.qr-nav-item.active {
  background: rgba(47, 227, 201, 0.25);
  border: 1px solid var(--cyan-accent);
}
.qr-main-ui {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.qr-stats-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}
.qr-mini-card {
  background: rgba(16, 42, 68, 0.5);
  border: 1px solid rgba(255, 255, 255, 0.06);
  border-radius: 6px;
  padding: 8px;
}
.qr-mini-card .val {
  font-weight: 700;
  font-size: 0.85rem;
  color: #fff;
}
.qr-mini-card .lbl {
  font-size: 0.6rem;
  color: var(--text-dim);
  font-family: var(--mono);
}
.qr-scanner-box {
  flex: 1;
  background: rgba(12, 33, 54, 0.8);
  border: 1px dashed rgba(47, 227, 201, 0.4);
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  position: relative;
}
.qr-target-icon {
  width: 54px;
  height: 54px;
  border: 2px solid var(--cyan-accent);
  border-radius: 8px;
  display: grid;
  place-items: center;
  position: relative;
  box-shadow: 0 0 16px rgba(47, 227, 201, 0.3);
}
.qr-scan-line {
  position: absolute;
  width: 80%;
  height: 2px;
  background: var(--cyan-accent);
  box-shadow: 0 0 8px var(--cyan-accent);
  animation: scan-anim 2.5s infinite alternate ease-in-out;
}
@keyframes scan-anim {
  0% { top: 15%; }
  100% { top: 80%; }
}

/* Project Content Column */
.project-details {
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.project-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
}
.category-badge {
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--cyan-accent);
  background: rgba(47, 227, 201, 0.1);
  border: 1px solid rgba(47, 227, 201, 0.25);
  padding: 3px 8px;
  border-radius: 4px;
  font-weight: 500;
}
.project-role {
  font-family: var(--mono);
  font-size: 0.74rem;
  color: var(--text-dim);
}
.project-details h3 {
  font-size: 1.85rem;
  font-weight: 700;
  letter-spacing: -0.02em;
  color: #fff;
  margin-bottom: 14px;
}
.project-details p {
  color: var(--text-muted);
  font-size: 0.95rem;
  line-height: 1.65;
  margin-bottom: 22px;
}

/* Feature Highlights Grid */
.feature-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin-bottom: 24px;
  padding: 14px;
  background: rgba(6, 17, 30, 0.5);
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.05);
}
.feature-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.8rem;
  color: var(--text-main);
  font-family: var(--mono);
}
.feature-item svg {
  color: var(--cyan-accent);
  flex-shrink: 0;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 7px;
  margin-bottom: 28px;
}
.tech-tag {
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--text-muted);
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  padding: 4px 10px;
  border-radius: 6px;
}

.project-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  padding-top: 20px;
}
.project-link-cta {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--cyan-accent);
  font-family: var(--mono);
  font-size: 0.86rem;
  font-weight: 600;
  text-decoration: none;
  transition: transform 0.2s;
}
.project-link-cta:hover {
  transform: translateX(4px);
}
.github-icon-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--text-muted);
  font-family: var(--mono);
  font-size: 0.78rem;
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 6px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  transition: all 0.2s;
}
.github-icon-btn:hover {
  color: #fff;
  border-color: rgba(255, 255, 255, 0.2);
}

/* Secondary 2-Column Bento Grid */
.projects-subgrid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 28px;
}
.subgrid-card {
  display: flex;
  flex-direction: column;
}
.subgrid-visual {
  height: 200px;
  background: #081c30;
  border-bottom: 1px solid var(--border-subtle);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.subgrid-visual-badge {
  position: absolute;
  top: 14px;
  right: 14px;
  background: rgba(5, 15, 26, 0.8);
  border: 1px solid rgba(47, 227, 201, 0.3);
  color: var(--cyan-accent);
  font-family: var(--mono);
  font-size: 0.68rem;
  padding: 3px 8px;
  border-radius: 4px;
}
.subgrid-details {
  padding: 28px;
  display: flex;
  flex-direction: column;
  flex: 1;
}

/* Realistic Catalog/Library UI Representation */
.library-ui-preview {
  width: 85%;
  height: 80%;
  background: #061423;
  border: 1px solid rgba(56, 189, 248, 0.2);
  border-radius: 8px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.library-search {
  height: 18px;
  background: rgba(255, 255, 255, 0.06);
  border-radius: 4px;
  border: 1px solid rgba(255, 255, 255, 0.08);
  width: 60%;
}
.library-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  flex: 1;
}
.book-cover {
  background: linear-gradient(135deg, #0e2f50, #164673);
  border-radius: 4px;
  border: 1px solid rgba(47, 227, 201, 0.15);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 6px;
}
.book-cover .b-line {
  height: 4px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
  margin-bottom: 3px;
}
.book-cover .b-line.half { width: 50%; }

/* DSA Graph Visual */
.dsa-visual-preview {
  width: 85%;
  height: 80%;
  background: #061423;
  border: 1px solid rgba(47, 227, 201, 0.2);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.tree-node {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #0e2d48;
  border: 1.5px solid var(--cyan-accent);
  color: var(--cyan-accent);
  display: grid;
  place-items: center;
  font-family: var(--mono);
  font-size: 0.65rem;
  font-weight: 700;
  box-shadow: 0 0 10px rgba(47, 227, 201, 0.3);
}

/* EXISTING EDUCATION, ABOUT, FOOTER STYLES PRESERVED & BLENDED */
.legacy-section {
  border-top: 1px solid var(--border-subtle);
  padding: 80px 0;
}
.certs-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
.cert-card {
  padding: 22px;
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: 12px;
  backdrop-filter: blur(12px);
  transition: border-color 0.2s;
}
.cert-card:hover {
  border-color: var(--border-focus);
}
.cert-card b {
  display: block;
  font-size: 1.02rem;
  color: #fff;
  margin-bottom: 4px;
}
.cert-card small {
  font-family: var(--mono);
  color: var(--text-dim);
  font-size: 0.74rem;
}

.edu-row {
  display: grid;
  grid-template-columns: 180px 1fr;
  gap: 40px;
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  padding: 32px;
}
.edu-row .yr {
  font-family: var(--mono);
  color: var(--cyan-accent);
  font-size: 0.9rem;
  font-weight: 600;
}
.edu-row h3 {
  font-size: 1.5rem;
  color: #fff;
  margin-bottom: 4px;
}
.edu-row .uni {
  color: var(--text-muted);
  font-family: var(--mono);
  font-size: 0.88rem;
  margin-bottom: 12px;
}

.about-grid {
  display: grid;
  grid-template-columns: 1.3fr 1fr;
  gap: 56px;
  align-items: center;
}
.about-grid h2 {
  font-size: clamp(1.8rem, 3.2vw, 2.4rem);
  font-weight: 700;
  line-height: 1.25;
  margin-bottom: 20px;
  letter-spacing: -0.02em;
}
.stats-banner {
  display: flex;
  gap: 32px;
  margin-top: 28px;
}
.stat-box {
  background: rgba(15, 38, 64, 0.6);
  border: 1px solid var(--border-subtle);
  padding: 16px 24px;
  border-radius: 10px;
  text-align: center;
  min-width: 110px;
}
.stat-box b {
  font-size: 1.8rem;
  color: var(--cyan-accent);
  display: block;
  line-height: 1;
  margin-bottom: 4px;
}
.stat-box small {
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--text-muted);
}

.cta-box {
  text-align: center;
  background: radial-gradient(circle at 50% 50%, rgba(47, 227, 201, 0.08) 0%, transparent 70%), var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: 24px;
  padding: 64px 32px;
}
.cta-box h2 {
  font-size: clamp(2rem, 3.8vw, 2.8rem);
  font-weight: 800;
  margin-bottom: 16px;
  letter-spacing: -0.02em;
}

footer {
  border-top: 1px solid var(--border-subtle);
  padding: 32px 0;
  color: var(--text-dim);
  font-size: 0.82rem;
  font-family: var(--mono);
  background: #030a13;
}
footer .wrap {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

@media (max-width: 960px) {
  .hero-layout { grid-template-columns: 1fr; }
  .project-card.featured { grid-template-columns: 1fr; }
  .projects-subgrid { grid-template-columns: 1fr; }
  .about-grid { grid-template-columns: 1fr; }
  .certs-grid { grid-template-columns: 1fr; }
  .edu-row { grid-template-columns: 1fr; gap: 12px; }
  .nav-links { display: none; }
}

.npc-companion {
  position: fixed;
  left: 0;
  top: 0;
  z-index: 60;
  width: 64px;
  height: 96px;
  pointer-events: none;
  will-change: transform;
}

.wandering-sprite {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 64px;
  height: 64px;
  image-rendering: pixelated;
  transform-origin: center bottom;
  filter: drop-shadow(0 8px 8px rgba(0, 0, 0, 0.45));
}

.npc-speech {
  position: absolute;
  bottom: 82px;
  left: 50%;
  min-width: max-content;
  max-width: 190px;
  padding: 6px 9px;
  border: 1px solid rgba(47, 227, 201, 0.35);
  border-radius: 7px;
  background: rgba(5, 15, 26, 0.92);
  color: var(--text-main);
  font: 0.66rem/1.35 var(--mono);
  opacity: 0;
  transform: translate(-50%, 6px);
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.npc-speech.visible {
  opacity: 1;
  transform: translate(-50%, 0);
}

@media (prefers-reduced-motion: reduce) {
  .wandering-sprite { opacity: 0.7; }
}
</style>
</head>
<body>
<div class="bg-ambient">
<div class="glow-orb-1"></div>
<div class="glow-orb-2"></div>

</div>
<div class="npc-companion" aria-hidden="true">
<div class="npc-speech"></div>
<canvas class="wandering-sprite" width="96" height="96"></canvas>
</div>
<!-- HEADER / NAV -->
<header>
<nav class="wrap">
<a class="logo" href="#home">
<span class="logo-tag">&lt;/&gt;</span>
<span>Fhilip<span style="color:var(--cyan-accent)">.dev</span></span>
</a>
<div class="nav-links">
<a class="active" href="#home">Overview</a>
<a href="#projects">Projects</a>
</div>
<div class="nav-actions">
<div class="status-pill">
<span class="status-dot"></span>
<span>Open to Internships</span>
</div>
<a class="btn btn-outline" href="#contact" style="padding: 8px 16px; font-size: 0.82rem;">Get in Touch</a>
</div>
</nav>
</header>
<main>
<!-- REDESIGNED HERO -->
<section class="hero" id="home">
<div class="wrap hero-layout">
<div>
<div class="hero-badge-wrap">
<svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="color:var(--cyan-accent)" viewBox="0 0 24 24" width="14"><path d="m18 16 4-4-4-4"></path><path d="m6 8-4 4 4 4"></path><path d="m14.5 4-5 16"></path></svg>
<span>Computer Science Student &amp; Developer</span>
</div>
<h1 data-platform>&lt;Hello, I'm Fhilip<span class="gradient-text">!&gt;</span><span class="accent-cursor">_</span></h1>
<h2 class="hero-subtitle" style="font-size: clamp(1.4rem, 2.5vw, 1.85rem); font-weight: 700; color: var(--text-main); line-height: 1.3; margin-bottom: 18px;">I study, design, and develop useful software.</h2>
<p class="hero-description">A third-year Computer Science student at the University of Santo Tomas–Legazpi, building projects while learning how technology solves real problems.</p>
<div class="hero-actions">
<a class="btn btn-primary" href="#projects">
<span>Explore Featured Projects</span>
<svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24" width="16"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
</a>
<a class="btn btn-ghost" href="#contact">
<span>Contact Me</span>
</a>aca
</div>
<!-- Tech Stack Ticker -->
<div class="hero-stack">
<div class="hero-stack-title">
<svg fill="currentColor" height="12" viewBox="0 0 24 24" width="12"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
<span>Primary Core Stack</span>
</div>
<div class="hero-stack-pills">
<span class="stack-chip" data-platform>Laravel 12</span>
<span class="stack-chip" data-platform>PHP 8.2</span>
<span class="stack-chip" data-platform>PostgreSQL</span>
<span class="stack-chip" data-platform>Supabase</span>
<span class="stack-chip" data-platform>Tailwind CSS</span>
<span class="stack-chip" data-platform>JavaScript</span>
<span class="stack-chip" data-platform>C++</span>
<span class="stack-chip" data-platform>Java</span></div>
</div>
</div>
<!-- Developer Terminal Window with Live Hologram Card -->
<div class="hero-visual-card"><div style="position: relative; max-width: 400px; margin: 0px auto; display: flex; flex-direction: column; align-items: center;"><div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 340px; height: 380px; background: radial-gradient(circle, rgba(47, 227, 201, 0.22) 0%, rgba(56, 189, 248, 0.12) 40%, transparent 70%); filter: blur(48px); pointer-events: none; z-index: 0;"></div><div style="position: relative; z-index: 1; width: 100%; max-width: 360px; height: 420px; border-radius: 28px; padding: 3px; background: linear-gradient(145deg, rgba(47, 227, 201, 0.65) 0%, rgba(56, 189, 248, 0.35) 45%, rgba(10, 26, 44, 0.8) 100%); box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.75), 0 0 45px rgba(47, 227, 201, 0.18); overflow: hidden;"><div style="position: relative; width: 100%; height: 100%; border-radius: 26px; overflow: hidden; background: #061423;"><img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjdroXHSSZb_zs8RppHMMJOlBzCGY04c_n3lfH2MK27P2WOeUsYEtZ0T5SLXcRUbjNqFakjx_RbUmeBxm4Ibc2Qu6zruE5TEQ6zTSEGx2orkbqwhGdYaNMdlaPX17UqxTHaA-mFghHXN_CB1_osZwLiflCQAIUZPsVVkaUUzAM2NoFnACVQ6yTT08DBbfSEB9ROxwXNPU-33WweoTtjht8Di16iKWplpV7uVXf19YCxtqE19oK0IYbhQ" alt="Fhilip Portrait" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; filter: contrast(1.03) saturate(1.05); transition: transform 0.4s ease;"><div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(5, 13, 24, 0.8) 0%, rgba(5, 13, 24, 0.2) 35%, transparent 60%); pointer-events: none;"></div><div style="position: absolute; top: 16px; right: 16px; background: rgba(5, 15, 26, 0.85); border: 1px solid rgba(47, 227, 201, 0.3); backdrop-filter: blur(12px); border-radius: 9999px; padding: 5px 12px; display: inline-flex; align-items: center; gap: 7px;"><span style="width: 7px; height: 7px; border-radius: 50%; background: var(--cyan-accent); box-shadow: 0 0 8px var(--cyan-accent);"></span><span style="font-family: var(--mono); font-size: 0.72rem; color: #fff; font-weight: 500;">Open for roles</span></div><div style="position: absolute; bottom: 18px; left: 18px; right: 18px; display: flex; justify-content: space-between; align-items: flex-end;"><div><div style="font-size: 1.15rem; font-weight: 700; color: #ffffff; letter-spacing: -0.01em; line-height: 1.2;">Fhilip Justyn Briones</div><div style="font-family: var(--mono); font-size: 0.75rem; color: var(--cyan-accent); margin-top: 3px;">CS Student &amp; Web Developer</div></div></div></div></div></div></div>
</div>
</section>
<!-- REDESIGNED PROJECT SHOWCASE -->
<section id="projects">
<div class="wrap">
<div class="section-header">
<div>
<div class="section-label">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
</div>
<h2 class="section-title" data-platform>Projects Designed</h2>
</div>
<div class="section-actions">
<a class="btn btn-ghost" href="#" style="font-size:0.8rem; font-family:var(--mono);">
<span>github.com/repositories</span>
<svg fill="none" height="12" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="12"><path d="M7 17L17 7M17 7H7M17 7V17"></path></svg>
</a>
</div>
</div>
<div class="projects-bento">
<!-- FEATURED CARD #1: QR Attendance System -->
<article class="project-card featured" data-platform>
<!-- Visual preview / browser frame -->
<div class="project-visual">
<div class="mockup-window">
<div class="mockup-bar">
<div class="terminal-dots">
<span class="tdot red"></span>
<span class="tdot yellow"></span>
<span class="tdot green"></span>
</div>
<div class="mockup-address">https://attendance-system.internal/dashboard</div>
</div>
<div class="mockup-content">
<!-- Rich Mock UI -->
<div class="qr-ui-canvas">
<div class="qr-sidebar">
<div class="qr-nav-item active"></div>
<div class="qr-nav-item"></div>
<div class="qr-nav-item"></div>
<div class="qr-nav-item"></div>
</div>
<div class="qr-main-ui">
<div class="qr-stats-row">
<div class="qr-mini-card">
<div class="val">98.2%</div>
<div class="lbl">Present Rate</div>
</div>
<div class="qr-mini-card">
<div class="val">1,420</div>
<div class="lbl">Total Scans</div>
</div>
<div class="qr-mini-card">
<div class="val">Active</div>
<div class="lbl">Scanner</div>
</div>
</div>
<div class="qr-scanner-box">
<div class="qr-target-icon">
<div class="qr-scan-line"></div>
<svg fill="none" height="24" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24"><rect height="6" rx="1" width="6" x="3" y="3"></rect><rect height="6" rx="1" width="6" x="15" y="3"></rect><rect height="6" rx="1" width="6" x="3" y="15"></rect><path d="M15 15h2v2h-2z"></path><path d="M19 19h2v2h-2z"></path></svg>
</div>
<span style="font-family:var(--mono); font-size:0.65rem; color:var(--cyan-accent);">REALTIME QR VERIFICATION</span>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Content Details -->
<div class="project-details">
<div>
<div class="project-eyebrow">
<span class="category-badge">Featured Web Application</span>
<span class="project-role">Lead Full-Stack Developer</span>
</div>
<h3>QR Attendance Management System</h3>
<p>
                An end-to-end institutional attendance system eliminating manual roll calls. Features role-based dashboards (Admin, Professor, Student), real-time encrypted QR scanning with anti-proxy verification, class schedule coordination, and automated audit exports.
              </p>
<!-- Feature Highlights -->
<div class="feature-list">
<div class="feature-item">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
<span>Role-Based Dashboards (RBAC)</span>
</div>
<div class="feature-item">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
<span>Dynamic QR Code Generation</span>
</div>
<div class="feature-item">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
<span>Real-Time Supabase Sync</span>
</div>
<div class="feature-item">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
<span>Automated Attendance Logs</span>
</div>
</div>
<div class="tech-tags">
<span class="tech-tag">Laravel</span>
<span class="tech-tag">PHP</span>
<span class="tech-tag">PostgreSQL</span>
<span class="tech-tag">Supabase</span>
<span class="tech-tag">Blade</span>
<span class="tech-tag">Tailwind CSS</span>
<span class="tech-tag">JavaScript</span>
</div>
</div>
<div class="project-footer">
<a class="project-link-cta" href="#">
<span>View Full Case Study</span>
<svg fill="none" height="16" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
</a>
<a class="github-icon-btn" href="https://github.com/Laxradil/qr_attendace-system" target="_blank" rel="noopener noreferrer">
<svg fill="currentColor" height="14" viewBox="0 0 24 24" width="14"><path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"></path></svg>
<span>Code Repository</span>
</a>
</div>
</div>
</article>
<!-- SUBGRID: 2 Column Bento for Project #2 & #3 -->
<div class="projects-subgrid">
<!-- PROJECT #2: LibraryHub -->
<article class="project-card subgrid-card" data-platform>
<div class="subgrid-visual"><div style="width: 100%; height: 100%; position: relative; overflow: hidden; background: rgb(6, 20, 35); display: flex; align-items: center; justify-content: center; padding: 10px;"><img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBuXVa7jRWVk3-YDKzAI24i7jxxj5iu_C9L3vgQbds_zMjLY6iWT22c-rABxPaenDck3SS6NXoKF3GZUnqCRmccHTK5N-T2rkfBipNUe4bj8mIjGIezr3IyfMGv1lXZBFV0Ic1Gikrn-fGci7u-BULr6KXbk4tYuEOdOcX-tPrySBa5NLHg_kzQQ7wLxivoWq6ELVEjxI8nPEHZJ5uc-WyvsCC2HAwgEnlxv6P_72d_gZ-qzlk_XmAhmw" alt="LibraryHub Books Catalog Interface" class="project-thumb-img" style="width: 100%; height: 100%; object-fit: cover; object-position: top center; border-radius: 8px; border: 1px solid rgba(47, 227, 201, 0.2); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5); transition: transform 0.3s ease;"><div style="position: absolute; top: 18px; right: 18px; background: rgba(5, 15, 26, 0.85); border: 1px solid rgba(47, 227, 201, 0.35); backdrop-filter: blur(8px); color: var(--cyan-accent); font-family: var(--mono); font-size: 0.68rem; padding: 3px 8px; border-radius: 4px; pointer-events: none;">Live Catalog</div></div></div>
<div class="subgrid-details"><div class="project-eyebrow"><span class="category-badge">Web Application</span><span class="project-role">Digital Library System</span></div><h3 style="font-size: 1.45rem; color: rgb(255, 255, 255); margin-bottom: 10px;">LibraryHub Catalog &amp; Management System</h3><p style="color: var(--text-muted); font-size: 0.9rem; flex-grow: 1; margin-bottom: 18px;">A responsive online library platform featuring real-time genre filtering, recently released curated book showcase, and multi-tier user access. Built with Laravel 12 and PHP 8.2 with role-based features for book browsing, contributor submissions, and admin approval workflows.</p><div class="tech-tags" style="margin-bottom: 20px;"><span class="tech-tag">Laravel 12</span><span class="tech-tag">PHP 8.2</span><span class="tech-tag">Tailwind CSS</span><span class="tech-tag">Alpine.js</span><span class="tech-tag">SQLite</span><span class="tech-tag">Blade</span></div><div class="project-footer"><a class="project-link-cta" href="#"><span>Explore Architecture</span><svg fill="none" height="14" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></a><a class="github-icon-btn" href="https://github.com/Laxradil/library-system" target="_blank" rel="noopener noreferrer">Source</a></div></div>
</article>
<!-- PROJECT #3: Data Structures & Algorithms Project -->
<article class="project-card subgrid-card" data-platform><div class="subgrid-visual"><div class="dsa-visual-preview" style="background: radial-gradient(circle at 50% 40%, rgba(47, 227, 201, 0.12) 0%, rgba(6, 20, 35, 0.95) 75%), #061423; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; position: relative; border-color: rgba(47, 227, 201, 0.25); overflow: hidden;"><div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(47, 227, 201, 0.05) 0%, transparent 60%); pointer-events: none;"></div><div style="position: relative; display: flex; align-items: center; justify-content: center;"><div style="position: absolute; width: 64px; height: 64px; border-radius: 50%; background: radial-gradient(circle, rgba(47, 227, 201, 0.25) 0%, transparent 70%); filter: blur(8px);"></div><div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(14, 38, 64, 0.9); border: 1.5px solid var(--cyan-accent); display: grid; place-items: center; box-shadow: 0 0 20px rgba(47, 227, 201, 0.35); position: relative; z-index: 1;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--cyan-accent);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path></svg></div></div><div style="text-align: center; position: relative; z-index: 1;"><div style="font-family: var(--mono); font-size: 0.76rem; font-weight: 700; letter-spacing: 0.12em; color: #fff; text-transform: uppercase;">The Digital Shield</div><div style="font-family: var(--mono); font-size: 0.62rem; color: var(--cyan-accent); margin-top: 2px;">Threat Recognition &amp; Phishing Awareness Website</div></div><div style="display: flex; gap: 8px; position: relative; z-index: 1;"><span style="font-family: var(--mono); font-size: 0.62rem; color: var(--text-dim); background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 2px 7px; border-radius: 4px;">PhishBait Gallery</span><span style="font-family: var(--mono); font-size: 0.62rem; color: var(--cyan-accent); background: rgba(47, 227, 201, 0.08); border: 1px solid rgba(47, 227, 201, 0.2); padding: 2px 7px; border-radius: 4px;">Red Flag Detection</span></div></div></div><div class="subgrid-details"><div class="project-eyebrow"><span class="category-badge">Interactive Cybersecurity Platform</span><span class="project-role">Educational Platform</span></div><h3 style="font-size:1.45rem; color:#fff; margin-bottom:10px;">The Digital Shield</h3><p style="color:var(--text-muted); font-size:0.9rem; flex-grow:1; margin-bottom:18px;">An interactive phishing awareness and digital threat recognition platform. Features an authentic vs deceptive phishing email comparison lab, simulated scam gallery (bank alerts, fake scholarships, password expiration traps), prevention guides, and infographic security breakdowns.</p><div class="tech-tags" style="margin-bottom:20px;"><span class="tech-tag">HTML5</span><span class="tech-tag">CSS3</span><span class="tech-tag">JavaScript</span><span class="tech-tag">Cybersecurity</span><span class="tech-tag">Phishing Analysis</span><span class="tech-tag">UI/UX Design</span></div><div class="project-footer"><a class="project-link-cta" href="/web_shield/" target="_blank" rel="noopener noreferrer"><span>Explore Security Hub</span><svg fill="none" height="14" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></a></div></div></article>
</div>
</div>
</div>
</section>
<!-- CERTIFICATES & SKILLS (Blended Modern Dark Glass) -->
<section class="legacy-section" id="skills">
<div class="wrap">
<div class="section-label">
</div>
<h2 class="section-title" data-platform style="margin-bottom:32px;">Technical Certifications</h2>
<div class="certs-grid">
<div class="cert-card" data-platform>
<b>Introduction to Cybersecurity</b>
<small>Verified Credential · Core Principles &amp; Defense</small>
</div>
<div class="cert-card" data-platform>
<b>Technical Security Essentials</b>
<small>Verified Credential · Threat Assessment &amp; Controls</small>
</div>
<div class="cert-card" data-platform>
<b>Cloud Security Basics</b>
<small>Verified Credential · Infrastructure &amp; Cloud IAM</small>
</div>
</div>
</div>
</section>
<!-- EDUCATION -->
<section class="legacy-section" id="education">
<div class="wrap">
<div class="section-label">
<svg fill="none" height="14" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
</div>
<h2 class="section-title" data-platform style="margin-bottom:32px;">Education</h2>
<div class="edu-row">
<div class="yr">2023 – Present</div>
<div>
<h3>Bachelor of Science in Computer Science</h3>
<div class="uni">University of Santo Tomas–Legazpi · 3rd Year Standing</div>
<p style="color:var(--text-muted); line-height: 1.7;">
            Developing rigorous foundations in object-oriented programming, data structures &amp; algorithms, database systems, software engineering principles, and web application architecture.
          </p>
</div>
</div>
</div>
</section>
<!-- ABOUT -->
<section class="legacy-section" id="about">
<div class="wrap about-grid">
<div>
<div class="section-label">
</div>
<h2>Learning how thoughtful technology can solve real problems.</h2>
<p style="color:var(--text-muted); margin-bottom:20px; line-height:1.7;">
          I'm Fhilip, a third-year Computer Science student at the University of Santo Tomas–Legazpi. I design and develop software while exploring how technology directly simplifies everyday organizational tasks. Currently expanding expertise in backend services, modern frontends, and robust system architecture.
        </p>
<div class="hero-stack-pills">
<span class="stack-chip">JavaScript</span>
<span class="stack-chip">Python</span>
<span class="stack-chip">React</span>
<span class="stack-chip">C++</span>
<span class="stack-chip">Figma</span>
<span class="stack-chip">SQL</span>
<span class="stack-chip">Java</span></div>
<div class="stats-banner">
<div class="stat-box"><b>3rd</b><small>Academic Year</small></div>
<div class="stat-box"><b>BS CS</b><small>Degree Focus</small></div>
<div class="stat-box"><b>3+</b><small>Core Systems</small></div>
</div>
</div>
<div>
<div style="position: relative; max-width: 380px; margin: 0px auto;"><div style="position: absolute; -webkit-filter: blur(45px); filter: blur(45px); inset: -12px; background: radial-gradient(circle, rgba(47, 227, 201, 0.18) 0%, rgba(56, 189, 248, 0.1) 50%, transparent 75%); border-radius: 32px; pointer-events: none; z-index: 0;"></div><div style="position: relative; z-index: 1; background: rgba(10, 25, 42, 0.85); border: 1px solid rgba(47, 227, 201, 0.28); border-radius: 24px; overflow: hidden; box-shadow: 0 20px 48px -10px rgba(0, 0, 0, 0.75), 0 0 32px rgba(47, 227, 201, 0.12); backdrop-filter: blur(20px);"><div style="position: relative; width: 100%; aspect-ratio: 4/4.5; overflow: hidden; background: #061523;"><img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvrl2fVEpbgvtTWD4tksskjNZvAbCBLNN8SIOI6VeldcS9-RGByhkpmoA6nnwoX-_ZGOgLTaYMN8NTVDe0VxwoeUZWRvQJEBFn-fzBC9IBWVZkMkfRCSO6mC4HxKvy01VRp9PQlzqoMAGXVxQcd55R4KL6_uHttH4aa7F3WSBp9DefDK4tWSIvBL-Ninh9HgZkUtrkGmT0hAHUpl4WQUkGpRC2Q2k3UiiCxCkS0emyiVZO4ez12kdXgA" alt="Fhilip Portrait" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; filter: contrast(1.03) saturate(1.05); transition: transform 0.4s ease;"><div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(7, 19, 32, 0.95) 0%, rgba(7, 19, 32, 0.3) 45%, transparent 70%); pointer-events: none;"></div><div style="position: absolute; top: 14px; right: 14px; background: rgba(5, 14, 25, 0.82); border: 1px solid rgba(47, 227, 201, 0.3); backdrop-filter: blur(12px); border-radius: 9999px; padding: 5px 12px; display: inline-flex; align-items: center; gap: 7px;"><span style="width: 7px; height: 7px; border-radius: 50%; background: var(--cyan-accent); box-shadow: 0 0 8px var(--cyan-accent);"></span><span style="font-family: var(--mono); font-size: 0.72rem; color: #fff; font-weight: 500;">Open for roles</span></div><div style="position: absolute; top: 14px; left: 14px; background: rgba(5, 14, 25, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); border-radius: 8px; padding: 4px 10px; font-family: var(--mono); font-size: 0.68rem; color: var(--text-dim); display: flex; align-items: center; gap: 6px;"><span style="color: var(--cyan-accent);">//</span> ust-legazpi</div><div style="position: absolute; bottom: 16px; left: 20px; right: 20px;"><h3 style="font-size: 1.45rem; font-weight: 700; color: #ffffff; letter-spacing: -0.02em; line-height: 1.2;">Fhilip</h3><p style="font-family: var(--mono); font-size: 0.8rem; color: var(--cyan-accent); margin-top: 3px;">CS Student &amp; Web Developer</p></div></div><div style="padding: 16px 20px 20px; border-top: 1px solid rgba(47, 227, 201, 0.12); display: flex; flex-direction: column; gap: 12px; background: rgba(6, 17, 30, 0.95);"><div style="display: flex; justify-content: space-between; align-items: center;"><span style="font-family: var(--mono); font-size: 0.74rem; color: var(--text-dim); display: flex; align-items: center; gap: 6px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>UST–Legazpi, Philippines</span><span style="font-family: var(--mono); font-size: 0.72rem; color: var(--cyan-accent); background: rgba(47, 227, 201, 0.1); border: 1px solid rgba(47, 227, 201, 0.2); border-radius: 6px; padding: 2px 8px;">Class of 2026</span></div></div></div></div>
</div>
</div>
</section>
<!-- CTA -->
<section class="legacy-section" id="contact" style="padding-top:40px;">
<div class="wrap">
<div class="cta-box">
<div class="status-pill" style="margin-bottom:16px;">
<span class="status-dot"></span>
<span>Let's collaborate</span>
</div>
<h2>Ready to build something meaningful?</h2>
<p style="color:var(--text-muted); max-width:540px; margin:0 auto 28px; font-size:1rem;">
          Whether you're looking for an enthusiastic engineering intern, project collaborator, or just want to talk tech and web development.
        </p>
<a class="btn btn-primary" href="mailto:fhilip@example.com" style="padding:14px 32px; font-size:1rem;">
<span>Get in touch via Email</span>
<svg fill="none" height="18" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18"><rect height="16" rx="2" width="20" x="2" y="4"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
</a>
</div>
</div>
</section>
</main>
<footer>
<div class="wrap">
<span>© 2026 Fhilip. Designed &amp; engineered with care.</span>
<span>BS Computer Science · UST–Legazpi · Year 3</span>
</div>
</footer>






</body></html>