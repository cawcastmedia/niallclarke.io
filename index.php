<?php
session_start();

const PW_HASH = '$2b$12$a7afe9qc7vZpbJ22TgPx9u.ByXkV4PLFLMT4yzi6mKl9./z/RjESu';

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (password_verify($_POST['password'], PW_HASH)) {
        session_regenerate_id(true);
        $_SESSION['auth'] = true;
        header('Location: /');
        exit;
    }
    $error = true;
}

if (empty($_SESSION['auth'])) {
    http_response_code(401);
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Restricted — Marketing Calendar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --navy-deep:#060a14;
    --surface:#0c1220;
    --card:#111a2e;
    --border:#1c2a45;
    --border-light:#283b5a;
    --red:#e8364f;
    --text:#e2e8f0;
    --text-muted:#7a8ba8;
    --text-dim:#4a5e7a;
    --white:#fff;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{
    font-family:'DM Sans',-apple-system,sans-serif;
    background:var(--navy-deep);
    color:var(--text);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    -webkit-font-smoothing:antialiased;
    position:relative;
    overflow:hidden;
  }
  body::before{
    content:'';
    position:fixed;
    inset:0;
    background:
      radial-gradient(ellipse 60% 50% at 20% 10%,rgba(232,54,79,0.08) 0%,transparent 60%),
      radial-gradient(ellipse 50% 40% at 80% 90%,rgba(96,165,250,0.05) 0%,transparent 60%);
    pointer-events:none;
  }
  .top-bar{
    height:3px;
    background:linear-gradient(90deg,var(--red),var(--red) 40%,transparent);
    position:fixed;top:0;left:0;right:0;z-index:200;
  }
  .gate{
    position:relative;
    z-index:1;
    width:100%;
    max-width:380px;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:16px;
    padding:36px 32px 32px;
    box-shadow:0 30px 80px rgba(0,0,0,0.55);
    animation:fadeUp .5s ease both;
  }
  @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
  .lock{
    width:44px;
    height:44px;
    border-radius:12px;
    background:linear-gradient(135deg,rgba(232,54,79,0.15),rgba(232,54,79,0.05));
    border:1px solid rgba(232,54,79,0.25);
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:20px;
  }
  .lock svg{width:18px;height:18px;color:var(--red)}
  h1{
    font-size:20px;
    font-weight:700;
    color:var(--white);
    letter-spacing:-0.02em;
    margin-bottom:6px;
  }
  .sub{
    font-size:13px;
    color:var(--text-muted);
    margin-bottom:24px;
  }
  .field{position:relative;margin-bottom:14px}
  label{
    display:block;
    font-size:10px;
    font-weight:600;
    color:var(--text-dim);
    letter-spacing:0.1em;
    text-transform:uppercase;
    margin-bottom:8px;
  }
  input[type="password"]{
    width:100%;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:10px;
    padding:13px 44px 13px 14px;
    color:var(--white);
    font-family:'JetBrains Mono',monospace;
    font-size:14px;
    letter-spacing:0.15em;
    outline:none;
    transition:border-color .2s,background .2s;
  }
  input[type="password"]::placeholder{
    color:var(--text-dim);
    letter-spacing:0.05em;
    font-family:'DM Sans',sans-serif;
  }
  input[type="password"]:focus{
    border-color:var(--border-light);
    background:#152038;
  }
  .toggle-eye{
    position:absolute;
    right:10px;
    bottom:10px;
    width:26px;
    height:26px;
    border:none;
    background:transparent;
    color:var(--text-dim);
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:6px;
    transition:color .15s,background .15s;
  }
  .toggle-eye:hover{color:var(--text-muted);background:rgba(255,255,255,0.04)}
  .toggle-eye svg{width:15px;height:15px}
  button[type="submit"]{
    width:100%;
    background:var(--red);
    color:var(--white);
    border:none;
    border-radius:10px;
    padding:13px;
    font-family:inherit;
    font-size:14px;
    font-weight:600;
    letter-spacing:0.01em;
    cursor:pointer;
    transition:background .2s,transform .1s;
    margin-top:4px;
  }
  button[type="submit"]:hover{background:#f14561}
  button[type="submit"]:active{transform:translateY(1px)}
  .error{
    background:rgba(232,54,79,0.08);
    border:1px solid rgba(232,54,79,0.2);
    color:#ff8a9c;
    font-size:12px;
    padding:10px 12px;
    border-radius:8px;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    gap:8px;
    animation:shake .35s ease;
  }
  .error svg{width:13px;height:13px;flex-shrink:0}
  @keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-4px)}75%{transform:translateX(4px)}}
  .footer-note{
    text-align:center;
    font-size:10.5px;
    color:var(--text-dim);
    margin-top:22px;
    letter-spacing:0.05em;
  }
</style>
</head>
<body>
<div class="top-bar"></div>
<form class="gate" method="POST" autocomplete="off">
  <div class="lock">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
  </div>
  <h1>Restricted Access</h1>
  <p class="sub">Enter the password to view the marketing calendar.</p>

  <?php if ($error): ?>
  <div class="error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Incorrect password. Try again.
  </div>
  <?php endif; ?>

  <div class="field">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required autofocus>
    <button type="button" class="toggle-eye" onclick="togglePw()" aria-label="Show password">
      <svg id="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    </button>
  </div>

  <button type="submit">Unlock</button>

  <div class="footer-note">FunderPro Global &middot; Confidential</div>
</form>

<script>
function togglePw(){
  const i=document.getElementById('password');
  const e=document.getElementById('eye-icon');
  if(i.type==='password'){
    i.type='text';
    e.innerHTML='<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
  }else{
    i.type='password';
    e.innerHTML='<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
}
</script>
</body>
</html>
<?php
    exit;
}

// Authenticated — serve the content
header('Content-Type: text/html; charset=utf-8');
readfile(__DIR__ . '/content.html');