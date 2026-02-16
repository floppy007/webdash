<?php
/**
 * webdash — Installation Wizard
 * Standalone script to set up webdash on a new server.
 * Delete this file after installation!
 *
 * Copyright (c) Florian Hesse / Comnic-IT
 * Fischer Str. 1, 16515 Oranienburg
 * info@comnic-it.de
 *
 * License: Free to use, modify, and for commercial use (MIT).
 *
 * https://github.com/floppy007/webdash
 */

// --- Language ---
$lang = 'de';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['de', 'en'], true)) {
    $lang = $_GET['lang'];
} elseif (preg_match('/^en/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '')) {
    $lang = 'en';
}
$t = $lang === 'de' ? [
    'title'=>'Installations-Wizard','toggle_lang'=>'English',
    'system_check'=>'Systemcheck','install_now'=>'Jetzt installieren',
    'installed'=>'Installiert','missing'=>'Fehlt','active'=>'Aktiv','unknown'=>'Unbekannt',
    'dir_writable'=>'Verzeichnis beschreibbar',
    'php_hint'=>'PHP 8.1 oder h&ouml;her ben&ouml;tigt',
    'warn_reqs'=>'Einige Voraussetzungen sind nicht erf&uuml;llt. Die Installation kann trotzdem versucht werden, funktioniert aber m&ouml;glicherweise nicht korrekt.',
    'success_title'=>'Installation erfolgreich!',
    'success_text'=>'webdash v%s wurde installiert.',
    'installed_files'=>'Installierte Dateien',
    'apache_config'=>'Apache-Konfiguration',
    'apache_hint'=>'Empfohlene Apache VirtualHost-Einstellungen:',
    'apache_rewrite'=>'Stelle sicher, dass <code>mod_rewrite</code> aktiviert ist:',
    'delete_warning'=>'<strong>Wichtig:</strong> L&ouml;sche diese Datei (<code>install.php</code>) nach der Installation aus Sicherheitsgr&uuml;nden!',
    'to_config'=>'Jetzt konfigurieren',
    'install_failed'=>'Installation fehlgeschlagen',
    'downloaded_files'=>'Heruntergeladene Dateien',
    'errors'=>'Fehler',
    'back_to_check'=>'Zur&uuml;ck zum Systemcheck',
    'retry'=>'Erneut versuchen',
] : [
    'title'=>'Installation Wizard','toggle_lang'=>'Deutsch',
    'system_check'=>'System Check','install_now'=>'Install now',
    'installed'=>'Installed','missing'=>'Missing','active'=>'Active','unknown'=>'Unknown',
    'dir_writable'=>'Directory writable',
    'php_hint'=>'PHP 8.1 or higher required',
    'warn_reqs'=>'Some requirements are not met. Installation can still be attempted but may not work correctly.',
    'success_title'=>'Installation successful!',
    'success_text'=>'webdash v%s has been installed.',
    'installed_files'=>'Installed files',
    'apache_config'=>'Apache Configuration',
    'apache_hint'=>'Recommended Apache VirtualHost settings:',
    'apache_rewrite'=>'Make sure <code>mod_rewrite</code> is enabled:',
    'delete_warning'=>'<strong>Important:</strong> Delete this file (<code>install.php</code>) after installation for security reasons!',
    'to_config'=>'Configure now',
    'install_failed'=>'Installation failed',
    'downloaded_files'=>'Downloaded files',
    'errors'=>'Errors',
    'back_to_check'=>'Back to system check',
    'retry'=>'Try again',
];

$step = $_GET['step'] ?? 'check';
$langParam = '&lang=' . $lang;

// --- System Check ---
function systemCheck(): array {
    global $t;
    $checks = [];

    $phpOk = version_compare(PHP_VERSION, '8.1', '>=');
    $checks[] = ['name' => 'PHP Version', 'value' => PHP_VERSION, 'ok' => $phpOk, 'hint' => $phpOk ? '' : $t['php_hint']];

    $curlOk = function_exists('curl_init');
    $checks[] = ['name' => 'cURL Extension', 'value' => $curlOk ? $t['installed'] : $t['missing'], 'ok' => $curlOk, 'hint' => $curlOk ? '' : 'apt install php-curl'];

    $modRewrite = false;
    if (function_exists('apache_get_modules')) {
        $modRewrite = in_array('mod_rewrite', apache_get_modules());
    }
    $checks[] = ['name' => 'Apache mod_rewrite', 'value' => $modRewrite ? $t['active'] : $t['unknown'], 'ok' => $modRewrite, 'hint' => $modRewrite ? '' : 'a2enmod rewrite && systemctl restart apache2'];

    $writable = is_writable(__DIR__);
    $checks[] = ['name' => $t['dir_writable'], 'value' => __DIR__, 'ok' => $writable, 'hint' => $writable ? '' : 'chown www-data:www-data ' . __DIR__];

    return $checks;
}

// --- Download & Install ---
function doInstall(): array {
    $result = ['success' => true, 'files' => [], 'errors' => []];

    $ch = curl_init('https://api.github.com/repos/floppy007/webdash/releases/latest');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => ['User-Agent: webdash-installer', 'Accept: application/vnd.github.v3+json'],
    ]);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        $result['success'] = false;
        $result['errors'][] = 'GitHub API (HTTP ' . $httpCode . ($curlErr ? " — $curlErr" : '') . ')';
        return $result;
    }

    $release = json_decode($response, true);
    $tag = $release['tag_name'] ?? '';
    $result['version'] = ltrim($tag, 'v');

    if (!$tag) {
        $result['success'] = false;
        $result['errors'][] = 'No release found on GitHub';
        return $result;
    }

    // Create .dashboard/ directory first
    $dashDir = __DIR__ . '/.dashboard';
    if (!is_dir($dashDir)) {
        if (mkdir($dashDir, 0755, true)) {
            $result['files'][] = '.dashboard/';
        } else {
            $result['success'] = false;
            $result['errors'][] = '.dashboard/ (mkdir failed)';
            return $result;
        }
    }

    // Required files (installation fails if these can't be downloaded)
    $requiredFiles = [
        'index.php'          => __DIR__ . '/index.php',
        '.dashboard/app.php' => $dashDir . '/app.php',
    ];
    // Optional files (installation continues if these fail)
    $optionalFiles = [
        '.htaccess'                    => __DIR__ . '/.htaccess',
        '.dashboard/app-logo-dark.png' => $dashDir . '/app-logo-dark.png',
        '.dashboard/app-logo-light.png'=> $dashDir . '/app-logo-light.png',
    ];

    foreach (array_merge($requiredFiles, $optionalFiles) as $repoPath => $localPath) {
        $isRequired = isset($requiredFiles[$repoPath]);
        $url = "https://raw.githubusercontent.com/floppy007/webdash/$tag/$repoPath";
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => ['User-Agent: webdash-installer'],
        ]);
        $content = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($code !== 200 || !$content) {
            if (!$isRequired) continue;
            $result['success'] = false;
            $result['errors'][] = "$repoPath (HTTP $code" . ($err ? " — $err" : '') . ')';
            continue;
        }

        if (file_put_contents($localPath, $content) !== false) {
            $result['files'][] = $repoPath;
        } else {
            if (!$isRequired) continue;
            $result['success'] = false;
            $result['errors'][] = "$repoPath (write failed)";
        }
    }

    @chmod(__DIR__ . '/index.php', 0644);
    @chmod($dashDir . '/app.php', 0644);
    if (file_exists(__DIR__ . '/.htaccess')) @chmod(__DIR__ . '/.htaccess', 0644);
    @chmod($dashDir, 0755);

    return $result;
}

