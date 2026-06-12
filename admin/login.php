<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>
body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--black);
  overflow: hidden;
}

/* ANIMATED BACKGROUND */
.bg-smoke {
  position: fixed;
  inset: 0;
  z-index: 0;
  overflow: hidden;
}

.bg-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  animation: orbFloat linear infinite;
}
.bg-orb:nth-child(1){
  width:400px;height:400px;
  background:rgba(99,102,241,0.12);
  top:-100px;left:-100px;
  animation-duration:20s;
}
.bg-orb:nth-child(2){
  width:300px;height:300px;
  background:rgba(6,182,212,0.08);
  bottom:-80px;right:-80px;
  animation-duration:25s;
  animation-direction:reverse;
}
.bg-orb:nth-child(3){
  width:200px;height:200px;
  background:rgba(232,121,249,0.06);
  top:50%;left:50%;
  animation-duration:18s;
}

@keyframes orbFloat {
  0%,100% { transform:translate(0,0) scale(1); }
  33%      { transform:translate(40px,-30px) scale(1.1); }
  66%      { transform:translate(-20px,40px) scale(0.95); }
}

/* SMOKE PARTICLES */
.smoke-bg {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 0;
}

.sp {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
  animation: spRise linear infinite;
}
.sp:nth-child(1){width:80px;height:80px;left:10%;bottom:0;animation-duration:8s;animation-delay:0s;}
.sp:nth-child(2){width:60px;height:60px;left:30%;bottom:0;animation-duration:10s;animation-delay:2s;}
.sp:nth-child(3){width:100px;height:100px;left:60%;bottom:0;animation-duration:12s;animation-delay:4s;}
.sp:nth-child(4){width:50px;height:50px;left:80%;bottom:0;animation-duration:9s;animation-delay:1s;}
.sp:nth-child(5){width:70px;height:70px;left:45%;bottom:0;animation-duration:11s;animation-delay:3s;}

@keyframes spRise {
  0%   { transform:translateY(0) scale(0.5) rotate(0deg); opacity:0.6; }
  100% { transform:translateY(-100vh) scale(3) rotate(180deg); opacity:0; }
}

/* LOGIN CARD */
.login-wrap {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 400px;
  padding: 16px;
  animation: fadeUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.login-card {
  background: rgba(19,19,31,0.85);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 40px 36px;
  backdrop-filter: blur(20px);
  box-shadow: 0 24px 80px rgba(0,0,0,0.7), 0 0 0 1px rgba(99,102,241,0.1), var(--shadow-neon);
  position: relative;
  overflow: hidden;
}

/* top neon line */
.login-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, var(--neon) 30%, var(--neon-cyan) 70%, transparent 100%);
  animation: shimmer 3s infinite;
}

/* VAPE DEVICE ILLUSTRATION */
.vape-device {
  display: flex;
  justify-content: center;
  margin-bottom: 28px;
}

.device-svg {
  width: 64px;
  filter: drop-shadow(0 0 16px rgba(99,102,241,0.6)) drop-shadow(0 0 32px rgba(6,182,212,0.3));
  animation: deviceFloat 4s ease-in-out infinite;
}

@keyframes deviceFloat {
  0%,100% { transform: translateY(0); }
  50%      { transform: translateY(-8px); }
}

/* smoke puff from device */
.puff {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.5) 0%, transparent 70%);
  animation: puff ease-out infinite;
  pointer-events: none;
}
.puff:nth-child(1){ width:20px;height:20px;animation-duration:2s;animation-delay:0s; }
.puff:nth-child(2){ width:14px;height:14px;animation-duration:2.4s;animation-delay:0.6s; }
.puff:nth-child(3){ width:18px;height:18px;animation-duration:2.2s;animation-delay:1.2s; }

@keyframes puff {
  0%   { transform:translate(0,0) scale(0.5); opacity:0.8; }
  100% { transform:translate(var(--dx,10px), -60px) scale(2.5); opacity:0; }
}

.login-logo {
  text-align: center;
  margin-bottom: 32px;
}

.login-icon-wrap {
  position: relative;
  display: inline-block;
}