$installResult = null;
$selfDeleted = false;
if ($step === 'install') {
    $installResult = doInstall();
    if ($installResult['success']) {
        $selfDeleted = @unlink(__FILE__);
    }
}

$checks = systemCheck();
$allOk = !array_filter($checks, fn($c) => !$c['ok']);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>webdash — <?= $lang === 'de' ? 'Installation' : 'Install' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --bg:#051a1a;--bg-end:#0a1628;--surface:rgba(8,32,35,.85);--surface-2:rgba(12,42,45,.7);
  --border:rgba(0,190,190,.1);--border-hover:rgba(0,210,210,.25);
  --accent:#00d4cc;--accent-dim:rgba(0,212,200,.12);
  --success:#10b981;--warn:#f59e0b;--danger:#ef4444;
  --text:#f1f5f9;--text-muted:#8faab4;--text-dim:#5f8a96;
  --shadow:rgba(0,0,0,.35);--dot-color:rgba(0,190,190,.03);
  --font:'Outfit',system-ui,sans-serif;
  --mono:'JetBrains Mono','Fira Code',monospace;
}
html{font-size:15px}
body{
  background:var(--bg);color:var(--text);font-family:var(--font);
  min-height:100vh;line-height:1.6;
  background-image:
    radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),
    linear-gradient(145deg, var(--bg) 0%, #0a2e2e 30%, #0d2a3a 60%, var(--bg-end) 100%);
  background-size:32px 32px, 100% 100%;
  background-attachment:scroll, fixed;
  display:flex;align-items:center;justify-content:center;
  padding:2rem;
}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

.card{
  background:var(--surface);border:1px solid var(--border);border-radius:20px;
  padding:2.5rem;width:100%;max-width:600px;animation:fadeUp .5s ease both;
}
.logo{text-align:center;margin-bottom:2rem}
.logo h1{font-size:1.8rem;font-weight:700;letter-spacing:-.03em;margin-bottom:.25rem}
.logo p{color:var(--text-muted);font-size:.85rem;font-weight:300}
.lang-toggle{
  position:absolute;top:1.5rem;right:1.5rem;font-size:.78rem;color:var(--text-muted);
  text-decoration:none;padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);
  transition:all .25s;font-family:var(--font);
}
.lang-toggle:hover{color:var(--accent);border-color:var(--border-hover)}
.card{position:relative}

.step-indicator{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:2rem}
.step-dot{width:10px;height:10px;border-radius:50%;background:var(--surface-2);border:1px solid var(--border);transition:all .3s}
.step-dot.active{background:var(--accent);border-color:var(--accent);box-shadow:0 0 10px rgba(0,212,200,.3)}
.step-dot.done{background:var(--success);border-color:var(--success)}
.step-line{width:40px;height:1px;background:var(--border)}

.section-title{font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--text-muted);margin-bottom:.75rem}

.check-list{display:flex;flex-direction:column;gap:.5rem;margin-bottom:2rem}
.check-item{display:flex;align-items:center;gap:.75rem;padding:.65rem 1rem;border-radius:10px;background:var(--surface-2);border:1px solid var(--border);font-size:.85rem}
.check-icon{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.75rem;font-weight:700}
.check-icon.ok{background:rgba(16,185,129,.15);color:var(--success)}
.check-icon.fail{background:rgba(239,68,68,.15);color:var(--danger)}
.check-name{font-weight:500;flex:1}
.check-value{font-family:var(--mono);font-size:.75rem;color:var(--text-muted)}
.check-hint{font-size:.72rem;color:var(--danger);margin-top:.2rem}

.file-list{margin-bottom:1.5rem}
.file-item{display:flex;align-items:center;gap:.5rem;padding:.4rem 0;font-family:var(--mono);font-size:.82rem;color:var(--text-muted)}
.file-item .ok{color:var(--success)}
.file-item .fail{color:var(--danger)}

.info-box{background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:.82rem;line-height:1.7}
.info-box code{font-family:var(--mono);font-size:.78rem;background:rgba(0,212,200,.08);padding:.15rem .4rem;border-radius:4px;color:var(--accent)}
.info-box .label{font-weight:600;color:var(--text);display:block;margin-bottom:.3rem}

.warning-box{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:.82rem;color:var(--danger);display:flex;align-items:flex-start;gap:.6rem}
.warning-box svg{flex-shrink:0;margin-top:2px}

.success-box{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:1.25rem;margin-bottom:1.5rem;text-align:center}
.success-box h3{color:var(--success);font-size:1.1rem;margin-bottom:.3rem}
.success-box p{font-size:.85rem;color:var(--text-muted)}