.login-brand {
  font-family: 'Orbitron', monospace;
  font-size: 24px;
  font-weight: 900;
  letter-spacing: 4px;
  background: linear-gradient(135deg, #fff 30%, var(--neon-cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-top: 12px;
}

.login-sub {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 4px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-top: 4px;
}

/* FORM */
.form-group { margin-bottom: 18px; }

.input-wrap {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  opacity: 0.5;
  pointer-events: none;
}

.form-control {
  padding-left: 42px;
}

.btn-login {
  width: 100%;
  padding: 13px;
  background: var(--neon);
  border: none;
  border-radius: var(--radius-sm);
  color: #fff;
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 3px;
  cursor: pointer;
  transition: all var(--transition);
  box-shadow: 0 0 20px rgba(99,102,241,0.4);
  margin-top: 8px;
  position: relative;
  overflow: hidden;
}

.btn-login::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%);
  transform: translateX(-100%);
  transition: transform 0.5s;
}

.btn-login:hover {
  background: #818cf8;
  box-shadow: 0 0 35px rgba(99,102,241,0.6);
  transform: translateY(-1px);
}

.btn-login:hover::after {
  transform: translateX(100%);
}

.login-divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 0 0;
}

.login-divider span {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 2px;
  color: var(--text-muted);
  text-transform: uppercase;
}

.login-divider::before,
.login-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

.login-footer {
  text-align: center;
  margin-top: 20px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1px;
  color: var(--text-muted);
}

.login-footer span {
  color: var(--neon-cyan);
}
</style>
</head>
<body>

<!-- BG SMOKE -->
<div class="bg-smoke">
  <div class="bg-orb"></div>
  <div class="bg-orb"></div>
  <div class="bg-orb"></div>
</div>
<div class="smoke-bg">
  <div class="sp"></div>
  <div class="sp"></div>
  <div class="sp"></div>
  <div class="sp"></div>
  <div class="sp"></div>
</div>

<!-- LOGIN CARD -->
<div class="login-wrap">
  <div class="login-card">

    <!-- VAPE ICON -->
    <div class="login-logo">
      <div class="login-icon-wrap">
        <!-- Inline SVG vape device -->
        <svg class="device-svg" viewBox="0 0 64 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Body -->
          <rect x="18" y="20" width="28" height="88" rx="8" fill="url(#vg)" stroke="rgba(99,102,241,0.6)" stroke-width="1"/>
          <!-- Screen -->
          <rect x="22" y="30" width="20" height="14" rx="3" fill="rgba(6,182,212,0.2)" stroke="rgba(6,182,212,0.5)" stroke-width="0.8"/>
          <!-- Screen glow lines -->
          <line x1="25" y1="35" x2="39" y2="35" stroke="#06b6d4" stroke-width="0.8" opacity="0.7"/>
          <line x1="25" y1="38" x2="35" y2="38" stroke="#6366f1" stroke-width="0.8" opacity="0.5"/>
          <!-- Button -->
          <rect x="26" y="52" width="12" height="5" rx="2.5" fill="rgba(99,102,241,0.4)" stroke="rgba(99,102,241,0.7)" stroke-width="0.8"/>
          <!-- Bottom vent -->
          <rect x="24" y="96" width="16" height="3" rx="1.5" fill="rgba(255,255,255,0.08)"/>
          <!-- Mouthpiece -->
          <rect x="22" y="8" width="20" height="14" rx="5" fill="url(#vg2)" stroke="rgba(99,102,241,0.5)" stroke-width="1"/>
          <!-- LED dot -->
          <circle cx="32" cy="62" r="3" fill="#06b6d4" opacity="0.8">
            <animate attributeName="opacity" values="0.8;0.2;0.8" dur="2s" repeatCount="indefinite"/>
          </circle>
          <defs>
            <linearGradient id="vg" x1="18" y1="20" x2="46" y2="108" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#1a1a2e"/>
              <stop offset="100%" stop-color="#0a0a18"/>
            </linearGradient>
            <linearGradient id="vg2" x1="22" y1="8" x2="42" y2="22" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#6366f1"/>
              <stop offset="100%" stop-color="#06b6d4"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      <div class="login-brand">AY VAPE</div>
      <div class="login-sub">Admin Dashboard</div>
    </div>

    <!-- FORM -->
    <form method="POST" action="../proses/proses_login.php">

      <div class="form-group">
        <label class="form-label">Username</label>
        <div class="input-wrap">
          <span class="input-icon">◈</span>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
          <span class="input-icon">⬡</span>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn-login">MASUK →</button>

    </form>

    <div class="login-divider"><span>AY Vape 2025</span></div>

    <div class="login-footer">
      Sistem Admin — <span>AUTHORIZED ONLY</span>
    </div>

  </div>
</div>

</body>
</html>