.actions{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
.btn{padding:.75rem 1.5rem;border-radius:12px;border:none;font-family:var(--font);font-size:.9rem;font-weight:600;cursor:pointer;transition:all .25s;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{opacity:.85;transform:translateY(-1px)}
.btn-secondary{background:var(--surface-2);color:var(--text-muted);border:1px solid var(--border)}
.btn-secondary:hover{color:var(--accent);border-color:var(--border-hover)}
</style>
</head>
<body>

<div class="card">
  <a href="?step=<?= $step ?>&lang=<?= $lang === 'de' ? 'en' : 'de' ?>" class="lang-toggle">
    <?= $lang === 'de' ? '&#127465;&#127466; DE' : '&#127468;&#127463; EN' ?>
  </a>
  <div class="logo">
    <h1>webdash</h1>
    <p><?= $t['title'] ?></p>
  </div>

  <div class="step-indicator">
    <div class="step-dot <?= $step === 'check' ? 'active' : ($step !== 'check' ? 'done' : '') ?>"></div>
    <div class="step-line"></div>
    <div class="step-dot <?= $step === 'install' ? 'active' : ($step === 'done' ? 'done' : '') ?>"></div>
    <div class="step-line"></div>
    <div class="step-dot <?= $step === 'done' ? 'active' : '' ?>"></div>
  </div>

<?php if ($step === 'check'): ?>
  <div class="section-title"><?= $t['system_check'] ?></div>
  <div class="check-list">
    <?php foreach ($checks as $check): ?>
    <div class="check-item">
      <div class="check-icon <?= $check['ok'] ? 'ok' : 'fail' ?>">
        <?= $check['ok'] ? '&#10003;' : '&#10007;' ?>
      </div>
      <span class="check-name"><?= htmlspecialchars($check['name']) ?></span>
      <span class="check-value"><?= htmlspecialchars($check['value']) ?></span>
    </div>
    <?php if ($check['hint']): ?>
      <div class="check-hint" style="padding-left:2.5rem"><?= $check['hint'] ?></div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php if (!$allOk): ?>
  <div class="warning-box">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span><?= $t['warn_reqs'] ?></span>
  </div>
  <?php endif; ?>

  <div class="actions">
    <a href="?step=install<?= $langParam ?>" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      <?= $t['install_now'] ?>
    </a>
  </div>

<?php elseif ($step === 'install' && $installResult): ?>

  <?php if ($installResult['success']): ?>
  <div class="success-box">
    <h3><?= $t['success_title'] ?></h3>
    <p><?= sprintf($t['success_text'], htmlspecialchars($installResult['version'] ?? '?')) ?></p>
  </div>

  <div class="section-title"><?= $t['installed_files'] ?></div>
  <div class="file-list">
    <?php foreach ($installResult['files'] as $file): ?>
    <div class="file-item"><span class="ok">&#10003;</span> <?= htmlspecialchars($file) ?></div>
    <?php endforeach; ?>
  </div>

  <div class="section-title"><?= $t['apache_config'] ?></div>
  <div class="info-box">
    <span class="label"><?= $t['apache_hint'] ?></span>
    <code>DocumentRoot <?= htmlspecialchars(__DIR__) ?></code><br>
    <code>AllowOverride All</code><br>
    <code>DirectoryIndex index.php</code><br><br>
    <?= $t['apache_rewrite'] ?><br>
    <code>a2enmod rewrite &amp;&amp; systemctl restart apache2</code>
  </div>

  <?php if ($selfDeleted): ?>
  <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:.82rem;color:var(--success);display:flex;align-items:center;gap:.6rem">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span><?= $lang === 'de' ? '<code>install.php</code> wurde automatisch gel&ouml;scht.' : '<code>install.php</code> was automatically deleted.' ?></span>
  </div>
  <?php else: ?>
  <div class="warning-box">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span><?= $t['delete_warning'] ?></span>
  </div>
  <?php endif; ?>

  <div class="actions">
    <a href="/" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      <?= $t['to_config'] ?>
    </a>
  </div>

  <?php else: ?>
  <div class="warning-box">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    <span><strong><?= $t['install_failed'] ?></strong></span>
  </div>

  <?php if ($installResult['files']): ?>
  <div class="section-title"><?= $t['downloaded_files'] ?></div>
  <div class="file-list">
    <?php foreach ($installResult['files'] as $file): ?>
    <div class="file-item"><span class="ok">&#10003;</span> <?= htmlspecialchars($file) ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="section-title"><?= $t['errors'] ?></div>
  <div class="file-list">
    <?php foreach ($installResult['errors'] as $error): ?>
    <div class="file-item"><span class="fail">&#10007;</span> <?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
  </div>

  <div class="actions">
    <a href="?step=check<?= $langParam ?>" class="btn btn-secondary"><?= $t['back_to_check'] ?></a>
    <a href="?step=install<?= $langParam ?>" class="btn btn-primary"><?= $t['retry'] ?></a>
  </div>
  <?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>
