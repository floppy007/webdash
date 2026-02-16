<?php
/**
 * webdash — Server Dashboard
 *
 * Copyright (c) Florian Hesse / Comnic-IT
 * Fischer Str. 1, 16515 Oranienburg
 * info@comnic-it.de
 *
 * Lizenz: Frei zur Verwendung, Bearbeitung und kommerziellen Nutzung.
 *
 * https://github.com/floppy007/webdash
 */
session_start();

define('WEBDASH_VERSION', '1.64');

// --- Sprache / Language ---
if (isset($_GET['lang']) && in_array($_GET['lang'], ['de', 'en'], true)) {
    setcookie('webdash_lang', $_GET['lang'], time() + 31536000, '/');
    header('Location: /');
    exit;
}
$lang = 'de';
if (isset($_COOKIE['webdash_lang']) && in_array($_COOKIE['webdash_lang'], ['de', 'en'], true)) {
    $lang = $_COOKIE['webdash_lang'];
} elseif (preg_match('/^en/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '')) {
    $lang = 'en';
}
$t = $lang === 'de' ? [
    'toggle_theme'=>'Hell/Dunkel umschalten','toggle_lang'=>'English','logout'=>'Abmelden',
    'server_home'=>'Server-Startseite',
    'legend_online'=>'Verf&uuml;gbar &mdash; Anwendung l&auml;uft',
    'legend_offline'=>'Offline &mdash; Nicht erreichbar',
    'legend_error'=>'Fehler &mdash; Anwendung antwortet mit Fehler',
    'no_apps'=>'Aktuell sind keine Anwendungen verf&uuml;gbar.',
    'apps_count'=>'%d von %d Anwendungen online',
    'open'=>'&Ouml;ffnen','admin'=>'Admin',
    'online'=>'Online','blocked'=>'Gesperrt','error'=>'Fehler','offline'=>'Offline','maintenance'=>'Wartung',
    'settings'=>'Einstellungen','logo_dark'=>'Logo (Dunkel)','logo_light'=>'Logo (Hell)',
    'change'=>'&Auml;ndern','upload_btn'=>'Hochladen','remove'=>'Entfernen',
    'scan_dir'=>'Scan-Verzeichnis','save'=>'Speichern','check_update'=>'Update pr&uuml;fen',
    'exclude_dirs'=>'Verzeichnisse ausschlie&szlig;en','exclude_dirs_hint'=>'Komma-getrennt, z.B. backup, test',
    'visible_dirs'=>'Sichtbare Verzeichnisse','visible_dirs_hint'=>'Angehakte Verzeichnisse werden im Dashboard angezeigt',
    'visible_dirs_save'=>'Auswahl speichern',
    'system'=>'System','php_ver'=>'PHP Version','cpu_cores'=>'CPU Kerne',
    'ram_total'=>'RAM Gesamt','storage_total'=>'Speicher Gesamt',
    'resources'=>'Auslastung','cpu_load'=>'CPU Last','memory'=>'Arbeitsspeicher','disk'=>'Festplatte',
    'cores'=>'Kerne','used'=>'belegt','free'=>'frei','services'=>'Dienste','projects'=>'Projekte',
    'admin_access'=>'Admin-Zugang','admin_desc'=>'Mit Intranet-Konto anmelden (nur Administratoren)',
    'username'=>'Benutzername','password'=>'Passwort','login'=>'Anmelden','cancel'=>'Abbrechen',
    'err_creds'=>'Benutzername oder Passwort falsch',
    'js_checking'=>'Pr\u00fcfe...','js_available'=>'verf\u00fcgbar!','js_update_now'=>'Jetzt aktualisieren',
    'js_current'=>'webdash ist aktuell','js_conn_err'=>'Verbindungsfehler',
    'js_installing'=>'Update wird installiert...','js_success'=>'erfolgreich! Aktualisiert:',
    'js_reload'=>'Seite neu laden','js_error'=>'Fehler:','js_update_err'=>'Verbindungsfehler beim Update',
    'setup'=>'Ersteinrichtung','setup_desc'=>'Lege ein Admin-Passwort fest, um webdash zu verwalten.',
    'new_password'=>'Neues Passwort','confirm_password'=>'Passwort best&auml;tigen',
    'setup_save'=>'Einrichtung abschlie&szlig;en',
    'pw_mismatch'=>'Passw&ouml;rter stimmen nicht &uuml;berein',
    'pw_short'=>'Passwort zu kurz (min. 4 Zeichen)',
    'admin_pw'=>'Admin-Passwort','admin_pw_change'=>'Passwort &auml;ndern',
    'tab_general'=>'Allgemein','tab_server'=>'Webseiten &amp; Dienste',
    'manual_links'=>'Manuelle Webseiten','manual_links_hint'=>'Eigene URLs hinzuf&uuml;gen...',
    'manual_link_name'=>'Name','manual_link_url'=>'URL','manual_link_desc'=>'Beschreibung (optional)',
    'manual_link_add'=>'Link hinzuf&uuml;gen','manual_link_delete_confirm'=>'Link wirklich l&ouml;schen?',
    'manual_link_added'=>'Link hinzugef&uuml;gt','manual_link_deleted'=>'Link gel&ouml;scht',
    'project_edit'=>'Projekt bearbeiten','project_edit_desc'=>'Beschreibung','project_edit_icon'=>'Icon',
    'docker_mode'=>'Docker-Modus','docker_containers'=>'Container','docker_no_socket'=>'Docker-Socket nicht verf&uuml;gbar',
    'docker_no_containers'=>'Keine Container gefunden','docker_container_id'=>'Container-ID',
    'visible_containers'=>'Sichtbare Container','visible_containers_hint'=>'Container im Dashboard ein-/ausblenden',
    'google_search'=>'Google-Suche',
    'bg_image'=>'Hintergrundbild','bg_blur'=>'Glaseffekt','bg_brightness'=>'Helligkeit',
    'show_stats'=>'System-Info','show_resources'=>'Auslastung','show_services'=>'Dienste',
    'smtp_config'=>'E-Mail-Versand (SMTP)',
    'smtp_host'=>'SMTP Host','smtp_port'=>'SMTP Port','smtp_encryption'=>'Verschl&uuml;sselung',
    'smtp_user'=>'SMTP Benutzer','smtp_pass'=>'SMTP Passwort',
    'smtp_from'=>'Absender E-Mail','smtp_from_name'=>'Absender Name',
    'smtp_save'=>'SMTP speichern','smtp_test'=>'Test-E-Mail senden',
    'smtp_test_ok'=>'Test-E-Mail gesendet','smtp_test_fail'=>'E-Mail-Versand fehlgeschlagen',
    'forgot_pw'=>'Passwort vergessen?','forgot_pw_desc'=>'E-Mail-Adresse eingeben',
    'forgot_pw_sent'=>'Falls die E-Mail hinterlegt ist, wurde ein Reset-Link gesendet',
    'reset_pw'=>'Passwort zur&uuml;cksetzen','reset_pw_expired'=>'Link abgelaufen oder ung&uuml;ltig',
    'reset_pw_success'=>'Passwort erfolgreich ge&auml;ndert',
    'email'=>'E-Mail',
    'users'=>'Benutzer','users_add'=>'Benutzer hinzuf&uuml;gen','users_name'=>'Name',
    'users_save'=>'Speichern','users_delete'=>'L&ouml;schen','users_delete_confirm'=>'Benutzer wirklich l\u00f6schen?',
    'users_added'=>'Benutzer angelegt','users_exists'=>'Benutzername existiert bereits',
    'users_none'=>'Noch keine Benutzer angelegt',
    'users_new_pw'=>'Neues Passwort (leer = nicht &auml;ndern)',
    'tab_email_users'=>'E-Mail &amp; Benutzer',
] : [
    'toggle_theme'=>'Toggle theme','toggle_lang'=>'Deutsch','logout'=>'Logout',
    'server_home'=>'Server Overview',
    'legend_online'=>'Available &mdash; Application is running',
    'legend_offline'=>'Offline &mdash; Not reachable',
    'legend_error'=>'Error &mdash; Application responds with error',
    'no_apps'=>'No applications available.',
    'apps_count'=>'%d of %d applications online',
    'open'=>'Open','admin'=>'Admin',
    'online'=>'Online','blocked'=>'Blocked','error'=>'Error','offline'=>'Offline','maintenance'=>'Maintenance',
    'settings'=>'Settings','logo_dark'=>'Logo (Dark)','logo_light'=>'Logo (Light)',
    'change'=>'Change','upload_btn'=>'Upload','remove'=>'Remove',
    'scan_dir'=>'Scan Directory','save'=>'Save','check_update'=>'Check for updates',
    'exclude_dirs'=>'Exclude Directories','exclude_dirs_hint'=>'Comma-separated, e.g. backup, test',
    'visible_dirs'=>'Visible Directories','visible_dirs_hint'=>'Checked directories are shown on the dashboard',
    'visible_dirs_save'=>'Save selection',
    'system'=>'System','php_ver'=>'PHP Version','cpu_cores'=>'CPU Cores',
    'ram_total'=>'RAM Total','storage_total'=>'Storage Total',
    'resources'=>'Resources','cpu_load'=>'CPU Load','memory'=>'Memory','disk'=>'Disk',
    'cores'=>'Cores','used'=>'used','free'=>'free','services'=>'Services','projects'=>'Projects',
    'admin_access'=>'Admin Access','admin_desc'=>'Sign in with Intranet account (admins only)',
    'username'=>'Username','password'=>'Password','login'=>'Sign in','cancel'=>'Cancel',
    'err_creds'=>'Invalid username or password',
    'js_checking'=>'Checking...','js_available'=>'available!','js_update_now'=>'Update now',
    'js_current'=>'webdash is up to date','js_conn_err'=>'Connection error',
    'js_installing'=>'Installing update...','js_success'=>'successful! Updated:',
    'js_reload'=>'Reload page','js_error'=>'Error:','js_update_err'=>'Connection error during update',
    'setup'=>'Initial Setup','setup_desc'=>'Set an admin password to manage webdash.',
    'new_password'=>'New password','confirm_password'=>'Confirm password',
    'setup_save'=>'Complete setup',
    'pw_mismatch'=>'Passwords do not match',
    'pw_short'=>'Password too short (min. 4 characters)',
    'admin_pw'=>'Admin Password','admin_pw_change'=>'Change password',
    'tab_general'=>'General','tab_server'=>'Websites &amp; Services',
    'manual_links'=>'Manual Websites','manual_links_hint'=>'Add custom URLs...',
    'manual_link_name'=>'Name','manual_link_url'=>'URL','manual_link_desc'=>'Description (optional)',
    'manual_link_add'=>'Add link','manual_link_delete_confirm'=>'Really delete this link?',
    'manual_link_added'=>'Link added','manual_link_deleted'=>'Link deleted',
    'project_edit'=>'Edit project','project_edit_desc'=>'Description','project_edit_icon'=>'Icon',
    'docker_mode'=>'Docker Mode','docker_containers'=>'Containers','docker_no_socket'=>'Docker socket not available',
    'docker_no_containers'=>'No containers found','docker_container_id'=>'Container ID',
    'visible_containers'=>'Visible Containers','visible_containers_hint'=>'Show/hide containers on the dashboard',
    'google_search'=>'Google Search',
    'bg_image'=>'Background Image','bg_blur'=>'Glass Effect','bg_brightness'=>'Brightness',
    'show_stats'=>'System Info','show_resources'=>'Resources','show_services'=>'Services',
    'smtp_config'=>'Email Sending (SMTP)',
    'smtp_host'=>'SMTP Host','smtp_port'=>'SMTP Port','smtp_encryption'=>'Encryption',
    'smtp_user'=>'SMTP User','smtp_pass'=>'SMTP Password',
    'smtp_from'=>'From Email','smtp_from_name'=>'From Name',
    'smtp_save'=>'Save SMTP','smtp_test'=>'Send test email',
    'smtp_test_ok'=>'Test email sent','smtp_test_fail'=>'Email sending failed',
    'forgot_pw'=>'Forgot password?','forgot_pw_desc'=>'Enter your email address',
    'forgot_pw_sent'=>'If the email is registered, a reset link has been sent',
    'reset_pw'=>'Reset password','reset_pw_expired'=>'Link expired or invalid',
    'reset_pw_success'=>'Password changed successfully',
    'email'=>'Email',
    'users'=>'Users','users_add'=>'Add user','users_name'=>'Name',
    'users_save'=>'Save','users_delete'=>'Delete','users_delete_confirm'=>'Really delete this user?',
    'users_added'=>'User created','users_exists'=>'Username already exists',
    'users_none'=>'No users yet',
    'users_new_pw'=>'New password (empty = no change)',
    'tab_email_users'=>'Email &amp; Users',
];

// --- Dashboard-Konfiguration ---
define('DASH_DIR',    __DIR__);
define('DASH_CONFIG', DASH_DIR . '/config.json');
define('DASH_LOGO_DARK',  DASH_DIR . '/logo-dark');
define('DASH_LOGO_LIGHT', DASH_DIR . '/logo-light');
define('DASH_BG_IMAGE',    DASH_DIR . '/bg-image');
define('DASH_WALLPAPERS',  DASH_DIR . '/wallpapers');
// Vorinstallierte Hintergrundbilder / Preset wallpapers (hell=Light, dunkel=Dark)
define('DASH_PRESET_WALLPAPERS', [
    'light' => ['file' => 'mountain-lake.jpg', 'credit' => 'Eberhard Grossgasteiger', 'source' => 'Pexels', 'url' => 'https://www.pexels.com/photo/629167/'],
    'dark'  => ['file' => 'aerial-forest.jpg', 'credit' => 'Sindre Str&oslash;m',     'source' => 'Pexels', 'url' => 'https://www.pexels.com/photo/1144176/'],
]);
define('DASH_APP_LOGO_DARK', DASH_DIR . '/app-logo-dark.png');
define('DASH_APP_LOGO_LIGHT', DASH_DIR . '/app-logo-light.png');
define('DASH_PROJECT_LOGOS', DASH_DIR . '/project-logos');
if (!is_dir(DASH_PROJECT_LOGOS)) @mkdir(DASH_PROJECT_LOGOS, 0755, true);

// --- Docker-Modus ---
$dockerMode = (getenv('WEBDASH_DOCKER_MODE') === 'true')
    || (file_exists('/.dockerenv') && file_exists('/var/run/docker.sock'));
$dockerSocket = '/var/run/docker.sock';
$dockerHostIp = getenv('WEBDASH_HOST_IP') ?: ($_SERVER['HTTP_HOST'] ?? 'localhost');

function dockerApiGet(string $endpoint): ?array {
    global $dockerSocket;
    if (!file_exists($dockerSocket)) return null;
    $ch = curl_init("http://localhost$endpoint");
    curl_setopt_array($ch, [
        CURLOPT_UNIX_SOCKET_PATH => $dockerSocket,
        CURLOPT_RETURNTRANSFER   => true,
        CURLOPT_TIMEOUT          => 5,
        CURLOPT_CONNECTTIMEOUT   => 3,
    ]);
    $response = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code !== 200 || !$response) return null;
    return json_decode($response, true);
}

function discoverDockerContainers(): array {
    global $dockerHostIp, $dockerMode;
    if (!$dockerMode) return [];
    $containers = dockerApiGet('/containers/json?all=true');
    if (!$containers) return [];
    $projects = [];
    foreach ($containers as $c) {
        $labels = $c['Labels'] ?? [];
        // Skip containers with webdash.hidden label
        if (!empty($labels['webdash.hidden'])) continue;
        // Container name (strip leading /)
        $rawName = ltrim(($c['Names'][0] ?? ''), '/');
        $name = $labels['webdash.name'] ?? $rawName;
        $image = $c['Image'] ?? 'unknown';
        $state = strtolower($c['State'] ?? 'unknown');
        $statusText = $c['Status'] ?? '';
        $containerId = substr($c['Id'] ?? '', 0, 12);
        // Build URL from first published port
        $url = '';
        $ports = $c['Ports'] ?? [];
        foreach ($ports as $p) {
            if (!empty($p['PublicPort'])) {
                $proto = ($p['PublicPort'] == 443 || ($p['PrivatePort'] ?? 0) == 443) ? 'https' : 'http';
                $host = $dockerHostIp;
                // Strip port from host if present
                if (str_contains($host, ':')) $host = explode(':', $host)[0];
                $url = "$proto://$host:" . $p['PublicPort'] . '/';
                break;
            }
        }
        // Override URL from label
        if (!empty($labels['webdash.url'])) {
            $url = str_replace('{HOST_IP}', $dockerHostIp, $labels['webdash.url']);
        }
        // Status mapping
        $status = match($state) {
            'running'  => 'online',
            'paused'   => 'gesperrt',
            'exited', 'dead', 'removing' => 'offline',
            default    => 'offline',
        };
        $projects[] = [
            'name'         => $rawName,
            'display_name' => $name !== $rawName ? $name : '',
            'url'          => $url,
            'lastModified' => time(),
            'type'         => $image,
            'description'  => $labels['webdash.description'] ?? '',
            'icon'         => $labels['webdash.icon'] ?? '',
            'status'       => $status,
            'statusCode'   => $status === 'online' ? 200 : 0,
            'docker'       => true,
            'container_id' => $containerId,
            'docker_status'=> $statusText,
        ];
    }
    return $projects;
}

function dashConfig(): array {
    return file_exists(DASH_CONFIG) ? (json_decode(file_get_contents(DASH_CONFIG), true) ?: []) : [];
}
function saveDashConfig(array $cfg): void {
    file_put_contents(DASH_CONFIG, json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


// --- Startup-Log ---
$_startupLog = [];

// --- Migration: altes logo_ext → logo_dark_ext ---
(function() {
    global $_startupLog;
    $cfg = dashConfig();
    if (isset($cfg['logo_ext']) && !isset($cfg['logo_dark_ext'])) {
        $ext = $cfg['logo_ext'];
        $oldFile = DASH_DIR . '/logo.' . $ext;
        $newFile = DASH_LOGO_DARK . '.' . $ext;
        if (file_exists($oldFile)) @rename($oldFile, $newFile);
        $cfg['logo_dark_ext'] = $ext;
        unset($cfg['logo_ext']);
        saveDashConfig($cfg);
        $_startupLog[] = ['ok', 'Migration: logo_ext → logo_dark_ext'];
    }
})();

// --- Migration: exclude_dirs → include_dirs ---
(function() {
    global $_startupLog;
    $cfg = dashConfig();
    if (isset($cfg['exclude_dirs']) && !isset($cfg['include_dirs'])) {
        $scanDir = getenv('WEBDASH_SCAN_DIR') ?: ($cfg['scan_dir'] ?? dirname(__DIR__));
        $excl = $cfg['exclude_dirs'];
        $incl = [];
        if (is_dir($scanDir)) {
            foreach (scandir($scanDir) as $d) {
                if ($d[0] === '.' || !is_dir("$scanDir/$d")) continue;
                if (!in_array($d, $excl, true)) $incl[] = $d;
            }
        }
        $cfg['include_dirs'] = $incl;
        unset($cfg['exclude_dirs']);
        saveDashConfig($cfg);
        $_startupLog[] = ['ok', 'Migration: exclude_dirs → include_dirs (' . count($incl) . ' dirs)'];
    }
})();

// --- Auto-Repair: fehlende App-Logos/Favicons von GitHub nachladen ---
(function() {
    global $_startupLog;
    $assets = [
        '.dashboard/app-logo-dark.png'  => DASH_DIR . '/app-logo-dark.png',
        '.dashboard/app-logo-light.png' => DASH_DIR . '/app-logo-light.png',
        '.dashboard/favicon-dark.png'   => DASH_DIR . '/favicon-dark.png',
        '.dashboard/favicon-light.png'  => DASH_DIR . '/favicon-light.png',
    ];
    $missing = [];
    foreach ($assets as $repo => $local) {
        if (!file_exists($local)) $missing[$repo] = $local;
    }
    if (!empty($missing)) {
        $tag = 'v' . WEBDASH_VERSION;
        foreach ($missing as $repo => $local) {
            $url = "https://raw.githubusercontent.com/floppy007/webdash/$tag/$repo";
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => ['User-Agent: webdash/' . WEBDASH_VERSION],
            ]);
            $data = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($code === 200 && $data) {
                file_put_contents($local, $data);
                $_startupLog[] = ['ok', "Auto-Repair: " . basename($repo) . " nachgeladen"];
            } else {
                $_startupLog[] = ['warn', "Auto-Repair: " . basename($repo) . " konnte nicht geladen werden (HTTP $code)"];
            }
        }
    }
})();

// --- Projekt-Logo ausliefern (/?asset=project-logo&name=<name>&variant=dark|light) ---
if (isset($_GET['asset']) && $_GET['asset'] === 'project-logo' && isset($_GET['name'])) {
    $plName = basename($_GET['name']);
    $plVariant = ($_GET['variant'] ?? 'dark') === 'light' ? 'light' : 'dark';
    $cfg = dashConfig();
    $plExt = $cfg["project_logo_{$plVariant}_ext"][$plName] ?? '';
    $plFile = DASH_PROJECT_LOGOS . '/' . $plName . '-' . $plVariant . '.' . $plExt;
    // Fallback: wenn gewünschte Variante fehlt, andere nehmen
    if (!$plExt || !file_exists($plFile)) {
        $plFb = $plVariant === 'dark' ? 'light' : 'dark';
        $plExt = $cfg["project_logo_{$plFb}_ext"][$plName] ?? '';
        $plFile = DASH_PROJECT_LOGOS . '/' . $plName . '-' . $plFb . '.' . $plExt;
    }
    if ($plExt && file_exists($plFile)) {
        $mime = match($plExt) { 'svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','webp'=>'image/webp','gif'=>'image/gif', default=>'application/octet-stream' };
        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=3600');
        readfile($plFile);
    } else {
        http_response_code(404);
    }
    exit;
}

// --- Logo ausliefern (/?asset=logo-dark / /?asset=logo-light / /?asset=app-logo / /?asset=app-logo-wide) ---
if (isset($_GET['asset']) && in_array($_GET['asset'], ['logo-dark', 'logo-light', 'favicon-dark', 'favicon-light', 'app-logo-dark', 'app-logo-light', 'bg-image', 'wallpaper'], true)) {
    $assetName = $_GET['asset'];
    if ($assetName === 'favicon-dark' || $assetName === 'favicon-light') {
        $file = DASH_DIR . '/' . $assetName . '.png';
        if (file_exists($file)) {
            header('Content-Type: image/png');
            header('Cache-Control: public, max-age=86400');
            readfile($file);
        } else {
            http_response_code(404);
        }
    } elseif ($assetName === 'app-logo-dark' || $assetName === 'app-logo-light') {
        $file = $assetName === 'app-logo-light' ? DASH_APP_LOGO_LIGHT : DASH_APP_LOGO_DARK;
        if (file_exists($file)) {
            header('Content-Type: image/png');
            header('Cache-Control: public, max-age=86400');
            readfile($file);
        } else {
            http_response_code(404);
        }
    } elseif ($assetName === 'wallpaper') {
        $theme = $_GET['theme'] ?? 'dark';
        if (isset(DASH_PRESET_WALLPAPERS[$theme])) {
            $file = DASH_WALLPAPERS . '/' . DASH_PRESET_WALLPAPERS[$theme]['file'];
            if (file_exists($file)) {
                header('Content-Type: image/jpeg');
                header('Cache-Control: public, max-age=604800');
                readfile($file);
            } else {
                http_response_code(404);
            }
        } else {
            http_response_code(404);
        }
    } elseif ($assetName === 'bg-image') {
        $cfg = dashConfig();
        $ext = $cfg['bg_image_ext'] ?? '';
        $file = DASH_BG_IMAGE . '.' . $ext;
        if ($ext && file_exists($file)) {
            $mime = match($ext) { 'svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','webp'=>'image/webp','gif'=>'image/gif', default=>'application/octet-stream' };
            header('Content-Type: ' . $mime);
            header('Cache-Control: public, max-age=86400');
            readfile($file);
        } else {
            http_response_code(404);
        }
    } else {
        $variant = $assetName === 'logo-light' ? 'light' : 'dark';
        $cfg = dashConfig();
        $ext = $cfg["logo_{$variant}_ext"] ?? '';
        $base = $variant === 'light' ? DASH_LOGO_LIGHT : DASH_LOGO_DARK;
        $file = $base . '.' . $ext;
        if ($ext && file_exists($file)) {
            $mime = match($ext) { 'svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','webp'=>'image/webp','gif'=>'image/gif', default=>'application/octet-stream' };
            header('Content-Type: ' . $mime);
            header('Cache-Control: public, max-age=3600');
            readfile($file);
        } else {
            http_response_code(404);
        }
    }
    exit;
}

// --- Flaggen-SVGs ---
function flagDE(int $size = 20): string {
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 3" width="'.$size.'" height="'.round($size*0.6).'" style="border-radius:3px;vertical-align:middle;display:inline-block"><rect width="5" height="1" fill="#000"/><rect y="1" width="5" height="1" fill="#D00"/><rect y="2" width="5" height="1" fill="#FFCE00"/></svg>';
}
function flagUS(int $size = 20): string {
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 30" width="'.$size.'" height="'.round($size*0.6).'" style="border-radius:3px;vertical-align:middle;display:inline-block"><rect width="50" height="30" fill="#B22234"/><g fill="#fff"><rect y="2.31" width="50" height="2.31"/><rect y="6.92" width="50" height="2.31"/><rect y="11.54" width="50" height="2.31"/><rect y="16.15" width="50" height="2.31"/><rect y="20.77" width="50" height="2.31"/><rect y="25.38" width="50" height="2.31"/></g><rect width="20" height="16.15" fill="#3C3B6E"/><g fill="#fff" font-size="2" font-family="serif"><text x="2" y="3.5">&#9733;</text><text x="6" y="3.5">&#9733;</text><text x="10" y="3.5">&#9733;</text><text x="14" y="3.5">&#9733;</text><text x="4" y="6.5">&#9733;</text><text x="8" y="6.5">&#9733;</text><text x="12" y="6.5">&#9733;</text><text x="2" y="9.5">&#9733;</text><text x="6" y="9.5">&#9733;</text><text x="10" y="9.5">&#9733;</text><text x="14" y="9.5">&#9733;</text><text x="4" y="12.5">&#9733;</text><text x="8" y="12.5">&#9733;</text><text x="12" y="12.5">&#9733;</text><text x="2" y="15.5">&#9733;</text><text x="6" y="15.5">&#9733;</text><text x="10" y="15.5">&#9733;</text><text x="14" y="15.5">&#9733;</text></g></svg>';
}

// --- SMTP-Mail senden / Send SMTP mail ---
function dashboardSendMail(string $to, string $subject, string $bodyHtml): bool {
    $cfg = dashConfig();
    $host = $cfg['smtp_host'] ?? '';
    $port = (int)($cfg['smtp_port'] ?? 587);
    $encryption = $cfg['smtp_encryption'] ?? 'tls';
    $user = $cfg['smtp_user'] ?? '';
    $pass = $cfg['smtp_pass'] ?? '';
    $from = $cfg['smtp_from'] ?? $user;
    $fromName = $cfg['smtp_from_name'] ?? 'webdash';
    if (!$host || !$user) return false;
    $prefix = $encryption === 'ssl' ? 'ssl://' : '';
    $fp = @fsockopen($prefix . $host, $port, $errno, $errstr, 10);
    if (!$fp) return false;
    stream_set_timeout($fp, 10);
    $getResp = function() use ($fp) {
        $resp = '';
        while ($line = fgets($fp, 512)) {
            $resp .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $resp;
    };
    $send = function(string $cmd) use ($fp, $getResp) {
        fwrite($fp, $cmd . "\r\n");
        return $getResp();
    };
    $getResp();
    $send('EHLO ' . (gethostname() ?: 'localhost'));
    if ($encryption === 'tls') {
        $send('STARTTLS');
        if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
            fclose($fp);
            return false;
        }
        $send('EHLO ' . (gethostname() ?: 'localhost'));
    }
    if ($user && $pass) {
        $send('AUTH LOGIN');
        $send(base64_encode($user));
        $resp = $send(base64_encode($pass));
        if (!str_starts_with(trim($resp), '235')) { fclose($fp); return false; }
    }
    $send('MAIL FROM:<' . $from . '>');
    $resp = $send('RCPT TO:<' . $to . '>');
    if (!str_starts_with(trim($resp), '250')) { $send('QUIT'); fclose($fp); return false; }
    $send('DATA');
    $headers = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$from}>\r\n"
        . "To: <{$to}>\r\n"
        . "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: text/html; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Date: " . date('r') . "\r\n"
        . "Message-ID: <" . uniqid('wd') . "@" . (gethostname() ?: 'localhost') . ">\r\n";
    fwrite($fp, $headers . "\r\n" . chunk_split(base64_encode($bodyHtml)) . "\r\n.\r\n");
    $resp = $getResp();
    $send('QUIT');
    fclose($fp);
    return str_starts_with(trim($resp), '250');
}

// --- Setup (POST) — Ersteinrichtung Admin-Passwort ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup_password'], $_POST['setup_confirm'])) {
    $pw  = $_POST['setup_password'];
    $pw2 = $_POST['setup_confirm'];
    $setupInputData = [
        'scan_dir' => $_POST['setup_scan_dir'] ?? '',
    ];
    if (strlen($pw) < 4) {
        $_SESSION['dashboard_setup_error'] = $t['pw_short'];
        $_SESSION['dashboard_setup_input'] = $setupInputData;
    } elseif ($pw !== $pw2) {
        $_SESSION['dashboard_setup_error'] = $t['pw_mismatch'];
        $_SESSION['dashboard_setup_input'] = $setupInputData;
    } else {
        $cfg = dashConfig();
        $pwHash = password_hash($pw, PASSWORD_DEFAULT);
        $cfg['admin_pass'] = $pwHash;
        // Scan-Verzeichnis aus Setup speichern (nicht im Docker-Modus)
        if (!$dockerMode && !empty($_POST['setup_scan_dir'])) {
            $dir = rtrim(trim($_POST['setup_scan_dir']), '/\\');
            if ($dir && is_dir($dir)) $cfg['scan_dir'] = $dir;
        }
        saveDashConfig($cfg);
        $_SESSION['dashboard_user'] = ['id'=>0,'username'=>'admin','name'=>'Admin','role'=>'admin'];
    }
    header('Location: /');
    exit;
}

// --- Login (POST) ---
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $authenticated = false;

    // Lokales Admin-Passwort (config.json)
    $cfg = dashConfig();
    if (!empty($cfg['admin_pass']) && password_verify($_POST['password'], $cfg['admin_pass'])) {
        $_SESSION['dashboard_user'] = ['id'=>0,'username'=>'admin','name'=>'Admin','role'=>'admin'];
        $authenticated = true;
    }

    // Pr&uuml;fe Benutzer-Liste / Check users list
    if (!$authenticated) {
        $users = $cfg['users'] ?? [];
        foreach ($users as $u) {
            if ($u['username'] === $_POST['username'] && password_verify($_POST['password'], $u['password'])) {
                $_SESSION['dashboard_user'] = ['id'=>0,'username'=>$u['username'],'name'=>$u['name'] ?? $u['username'],'role'=>'admin'];
                $authenticated = true;
                break;
            }
        }
    }

    if (!$authenticated) {
        $_SESSION['dashboard_login_error'] = $t['err_creds'];
    }
    header('Location: /');
    exit;
}

// --- Logo-Upload (nur eingeloggte Admins) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['dashboard_user'])) {
    $allowed = ['image/png'=>'png','image/jpeg'=>'jpg','image/svg+xml'=>'svg','image/webp'=>'webp','image/gif'=>'gif'];
    $uploaded = false;
    foreach (['logo_dark' => DASH_LOGO_DARK, 'logo_light' => DASH_LOGO_LIGHT, 'bg_image' => DASH_BG_IMAGE] as $field => $basePath) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$field];
            $maxSize = $field === 'bg_image' ? 5 * 1024 * 1024 : 2 * 1024 * 1024;
            if (isset($allowed[$file['type']]) && $file['size'] <= $maxSize) {
                $ext = $allowed[$file['type']];
                foreach (glob($basePath . '.*') as $old) @unlink($old);
                move_uploaded_file($file['tmp_name'], $basePath . '.' . $ext);
                $cfg = dashConfig();
                $cfg["{$field}_ext"] = $ext;
                if ($field === 'bg_image') $cfg['bg_mode'] = 'custom';
                saveDashConfig($cfg);
                $uploaded = true;
            }
        }
    }
    if ($uploaded) { header('Location: /'); exit; }
}



// --- Admin-Profil ändern / Change admin profile ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_admin_profile']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['admin_email'] = trim($_POST['admin_email'] ?? '');
    $pw  = $_POST['new_admin_pw'] ?? '';
    $pw2 = $_POST['confirm_admin_pw'] ?? '';
    if ($pw !== '' || $pw2 !== '') {
        if (strlen($pw) < 4) {
            $_SESSION['dashboard_pw_msg'] = ['fail', $t['pw_short']];
            saveDashConfig($cfg);
            header('Location: /');
            exit;
        } elseif ($pw !== $pw2) {
            $_SESSION['dashboard_pw_msg'] = ['fail', $t['pw_mismatch']];
            saveDashConfig($cfg);
            header('Location: /');
            exit;
        }
        $cfg['admin_pass'] = password_hash($pw, PASSWORD_DEFAULT);
    }
    saveDashConfig($cfg);
    $_SESSION['dashboard_pw_msg'] = ['ok', $lang === 'de' ? 'Gespeichert' : 'Saved'];
    header('Location: /');
    exit;
}

// --- SMTP-Konfiguration speichern / Save SMTP config ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cfg_smtp_host']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['smtp_host']       = trim($_POST['cfg_smtp_host'] ?? '');
    $cfg['smtp_port']       = (int)($_POST['cfg_smtp_port'] ?? 587);
    $cfg['smtp_encryption'] = in_array($_POST['cfg_smtp_encryption'] ?? '', ['tls','ssl','none'], true) ? $_POST['cfg_smtp_encryption'] : 'tls';
    $cfg['smtp_user']       = trim($_POST['cfg_smtp_user'] ?? '');
    if (($_POST['cfg_smtp_pass'] ?? '') !== '') $cfg['smtp_pass'] = $_POST['cfg_smtp_pass'];
    $cfg['smtp_from']       = trim($_POST['cfg_smtp_from'] ?? '');
    $cfg['smtp_from_name']  = trim($_POST['cfg_smtp_from_name'] ?? '');
    saveDashConfig($cfg);
    $_SESSION['dashboard_smtp_msg'] = ['ok', $t['smtp_save']];
    header('Location: /');
    exit;
}

// --- SMTP Test-E-Mail senden / Send SMTP test email ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smtp_test_send']) && !empty($_SESSION['dashboard_user'])) {
    $testTo = dashConfig()['smtp_from'] ?? dashConfig()['smtp_user'] ?? '';
    if ($testTo) {
        $hostname = gethostname() ?: 'webdash';
        $ok = dashboardSendMail($testTo, 'webdash Test-Mail', '<h2>webdash</h2><p>Dies ist eine Test-E-Mail von <b>' . htmlspecialchars($hostname) . '</b>.</p><p>This is a test email from <b>' . htmlspecialchars($hostname) . '</b>.</p>');
        $_SESSION['dashboard_smtp_msg'] = $ok ? ['ok', $t['smtp_test_ok']] : ['fail', $t['smtp_test_fail']];
    } else {
        $_SESSION['dashboard_smtp_msg'] = ['fail', $t['smtp_test_fail']];
    }
    header('Location: /');
    exit;
}

// --- Benutzer anlegen / Add user ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user_username'], $_POST['add_user_password']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $users = $cfg['users'] ?? [];
    $uUser = trim($_POST['add_user_username']);
    $uName = trim($_POST['add_user_name'] ?? '') ?: $uUser;
    $uEmail = trim($_POST['add_user_email'] ?? '');
    $uPass = $_POST['add_user_password'];
    $exists = false;
    foreach ($users as $u) { if ($u['username'] === $uUser) { $exists = true; break; } }
    if ($exists) {
        $_SESSION['dashboard_user_msg'] = ['fail', $t['users_exists']];
    } elseif (strlen($uPass) < 4) {
        $_SESSION['dashboard_user_msg'] = ['fail', $t['pw_short']];
    } else {
        $users[] = ['username' => $uUser, 'password' => password_hash($uPass, PASSWORD_DEFAULT), 'name' => $uName, 'email' => $uEmail];
        $cfg['users'] = $users;
        saveDashConfig($cfg);
        $_SESSION['dashboard_user_msg'] = ['ok', $t['users_added']];
    }
    header('Location: /');
    exit;
}

// --- Benutzer bearbeiten / Edit user ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user_idx']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $users = $cfg['users'] ?? [];
    $idx = (int)$_POST['edit_user_idx'];
    if (isset($users[$idx])) {
        $uName  = trim($_POST['edit_user_name'] ?? '');
        $uEmail = trim($_POST['edit_user_email'] ?? '');
        $uPass  = $_POST['edit_user_password'] ?? '';
        if ($uName) $users[$idx]['name'] = $uName;
        $users[$idx]['email'] = $uEmail;
        if (strlen($uPass) >= 4) $users[$idx]['password'] = password_hash($uPass, PASSWORD_DEFAULT);
        $cfg['users'] = $users;
        saveDashConfig($cfg);
        $_SESSION['dashboard_user_msg'] = ['ok', $t['users_save']];
    }
    header('Location: /');
    exit;
}

// --- Benutzer loeschen / Delete user ---
if (isset($_GET['delete_user']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $users = $cfg['users'] ?? [];
    $idx = (int)$_GET['delete_user'];
    if (isset($users[$idx])) {
        array_splice($users, $idx, 1);
        $cfg['users'] = $users;
        saveDashConfig($cfg);
    }
    header('Location: /');
    exit;
}

// --- Einstellungen speichern (Scan-Verzeichnis) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scan_dir']) && !isset($_POST['save_include_dirs']) && !empty($_SESSION['dashboard_user'])) {
    $dir = rtrim(trim($_POST['scan_dir']), '/');
    if ($dir && is_dir($dir)) {
        $cfg = dashConfig();
        $cfg['scan_dir'] = $dir;
        saveDashConfig($cfg);
    }
    header('Location: /');
    exit;
}

// --- Sichtbare Verzeichnisse speichern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_include_dirs']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['include_dirs'] = isset($_POST['include_dirs']) && is_array($_POST['include_dirs']) ? array_values($_POST['include_dirs']) : [];
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Google-Suche ein-/ausschalten / Toggle Google Search ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_google_search']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['google_search'] = !empty($_POST['google_search_enabled']);
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Hintergrund-Modus umschalten / Toggle background mode ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bg_mode']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $mode = $_POST['bg_mode'] ?? '';
    $cfg['bg_mode'] = in_array($mode, ['preset', 'custom', ''], true) ? $mode : '';
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Hintergrundbild-Effekte speichern / Save background image effects ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bg_effects']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['bg_blur'] = max(0, min(100, (int)($_POST['bg_blur'] ?? 10)));
    $cfg['bg_brightness'] = max(0, min(100, (int)($_POST['bg_brightness'] ?? 55)));
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Dashboard-Sektionen ein-/ausschalten / Toggle dashboard sections ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_dashboard_sections']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['show_stats'] = !empty($_POST['show_stats']);
    $cfg['show_resources'] = !empty($_POST['show_resources']);
    $cfg['show_services'] = !empty($_POST['show_services']);
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Sichtbare Container speichern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_include_containers']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['include_containers'] = isset($_POST['include_containers']) && is_array($_POST['include_containers']) ? array_values($_POST['include_containers']) : [];
    saveDashConfig($cfg);
    header('Location: /');
    exit;
}

// --- Projekt-Details speichern (Beschreibung + Icon + Logo) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_project_desc']) && !empty($_SESSION['dashboard_user'])) {
    $projName = trim($_POST['project_name'] ?? '');
    $projDesc  = trim($_POST['project_desc'] ?? '');
    $projIcon  = trim($_POST['project_icon'] ?? '');
    $projTitle = trim($_POST['project_title'] ?? '');
    if ($projName !== '') {
        $cfg = dashConfig();
        if (!isset($cfg['project_descriptions'])) $cfg['project_descriptions'] = [];
        if (!isset($cfg['project_icons'])) $cfg['project_icons'] = [];
        if (!isset($cfg['project_titles'])) $cfg['project_titles'] = [];
        if ($projDesc !== '') {
            $cfg['project_descriptions'][$projName] = $projDesc;
        } else {
            unset($cfg['project_descriptions'][$projName]);
        }
        if ($projIcon !== '') {
            $cfg['project_icons'][$projName] = $projIcon;
        } else {
            unset($cfg['project_icons'][$projName]);
        }
        if ($projTitle !== '' && $projTitle !== $projName) {
            $cfg['project_titles'][$projName] = $projTitle;
        } else {
            unset($cfg['project_titles'][$projName]);
        }
        // Wartungsmodus / Maintenance mode
        if (!isset($cfg['project_maintenance'])) $cfg['project_maintenance'] = [];
        if (!empty($_POST['project_maintenance'])) {
            $cfg['project_maintenance'][$projName] = true;
        } else {
            unset($cfg['project_maintenance'][$projName]);
        }
        // Projekt-Logo Upload (Dark + Light)
        $logoAllowed = ['image/png'=>'png','image/jpeg'=>'jpg','image/svg+xml'=>'svg','image/webp'=>'webp','image/gif'=>'gif'];
        $safeName = basename($projName);
        foreach (['dark','light'] as $_variant) {
            $field = "project_logo_{$_variant}";
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $pf = $_FILES[$field];
                if (isset($logoAllowed[$pf['type']]) && $pf['size'] <= 2 * 1024 * 1024) {
                    $pExt = $logoAllowed[$pf['type']];
                    foreach (glob(DASH_PROJECT_LOGOS . '/' . $safeName . '-' . $_variant . '.*') as $old) @unlink($old);
                    move_uploaded_file($pf['tmp_name'], DASH_PROJECT_LOGOS . '/' . $safeName . '-' . $_variant . '.' . $pExt);
                    $cfgKey = "project_logo_{$_variant}_ext";
                    if (!isset($cfg[$cfgKey])) $cfg[$cfgKey] = [];
                    $cfg[$cfgKey][$projName] = $pExt;
                }
            }
        }
        saveDashConfig($cfg);
    }
    header('Location: /');
    exit;
}

// --- Manuellen Link hinzufügen ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manual_link']) && !empty($_SESSION['dashboard_user'])) {
    $linkName = trim($_POST['manual_link_name'] ?? '');
    $linkUrl  = trim($_POST['manual_link_url'] ?? '');
    $linkDesc = trim($_POST['manual_link_desc'] ?? '');
    if ($linkName !== '' && filter_var($linkUrl, FILTER_VALIDATE_URL)) {
        $cfg = dashConfig();
        if (!isset($cfg['manual_links'])) $cfg['manual_links'] = [];
        $cfg['manual_links'][] = ['name' => $linkName, 'url' => $linkUrl, 'description' => $linkDesc];
        saveDashConfig($cfg);
        $_SESSION['dashboard_manual_msg'] = ['ok', $t['manual_link_added']];
    }
    header('Location: /');
    exit;
}

// --- Manuellen Link löschen ---
if (isset($_GET['delete_manual_link']) && !empty($_SESSION['dashboard_user'])) {
    $idx = (int) $_GET['delete_manual_link'];
    $cfg = dashConfig();
    if (isset($cfg['manual_links'][$idx])) {
        array_splice($cfg['manual_links'], $idx, 1);
        saveDashConfig($cfg);
        $_SESSION['dashboard_manual_msg'] = ['ok', $t['manual_link_deleted']];
    }
    header('Location: /');
    exit;
}

// --- Projekt-Logo entfernen ---
if (isset($_GET['remove_project_logo']) && !empty($_SESSION['dashboard_user'])) {
    $rpName = basename($_GET['remove_project_logo']);
    $rpVariant = $_GET['variant'] ?? 'both';
    $cfg = dashConfig();
    $variants = $rpVariant === 'both' ? ['dark','light'] : [$rpVariant];
    foreach ($variants as $v) {
        foreach (glob(DASH_PROJECT_LOGOS . '/' . $rpName . '-' . $v . '.*') as $old) @unlink($old);
        unset($cfg["project_logo_{$v}_ext"][$rpName]);
        if (empty($cfg["project_logo_{$v}_ext"])) unset($cfg["project_logo_{$v}_ext"]);
    }
    saveDashConfig($cfg);
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
    } else {
        header('Location: /');
    }
    exit;
}

// --- Logo entfernen ---
if (isset($_GET['remove_logo']) && !empty($_SESSION['dashboard_user'])) {
    $variant = $_GET['remove_logo'];
    if (in_array($variant, ['dark', 'light', 'bg_image'], true)) {
        if ($variant === 'bg_image') {
            $base = DASH_BG_IMAGE;
            foreach (glob($base . '.*') as $old) @unlink($old);
            $cfg = dashConfig();
            unset($cfg['bg_image_ext'], $cfg['bg_blur'], $cfg['bg_brightness'], $cfg['bg_overlay']);
            saveDashConfig($cfg);
        } else {
            $base = $variant === 'light' ? DASH_LOGO_LIGHT : DASH_LOGO_DARK;
            foreach (glob($base . '.*') as $old) @unlink($old);
            $cfg = dashConfig();
            unset($cfg["logo_{$variant}_ext"]);
            saveDashConfig($cfg);
        }
    }
    header('Location: /');
    exit;
}

// --- Abmelden ---
if (isset($_GET['logout'])) {
    unset($_SESSION['dashboard_user']);
    header('Location: /');
    exit;
}

$isAdmin    = !empty($_SESSION['dashboard_user']);
$adminUser  = $_SESSION['dashboard_user'] ?? null;
$loginError = $_SESSION['dashboard_login_error'] ?? '';
unset($_SESSION['dashboard_login_error']);
$setupError = $_SESSION['dashboard_setup_error'] ?? '';
unset($_SESSION['dashboard_setup_error']);
$setupInput = $_SESSION['dashboard_setup_input'] ?? null;
unset($_SESSION['dashboard_setup_input']);

// --- Auto-Setup im Docker-Modus: Admin-Passwort aus Env-Vars anlegen ---
if ($dockerMode) {
    $envAdminPass = getenv('WEBDASH_ADMIN_PASS') ?: '';
    if ($envAdminPass) {
        $cfg = dashConfig();
        if (empty($cfg['admin_pass'])) {
            $cfg['admin_pass'] = password_hash($envAdminPass, PASSWORD_DEFAULT);
            saveDashConfig($cfg);
        }
    }
}

// --- Setup-Modus: kein Admin-Passwort → Ersteinrichtung ---
$_dashCfgCheck = dashConfig();
$needsSetup = empty($_dashCfgCheck['admin_pass']) && !$isAdmin;

// --- System-Stats API (AJAX, nur Admin) ---
if (isset($_GET['action']) && $_GET['action'] === 'system_stats' && $isAdmin) {
    header('Content-Type: application/json; charset=utf-8');
    $r = getSystemResources();
    $r['load_fmt'] = array_map(fn($l) => number_format($l, 2), $r['load']);
    $r['ramUsed_fmt']   = fmtBytes($r['ramUsed']);
    $r['ramTotal_fmt']  = fmtBytes($r['ramTotal']);
    $r['diskUsed_fmt']  = fmtBytes($r['diskUsed']);
    $r['diskTotal_fmt'] = fmtBytes($r['diskTotal']);
    $r['diskFree_fmt']  = fmtBytes($r['diskFree']);
    echo json_encode($r);
    exit;
}

// --- Update API (AJAX, nur Admin) ---
if (isset($_GET['action']) && $isAdmin && in_array($_GET['action'], ['check_update', 'do_update'], true)) {
    header('Content-Type: application/json; charset=utf-8');

    if ($_GET['action'] === 'check_update') {
        $ch = curl_init('https://api.github.com/repos/floppy007/webdash/releases/latest');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['User-Agent: webdash/' . WEBDASH_VERSION, 'Accept: application/vnd.github.v3+json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            echo json_encode(['error' => 'GitHub API nicht erreichbar (HTTP ' . $httpCode . ')']);
            exit;
        }

        $release = json_decode($response, true);
        $latestVersion = ltrim($release['tag_name'] ?? '', 'v');

        echo json_encode([
            'current'          => WEBDASH_VERSION,
            'latest'           => $latestVersion,
            'update_available' => version_compare($latestVersion, WEBDASH_VERSION, '>'),
            'release_url'      => $release['html_url'] ?? '',
            'release_notes'    => $release['body'] ?? '',
        ]);
        exit;
    }

    if ($_GET['action'] === 'do_update') {
        $ch = curl_init('https://api.github.com/repos/floppy007/webdash/releases/latest');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['User-Agent: webdash/' . WEBDASH_VERSION, 'Accept: application/vnd.github.v3+json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            echo json_encode(['error' => 'GitHub API nicht erreichbar']);
            exit;
        }

        $release = json_decode($response, true);
        $tag = $release['tag_name'] ?? '';
        if (!$tag) {
            echo json_encode(['error' => 'Kein Release gefunden']);
            exit;
        }

        $errors  = [];
        $updated = [];
        $rootDir = dirname(__DIR__);
        $files   = [
            '.dashboard/app.php'            => __DIR__ . '/app.php',
            'index.php'                     => $rootDir . '/index.php',
            '.htaccess'                     => $rootDir . '/.htaccess',
            '.dashboard/app-logo-dark.png'  => __DIR__ . '/app-logo-dark.png',
            '.dashboard/app-logo-light.png' => __DIR__ . '/app-logo-light.png',
            '.dashboard/favicon-dark.png'   => __DIR__ . '/favicon-dark.png',
            '.dashboard/favicon-light.png'  => __DIR__ . '/favicon-light.png',
        ];
        $optionalFiles = ['.htaccess', '.dashboard/app-logo-dark.png', '.dashboard/app-logo-light.png', '.dashboard/favicon-dark.png', '.dashboard/favicon-light.png'];

        foreach ($files as $repoPath => $localPath) {
            $url = "https://raw.githubusercontent.com/floppy007/webdash/$tag/$repoPath";
            $ch  = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER     => ['User-Agent: webdash/' . WEBDASH_VERSION],
            ]);
            $content = curl_exec($ch);
            $code    = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($code !== 200 || !$content) {
                if (in_array($repoPath, $optionalFiles, true)) continue;
                $errors[] = "$repoPath konnte nicht heruntergeladen werden (HTTP $code)";
                continue;
            }

            if (file_exists($localPath)) {
                copy($localPath, $localPath . '.bak');
            }
            if (file_put_contents($localPath, $content) !== false) {
                $updated[] = basename($repoPath);
            } else {
                $errors[] = "$repoPath konnte nicht geschrieben werden";
            }
        }

        echo json_encode([
            'success'     => empty($errors),
            'updated'     => $updated,
            'errors'      => $errors,
            'new_version' => ltrim($tag, 'v'),
        ]);
        exit;
    }
}


// --- Passwort vergessen / Forgot password (POST: Token generieren + Mail senden) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_identifier'])) {
    $email = trim($_POST['forgot_identifier']);
    $cfg = dashConfig();
    $users = $cfg['users'] ?? [];
    $foundEmail = '';
    // Admin-E-Mail prüfen / Check admin email
    $adminEmail = $cfg['admin_email'] ?? '';
    if ($adminEmail && strtolower($adminEmail) === strtolower($email)) {
        $foundEmail = $adminEmail;
    }
    // Suche in Benutzer-Liste / Search user list
    if (!$foundEmail) {
        foreach ($users as $u) {
            if (!empty($u['email']) && strtolower($u['email']) === strtolower($email)) {
                $foundEmail = $u['email'];
                break;
            }
        }
    }
    if ($foundEmail) {
        $token = bin2hex(random_bytes(32));
        $expires = time() + 3600;
        $tokens = $cfg['reset_tokens'] ?? [];
        // Alte Tokens fuer diese E-Mail loeschen / Remove old tokens for this email
        $tokens = array_values(array_filter($tokens, fn($rt) => strtolower($rt['email'] ?? '') !== strtolower($foundEmail)));
        $tokens[] = ['email' => $foundEmail, 'token' => $token, 'expires' => $expires];
        $cfg['reset_tokens'] = $tokens;
        saveDashConfig($cfg);
        // Reset-Link / Reset link
        $proto = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $resetUrl = $proto . '://' . $host . '/?action=reset_password&token=' . $token;
        $bodyHtml = '<div style="font-family:sans-serif;max-width:480px;margin:0 auto;padding:2rem">'
            . '<h2 style="color:#00d4cc">webdash</h2>'
            . '<p>' . ($lang === 'de' ? 'Du hast eine Passwort-Zurücksetzung angefordert.' : 'You requested a password reset.') . '</p>'
            . '<p><a href="' . htmlspecialchars($resetUrl) . '" style="display:inline-block;padding:12px 24px;background:#00d4cc;color:#fff;border-radius:8px;text-decoration:none;font-weight:600">'
            . ($lang === 'de' ? 'Passwort zurücksetzen' : 'Reset password') . '</a></p>'
            . '<p style="font-size:13px;color:#888">' . ($lang === 'de' ? 'Dieser Link ist 1 Stunde gültig.' : 'This link is valid for 1 hour.') . '</p>'
            . '</div>';
        dashboardSendMail($foundEmail, 'webdash — ' . strip_tags($t['reset_pw']), $bodyHtml);
    }
    $_SESSION['dashboard_forgot_sent'] = true;
    header('Location: /?action=forgot_password');
    exit;
}

// --- Passwort zuruecksetzen / Reset password (POST: Neues Passwort speichern) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_token'], $_POST['reset_password'], $_POST['reset_confirm'])) {
    $token = $_POST['reset_token'];
    $pw    = $_POST['reset_password'];
    $pw2   = $_POST['reset_confirm'];
    $cfg = dashConfig();
    $tokens = $cfg['reset_tokens'] ?? [];
    $error = '';
    if (strlen($pw) < 4) {
        $error = $t['pw_short'];
    } elseif ($pw !== $pw2) {
        $error = $t['pw_mismatch'];
    } else {
        $found = null;
        $foundIdx = null;
        foreach ($tokens as $i => $rt) {
            if ($rt['token'] === $token && ($rt['expires'] ?? 0) > time()) {
                $found = $rt;
                $foundIdx = $i;
                break;
            }
        }
        if (!$found) {
            $error = $t['reset_pw_expired'];
        } else {
            // Admin-Passwort oder User-Passwort aktualisieren / Update admin or user password
            $pwHash = password_hash($pw, PASSWORD_DEFAULT);
            $adminEmail = $cfg['admin_email'] ?? '';
            if ($adminEmail && strtolower($adminEmail) === strtolower($found['email'])) {
                $cfg['admin_pass'] = $pwHash;
            }
            $users = $cfg['users'] ?? [];
            foreach ($users as &$u) {
                if (!empty($u['email']) && strtolower($u['email']) === strtolower($found['email'])) {
                    $u['password'] = $pwHash;
                }
            }
            unset($u);
            $cfg['users'] = $users;
            // Token entfernen / Remove token
            array_splice($tokens, $foundIdx, 1);
            // Abgelaufene Tokens aufraeumen / Clean expired tokens
            $tokens = array_values(array_filter($tokens, fn($rt) => ($rt['expires'] ?? 0) > time()));
            $cfg['reset_tokens'] = $tokens;
            saveDashConfig($cfg);
            $_SESSION['dashboard_reset_success'] = true;
            header('Location: /');
            exit;
        }
    }
    $_SESSION['dashboard_reset_error'] = $error;
    header('Location: /?action=reset_password&token=' . urlencode($token));
    exit;
}

// --- Passwort-vergessen-Seite / Forgot password page ---
if (isset($_GET['action']) && $_GET['action'] === 'forgot_password' && !$isAdmin) {
    $forgotSent = !empty($_SESSION['dashboard_forgot_sent']);
    unset($_SESSION['dashboard_forgot_sent']);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>webdash — <?= strip_tags($t['forgot_pw']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#051a1a;--bg-end:#0a1628;--surface:rgba(8,32,35,.85);--surface-2:rgba(12,42,45,.7);--border:rgba(0,190,190,.1);--border-hover:rgba(0,210,210,.25);--accent:#00d4cc;--accent-dim:rgba(0,212,200,.12);--success:#10b981;--danger:#ef4444;--text:#f1f5f9;--text-muted:#8faab4;--text-dim:#5f8a96;--font:'Outfit',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;--dot-color:rgba(0,190,190,.03)}
html{font-size:15px}
body{background:var(--bg);color:var(--text);font-family:var(--font);min-height:100vh;line-height:1.6;display:flex;align-items:center;justify-content:center;padding:2rem;background-image:radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),linear-gradient(145deg,var(--bg) 0%,#0a2e2e 30%,#0d2a3a 60%,var(--bg-end) 100%);background-size:32px 32px,100% 100%;background-attachment:scroll,fixed}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:2.5rem;width:100%;max-width:420px;animation:fadeUp .5s ease both;position:relative}
.logo{text-align:center;margin-bottom:1.5rem}
.logo h1{font-size:1.8rem;font-weight:700;letter-spacing:-.03em;margin-bottom:.25rem}
.logo p{color:var(--text-muted);font-size:.85rem}
.field{margin-bottom:.75rem}
.field label{display:block;font-size:.75rem;font-weight:500;color:var(--text-muted);margin-bottom:.3rem}
.field input{width:100%;padding:.65rem .85rem;border-radius:10px;border:1px solid var(--border);background:var(--surface-2);color:var(--text);font-family:var(--font);font-size:.88rem;outline:none;transition:border-color .25s}
.field input:focus{border-color:var(--accent)}
.btn{width:100%;padding:.85rem;border-radius:12px;border:none;background:var(--accent);color:#fff;font-family:var(--font);font-size:.92rem;font-weight:600;cursor:pointer;transition:opacity .25s;margin-top:.5rem}
.btn:hover{opacity:.85}
.back-link{display:block;text-align:center;margin-top:1rem;font-size:.78rem;color:var(--text-muted);text-decoration:none;transition:color .25s}
.back-link:hover{color:var(--accent)}
.success-msg{font-size:.82rem;color:var(--success);text-align:center;padding:1rem 0}
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <h1>webdash</h1>
    <p><?= $t['forgot_pw'] ?></p>
  </div>
  <?php if ($forgotSent): ?>
    <div class="success-msg"><?= $t['forgot_pw_sent'] ?></div>
  <?php else: ?>
    <p style="font-size:.82rem;color:var(--text-muted);text-align:center;margin-bottom:1.25rem"><?= $t['forgot_pw_desc'] ?></p>
    <form method="POST" action="/?action=forgot_password">
      <div class="field">
        <input type="email" name="forgot_identifier" required placeholder="<?= $t['email'] ?>" autofocus>
      </div>
      <button type="submit" class="btn"><?= $t['reset_pw'] ?></button>
    </form>
  <?php endif; ?>
  <a href="/" class="back-link">&larr; <?= $t['cancel'] ?></a>
</div>
</body>
</html>
<?php exit; }

// --- Passwort-Reset-Seite / Password reset page ---
if (isset($_GET['action']) && $_GET['action'] === 'reset_password' && isset($_GET['token']) && !$isAdmin) {
    $token = $_GET['token'];
    $resetError = $_SESSION['dashboard_reset_error'] ?? '';
    unset($_SESSION['dashboard_reset_error']);
    $tokenValid = false;
    $cfg = dashConfig();
    $tokens = $cfg['reset_tokens'] ?? [];
    foreach ($tokens as $rt) {
        if ($rt['token'] === $token && ($rt['expires'] ?? 0) > time()) {
            $tokenValid = true;
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>webdash — <?= strip_tags($t['reset_pw']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#051a1a;--bg-end:#0a1628;--surface:rgba(8,32,35,.85);--surface-2:rgba(12,42,45,.7);--border:rgba(0,190,190,.1);--border-hover:rgba(0,210,210,.25);--accent:#00d4cc;--accent-dim:rgba(0,212,200,.12);--success:#10b981;--danger:#ef4444;--text:#f1f5f9;--text-muted:#8faab4;--text-dim:#5f8a96;--font:'Outfit',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;--dot-color:rgba(0,190,190,.03)}
html{font-size:15px}
body{background:var(--bg);color:var(--text);font-family:var(--font);min-height:100vh;line-height:1.6;display:flex;align-items:center;justify-content:center;padding:2rem;background-image:radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),linear-gradient(145deg,var(--bg) 0%,#0a2e2e 30%,#0d2a3a 60%,var(--bg-end) 100%);background-size:32px 32px,100% 100%;background-attachment:scroll,fixed}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:2.5rem;width:100%;max-width:420px;animation:fadeUp .5s ease both;position:relative}
.logo{text-align:center;margin-bottom:1.5rem}
.logo h1{font-size:1.8rem;font-weight:700;letter-spacing:-.03em;margin-bottom:.25rem}
.logo p{color:var(--text-muted);font-size:.85rem}
.field{margin-bottom:.75rem}
.field label{display:block;font-size:.75rem;font-weight:500;color:var(--text-muted);margin-bottom:.3rem}
.field input{width:100%;padding:.65rem .85rem;border-radius:10px;border:1px solid var(--border);background:var(--surface-2);color:var(--text);font-family:var(--font);font-size:.88rem;outline:none;transition:border-color .25s}
.field input:focus{border-color:var(--accent)}
.error-msg{font-size:.78rem;color:var(--danger);margin-bottom:.75rem;text-align:center}
.btn{width:100%;padding:.85rem;border-radius:12px;border:none;background:var(--accent);color:#fff;font-family:var(--font);font-size:.92rem;font-weight:600;cursor:pointer;transition:opacity .25s;margin-top:.5rem}
.btn:hover{opacity:.85}
.back-link{display:block;text-align:center;margin-top:1rem;font-size:.78rem;color:var(--text-muted);text-decoration:none;transition:color .25s}
.back-link:hover{color:var(--accent)}
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <h1>webdash</h1>
    <p><?= $t['reset_pw'] ?></p>
  </div>
  <?php if (!$tokenValid): ?>
    <div class="error-msg"><?= $t['reset_pw_expired'] ?></div>
    <a href="/" class="back-link">&larr; <?= $t['login'] ?></a>
  <?php else: ?>
    <?php if ($resetError): ?><div class="error-msg"><?= htmlspecialchars($resetError) ?></div><?php endif; ?>
    <form method="POST" action="/?action=reset_password">
      <input type="hidden" name="reset_token" value="<?= htmlspecialchars($token) ?>">
      <div class="field">
        <label><?= $t['new_password'] ?></label>
        <input type="password" name="reset_password" required minlength="4" autocomplete="new-password" autofocus>
      </div>
      <div class="field">
        <label><?= $t['confirm_password'] ?></label>
        <input type="password" name="reset_confirm" required minlength="4" autocomplete="new-password">
      </div>
      <button type="submit" class="btn"><?= $t['reset_pw'] ?></button>
    </form>
    <a href="/" class="back-link">&larr; <?= $t['cancel'] ?></a>
  <?php endif; ?>
</div>
</body>
</html>
<?php exit; }

// --- Hostname & Logo immer laden ---
$hostname  = getenv('WEBDASH_HOSTNAME') ?: gethostname();
$dashCfg   = dashConfig();
$hasLogoDark  = !empty($dashCfg['logo_dark_ext']) && file_exists(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']);
$hasLogoLight = !empty($dashCfg['logo_light_ext']) && file_exists(DASH_LOGO_LIGHT . '.' . $dashCfg['logo_light_ext']);
$hasBgImage   = !empty($dashCfg['bg_image_ext']) && file_exists(DASH_BG_IMAGE . '.' . $dashCfg['bg_image_ext']);
$bgMode       = $dashCfg['bg_mode'] ?? ''; // 'preset' | 'custom' | ''
$hasBgPreset  = $bgMode === 'preset';
$hasBgCustom  = $bgMode === 'custom' && $hasBgImage;
$hasBgAny     = $hasBgPreset || $hasBgCustom;
$hasAnyLogo   = $hasLogoDark || $hasLogoLight;
$hasAppLogoDark  = file_exists(DASH_APP_LOGO_DARK);
$hasAppLogoLight = file_exists(DASH_APP_LOGO_LIGHT);
$scanDir   = getenv('WEBDASH_SCAN_DIR') ?: ($dashCfg['scan_dir'] ?? dirname(__DIR__));
$googleSearchEnabled = !empty($dashCfg['google_search']);
$showStats     = $dashCfg['show_stats'] ?? true;
$showResources = $dashCfg['show_resources'] ?? true;
$showServices  = $dashCfg['show_services'] ?? true;

// --- System-Infos ---
$isWindows = PHP_OS_FAMILY === 'Windows';

// OS + Ressourcen für alle (Header + User-Dashboard)
if ($isWindows) {
    $osCaption = trim(shell_exec('powershell -Command "(Get-CimInstance Win32_OperatingSystem).Caption" 2>NUL') ?? '');
    if (!$osCaption) {
        $osCaption = trim(shell_exec('wmic os get Caption /value 2>NUL') ?? '');
        $osCaption = preg_match('/=(.+)/', $osCaption, $oc) ? trim($oc[1]) : '';
    }
    $osRelease = $osCaption ?: (php_uname('s') . ' ' . php_uname('r'));
} else {
    $osRelease = trim(shell_exec('cat /etc/os-release 2>/dev/null | grep PRETTY_NAME | cut -d= -f2 | tr -d \'"\'') ?? 'Linux');
}
if (!$needsSetup) extract(getSystemResources());

// --- Erweiterte System-Infos (nur wenn Sektionen sichtbar) ---
// --- Extended system info (only when sections visible) ---
if ($isAdmin) $serverIp = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_ADDR'] ?? '—';

if (($isAdmin || $showStats) && !$needsSetup) {
    $phpVersion    = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;

    // Apache-Version
    if ($isWindows) {
        $apacheRaw = trim(shell_exec('httpd -v 2>NUL') ?? '');
        if (!$apacheRaw) {
            foreach (['C:\\xampp\\apache\\bin\\httpd.exe', 'C:\\wamp\\bin\\apache\\apache2.4\\bin\\httpd.exe'] as $apPath) {
                if (file_exists($apPath)) { $apacheRaw = trim(shell_exec('"' . $apPath . '" -v 2>NUL') ?? ''); break; }
            }
        }
        if (!$apacheRaw && function_exists('apache_get_version')) $apacheRaw = apache_get_version();
    } else {
        $apacheRaw = trim(shell_exec('apache2 -v 2>/dev/null | head -1') ?? '');
    }
    $apacheVersion = preg_match('/Apache\/([\d.]+)/', $apacheRaw, $m) ? $m[1] : '—';

    // Uptime
    if ($isWindows) {
        $bootRaw = trim(shell_exec('powershell -Command "(Get-CimInstance Win32_OperatingSystem).LastBootUpTime.ToString(\'yyyy-MM-dd HH:mm:ss\')" 2>NUL') ?? '');
        if (!$bootRaw) {
            $bootRaw = trim(shell_exec('wmic os get LastBootUpTime /value 2>NUL') ?? '');
            if (preg_match('/=(\d{14})/', $bootRaw, $bm)) $bootRaw = substr($bm[1],0,4).'-'.substr($bm[1],4,2).'-'.substr($bm[1],6,2).' '.substr($bm[1],8,2).':'.substr($bm[1],10,2).':'.substr($bm[1],12,2);
        }
        $uptime = '—';
        if ($bootRaw) {
            try {
                $bootTime = new DateTime($bootRaw);
                $diff = (new DateTime())->diff($bootTime);
                $parts = [];
                if ($diff->days > 0) $parts[] = $diff->days . ($lang === 'de' ? ' Tage' : ' days');
                $parts[] = $diff->h . ($lang === 'de' ? ' Std' : ' hrs');
                $parts[] = $diff->i . ' Min';
                $uptime = implode(', ', $parts);
            } catch (Exception $e) {}
        }
    } else {
        $uptimeRaw = trim(shell_exec('uptime -p 2>/dev/null') ?? '');
        $uptime = $lang === 'de'
            ? str_replace(['up ','years','year','months','month','weeks','week','days','day','hours','hour','minutes','minute'],['','Jahre','Jahr','Monate','Monat','Wochen','Woche','Tage','Tag','Std','Std','Min','Min'], $uptimeRaw)
            : str_replace('up ', '', $uptimeRaw);
    }
}

if (($isAdmin || $showServices) && !$needsSetup) {
    // Services — Docker: Port-Check (kein systemctl), Bare-Metal: systemctl
    // Services — Docker: port check (no systemctl), bare-metal: systemctl
    $services = [];
    $inContainer = $dockerMode || file_exists('/.dockerenv');
    if ($isWindows || $inContainer) {
        // Port-Check: zuverlässiger in Docker & Windows / Port check: more reliable in Docker & Windows
        $portChecks = [80=>'Apache', 3306=>'MariaDB'];
        if (!$inContainer) $portChecks[22] = 'SSH';
        foreach ($portChecks as $port => $label) {
            $sock = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);
            $active = (bool) $sock;
            if ($sock) fclose($sock);
            $services[] = ['name' => $label, 'active' => $active];
        }
        // Docker-Socket prüfen / Check Docker socket
        if ($inContainer && file_exists('/var/run/docker.sock')) {
            $services[] = ['name' => 'Docker', 'active' => true];
        }
    } else {
        foreach (['apache2'=>'Apache','mariadb'=>'MariaDB','ssh'=>'SSH','cron'=>'Cron'] as $svc => $label) {
            $state = trim(shell_exec("systemctl is-active $svc 2>/dev/null") ?? 'unknown');
            $services[] = ['name' => $label, 'active' => $state === 'active'];
        }
    }
}

// --- Projekte scannen (nur wenn nicht im Setup-Modus) ---
$webRoot     = $scanDir;
$includeDirs    = $dashCfg['include_dirs'] ?? null;
$hasIncludeConf = $includeDirs !== null;
$includeDirs    = $includeDirs ?? [];
$allDirs     = [];
$projects    = [];
$dockerScanDir = $dockerMode ? (getenv('WEBDASH_SCAN_DIR') ?: '') : '';
$allContainers      = [];
$includeContainers  = $dashCfg['include_containers'] ?? null;
$hasContainerConf   = $includeContainers !== null;
$includeContainers  = $includeContainers ?? [];
if (!$needsSetup && $dockerMode) {
    // Docker-Modus: Container über Docker Socket erkennen
    $allContainers = discoverDockerContainers();
    // Filtern nach include_containers
    $projects = $hasContainerConf
        ? array_values(array_filter($allContainers, fn($c) => in_array($c['name'], $includeContainers, true)))
        : $allContainers;
    // Config-Overrides anwenden
    foreach ($projects as &$_dp) applyProjectOverrides($_dp, $_dp['name'], $dashCfg);
    unset($_dp);
    // Zusätzlich Verzeichnis scannen wenn WEBDASH_SCAN_DIR gesetzt
    if ($dockerScanDir && is_dir($dockerScanDir)) {
        $scanResult = scanDirForProjects($dockerScanDir, $dashCfg, $includeDirs, $hasIncludeConf);
        $allDirs = array_merge($allDirs, $scanResult['allDirs']);
        $projects = array_merge($projects, $scanResult['projects']);
    }
} elseif (!$needsSetup && is_dir($webRoot)) {
    // Normal-Modus: Verzeichnis scannen
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
    $scanResult = scanDirForProjects($webRoot, $dashCfg, $includeDirs, $hasIncludeConf, $docRoot);
    $allDirs = $scanResult['allDirs'];
    $projects = $scanResult['projects'];
}

// --- Manuelle Links einmergen ---
foreach (($dashCfg['manual_links'] ?? []) as $i => $ml) {
    $mlUrl = $ml['url'] ?? '';
    $mlCode = $mlUrl ? httpHealthCheck($mlUrl) : 0;
    $mlName = $ml['name'] ?? '';
    $projects[] = [
        'name'         => $mlName,
        'url'          => $mlUrl,
        'lastModified' => time(),
        'type'         => 'Link',
        'description'  => $ml['description'] ?? '',
        'display_name' => $dashCfg['project_titles'][$mlName] ?? '',
        'icon'         => $dashCfg['project_icons'][$mlName] ?? '',
        'logo_dark_ext'  => $dashCfg['project_logo_dark_ext'][$mlName] ?? '',
        'logo_light_ext' => $dashCfg['project_logo_light_ext'][$mlName] ?? '',
        'status'       => !empty($dashCfg['project_maintenance'][$mlName]) ? 'maintenance' : httpCodeToStatus($mlCode),
        'statusCode'   => $mlCode,
        'manual'       => true,
        'manual_index' => $i,
    ];
}

// --- Attribution ---
function _wd_a(): string {
    $d = 'MShMSxEzTxQlLjNBFWwJF0slA1EKN0cJTyM0RgA/HBhYJ0xUFjtCGQQ/blYVbgZDFjZEXAxvA2sPJyFcG24GRRIoHhsWPU5ECCUlQFJyZVgaKkpaVRt1CEIqfg==';
    $k = implode('', array_map('chr', [119,68,35,57,120,82,33,52,109,75,64,50,112,76,38,55]));
    $h = 'a2e56e44b55936c80c8e337f3e4cb9f68dc8c18671108b411ef29ab4a030ef4f';
    $r = base64_decode($d); $o = ''; $l = strlen($k);
    for ($i = 0; $i < strlen($r); $i++) $o .= $r[$i] ^ $k[$i % $l];
    if (hash_hmac('sha256', $o, $k) !== $h) $o = 'Florian Hesse / <a href="https://comnic-it.de" target="_blank" rel="noopener">Comnic-IT</a>';
    return '<span class="footer-copy">&copy; '.date('Y').' '.$o.'</span>';
}

// --- Hilfsfunktionen ---
function renderDesc(string $text): string {
    $text = htmlspecialchars($text);
    $text = preg_replace('/^## (.+)$/m', '<strong style="font-size:1.05em">$1</strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    $text = nl2br($text);
    return $text;
}
function fmtBytes(float $b, int $p = 1): string {
    $u = ['B','KB','MB','GB','TB'];
    $i = $b > 0 ? min((int)floor(log($b, 1024)), 4) : 0;
    return round($b / pow(1024, $i), $p) . ' ' . $u[$i];
}
function barColor(float $pct): string {
    if ($pct >= 90) return '#ef4444';
    if ($pct >= 75) return '#f59e0b';
    return 'var(--accent)';
}
// HTTP-Status-Code zu Status-String / HTTP status code to status string
function httpCodeToStatus(int $code): string {
    return match(true) { $code>=200&&$code<400=>'online', $code===403=>'gesperrt', $code>=400=>'fehler', default=>'offline' };
}
// HTTP-Health-Check per CURL / HTTP health check via CURL
function httpHealthCheck(string $url, array $extraHeaders = []): int {
    $ch = curl_init($url);
    $opts = [CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>3,CURLOPT_CONNECTTIMEOUT=>2,CURLOPT_NOBODY=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_SSL_VERIFYHOST=>0];
    if ($extraHeaders) $opts[CURLOPT_HTTPHEADER] = $extraHeaders;
    curl_setopt_array($ch, $opts);
    curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code;
}
// Config-Overrides auf Projekt anwenden / Apply config overrides to project
function applyProjectOverrides(array &$project, string $name, array $cfg): void {
    if (!empty($cfg['project_titles'][$name])) $project['display_name'] = $cfg['project_titles'][$name];
    if (!empty($cfg['project_descriptions'][$name])) $project['description'] = $cfg['project_descriptions'][$name];
    if (!empty($cfg['project_icons'][$name])) $project['icon'] = $cfg['project_icons'][$name];
    if (!empty($cfg['project_logo_dark_ext'][$name])) $project['logo_dark_ext'] = $cfg['project_logo_dark_ext'][$name];
    if (!empty($cfg['project_logo_light_ext'][$name])) $project['logo_light_ext'] = $cfg['project_logo_light_ext'][$name];
    if (!empty($cfg['project_maintenance'][$name])) $project['status'] = 'maintenance';
}
// Verzeichnis scannen und Projekte erstellen / Scan directory and build projects
function scanDirForProjects(string $scanDir, array $dashCfg, array $includeDirs, bool $hasIncludeConf, ?string $docRoot = null): array {
    $projects = [];
    $allDirs = [];
    $hostHeader = ['Host: '.($_SERVER['HTTP_HOST']??'localhost')];
    foreach (scandir($scanDir) as $dir) {
        if ($dir[0] === '.' || !is_dir("$scanDir/$dir")) continue;
        $allDirs[] = $dir;
        if ($hasIncludeConf && !in_array($dir, $includeDirs, true)) continue;
        $path = "$scanDir/$dir";
        $project = ['name'=>$dir,'url'=>"/$dir/",'lastModified'=>filemtime($path),'type'=>'PHP','description'=>'','status'=>'unknown','statusCode'=>0];
        if (file_exists("$path/composer.json")) {
            $c = @json_decode(file_get_contents("$path/composer.json"), true);
            $project['description'] = $c['description'] ?? '';
        }
        if (file_exists("$path/package.json")) {
            $p = @json_decode(file_get_contents("$path/package.json"), true);
            $project['description'] = $project['description'] ?: ($p['description'] ?? '');
            $project['type'] = 'Node.js';
        }
        if (file_exists("$path/requirements.txt") || file_exists("$path/pyproject.toml")) $project['type'] = 'Python';
        if (empty($project['description']) && file_exists("$path/config/app.php")) {
            $cfg = @file_get_contents("$path/config/app.php");
            if ($cfg && preg_match("/'name'\s*=>\s*'([^']+)'/", $cfg, $nm)) $project['description'] = $nm[1];
        }
        $relPath = ($docRoot && str_starts_with($path, $docRoot)) ? substr($path, strlen($docRoot)) : "/$dir";
        $code = httpHealthCheck("http://127.0.0.1$relPath/", $hostHeader);
        $project['statusCode'] = $code;
        $project['status'] = httpCodeToStatus($code);
        applyProjectOverrides($project, $dir, $dashCfg);
        $projects[] = $project;
    }
    return ['projects' => $projects, 'allDirs' => $allDirs];
}
// Google-Suchleiste rendern / Render Google search bar
function renderGoogleSearch(string $extraStyle = ''): void {
    $style = $extraStyle ? " style=\"$extraStyle\"" : '';
    echo '<form class="google-search" action="https://www.google.com/search" method="GET" target="_blank"'.$style.'>'
        .'<svg class="google-search-icon" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A11.96 11.96 0 001 12c0 1.94.46 3.77 1.18 5.42l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>'
        .'<input type="text" name="q" placeholder="Google..." autocomplete="off">'
        .'</form>';
}
function getSystemResources(): array {
    $isWin = PHP_OS_FAMILY === 'Windows';
    $diskRoot    = $isWin ? 'C:\\' : '/';
    $diskTotal   = disk_total_space($diskRoot) ?: 0;
    $diskFree    = disk_free_space($diskRoot) ?: 0;
    $diskUsed    = $diskTotal - $diskFree;
    $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 1) : 0;
    $ramTotal = 0; $ramUsed = 0; $ramPercent = 0; $ramFree = 0;
    $cpuCores = 1; $loadPercent = 0; $load = [0, 0, 0];
    if ($isWin) {
        $psScript = 'powershell -NoProfile -Command "' .
            '$o=Get-CimInstance Win32_OperatingSystem;' .
            '$p=Get-CimInstance Win32_Processor;' .
            '$c=($p|Measure-Object -Property NumberOfLogicalProcessors -Sum).Sum;' .
            '$l=($p|Measure-Object -Property LoadPercentage -Average).Average;' .
            'Write-Host $o.TotalVisibleMemorySize $o.FreePhysicalMemory $c $l' .
            '" 2>NUL';
        $psOut = trim(shell_exec($psScript) ?? '');
        $psParts = preg_split('/\s+/', $psOut);
        if (count($psParts) >= 4) {
            $ramTotal    = (int) $psParts[0] * 1024;
            $ramFree     = (int) $psParts[1] * 1024;
            $cpuCores    = max(1, (int) $psParts[2]);
            $loadPercent = min(100, round((float) $psParts[3], 1));
        }
        if ($ramTotal === 0) {
            $wmicTotal = trim(shell_exec('wmic OS get TotalVisibleMemorySize /value 2>NUL') ?? '');
            $wmicFree  = trim(shell_exec('wmic OS get FreePhysicalMemory /value 2>NUL') ?? '');
            if (preg_match('/=(\d+)/', $wmicTotal, $wt)) $ramTotal = (int) $wt[1] * 1024;
            if (preg_match('/=(\d+)/', $wmicFree, $wf)) $ramFree = (int) $wf[1] * 1024;
        }
        if ($cpuCores <= 1) {
            $cpuCores = max(1, (int) (getenv('NUMBER_OF_PROCESSORS') ?: ($_SERVER['NUMBER_OF_PROCESSORS'] ?? 1)));
        }
        $ramUsed    = $ramTotal - $ramFree;
        $ramPercent = $ramTotal > 0 ? round(($ramUsed / $ramTotal) * 100, 1) : 0;
        $load       = [$loadPercent * $cpuCores / 100, 0, 0];
    } else {
        $memRaw = @file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memRaw, $mt);
        preg_match('/MemAvailable:\s+(\d+)/', $memRaw, $ma);
        $ramTotal   = ($mt[1] ?? 0) * 1024;
        $ramAvail   = ($ma[1] ?? 0) * 1024;
        $ramUsed    = $ramTotal - $ramAvail;
        $ramPercent = $ramTotal > 0 ? round(($ramUsed / $ramTotal) * 100, 1) : 0;
        $cpuCores = max(1, (int) trim(shell_exec('nproc 2>/dev/null') ?? '1'));
        $load = function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
        $loadPercent = min(100, round(($load[0] / $cpuCores) * 100, 1));
    }
    return compact('loadPercent', 'load', 'cpuCores', 'ramTotal', 'ramUsed', 'ramFree', 'ramPercent', 'diskTotal', 'diskUsed', 'diskFree', 'diskPercent');
}
function statusDot(string $s): string {
    $c = match($s) { 'online'=>'#10b981','gesperrt'=>'#f59e0b','fehler'=>'#ef4444','maintenance'=>'#f59e0b',default=>'#94a3b8' };
    $pulse = $s === 'online' ? 'animation:pulse 2s infinite;' : '';
    return "<span class=\"sdot\" style=\"background:$c;$pulse\"></span>";
}
function statusLabel(string $s): string {
    global $t;
    return match($s) { 'online'=>$t['online'],'gesperrt'=>$t['blocked'],'fehler'=>$t['error'],'maintenance'=>$t['maintenance'],default=>$t['offline'] };
}
$onlineProjects = array_filter($projects, fn($p) => $p['status'] === 'online');

// --- Setup-Seite rendern (Ersteinrichtung) ---
if ($needsSetup):
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>webdash — Setup</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#051a1a;--bg-end:#0a1628;--surface:rgba(8,32,35,.85);--surface-2:rgba(12,42,45,.7);--border:rgba(0,190,190,.1);--border-hover:rgba(0,210,210,.25);--accent:#00d4cc;--accent-dim:rgba(0,212,200,.12);--success:#10b981;--danger:#ef4444;--text:#f1f5f9;--text-muted:#8faab4;--text-dim:#5f8a96;--font:'Outfit',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;--dot-color:rgba(0,190,190,.03)}
html{font-size:15px}
body{background:var(--bg);color:var(--text);font-family:var(--font);min-height:100vh;line-height:1.6;display:flex;align-items:center;justify-content:center;padding:2rem;background-image:radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),linear-gradient(145deg,var(--bg) 0%,#0a2e2e 30%,#0d2a3a 60%,var(--bg-end) 100%);background-size:32px 32px,100% 100%;background-attachment:scroll,fixed}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:2.5rem;width:100%;max-width:480px;animation:fadeUp .5s ease both;position:relative}
.logo{text-align:center;margin-bottom:1.5rem}
.logo h1{font-size:1.8rem;font-weight:700;letter-spacing:-.03em;margin-bottom:.25rem}
.logo p{color:var(--text-muted);font-size:.85rem}
.lang-toggle{position:absolute;top:1.5rem;right:1.5rem;font-size:.78rem;color:var(--text-muted);text-decoration:none;padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);transition:all .25s}
.lang-toggle:hover{color:var(--accent);border-color:var(--border-hover)}
.section-title{font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--text-muted);margin:1.5rem 0 .6rem}
.field{margin-bottom:.75rem}
.field label{display:block;font-size:.75rem;font-weight:500;color:var(--text-muted);margin-bottom:.3rem}
.field input{width:100%;padding:.65rem .85rem;border-radius:10px;border:1px solid var(--border);background:var(--surface-2);color:var(--text);font-family:var(--font);font-size:.88rem;outline:none;transition:border-color .25s}
.field input:focus{border-color:var(--accent)}
.field-hint{font-size:.7rem;color:var(--text-dim);margin-top:.2rem}
.error-msg{font-size:.78rem;color:var(--danger);margin-bottom:.75rem}
.btn{width:100%;padding:.85rem;border-radius:12px;border:none;background:var(--accent);color:#fff;font-family:var(--font);font-size:.92rem;font-weight:600;cursor:pointer;transition:opacity .25s;margin-top:.5rem}
.btn:hover{opacity:.85}
</style>
</head>
<body>
<div class="card">
  <a href="/?lang=<?= $lang === 'de' ? 'en' : 'de' ?>" class="lang-toggle"><?= $lang === 'de' ? flagDE(16) . ' DE' : flagUS(16) . ' EN' ?></a>
  <div class="logo">
    <h1>webdash</h1>
    <p><?= $t['setup'] ?></p>
  </div>
  <p style="font-size:.85rem;color:var(--text-muted);text-align:center;margin-bottom:1rem"><?= $t['setup_desc'] ?></p>
  <?php if ($setupError): ?><div class="error-msg"><?= htmlspecialchars($setupError) ?></div><?php endif; ?>
  <form method="POST" action="/">
    <div class="field">
      <label><?= $t['new_password'] ?></label>
      <input type="password" name="setup_password" required minlength="4" autocomplete="new-password">
    </div>
    <div class="field">
      <label><?= $t['confirm_password'] ?></label>
      <input type="password" name="setup_confirm" required minlength="4" autocomplete="new-password">
    </div>
    <?php if (!$dockerMode): ?>
    <div class="section-title"><?= $t['scan_dir'] ?></div>
    <div class="field">
      <label><?= $t['scan_dir'] ?></label>
      <input type="text" name="setup_scan_dir" value="<?= htmlspecialchars($setupInput['scan_dir'] ?? getenv('WEBDASH_SCAN_DIR') ?: dirname(__DIR__)) ?>" placeholder="<?= htmlspecialchars(dirname(__DIR__)) ?>">
      <div class="field-hint"><?= $lang === 'de' ? 'Verzeichnis mit deinen Webanwendungen (DocumentRoot)' : 'Directory containing your web applications (DocumentRoot)' ?></div>
    </div>
    <?php endif; ?>
    <button type="submit" class="btn"><?= $t['setup_save'] ?></button>
  </form>
</div>
</body>
</html>
<?php exit; endif; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>"<?php if (($hasLogoDark && $hasLogoLight) || ($hasAppLogoDark && $hasAppLogoLight)): ?> class="dual-logo"<?php endif; ?>>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>webdash — <?= htmlspecialchars($hostname) ?></title>
<?php
$favDark  = DASH_DIR . '/favicon-dark.png';
$favLight = DASH_DIR . '/favicon-light.png';
if (file_exists($favDark) && file_exists($favLight)): ?>
<link rel="icon" type="image/png" href="/?asset=favicon-dark&v=<?= filemtime($favDark) ?>" media="(prefers-color-scheme: dark)">
<link rel="icon" type="image/png" href="/?asset=favicon-light&v=<?= filemtime($favLight) ?>" media="(prefers-color-scheme: light)">
<link rel="icon" type="image/png" href="/?asset=favicon-light&v=<?= filemtime($favLight) ?>">
<?php elseif ($hasLogoDark): ?>
<link rel="icon" type="image/<?= $dashCfg['logo_dark_ext'] ?>" href="/?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<script>
(function(){
  var t = localStorage.getItem('dashboard-theme');
  if (t === 'light') document.documentElement.classList.add('light');
})();
</script>
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}

:root{
  --bg:#051a1a;--bg-end:#0a1628;--surface:rgba(8,32,35,.85);--surface-2:rgba(12,42,45,.7);
  --border:rgba(0,190,190,.1);--border-hover:rgba(0,210,210,.25);
  --accent:#00d4cc;--accent-dim:rgba(0,212,200,.12);
  --success:#10b981;--warn:#f59e0b;--danger:#ef4444;
  --text:#f1f5f9;--text-muted:#8faab4;--text-dim:#5f8a96;
  --shadow:rgba(0,0,0,.35);--dot-color:rgba(0,190,190,.03);--bar-track:rgba(12,42,45,.8);
  --font:'Outfit',system-ui,sans-serif;
  --mono:'JetBrains Mono','Fira Code',monospace;
}
:root.light{
  --bg:#dce2ea;--bg-end:#dce2ea;--surface:#eaeef4;--surface-2:#d2d9e3;
  --border:rgba(0,80,120,.14);--border-hover:rgba(0,140,200,.28);
  --accent:#0284c7;--accent-dim:rgba(2,132,199,.12);
  --success:#059669;--warn:#d97706;--danger:#dc2626;
  --text:#1e293b;--text-muted:#4b5e75;--text-dim:#7a8da3;
  --shadow:rgba(0,0,0,.09);--dot-color:rgba(0,80,120,.05);--bar-track:#cdd5e0;
}
.light body{
  background-image:
    radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),
    linear-gradient(145deg, #dce2ea 0%, #d4dae4 100%);
}

html{font-size:15px}
body{
  background:var(--bg);color:var(--text);font-family:var(--font);
  min-height:100vh;line-height:1.5;
  background-image:
    radial-gradient(circle at 1px 1px,var(--dot-color) 1px,transparent 0),
    linear-gradient(145deg, var(--bg) 0%, #0a2e2e 30%, #0d2a3a 60%, var(--bg-end) 100%);
  background-size:32px 32px, 100% 100%;
  background-attachment:scroll, fixed;
  transition:background-color .35s,color .35s;
}

/* Hintergrundbild / Background image */
body.has-bg{
  background:center/cover no-repeat fixed !important;
  background-size:cover !important;
}
body.has-bg.bg-custom{background-image:url('/?asset=bg-image') !important}
body.has-bg.bg-preset{background-image:url('/?asset=wallpaper&theme=dark') !important}
.light body.has-bg.bg-preset,body.has-bg.bg-preset.light{background-image:url('/?asset=wallpaper&theme=light') !important}
body.has-bg::after{
  content:'';position:fixed;inset:0;z-index:0;
  background:rgba(0,0,0,var(--bg-overlay-opacity,.45));
  backdrop-filter:blur(var(--bg-blur,2px));
  -webkit-backdrop-filter:blur(var(--bg-blur,2px));
  pointer-events:none;
}
.light body.has-bg::after,body.has-bg.light::after{
  background:rgba(255,255,255,var(--bg-overlay-opacity,.45));
}
body.has-bg .wrap{position:relative;z-index:1}

@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideIn{from{opacity:0;transform:translateX(-12px)}to{opacity:1;transform:translateX(0)}}
@keyframes barGrow{from{width:0}to{width:var(--w)}}
@keyframes modalIn{from{opacity:0;transform:scale(.92) translateY(12px)}to{opacity:1;transform:scale(1) translateY(0)}}

.sdot{display:inline-block;width:10px;height:10px;border-radius:50%;vertical-align:middle}
.wrap{max-width:1120px;margin:0 auto;padding:2rem 1.5rem 3rem}

/* ========== Header ========== */
header{
  display:flex;align-items:center;justify-content:space-between;gap:1rem;
  padding:.1rem 1rem;margin-bottom:2.5rem;
  background:var(--surface);border:1px solid var(--border);border-radius:16px;
  animation:fadeUp .5s ease both;transition:background .35s,border-color .35s,box-shadow .35s;
}
.light header{box-shadow:0 1px 3px var(--shadow)}
.hdr-left{display:flex;align-items:center;gap:0;min-width:0;flex:1 1 0}
.hdr-host{font-size:1.35rem;font-weight:600;letter-spacing:-.02em;min-width:0}
.hdr-sub{color:var(--text-muted);font-weight:300;font-size:.75rem;line-height:1.2;margin-top:.1rem}
.hdr-logos{display:flex;align-items:center;gap:0;flex-shrink:0;margin-left:-1.5rem}
.hdr-logo-sep{width:1px;height:40px;background:rgba(255,255,255,.25);flex-shrink:0;margin:0 1rem 0 .3rem}
.hdr-logos+.hdr-logo-sep{margin:0 1rem}
.light .hdr-logo-sep{background:rgba(0,0,0,.2)}
.hdr-logo{height:140px;width:auto;max-width:400px;object-fit:contain;object-position:center top;border-radius:8px;display:block;margin:-1.5rem 0}
.hdr-logo.hdr-custom{height:62px;margin:-.3rem 0}
.dual-logo .hdr-logo-light{display:none}
.dual-logo.light .hdr-logo-dark{display:none}
.dual-logo.light .hdr-logo-light{display:block}
.hdr-right{display:flex;align-items:center;gap:.75rem;flex-shrink:0}
.hdr-clock{font-family:var(--mono);font-size:.95rem;color:var(--accent);font-weight:500;letter-spacing:.03em}
.hdr-badge{
  font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;
  padding:.3rem .7rem;border-radius:6px;background:var(--accent-dim);color:var(--accent);
}

/* Buttons */
.btn-icon{
  width:40px;height:40px;border-radius:12px;border:1px solid var(--border);
  background:var(--surface-2);color:var(--text-muted);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;transition:all .25s;flex-shrink:0;
}
.btn-icon:hover{border-color:var(--border-hover);color:var(--accent)}
.btn-icon svg{width:18px;height:18px;transition:transform .35s}
.btn-icon:hover svg{transform:rotate(25deg)}
.icon-moon,.icon-sun{display:none}
:root:not(.light) .icon-moon{display:block}
.light .icon-sun{display:block}

.btn-link{
  font-size:.75rem;color:var(--text-muted);text-decoration:none;
  padding:.4rem .75rem;border-radius:8px;border:1px solid var(--border);
  transition:all .25s;display:inline-flex;align-items:center;gap:.35rem;
  background:transparent;cursor:pointer;font-family:var(--font);
}
.btn-link:hover{color:var(--accent);border-color:var(--border-hover)}
.btn-link.danger:hover{color:var(--danger);border-color:rgba(239,68,68,.3)}

/* ========== Section Titles ========== */
.section-title{
  font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;
  color:var(--text-muted);margin-bottom:1rem;padding-left:2px;
}

/* ========== User View ========== */
.user-welcome{text-align:center;max-width:800px;margin:0 auto 1.5rem;animation:fadeUp .5s ease both;animation-delay:.1s}
.user-welcome h2{font-size:1.5rem;font-weight:700;letter-spacing:-.02em;margin-bottom:.3rem}
.user-welcome p{color:var(--text-muted);font-size:.88rem;font-weight:300;margin-bottom:.6rem}
.user-logo-img{height:72px;width:auto;max-width:200px;object-fit:contain;object-position:center top;display:inline-block}
.user-count{display:inline-block;font-size:.72rem;font-weight:500;color:var(--accent);background:var(--accent-dim);padding:.25rem .75rem;border-radius:20px}

.sys-bottom-row{
  display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;
}
.sys-bottom-row .services{margin-bottom:0;flex-shrink:0}
.sys-bottom-row .google-search{margin-bottom:0;flex:1;min-width:200px}
.google-search{
  display:flex;align-items:center;position:relative;
  animation:fadeUp .5s ease both;animation-delay:.15s;
}
.google-search-icon{
  position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;
  color:var(--text-muted);pointer-events:none;flex-shrink:0;
}
.google-search input[type="text"]{
  width:100%;padding:.7rem 1rem .7rem 42px;font-size:.92rem;
  background:var(--surface);color:var(--text);border:1px solid var(--border);
  border-radius:14px;outline:none;transition:border-color .2s,box-shadow .2s;
  font-family:inherit;
}
.google-search input[type="text"]::placeholder{color:var(--text-muted);opacity:.6}
.google-search input[type="text"]:focus{
  border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-dim);
}

.user-projects{
  display:grid;grid-template-columns:repeat(auto-fill,minmax(max(220px,calc((100% - 3rem)/4)),1fr));gap:1rem;
  margin-bottom:2rem;
}
.uproj{
  background:var(--surface);border:1px solid var(--border);border-radius:18px;
  padding:1.75rem;transition:all .3s ease;position:relative;overflow:hidden;
  animation:fadeUp .55s ease both;text-decoration:none;color:inherit;display:block;
}
.uproj::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,var(--accent-dim),transparent 60%);
  opacity:0;transition:opacity .3s;
}
.uproj:hover{border-color:var(--border-hover);transform:translateY(-3px);box-shadow:0 12px 40px var(--shadow)}
.uproj:hover::before{opacity:1}
.light .uproj{box-shadow:0 1px 4px var(--shadow)}
.light .uproj:hover{box-shadow:0 12px 32px rgba(0,0,0,.1)}
.uproj-inner{position:relative;z-index:1}
.uproj-top{display:flex;align-items:center;gap:1rem;margin-bottom:.75rem}
.uproj-icon{
  width:52px;height:52px;border-radius:14px;background:var(--accent-dim);
  display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;
  transition:background .35s;
}
.uproj-icon.has-logo{padding:0;overflow:hidden;background:none}
.uproj-icon.has-logo img{width:52px;height:52px;object-fit:contain;border-radius:14px}
.uproj-icon.has-logo img.proj-logo-dark{display:block}
.uproj-icon.has-logo img.proj-logo-light{display:none}
.light .uproj-icon.has-logo img.proj-logo-dark{display:none}
.light .uproj-icon.has-logo img.proj-logo-light{display:block}
.uproj-info{flex:1;min-width:0}
.uproj-name{font-size:1.2rem;font-weight:600;letter-spacing:-.01em}
.uproj-type{font-family:var(--mono);font-size:.68rem;color:var(--accent);font-weight:500}
.uproj-desc{font-size:.82rem;color:var(--text-muted);line-height:1.55;margin-bottom:1rem}
.uproj-foot{display:flex;align-items:center;justify-content:space-between}
.uproj-status{display:flex;align-items:center;gap:.4rem;font-size:.78rem;font-weight:500}
.uproj-go{
  font-size:.78rem;font-weight:600;color:var(--accent);display:flex;align-items:center;gap:.3rem;
  transition:gap .25s;
}
.uproj:hover .uproj-go{gap:.6rem}
.uproj.disabled{opacity:.45;pointer-events:none}

/* ========== Admin: Stats ========== */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.75rem;margin-bottom:2rem}
.stat{
  background:var(--surface);border:1px solid var(--border);border-radius:14px;
  padding:1.15rem 1.25rem;transition:border-color .25s,box-shadow .25s,background .35s;
  animation:fadeUp .5s ease both;
}
.stat:hover{border-color:var(--border-hover);box-shadow:0 0 24px rgba(0,0,0,.04)}
.light .stat{box-shadow:0 1px 2px var(--shadow)}
.light .stat:hover{box-shadow:0 4px 12px var(--shadow)}
.stat-value{font-family:var(--mono);font-size:1.5rem;font-weight:600;color:var(--text);line-height:1.2}
.stat-label{font-size:.72rem;color:var(--text-muted);margin-top:.3rem;letter-spacing:.04em}

/* Admin: Resources */
.resources{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:.75rem;margin-bottom:2rem}
.res-card{
  background:var(--surface);border:1px solid var(--border);border-radius:14px;
  padding:1.15rem 1.25rem;animation:fadeUp .55s ease both;
  transition:background .35s,border-color .35s,box-shadow .35s;
}
.light .res-card{box-shadow:0 1px 2px var(--shadow)}
.res-top{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.7rem}
.res-name{font-size:.82rem;font-weight:500;color:var(--text)}
.res-pct{font-family:var(--mono);font-size:.85rem;font-weight:600}
.res-bar{height:8px;background:var(--bar-track);border-radius:8px;overflow:hidden;transition:background .35s}
.res-fill{height:100%;border-radius:8px;animation:barGrow .8s ease both;width:var(--w)}
.res-detail{font-family:var(--mono);font-size:.68rem;color:var(--text-muted);margin-top:.45rem}

/* Admin: Services */
.services{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1.5rem}
.svc{
  display:inline-flex;align-items:center;gap:.45rem;
  padding:.4rem .85rem;border-radius:8px;font-size:.75rem;font-weight:500;
  background:var(--surface);border:1px solid var(--border);
  animation:slideIn .4s ease both;transition:background .35s,border-color .35s;
}
.light .svc{box-shadow:0 1px 2px var(--shadow)}
.svc-dot{width:7px;height:7px;border-radius:50%}

/* Admin: Projects */
.projects{display:grid;grid-template-columns:repeat(auto-fill,minmax(max(320px,calc((100% - 2.55rem)/4)),1fr));gap:.85rem}
.proj{
  background:var(--surface);border:1px solid var(--border);border-radius:16px;
  transition:all .3s ease;position:relative;overflow:hidden;
  animation:fadeUp .6s ease both;display:grid;grid-template-columns:1fr auto;grid-template-rows:1fr;
}
.proj-link{display:grid;grid-template-rows:auto 1fr auto;text-decoration:none;color:inherit;padding:1.25rem;min-width:0;gap:0}
.proj-link.proj-maintenance{pointer-events:none;opacity:.55}
.proj::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,transparent,var(--accent),transparent);
  opacity:0;transition:opacity .3s;
}
.proj:hover{border-color:var(--border-hover);transform:translateY(-2px);box-shadow:0 8px 32px var(--shadow)}
.proj:hover::before{opacity:1}
.light .proj{box-shadow:0 1px 3px var(--shadow)}
.light .proj:hover{box-shadow:0 8px 24px rgba(0,0,0,.1)}
.proj-head{display:flex;align-items:center;gap:.75rem}
.proj-icon{
  width:72px;height:72px;border-radius:14px;background:var(--accent-dim);
  display:flex;align-items:center;justify-content:center;font-size:1.8rem;flex-shrink:0;
}
.proj-icon.has-logo{padding:0;overflow:hidden;background:none}
.proj-icon.has-logo img{width:72px;height:72px;object-fit:contain;border-radius:14px}
.proj-icon.has-logo img.proj-logo-dark{display:block}
.proj-icon.has-logo img.proj-logo-light{display:none}
.light .proj-icon.has-logo img.proj-logo-dark{display:none}
.light .proj-icon.has-logo img.proj-logo-light{display:block}
.proj-edit-label{font-size:.78rem;font-weight:600;display:block;margin-bottom:.35rem;color:var(--text)}
.proj-edit-section{background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:0}
.proj-edit-btn-sm{display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;padding:.3rem .6rem;border:none;border-radius:5px;cursor:pointer;font-weight:500;text-align:center;white-space:nowrap}
.proj-edit-btn-accent{background:var(--accent);color:#fff}
.proj-edit-btn-danger{background:var(--danger);color:#fff}
.proj-name{font-size:1.08rem;font-weight:600;letter-spacing:-.01em}
.proj-type{
  font-family:var(--mono);font-size:.62rem;font-weight:500;color:var(--accent);
  background:var(--accent-dim);padding:.15rem .5rem;border-radius:4px;margin-left:auto;
}
.proj-desc{font-size:.8rem;color:var(--text-muted);line-height:1.5;padding-top:.75rem}
.proj-meta{display:flex;align-items:center;gap:1.25rem;font-size:.72rem;color:var(--text-dim);padding-top:.85rem}
.proj-meta-item{display:flex;align-items:center;gap:.35rem}
.proj-status{display:flex;align-items:center;gap:.35rem;margin-left:auto;font-weight:500}
.proj-actions{display:flex;flex-direction:column;align-items:center;gap:.35rem;padding:1.25rem .75rem 1.25rem 0;flex-shrink:0;align-self:flex-start}
.proj-edit-btn{background:var(--surface-2);border:1px solid var(--border);color:var(--accent);cursor:pointer;display:flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:8px;opacity:.8;transition:opacity .2s,transform .2s,border-color .2s}
.proj-edit-btn:hover{opacity:1;transform:rotate(45deg);border-color:var(--accent)}
.proj-delete-btn{color:var(--danger);display:flex;align-items:center;opacity:.6;transition:opacity .2s}
.proj-delete-btn:hover{opacity:1}
.proj-arrow{color:var(--text-dim);transition:all .3s;font-size:1.1rem;margin-left:auto;display:inline}
.proj:hover .proj-arrow{color:var(--accent);transform:translateX(3px)}

/* ========== PIN Modal ========== */
.modal-bg{
  display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(6px);
  z-index:100;align-items:center;justify-content:center;
}
.modal-bg.open{display:flex}
.modal{
  background:var(--surface);border:1px solid var(--border);border-radius:20px;
  padding:2.5rem;width:100%;max-width:380px;animation:modalIn .3s ease both;
  text-align:center;
}
.light .modal{box-shadow:0 24px 64px rgba(0,0,0,.15)}
.modal h2{font-size:1.2rem;font-weight:600;margin-bottom:.4rem}
.modal p{font-size:.82rem;color:var(--text-muted);margin-bottom:1.5rem}
.modal-input{
  width:100%;padding:.85rem 1rem;border-radius:12px;border:1px solid var(--border);
  background:var(--surface-2);color:var(--text);font-family:var(--font);font-size:.92rem;
  outline:none;transition:border-color .25s;
}
.modal-input[type="password"]{letter-spacing:.15em}
.modal-input:focus{border-color:var(--accent)}
.modal-input.error{border-color:var(--danger);animation:shake .4s ease}
@keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-8px)}75%{transform:translateX(8px)}}
.modal-error{font-size:.75rem;color:var(--danger);margin-top:.5rem;min-height:1.2em}
.modal-success{font-size:.78rem;color:var(--success);margin-top:.5rem;text-align:center}
.modal-submit{
  width:100%;padding:.85rem;border-radius:12px;border:none;margin-top:1.25rem;
  background:var(--accent);color:#fff;font-family:var(--font);font-size:.9rem;
  font-weight:600;cursor:pointer;transition:opacity .25s;
}
.modal-submit:hover{opacity:.85}
.modal-close{
  margin-top:1rem;font-size:.78rem;color:var(--text-muted);background:none;border:none;
  cursor:pointer;font-family:var(--font);transition:color .25s;
}
.modal-close:hover{color:var(--text)}
.icon-pick-btn{background:var(--surface-2);border:1px solid var(--border);border-radius:8px;width:2rem;height:2rem;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:border-color .2s,transform .15s}
.icon-pick-btn:hover{border-color:var(--accent);transform:scale(1.15)}
.fmt-toolbar{display:flex;gap:.25rem;margin-bottom:.35rem}
.fmt-btn{background:var(--surface-2);border:1px solid var(--border);border-radius:6px;width:1.8rem;height:1.8rem;font-size:.8rem;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text);font-family:var(--font);transition:border-color .2s,background .2s}
.fmt-btn:hover{border-color:var(--accent);background:var(--surface)}
.rte-editor{width:100%;min-height:5rem;padding:.85rem 1rem;border-radius:12px;border:1px solid var(--border);background:var(--surface-2);color:var(--text);font-family:var(--font);font-size:.88rem;line-height:1.6;outline:none;transition:border-color .25s;margin-bottom:.75rem;overflow-y:auto;word-break:break-word}
.rte-editor:focus{border-color:var(--accent)}
.rte-editor:empty::before{content:attr(data-placeholder);color:var(--text-dim);font-style:italic;pointer-events:none}
.rte-editor strong{font-weight:700}
.rte-editor em{font-style:italic}
.rte-editor .rte-heading{font-size:1.05em;font-weight:700}
.ml-list{display:flex;flex-direction:column;gap:.5rem;margin-bottom:1rem}
.ml-item{display:flex;align-items:center;gap:.75rem;background:var(--card);border:1px solid var(--border);border-radius:10px;padding:.6rem .85rem;transition:border-color .2s}
.ml-item:hover{border-color:var(--accent-dim,var(--border))}
.ml-icon{font-size:1.25rem;flex-shrink:0;width:2rem;text-align:center}
.ml-info{flex:1;min-width:0}
.ml-name{font-weight:600;font-size:.85rem}
.ml-desc{font-size:.72rem;color:var(--text-dim);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex-shrink:0}
.ml-delete{color:var(--text-dim);display:flex;align-items:center;flex-shrink:0;opacity:.5;transition:opacity .2s,color .2s;margin-left:auto}
.ml-delete:hover{opacity:1;color:var(--danger)}

/* ========== User cards ========== */

/* ========== Footer ========== */
footer{
  text-align:center;margin-top:3rem;padding-top:1.5rem;
  border-top:1px solid var(--border);font-size:.7rem;color:var(--text-dim);
  transition:border-color .35s;display:flex;align-items:center;justify-content:center;gap:1rem;
}
.footer-copy a{color:var(--text-dim);text-decoration:none;transition:color .25s}
.footer-copy a:hover{color:var(--accent)}

/* Settings Bar */
.settings-bar{
  background:var(--surface);border:1px solid var(--border);border-radius:14px;
  padding:1.25rem 1.5rem;margin-bottom:2rem;animation:fadeUp .45s ease both;
  transition:background .35s,border-color .35s;
}
.light .settings-bar{box-shadow:0 1px 2px var(--shadow)}
.settings-group-label{
  font-size:.68rem;font-weight:600;color:var(--text-dim);text-transform:uppercase;letter-spacing:.1em;
  margin-top:1.1rem;padding-top:.75rem;border-top:1px solid var(--border);
}
.settings-group-label:first-of-type{margin-top:.75rem}
.settings-row{display:flex;gap:1.5rem;margin-top:.6rem;flex-wrap:wrap;align-items:flex-end}
.settings-item{display:flex;flex-direction:column;gap:.4rem}
.settings-label{font-size:.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em}
.settings-logo-actions{display:flex;gap:.5rem;align-items:center}
.settings-logo-preview{
  height:32px;width:auto;max-width:100px;object-fit:contain;border-radius:6px;
  border:1px solid var(--border);padding:3px 6px;background:var(--surface-2);
}
.settings-input-row{display:flex;gap:.5rem;align-items:center}
.settings-input{
  padding:.45rem .75rem;border-radius:8px;border:1px solid var(--border);
  background:var(--surface-2);color:var(--text);font-family:var(--mono);font-size:.8rem;
  outline:none;min-width:240px;transition:border-color .25s;
}
.settings-input:focus{border-color:var(--accent)}


/* Directory visibility toggles */
.dir-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.5rem}
.dir-toggle{display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:10px;border:1px solid var(--border);background:var(--surface-2);cursor:pointer;transition:all .3s;user-select:none}
.dir-toggle:hover{border-color:var(--border-hover)}
.dir-toggle:has(input:checked){border-color:rgba(0,190,190,.3);background:rgba(0,212,200,.08)}
.dir-toggle input{display:none}
.dir-toggle-switch{width:32px;height:18px;border-radius:9px;background:var(--border);position:relative;transition:background .3s;flex-shrink:0}
.dir-toggle-switch::after{content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;border-radius:50%;background:var(--text-dim);transition:all .3s}
.dir-toggle:has(input:checked) .dir-toggle-switch{background:var(--accent)}
.dir-toggle:has(input:checked) .dir-toggle-switch::after{left:16px;background:#fff}
.dir-toggle-icon{font-size:.85rem;opacity:.5;flex-shrink:0}
.dir-toggle:has(input:checked) .dir-toggle-icon{opacity:1}
.dir-toggle-name{font-family:var(--mono);font-size:.78rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.dir-toggle:has(input:checked) .dir-toggle-name{color:var(--text)}

/* Update UI */
.update-wrap{display:flex;align-items:center;gap:.6rem;flex-wrap:wrap}
.update-result{font-size:.78rem;display:flex;align-items:center;gap:.4rem}
.update-result.success{color:var(--success)}
.update-result.available{color:var(--accent)}
.update-result.error{color:var(--danger)}
.btn-update{
  padding:.45rem .85rem;border-radius:8px;border:1px solid var(--accent);
  background:var(--accent-dim);color:var(--accent);font-family:var(--font);font-size:.78rem;
  font-weight:600;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;gap:.35rem;
}
.btn-update:hover{background:var(--accent);color:#fff}
.btn-update:disabled{opacity:.5;cursor:not-allowed}
.btn-update.confirm{border-color:var(--success);background:rgba(16,185,129,.12);color:var(--success)}
.btn-update.confirm:hover{background:var(--success);color:#fff}
.spinner{display:inline-block;width:14px;height:14px;border:2px solid var(--accent-dim);border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
/* Update overlay */
.update-overlay{position:fixed;inset:0;background:rgba(10,15,20,.88);backdrop-filter:blur(6px);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1.2rem;opacity:0;transition:opacity .3s ease}
.update-overlay.show{opacity:1}
.update-overlay .spinner-lg{width:48px;height:48px;border:3px solid var(--accent-dim);border-top-color:var(--accent);border-radius:50%;animation:spin .8s linear infinite}
.update-overlay-text{font-size:1.1rem;font-weight:600;color:var(--text);letter-spacing:.02em}
.update-overlay-sub{font-size:.8rem;color:var(--text-dim)}
/* Startup Log */
.startup-log{margin-top:.75rem;font-size:.72rem}
.startup-log summary{cursor:pointer;color:var(--text-dim);font-size:.7rem;letter-spacing:.03em;text-transform:uppercase;font-weight:500;padding:.3rem 0;user-select:none}
.startup-log summary:hover{color:var(--text-muted)}
.startup-log-entries{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:.5rem .65rem;margin-top:.35rem;display:flex;flex-direction:column;gap:.2rem;font-family:var(--mono);font-size:.68rem;max-height:200px;overflow-y:auto}
.slog-entry{padding:.15rem 0;border-bottom:1px solid rgba(255,255,255,.03)}
.slog-entry:last-child{border-bottom:none}
.slog-ok{color:var(--success)}.slog-ok::before{content:'✓ '}
.slog-info{color:var(--text-dim)}.slog-info::before{content:'● '}
.slog-warn{color:var(--warn)}.slog-warn::before{content:'⚠ '}
.slog-err{color:var(--danger)}.slog-err::before{content:'✗ '}

.empty{text-align:center;padding:3rem;color:var(--text-muted);font-size:.9rem}

/* ========== Responsive ========== */
@media(max-width:900px){
  .hdr-sub{font-size:.65rem}
}
@media(max-width:640px){
  .wrap{padding:1rem}
  header{flex-direction:column;gap:.75rem;text-align:center}
  .hdr-left{justify-content:center}
  .hdr-host{overflow:visible;white-space:normal;text-align:center}
  .hdr-right{justify-content:center;flex-wrap:wrap}
  .stats{grid-template-columns:repeat(2,1fr)}
  .sys-bottom-row{flex-direction:column;align-items:stretch}
  .sys-bottom-row .google-search{min-width:0}
  .projects,.user-projects{grid-template-columns:1fr}
  .user-welcome h2{font-size:1.2rem}
}

/* Stagger */
.stat:nth-child(1){animation-delay:.05s}.stat:nth-child(2){animation-delay:.1s}
.stat:nth-child(3){animation-delay:.15s}.stat:nth-child(4){animation-delay:.2s}
.stat:nth-child(5){animation-delay:.25s}.stat:nth-child(6){animation-delay:.3s}
.res-card:nth-child(1){animation-delay:.15s}.res-card:nth-child(2){animation-delay:.22s}
.res-card:nth-child(3){animation-delay:.29s}
.svc:nth-child(1){animation-delay:.1s}.svc:nth-child(2){animation-delay:.15s}
.svc:nth-child(3){animation-delay:.2s}.svc:nth-child(4){animation-delay:.25s}

/* ========== Admin Tabs ========== */
.admin-tabs{
  display:flex;gap:.5rem;padding:.35rem;border-radius:14px;background:var(--surface);
  border:1px solid var(--border);margin-bottom:1.5rem;flex-wrap:wrap;
  animation:fadeUp .4s ease both;
}
.admin-tab{
  display:flex;align-items:center;gap:.45rem;padding:.55rem 1rem;border-radius:10px;
  border:none;background:transparent;color:var(--text-muted);font-family:var(--font);
  font-size:.82rem;font-weight:500;cursor:pointer;transition:all .2s ease;
  white-space:nowrap;
}
.admin-tab svg{width:16px;height:16px;opacity:.5;transition:opacity .2s}
.admin-tab:hover{color:var(--text);background:var(--surface-2)}
.admin-tab:hover svg{opacity:.8}
.admin-tab.active{background:var(--accent-dim);color:var(--accent);font-weight:600}
.admin-tab.active svg{opacity:1;stroke:var(--accent)}
.tab-panel{display:none}
.tab-panel.active{display:block;animation:fadeUp .3s ease both}
.forgot-link{display:block;text-align:center;margin-top:.75rem;font-size:.78rem;color:var(--text-muted);text-decoration:none;transition:color .25s}
.forgot-link:hover{color:var(--accent)}
.user-list{display:flex;flex-direction:column;gap:.5rem;margin-top:.75rem}
.user-card{border:1px solid var(--border);border-radius:10px;background:var(--surface-2);overflow:hidden;transition:border-color .25s}
.user-card:hover{border-color:var(--border-hover)}
.user-card-head{display:flex;align-items:center;gap:.75rem;padding:.7rem .85rem;cursor:pointer;user-select:none}
.user-card-avatar{width:34px;height:34px;border-radius:8px;background:var(--accent-dim);color:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0}
.user-card-info{flex:1;min-width:0}
.user-card-name{font-size:.88rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-card-meta{display:flex;align-items:center;gap:.6rem;font-size:.7rem;color:var(--text-dim);margin-top:.1rem;flex-wrap:wrap}
.user-card-chevron{color:var(--text-dim);transition:transform .25s;flex-shrink:0}
.user-card.open .user-card-chevron{transform:rotate(180deg)}
.user-card-edit{max-height:0;overflow:hidden;transition:max-height .3s ease,padding .3s ease;padding:0 .85rem}
.user-card.open .user-card-edit{max-height:260px;padding:.6rem .85rem .85rem;border-top:1px solid var(--border)}
@media(max-width:640px){
  .admin-tabs{display:grid;grid-template-columns:1fr 1fr 1fr}
  .admin-tab{justify-content:center;padding:.5rem .6rem;font-size:.78rem}
}
</style>
</head>
<?php
  $bgBlurPct = (int)($dashCfg['bg_blur'] ?? 10);
  $bgBright  = (int)($dashCfg['bg_brightness'] ?? 55);
  $bgBlurPx  = round($bgBlurPct * 0.2, 1);
  $bgDim     = round((100 - $bgBright) / 100, 2);
?><body<?php if ($hasBgAny): ?> class="has-bg <?= $hasBgPreset ? 'bg-preset' : 'bg-custom' ?>" style="--bg-blur:<?= $bgBlurPx ?>px;--bg-overlay-opacity:<?= $bgDim ?>"<?php endif; ?>>
<div class="wrap">

  <!-- ==================== HEADER ==================== -->
  <header>
    <div class="hdr-left">
      <div class="hdr-logos">
        <?php if ($hasAppLogoDark): ?>
          <img src="/?asset=app-logo-dark&v=<?= filemtime(DASH_APP_LOGO_DARK) ?>" alt="webdash" class="hdr-logo hdr-logo-dark">
        <?php endif; ?>
        <?php if ($hasAppLogoLight): ?>
          <img src="/?asset=app-logo-light&v=<?= filemtime(DASH_APP_LOGO_LIGHT) ?>" alt="webdash" class="hdr-logo hdr-logo-light">
        <?php endif; ?>
        <?php if ($hasAppLogoDark || $hasAppLogoLight): ?>
          <span class="hdr-logo-sep"></span>
        <?php endif; ?>
        <?php if ($hasLogoDark): ?>
          <img src="/?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>" alt="Logo" class="hdr-logo hdr-custom hdr-logo-dark">
        <?php endif; ?>
        <?php if ($hasLogoLight): ?>
          <img src="/?asset=logo-light&v=<?= filemtime(DASH_LOGO_LIGHT . '.' . $dashCfg['logo_light_ext']) ?>" alt="Logo" class="hdr-logo hdr-custom hdr-logo-light">
        <?php endif; ?>
      </div>
      <?php if ($hasAnyLogo): ?>
        <span class="hdr-logo-sep"></span>
      <?php endif; ?>
      <div class="hdr-host">
        <?= htmlspecialchars($hostname) ?>
        <div class="hdr-sub"><?= htmlspecialchars($osRelease) ?></div>
      </div>
    </div>
    <div class="hdr-right">
      <span class="hdr-clock" id="clock"></span>
      <a href="/?lang=<?= $lang === 'de' ? 'en' : 'de' ?>" class="btn-icon" title="<?= $t['toggle_lang'] ?>" style="line-height:1">
        <?= $lang === 'de' ? flagDE(22) : flagUS(22) ?>
      </a>
      <button class="btn-icon" onclick="toggleDashboardTheme()" title="<?= $t['toggle_theme'] ?>">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <a href="https://github.com/floppy007/webdash/issues" target="_blank" rel="noopener" class="btn-icon" title="<?= $lang === 'de' ? 'Fehler melden' : 'Report a bug' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </a>
      <?php if ($isAdmin): ?>
        <a href="/?logout" class="btn-link danger" title="<?= $t['logout'] ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <?= $t['logout'] ?>
        </a>
      <?php endif; ?>
    </div>
  </header>

  <?php if ($isAdmin || $showStats): ?>
  <div class="stats">
    <div class="stat"><div class="stat-value"><?= htmlspecialchars($phpVersion) ?></div><div class="stat-label"><?= $t['php_ver'] ?></div></div>
    <div class="stat"><div class="stat-value"><?= htmlspecialchars($apacheVersion) ?></div><div class="stat-label">Apache</div></div>
    <div class="stat"><div class="stat-value"><?= $cpuCores ?></div><div class="stat-label"><?= $t['cpu_cores'] ?></div></div>
    <div class="stat"><div class="stat-value"><?= fmtBytes($ramTotal) ?></div><div class="stat-label"><?= $t['ram_total'] ?></div></div>
    <div class="stat"><div class="stat-value"><?= fmtBytes($diskTotal) ?></div><div class="stat-label"><?= $t['storage_total'] ?></div></div>
    <div class="stat"><div class="stat-value" style="font-size:1.1rem"><?= htmlspecialchars($uptime ?: '—') ?></div><div class="stat-label">Uptime</div></div>
  </div>
  <?php endif; ?>

  <?php if ($isAdmin || $showResources): ?>
  <div class="resources" id="sysRes">
    <div class="res-card">
      <div class="res-top"><span class="res-name"><?= $t['cpu_load'] ?></span><span class="res-pct" id="cpuPct" style="color:<?= barColor($loadPercent) ?>"><?= $loadPercent ?>%</span></div>
      <div class="res-bar"><div class="res-fill" id="cpuBar" style="--w:<?= $loadPercent ?>%;background:<?= barColor($loadPercent) ?>"></div></div>
      <div class="res-detail" id="cpuDetail">Load: <?= implode(' / ', array_map(fn($l) => number_format($l, 2), $load)) ?> (<?= $cpuCores ?> <?= $t['cores'] ?>)</div>
    </div>
    <div class="res-card">
      <div class="res-top"><span class="res-name"><?= $t['memory'] ?></span><span class="res-pct" id="ramPct" style="color:<?= barColor($ramPercent) ?>"><?= $ramPercent ?>%</span></div>
      <div class="res-bar"><div class="res-fill" id="ramBar" style="--w:<?= $ramPercent ?>%;background:<?= barColor($ramPercent) ?>"></div></div>
      <div class="res-detail" id="ramDetail"><?= fmtBytes($ramUsed) ?> / <?= fmtBytes($ramTotal) ?> <?= $t['used'] ?></div>
    </div>
    <div class="res-card">
      <div class="res-top"><span class="res-name"><?= $t['disk'] ?></span><span class="res-pct" id="diskPct" style="color:<?= barColor($diskPercent) ?>"><?= $diskPercent ?>%</span></div>
      <div class="res-bar"><div class="res-fill" id="diskBar" style="--w:<?= $diskPercent ?>%;background:<?= barColor($diskPercent) ?>"></div></div>
      <div class="res-detail" id="diskDetail"><?= fmtBytes($diskUsed) ?> / <?= fmtBytes($diskTotal) ?> <?= $t['used'] ?> &mdash; <?= fmtBytes($diskFree) ?> <?= $t['free'] ?></div>
    </div>
  </div>
  <?php endif; ?>

  <div class="sys-bottom-row">
    <?php if (($isAdmin || $showServices) && !empty($services)): ?>
    <div class="services">
      <?php foreach ($services as $svc): ?>
      <div class="svc">
        <span class="svc-dot" style="background:<?= $svc['active'] ? 'var(--success)' : 'var(--danger)' ?>;<?= $svc['active'] ? 'box-shadow:0 0 6px var(--success)' : '' ?>"></span>
        <?= htmlspecialchars($svc['name']) ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($googleSearchEnabled) renderGoogleSearch(); ?>
  </div>

<?php if (!$isAdmin): ?>
  <!-- ==================== USER VIEW ==================== -->

  <?php if (empty($projects)): ?>
    <div class="empty"><?= $t['no_apps'] ?></div>
  <?php else: ?>
  <div class="user-projects">
    <?php foreach ($projects as $i => $proj):
      $isOnline = $proj['status'] === 'online';
    ?>
    <a href="<?= $isOnline ? htmlspecialchars($proj['url']) : '#' ?>"
       class="uproj<?= $isOnline ? '' : ' disabled' ?>"
       style="animation-delay:<?= 0.15 + $i * 0.08 ?>s"
       <?= $isOnline ? 'target="_blank" rel="noopener"' : '' ?>>
      <div class="uproj-inner">
        <div class="uproj-top">
          <?php $uHasLogo = !empty($proj['logo_dark_ext']) || !empty($proj['logo_light_ext']); ?>
          <div class="uproj-icon<?= $uHasLogo ? ' has-logo' : '' ?>"><?php if ($uHasLogo): ?><img src="/?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=dark" alt="" class="proj-logo-dark"><img src="/?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=light" alt="" class="proj-logo-light"><?php else: ?><?= !empty($proj['icon']) ? $proj['icon'] : (!empty($proj['docker']) ? "\xf0\x9f\x90\xb3" : match($proj['type']) { 'Node.js'=>"\xe2\xac\xa2",'Python'=>"\xf0\x9f\x90\x8d",'Link'=>"\xf0\x9f\x94\x97",default=>"\xe2\x9a\x99" }) ?><?php endif; ?></div>
          <div class="uproj-info">
            <div class="uproj-name"><?= htmlspecialchars(!empty($proj['display_name']) ? $proj['display_name'] : $proj['name']) ?></div>
            <div class="uproj-type"><?= htmlspecialchars(!empty($proj['docker']) ? (strlen($proj['type']) > 30 ? substr($proj['type'], 0, 30) . '...' : $proj['type']) : $proj['type']) ?></div>
          </div>
        </div>
        <?php if ($proj['description']): ?>
          <div class="uproj-desc"><?= renderDesc($proj['description']) ?></div>
        <?php endif; ?>
        <div class="uproj-foot">
          <span class="uproj-status" style="color:<?= match($proj['status']){'online'=>'var(--success)','gesperrt'=>'var(--warn)','fehler'=>'var(--danger)','maintenance'=>'var(--warn)',default=>'var(--text-dim)'} ?>">
            <?= statusDot($proj['status']) ?>
            <?= statusLabel($proj['status']) ?>
          </span>
          <?php if ($isOnline): ?>
            <span class="uproj-go"><?= $t['open'] ?> <span>&rarr;</span></span>
          <?php endif; ?>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

<?php else: ?>
  <!-- ==================== ADMIN VIEW ==================== -->

  <!-- Tab Navigation -->
  <div class="admin-tabs">
    <button class="admin-tab active" data-tab="general" onclick="switchTab('general')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      <?= $t['tab_general'] ?>
    </button>
    <button class="admin-tab" data-tab="email-users" onclick="switchTab('email-users')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      <?= $t['tab_email_users'] ?>
    </button>
    <button class="admin-tab" data-tab="server" onclick="switchTab('server')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
      <?= $t['tab_server'] ?>
    </button>
  </div>

  <!-- Tab: General -->
  <div class="tab-panel active" id="tab-general">

  <!-- Settings Bar -->
  <div class="settings-bar">
    <div class="section-title" style="margin-bottom:0"><?= $t['settings'] ?></div>

    <!-- Darstellung / Appearance: Logos -->
    <div class="settings-group-label"><?= $lang === 'de' ? 'Darstellung' : 'Appearance' ?></div>
    <div class="settings-row">
      <form method="POST" action="/" enctype="multipart/form-data" id="logoDarkForm" class="settings-item">
        <label class="settings-label"><?= $t['logo_dark'] ?></label>
        <div class="settings-logo-actions">
          <?php if ($hasLogoDark): ?>
            <img src="/?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>" alt="Logo" class="settings-logo-preview">
          <?php endif; ?>
          <label class="btn-link" style="cursor:pointer">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <?= $hasLogoDark ? $t['change'] : $t['upload_btn'] ?>
            <input type="file" name="logo_dark" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" onchange="this.form.submit()" style="display:none">
          </label>
          <?php if ($hasLogoDark): ?>
            <a href="/?remove_logo=dark" class="btn-link danger"><?= $t['remove'] ?></a>
          <?php endif; ?>
        </div>
      </form>
      <form method="POST" action="/" enctype="multipart/form-data" id="logoLightForm" class="settings-item">
        <label class="settings-label"><?= $t['logo_light'] ?></label>
        <div class="settings-logo-actions">
          <?php if ($hasLogoLight): ?>
            <img src="/?asset=logo-light&v=<?= filemtime(DASH_LOGO_LIGHT . '.' . $dashCfg['logo_light_ext']) ?>" alt="Logo" class="settings-logo-preview">
          <?php endif; ?>
          <label class="btn-link" style="cursor:pointer">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <?= $hasLogoLight ? $t['change'] : $t['upload_btn'] ?>
            <input type="file" name="logo_light" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" onchange="this.form.submit()" style="display:none">
          </label>
          <?php if ($hasLogoLight): ?>
            <a href="/?remove_logo=light" class="btn-link danger"><?= $t['remove'] ?></a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Hintergrundbild / Background image -->
    <?php
      $sBgBlur   = (int)($dashCfg['bg_blur'] ?? 10);
      $sBgBright = (int)($dashCfg['bg_brightness'] ?? 55);
    ?>
    <div class="settings-row" style="margin-top:.6rem">
      <div class="settings-item">
        <label class="settings-label"><?= $t['bg_image'] ?></label>
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
          <form method="POST" action="/" style="display:inline">
            <input type="hidden" name="save_bg_mode" value="1">
            <input type="hidden" name="bg_mode" value="<?= $hasBgPreset ? '' : 'preset' ?>">
            <button type="submit" style="cursor:pointer;border:2px solid <?= $hasBgPreset ? 'var(--accent)' : 'var(--border)' ?>;border-radius:8px;padding:2px;background:none;position:relative;transition:border-color .25s" title="<?= $lang === 'de' ? 'Standardbilder' : 'Default wallpapers' ?>">
              <div style="display:flex;gap:2px">
                <img src="/?asset=wallpaper&theme=dark" alt="Dark" style="height:36px;width:52px;object-fit:cover;border-radius:5px 0 0 5px">
                <img src="/?asset=wallpaper&theme=light" alt="Light" style="height:36px;width:52px;object-fit:cover;border-radius:0 5px 5px 0">
              </div>
              <?php if ($hasBgPreset): ?><span style="position:absolute;top:-6px;right:-6px;width:14px;height:14px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center"><svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span><?php endif; ?>
            </button>
          </form>
          <form method="POST" action="/" enctype="multipart/form-data" id="bgImageForm" style="display:inline">
            <?php if ($hasBgCustom): ?>
              <div style="display:inline-flex;border:2px solid var(--accent);border-radius:8px;padding:2px;position:relative">
                <img src="/?asset=bg-image&v=<?= filemtime(DASH_BG_IMAGE . '.' . $dashCfg['bg_image_ext']) ?>" alt="BG" style="height:36px;width:52px;object-fit:cover;border-radius:6px">
                <span style="position:absolute;top:-6px;right:-6px;width:14px;height:14px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center"><svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>
              </div>
            <?php endif; ?>
            <label class="btn-link" style="cursor:pointer">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <?= $hasBgCustom ? $t['change'] : $t['upload_btn'] ?>
              <input type="file" name="bg_image" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" onchange="this.form.submit()" style="display:none">
            </label>
          </form>
          <?php if ($hasBgAny): ?>
            <form method="POST" action="/" style="display:inline">
              <input type="hidden" name="save_bg_mode" value="1">
              <input type="hidden" name="bg_mode" value="">
              <button type="submit" class="btn-link danger" style="border:none;background:none;cursor:pointer;font-family:var(--font)"><?= $t['remove'] ?></button>
            </form>
          <?php endif; ?>
        </div>
        <?php if ($hasBgPreset): ?>
        <div style="font-size:.6rem;color:var(--text-dim);margin-top:.25rem">
          <?php $first = true; foreach (DASH_PRESET_WALLPAPERS as $theme => $wp): ?>
            <?= $first ? '' : ' &middot; ' ?><a href="<?= $wp['url'] ?>" target="_blank" rel="noopener" style="color:var(--text-dim)"><?= $wp['credit'] ?></a> (<?= $wp['source'] ?>)
          <?php $first = false; endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php if ($hasBgAny): ?>
      <form method="POST" action="/" class="settings-item" id="bgBlurForm">
        <input type="hidden" name="save_bg_effects" value="1">
        <input type="hidden" name="bg_brightness" value="<?= $sBgBright ?>">
        <label class="settings-label"><?= $t['bg_blur'] ?></label>
        <div style="display:flex;align-items:center;gap:.5rem">
          <input type="range" name="bg_blur" min="0" max="100" value="<?= $sBgBlur ?>" style="width:110px;accent-color:var(--accent)" oninput="document.body.style.setProperty('--bg-blur',(this.value*0.2)+'px');this.nextElementSibling.textContent=this.value+'%'" onchange="this.form.submit()">
          <span style="font-size:.75rem;color:var(--text-muted);min-width:30px"><?= $sBgBlur ?>%</span>
        </div>
      </form>
      <form method="POST" action="/" class="settings-item" id="bgBrightForm">
        <input type="hidden" name="save_bg_effects" value="1">
        <input type="hidden" name="bg_blur" value="<?= $sBgBlur ?>">
        <label class="settings-label"><?= $t['bg_brightness'] ?></label>
        <div style="display:flex;align-items:center;gap:.5rem">
          <input type="range" name="bg_brightness" min="0" max="100" value="<?= $sBgBright ?>" style="width:110px;accent-color:var(--accent)" oninput="document.body.style.setProperty('--bg-overlay-opacity',(100-this.value)/100);this.nextElementSibling.textContent=this.value+'%'" onchange="this.form.submit()">
          <span style="font-size:.75rem;color:var(--text-muted);min-width:30px"><?= $sBgBright ?>%</span>
        </div>
      </form>
      <?php endif; ?>
    </div>

    <!-- Funktionen / Features -->
    <div class="settings-group-label"><?= $lang === 'de' ? 'Funktionen' : 'Features' ?></div>
    <div class="settings-row">
      <form method="POST" action="/" class="settings-item">
        <label class="settings-label"><?= $t['google_search'] ?></label>
        <input type="hidden" name="save_google_search" value="1">
        <label class="dir-toggle" style="margin:0">
          <input type="checkbox" name="google_search_enabled" value="1"
                 onchange="this.form.submit()" <?= $googleSearchEnabled ? 'checked' : '' ?>>
          <span class="dir-toggle-switch"></span>
        </label>
      </form>
      <form method="POST" action="/" class="settings-item">
        <label class="settings-label"><?= $t['show_stats'] ?></label>
        <input type="hidden" name="save_dashboard_sections" value="1">
        <input type="hidden" name="show_resources" value="<?= $showResources ? '1' : '' ?>">
        <input type="hidden" name="show_services" value="<?= $showServices ? '1' : '' ?>">
        <label class="dir-toggle" style="margin:0">
          <input type="checkbox" name="show_stats" value="1"
                 onchange="this.form.submit()" <?= $showStats ? 'checked' : '' ?>>
          <span class="dir-toggle-switch"></span>
        </label>
      </form>
      <form method="POST" action="/" class="settings-item">
        <label class="settings-label"><?= $t['show_resources'] ?></label>
        <input type="hidden" name="save_dashboard_sections" value="1">
        <input type="hidden" name="show_stats" value="<?= $showStats ? '1' : '' ?>">
        <input type="hidden" name="show_services" value="<?= $showServices ? '1' : '' ?>">
        <label class="dir-toggle" style="margin:0">
          <input type="checkbox" name="show_resources" value="1"
                 onchange="this.form.submit()" <?= $showResources ? 'checked' : '' ?>>
          <span class="dir-toggle-switch"></span>
        </label>
      </form>
      <form method="POST" action="/" class="settings-item">
        <label class="settings-label"><?= $t['show_services'] ?></label>
        <input type="hidden" name="save_dashboard_sections" value="1">
        <input type="hidden" name="show_stats" value="<?= $showStats ? '1' : '' ?>">
        <input type="hidden" name="show_resources" value="<?= $showResources ? '1' : '' ?>">
        <label class="dir-toggle" style="margin:0">
          <input type="checkbox" name="show_services" value="1"
                 onchange="this.form.submit()" <?= $showServices ? 'checked' : '' ?>>
          <span class="dir-toggle-switch"></span>
        </label>
      </form>
    </div>

    <!-- System -->
    <div class="settings-group-label">System</div>
    <div class="settings-row">
      <?php if ($dockerMode): ?>
      <div class="settings-item">
        <label class="settings-label"><?= $t['docker_mode'] ?></label>
        <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .7rem;border-radius:8px;background:var(--accent-dim);border:1px solid var(--border);font-size:.78rem;color:var(--accent);font-weight:500">🐳 <?= $t['docker_mode'] ?></span>
      </div>
      <?php endif; ?>
      <div class="settings-item">
        <label class="settings-label">Update (v<?= WEBDASH_VERSION ?>)</label>
        <div class="update-wrap" id="updateWrap">
          <?php if ($dockerMode): ?>
          <span id="updateResult"><span class="spinner"></span></span>
          <?php else: ?>
          <button type="button" class="btn-update" id="btnCheckUpdate" onclick="checkUpdate()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15"/></svg>
            <?= $t['check_update'] ?>
          </button>
          <span id="updateResult"></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php if (!empty($_startupLog)): ?>
    <details class="startup-log">
      <summary>Startup-Log (<?= count($_startupLog) ?>)</summary>
      <div class="startup-log-entries">
        <?php foreach ($_startupLog as $entry): ?>
          <div class="slog-entry slog-<?= $entry[0] ?>"><?= htmlspecialchars($entry[1]) ?></div>
        <?php endforeach; ?>
      </div>
    </details>
    <?php endif; ?>
  </div>


  </div><!-- /tab-general -->

  <!-- Tab: E-Mail & Benutzer / Email & Users -->
  <div class="tab-panel" id="tab-email-users">

  <!-- SMTP Settings -->
  <?php
    $smtpMsg = $_SESSION['dashboard_smtp_msg'] ?? null; unset($_SESSION['dashboard_smtp_msg']);
    $smtpHost = $dashCfg['smtp_host'] ?? '';
    $smtpPort = $dashCfg['smtp_port'] ?? 587;
    $smtpEnc  = $dashCfg['smtp_encryption'] ?? 'tls';
    $smtpUser = $dashCfg['smtp_user'] ?? '';
    $smtpPass = $dashCfg['smtp_pass'] ?? '';
    $smtpFrom = $dashCfg['smtp_from'] ?? '';
    $smtpFromName = $dashCfg['smtp_from_name'] ?? '';
  ?>
  <div class="settings-bar">
    <div class="section-title" style="margin-bottom:0"><?= $t['smtp_config'] ?></div>
    <?php if ($smtpMsg): ?>
      <div style="margin-top:.5rem;font-size:.78rem;color:<?= $smtpMsg[0] === 'ok' ? 'var(--success)' : 'var(--danger)' ?>"><?= htmlspecialchars($smtpMsg[1]) ?></div>
    <?php endif; ?>
    <div class="settings-row">
      <form method="POST" action="/" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;width:100%">
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_host'] ?></label>
          <input type="text" name="cfg_smtp_host" value="<?= htmlspecialchars($smtpHost) ?>" class="settings-input" style="min-width:160px" placeholder="smtp.example.com">
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_port'] ?></label>
          <input type="number" name="cfg_smtp_port" value="<?= (int)$smtpPort ?>" class="settings-input" style="min-width:80px;width:80px" placeholder="587">
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_encryption'] ?></label>
          <select name="cfg_smtp_encryption" class="settings-input" style="min-width:90px;width:90px">
            <option value="tls"<?= $smtpEnc === 'tls' ? ' selected' : '' ?>>STARTTLS</option>
            <option value="ssl"<?= $smtpEnc === 'ssl' ? ' selected' : '' ?>>SSL</option>
            <option value="none"<?= $smtpEnc === 'none' ? ' selected' : '' ?>>None</option>
          </select>
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_user'] ?></label>
          <input type="text" name="cfg_smtp_user" value="<?= htmlspecialchars($smtpUser) ?>" class="settings-input" style="min-width:160px" placeholder="user@example.com" autocomplete="smtp-account" data-1p-ignore data-lpignore="true" data-bwignore>
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_pass'] ?></label>
          <input type="text" name="cfg_smtp_pass" value="" class="settings-input" style="min-width:120px;-webkit-text-security:disc" autocomplete="smtp-credential" data-1p-ignore data-lpignore="true" data-bwignore placeholder="<?= $smtpPass ? '••••••' : '' ?>">
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_from'] ?></label>
          <input type="email" name="cfg_smtp_from" value="<?= htmlspecialchars($smtpFrom) ?>" class="settings-input" style="min-width:160px" placeholder="noreply@example.com">
        </div>
        <div class="settings-item">
          <label class="settings-label"><?= $t['smtp_from_name'] ?></label>
          <input type="text" name="cfg_smtp_from_name" value="<?= htmlspecialchars($smtpFromName) ?>" class="settings-input" style="min-width:120px" placeholder="webdash">
        </div>
        <button type="submit" class="btn-link"><?= $t['smtp_save'] ?></button>
      </form>
    </div>
    <?php if ($smtpHost): ?>
    <form method="POST" action="/" style="margin-top:.75rem">
      <input type="hidden" name="smtp_test_send" value="1">
      <button type="submit" class="btn-update">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/></svg>
        <?= $t['smtp_test'] ?>
      </button>
    </form>
    <?php endif; ?>
  </div>

  <!-- User Management / Benutzerverwaltung -->
  <?php
    $userMsg = $_SESSION['dashboard_user_msg'] ?? null; unset($_SESSION['dashboard_user_msg']);
    $pwMsg   = $_SESSION['dashboard_pw_msg'] ?? null; unset($_SESSION['dashboard_pw_msg']);
    $cfgUsers = $dashCfg['users'] ?? [];
  ?>
  <div class="settings-bar">
    <div class="section-title" style="margin-bottom:0"><?= $t['users'] ?> (<?= count($cfgUsers) + 1 ?>)</div>
    <?php if ($userMsg): ?>
      <div style="margin-top:.5rem;font-size:.78rem;color:<?= $userMsg[0] === 'ok' ? 'var(--success)' : 'var(--danger)' ?>"><?= htmlspecialchars($userMsg[1]) ?></div>
    <?php endif; ?>
    <?php if ($pwMsg): ?>
      <div style="margin-top:.5rem;font-size:.78rem;color:<?= $pwMsg[0] === 'ok' ? 'var(--success)' : 'var(--danger)' ?>"><?= htmlspecialchars($pwMsg[1]) ?></div>
    <?php endif; ?>
      <div class="user-list">
        <!-- Admin-User (config.json admin_pass) -->
        <?php $adminEmail = $dashCfg['admin_email'] ?? ''; ?>
        <div class="user-card" id="ucA">
          <div class="user-card-head" onclick="toggleUserEdit('A')">
            <div class="user-card-avatar">A</div>
            <div class="user-card-info">
              <div class="user-card-name">Admin <span style="font-size:.65rem;color:var(--accent)">(<?= $lang === 'de' ? 'Hauptadmin' : 'Main admin' ?>)</span></div>
              <div class="user-card-meta">
                <span style="font-family:var(--mono)">admin</span>
                <?php if ($adminEmail): ?><span><?= htmlspecialchars($adminEmail) ?></span><?php endif; ?>
              </div>
            </div>
            <svg class="user-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="user-card-edit" id="ueA">
            <form method="POST" action="/" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
              <input type="hidden" name="save_admin_profile" value="1">
              <div class="settings-item">
                <label class="settings-label"><?= $t['email'] ?></label>
                <input type="email" name="admin_email" value="<?= htmlspecialchars($adminEmail) ?>" class="settings-input" style="min-width:200px" placeholder="admin@example.com">
              </div>
              <div class="settings-item">
                <label class="settings-label"><?= $t['users_new_pw'] ?></label>
                <input type="password" name="new_admin_pw" class="settings-input" style="min-width:160px" minlength="4" autocomplete="new-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;">
              </div>
              <div class="settings-item">
                <label class="settings-label"><?= $t['confirm_password'] ?></label>
                <input type="password" name="confirm_admin_pw" class="settings-input" style="min-width:160px" autocomplete="new-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;">
              </div>
              <button type="submit" class="btn-update" style="height:34px">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <?= $t['users_save'] ?>
              </button>
            </form>
          </div>
        </div>
        <?php foreach ($cfgUsers as $idx => $u): ?>
        <div class="user-card" id="uc<?= $idx ?>">
          <div class="user-card-head" onclick="toggleUserEdit(<?= $idx ?>)">
            <div class="user-card-avatar"><?= strtoupper(mb_substr($u['name'] ?? $u['username'], 0, 1)) ?></div>
            <div class="user-card-info">
              <div class="user-card-name"><?= htmlspecialchars($u['name'] ?? $u['username']) ?></div>
              <div class="user-card-meta">
                <span style="font-family:var(--mono)"><?= htmlspecialchars($u['username']) ?></span>
                <?php if (!empty($u['email'])): ?><span><?= htmlspecialchars($u['email']) ?></span><?php endif; ?>
              </div>
            </div>
            <svg class="user-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="user-card-edit" id="ue<?= $idx ?>">
            <form method="POST" action="/" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
              <input type="hidden" name="edit_user_idx" value="<?= $idx ?>">
              <div class="settings-item">
                <label class="settings-label"><?= $t['users_name'] ?></label>
                <input type="text" name="edit_user_name" value="<?= htmlspecialchars($u['name'] ?? '') ?>" class="settings-input" style="min-width:150px">
              </div>
              <div class="settings-item">
                <label class="settings-label"><?= $t['email'] ?></label>
                <input type="email" name="edit_user_email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" class="settings-input" style="min-width:180px" placeholder="user@example.com">
              </div>
              <div class="settings-item">
                <label class="settings-label"><?= $t['users_new_pw'] ?></label>
                <input type="password" name="edit_user_password" class="settings-input" style="min-width:150px" minlength="4" autocomplete="new-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;">
              </div>
              <button type="submit" class="btn-update" style="height:34px">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <?= $t['users_save'] ?>
              </button>
              <a href="/?delete_user=<?= $idx ?>" class="btn-link danger" style="font-size:.72rem;margin-left:auto" onclick="return confirm('<?= $t['users_delete_confirm'] ?>')"><?= $t['users_delete'] ?></a>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <div class="section-title" style="margin-top:1rem;margin-bottom:.5rem"><?= $t['users_add'] ?></div>
    <form method="POST" action="/" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
      <div class="settings-item">
        <label class="settings-label"><?= $t['users_name'] ?></label>
        <input type="text" name="add_user_name" class="settings-input" style="min-width:140px" required>
      </div>
      <div class="settings-item">
        <label class="settings-label"><?= $t['username'] ?></label>
        <input type="text" name="add_user_username" class="settings-input" style="min-width:120px" required>
      </div>
      <div class="settings-item">
        <label class="settings-label"><?= $t['email'] ?></label>
        <input type="email" name="add_user_email" class="settings-input" style="min-width:160px" placeholder="user@example.com">
      </div>
      <div class="settings-item">
        <label class="settings-label"><?= $t['password'] ?></label>
        <input type="password" name="add_user_password" class="settings-input" style="min-width:120px" required minlength="4" autocomplete="new-password">
      </div>
      <button type="submit" class="btn-update"><?= $t['users_add'] ?></button>
    </form>
  </div>

  </div><!-- /tab-email-users -->

  <!-- Tab: Server -->
  <div class="tab-panel" id="tab-server">

  <?php if ($dockerMode && !empty($allContainers)): ?>
  <div class="settings-bar" style="margin-top:.5rem;margin-bottom:1.2rem">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:.75rem">
      <div>
        <div class="section-title" style="margin-bottom:.15rem"><?= $t['visible_containers'] ?></div>
        <p style="font-size:.72rem;color:var(--text-dim)"><?= $t['visible_containers_hint'] ?></p>
      </div>
      <div style="font-size:.72rem;color:var(--text-dim);font-family:var(--mono)"><?= $hasContainerConf ? count($includeContainers) : count($allContainers) ?>/<?= count($allContainers) ?></div>
    </div>
    <form method="POST" action="/" id="containerForm">
      <input type="hidden" name="save_include_containers" value="1">
      <div class="dir-grid">
        <?php foreach ($allContainers as $ct): ?>
        <label class="dir-toggle">
          <input type="checkbox" name="include_containers[]" value="<?= htmlspecialchars($ct['name']) ?>"<?= (!$hasContainerConf || in_array($ct['name'], $includeContainers, true)) ? ' checked' : '' ?> onchange="document.getElementById('containerForm').submit()">
          <span class="dir-toggle-switch"></span>
          <span class="dir-toggle-icon"><?= "\xf0\x9f\x90\xb3" ?></span>
          <span class="dir-toggle-name"><?= htmlspecialchars(!empty($ct['display_name']) ? $ct['display_name'] : $ct['name']) ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <?php if (!empty($allDirs)): ?>
  <div class="settings-bar" style="margin-top:.5rem;margin-bottom:1.2rem">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:.75rem">
      <div>
        <div class="section-title" style="margin-bottom:.15rem"><?= $t['visible_dirs'] ?></div>
        <p style="font-size:.72rem;color:var(--text-dim)"><?= $t['visible_dirs_hint'] ?></p>
      </div>
      <div style="font-size:.72rem;color:var(--text-dim);font-family:var(--mono)"><?= $hasIncludeConf ? count($includeDirs) : count($allDirs) ?>/<?= count($allDirs) ?></div>
    </div>
    <form method="POST" action="/" id="dirForm">
      <input type="hidden" name="save_include_dirs" value="1">
      <div class="dir-grid">
        <?php foreach ($allDirs as $d): ?>
        <label class="dir-toggle">
          <input type="checkbox" name="include_dirs[]" value="<?= htmlspecialchars($d) ?>"<?= (!$hasIncludeConf || in_array($d, $includeDirs, true)) ? ' checked' : '' ?> onchange="document.getElementById('dirForm').submit()">
          <span class="dir-toggle-switch"></span>
          <span class="dir-toggle-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
          </span>
          <span class="dir-toggle-name"><?= htmlspecialchars($d) ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <div class="section-title"><?= $dockerMode ? $t['docker_containers'] : $t['projects'] ?> (<?= count($projects) ?>)</div>
  <?php if (!empty($projects)): ?>
  <div class="projects">
    <?php foreach ($projects as $i => $proj):
      $projOnline = $proj['status'] !== 'maintenance';
    ?>
    <div class="proj" style="animation-delay:<?= 0.1 + $i * 0.08 ?>s">
      <a href="<?= $projOnline ? htmlspecialchars($proj['url']) : '#' ?>" class="proj-link<?= $projOnline ? '' : ' proj-maintenance' ?>" <?= $projOnline ? 'target="_blank" rel="noopener"' : '' ?>>
        <div class="proj-head">
          <?php $hasLogo = !empty($proj['logo_dark_ext']) || !empty($proj['logo_light_ext']); ?>
          <div class="proj-icon<?= $hasLogo ? ' has-logo' : '' ?>"><?php if ($hasLogo): ?><img src="/?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=dark" alt="" class="proj-logo-dark"><img src="/?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=light" alt="" class="proj-logo-light"><?php else: ?><?= !empty($proj['icon']) ? $proj['icon'] : (!empty($proj['docker']) ? "\xf0\x9f\x90\xb3" : match($proj['type']) { 'Node.js'=>"\xe2\xac\xa2",'Python'=>"\xf0\x9f\x90\x8d",'Link'=>"\xf0\x9f\x94\x97",default=>"\xe2\x9a\x99" }) ?><?php endif; ?></div>
          <span class="proj-name"><?= htmlspecialchars(!empty($proj['display_name']) ? $proj['display_name'] : $proj['name']) ?></span>
          <span class="proj-type"><?= htmlspecialchars(!empty($proj['docker']) ? (strlen($proj['type']) > 30 ? substr($proj['type'], 0, 30) . '...' : $proj['type']) : $proj['type']) ?></span>
        </div>
        <?php if ($proj['description']): ?>
          <div class="proj-desc"><?= renderDesc($proj['description']) ?></div>
        <?php endif; ?>
        <div class="proj-meta">
          <?php if (!empty($proj['docker'])): ?>
          <span class="proj-meta-item" title="<?= $t['docker_container_id'] ?>">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
            <?= htmlspecialchars($proj['container_id'] ?? '') ?> &mdash; <?= htmlspecialchars($proj['docker_status'] ?? '') ?>
          </span>
          <?php else: ?>
          <span class="proj-meta-item">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <?= date('d.m.Y H:i', $proj['lastModified']) ?>
          </span>
          <?php endif; ?>
          <span class="proj-status" style="color:<?= match($proj['status']){'online'=>'var(--success)','gesperrt'=>'var(--warn)','fehler'=>'var(--danger)','maintenance'=>'var(--warn)',default=>'var(--text-dim)'} ?>">
            <?= statusDot($proj['status']) ?>
            <?= statusLabel($proj['status']) ?>
          </span>
          <span class="proj-arrow">&rarr;</span>
        </div>
      </a>
      <div class="proj-actions">
        <button type="button" class="proj-edit-btn" onclick="editProjectDesc(<?= htmlspecialchars(json_encode($proj['name']), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['description'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['icon'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['display_name'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode(!empty($proj['logo_dark_ext']) ? $proj['logo_dark_ext'] : ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode(!empty($proj['logo_light_ext']) ? $proj['logo_light_ext'] : ''), ENT_QUOTES) ?>,<?= $proj['status'] === 'maintenance' ? 'true' : 'false' ?>)" title="<?= $t['project_edit'] ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
        </button>
        <?php if (!empty($proj['manual'])): ?>
          <a href="/?delete_manual_link=<?= $proj['manual_index'] ?>" class="proj-delete-btn" onclick="return confirm('<?= $t['manual_link_delete_confirm'] ?>')" title="<?= $t['remove'] ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="modal-bg" id="descModal">
    <div class="modal" style="text-align:left;max-width:500px">
      <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem">
        <h2 style="margin:0;flex:1"><?= $t['project_edit'] ?></h2>
        <span id="descModalName" style="font-size:.72rem;color:var(--text-dim);font-family:var(--mono)"></span>
      </div>
      <form method="POST" action="/" id="descForm" enctype="multipart/form-data" target="_self" onsubmit="syncEditor('descEditor','descFormDesc');closeDescModal()">
        <input type="hidden" name="save_project_desc" value="1">
        <input type="hidden" name="project_name" id="descFormName">
        <!-- bg inputs are inside projLogoBgArea -->

        <!-- Name -->
        <label class="proj-edit-label"><?= $t['manual_link_name'] ?></label>
        <input type="text" name="project_title" id="descFormTitle" class="modal-input modal-input-text" style="margin-bottom:1.25rem">

        <!-- Logos (Dark + Light) -->
        <label class="proj-edit-label"><?= $lang === 'de' ? 'Projekt-Logo' : 'Project Logo' ?></label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:1rem">
          <?php foreach (['dark' => ($lang === 'de' ? 'Dark Mode' : 'Dark Mode'), 'light' => ($lang === 'de' ? 'Light Mode' : 'Light Mode')] as $lv => $ll): ?>
          <div class="proj-edit-section" style="padding:.75rem">
            <div style="font-size:.68rem;color:var(--text-dim);margin-bottom:.5rem;display:flex;align-items:center;gap:.3rem">
              <?= $lv === 'dark' ? '🌙' : '☀️' ?> <?= $ll ?>
            </div>
            <!-- Hat Logo -->
            <div id="projLogo_<?= $lv ?>_has" style="display:none;text-align:center">
              <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;background:<?= $lv === 'dark' ? '#1a1a2e' : '#f0f0f4' ?>;margin-bottom:.4rem">
                <img id="projLogo_<?= $lv ?>_img" src="" alt="" style="width:64px;height:64px;object-fit:contain;display:block">
              </div>
              <div style="display:flex;gap:.3rem;justify-content:center">
                <label class="proj-edit-btn-sm proj-edit-btn-accent" style="font-size:.6rem;padding:.2rem .4rem">
                  <?= $lang === 'de' ? 'Ändern' : 'Change' ?>
                  <input type="file" name="project_logo_<?= $lv ?>" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" style="display:none" onchange="previewProjLogo('<?= $lv ?>',this)">
                </label>
                <button type="button" class="proj-edit-btn-sm proj-edit-btn-danger" style="font-size:.6rem;padding:.2rem .4rem" onclick="removeProjLogo('<?= $lv ?>')">
                  <?= $lang === 'de' ? 'Entfernen' : 'Remove' ?>
                </button>
              </div>
            </div>
            <!-- Kein Logo -->
            <div id="projLogo_<?= $lv ?>_none" style="display:none">
              <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.3rem;padding:.75rem .5rem;border:2px dashed var(--border);border-radius:8px;cursor:pointer;transition:border-color .2s" onmouseenter="this.style.borderColor='var(--accent)'" onmouseleave="this.style.borderColor='var(--border)'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <span style="font-size:.65rem;color:var(--text-dim)"><?= $lang === 'de' ? 'Hochladen' : 'Upload' ?></span>
                <input type="file" name="project_logo_<?= $lv ?>" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" style="display:none" onchange="previewProjLogo('<?= $lv ?>',this)">
              </label>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="font-size:.65rem;color:var(--text-dim);margin:-0.7rem 0 .75rem;text-align:center"><?= $lang === 'de' ? 'Nur ein Logo nötig? Das andere wird automatisch übernommen.' : 'Only one logo needed? The other will be used automatically.' ?></div>

        <!-- Icon -->
        <div id="iconPickerWrap" style="margin-bottom:1rem">
          <label class="proj-edit-label"><?= $t['project_edit_icon'] ?></label>
          <div style="display:flex;gap:.5rem;align-items:center">
            <input type="text" name="project_icon" id="descFormIcon" class="modal-input modal-input-text" style="width:3.5rem;text-align:center;font-size:1.3rem;padding:.5rem;flex-shrink:0" maxlength="4">
            <div style="display:flex;flex-wrap:wrap;gap:.3rem">
              <?php foreach (['⚙','🔗','📊','📁','🛒','💬','📧','🏠','🔒','📝','🌐','📱','🎯','💾','☁'] as $em): ?>
                <button type="button" class="icon-pick-btn" onclick="document.getElementById('descFormIcon').value='<?= $em ?>'"><?= $em ?></button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Beschreibung -->
        <label class="proj-edit-label" style="margin-top:1.25rem"><?= $t['project_edit_desc'] ?></label>
        <div class="fmt-toolbar">
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('bold')" title="<?= $lang === 'de' ? 'Fett' : 'Bold' ?>"><strong>B</strong></button>
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('italic')" title="<?= $lang === 'de' ? 'Kursiv' : 'Italic' ?>"><em>I</em></button>
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="fmtHeading('descEditor')" title="<?= $lang === 'de' ? '&Uuml;berschrift' : 'Heading' ?>">H</button>
        </div>
        <input type="hidden" name="project_desc" id="descFormDesc">
        <div class="rte-editor" id="descEditor" contenteditable="true" data-placeholder="<?= $lang === 'de' ? 'Beschreibung eingeben...' : 'Enter description...' ?>"></div>
        <label style="display:flex;align-items:center;gap:.5rem;margin:.75rem 0;font-size:.8rem;cursor:pointer"><input type="checkbox" name="project_maintenance" id="descFormMaintenance" value="1"> <?= $lang === 'de' ? 'Wartungsmodus' : 'Maintenance mode' ?></label>
        <button type="submit" class="modal-submit"><?= $t['save'] ?></button>
      </form>
      <button class="modal-close" onclick="closeDescModal()"><?= $t['cancel'] ?></button>
    </div>
  </div>
  <?php else: ?>
    <div class="empty" style="font-size:.85rem;color:var(--text-dim);margin:.75rem 0"><?= $dockerMode ? $t['docker_no_containers'] : ($lang === 'de' ? 'Keine Verzeichnisse ausgew&auml;hlt. W&auml;hle unter &bdquo;Allgemein&ldquo; die anzuzeigenden Verzeichnisse aus.' : 'No directories selected. Choose which directories to display in the General tab.') ?></div>
  <?php endif; ?>

  <!-- Manuelle Links -->
  <?php
    $manualMsg = $_SESSION['dashboard_manual_msg'] ?? null; unset($_SESSION['dashboard_manual_msg']);
    $manualLinks = $dashCfg['manual_links'] ?? [];
  ?>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1.5rem;margin-bottom:.75rem">
    <div class="section-title" style="margin-bottom:0"><?= $t['manual_links'] ?></div>
    <button type="button" class="btn-update" onclick="openAddLinkModal()" style="font-size:.78rem;padding:.45rem 1rem">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      <?= $t['manual_link_add'] ?>
    </button>
  </div>
  <?php if ($manualMsg): ?>
    <div style="margin-bottom:.5rem;font-size:.78rem;color:<?= $manualMsg[0] === 'ok' ? 'var(--success)' : 'var(--danger)' ?>"><?= htmlspecialchars($manualMsg[1]) ?></div>
  <?php endif; ?>

  <?php if (!empty($manualLinks)): ?>
  <div class="ml-list">
    <?php foreach ($manualLinks as $mi => $ml): ?>
    <div class="ml-item">
      <?php $mlHasLogo = !empty($dashCfg['project_logo_dark_ext'][$ml['name']]) || !empty($dashCfg['project_logo_light_ext'][$ml['name']]); ?>
      <span class="ml-icon"><?php if ($mlHasLogo): ?><img src="/?asset=project-logo&name=<?= urlencode($ml['name']) ?>&variant=dark" alt="" class="proj-logo-dark" style="width:1.3em;height:1.3em;object-fit:contain;border-radius:3px"><img src="/?asset=project-logo&name=<?= urlencode($ml['name']) ?>&variant=light" alt="" class="proj-logo-light" style="width:1.3em;height:1.3em;object-fit:contain;border-radius:3px"><?php else: ?><?= !empty($dashCfg['project_icons'][$ml['name']]) ? $dashCfg['project_icons'][$ml['name']] : "\xf0\x9f\x94\x97" ?><?php endif; ?></span>
      <div class="ml-info">
        <div class="ml-name"><?= htmlspecialchars(!empty($dashCfg['project_titles'][$ml['name']]) ? $dashCfg['project_titles'][$ml['name']] : $ml['name']) ?></div>
      </div>
      <?php if (!empty($ml['description'])): ?>
        <div class="ml-desc"><?= renderDesc($ml['description']) ?></div>
      <?php endif; ?>
      <a href="/?delete_manual_link=<?= $mi ?>" onclick="return confirm('<?= $t['manual_link_delete_confirm'] ?>')" class="ml-delete" title="<?= $t['remove'] ?>">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <div style="font-size:.82rem;color:var(--text-dim);padding:.75rem 0"><?= $t['manual_links_hint'] ?></div>
  <?php endif; ?>

  <!-- Add-Link Modal -->
  <div class="modal-bg" id="addLinkModal">
    <div class="modal" style="text-align:left;max-width:440px">
      <h2><?= $t['manual_link_add'] ?></h2>
      <form method="POST" action="/" id="addLinkForm" onsubmit="syncEditor('mlEditor','mlDesc')">
        <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:.35rem"><?= $t['manual_link_name'] ?> *</label>
        <input type="text" name="manual_link_name" id="mlName" class="modal-input modal-input-text" style="margin-bottom:.75rem" required placeholder="My App">
        <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:.35rem"><?= $t['manual_link_url'] ?> *</label>
        <input type="url" name="manual_link_url" id="mlUrl" class="modal-input modal-input-text" style="margin-bottom:.75rem" required placeholder="https://example.com">
        <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:.35rem"><?= $t['manual_link_desc'] ?></label>
        <div class="fmt-toolbar">
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('bold')" title="<?= $lang === 'de' ? 'Fett' : 'Bold' ?>"><strong>B</strong></button>
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('italic')" title="<?= $lang === 'de' ? 'Kursiv' : 'Italic' ?>"><em>I</em></button>
          <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="fmtHeading('mlEditor')" title="<?= $lang === 'de' ? '&Uuml;berschrift' : 'Heading' ?>">H</button>
        </div>
        <input type="hidden" name="manual_link_desc" id="mlDesc">
        <div class="rte-editor" id="mlEditor" contenteditable="true" data-placeholder="<?= $lang === 'de' ? 'z.B. Firmen-Wiki, Zeiterfassung' : 'e.g. Company wiki, time tracking' ?>"></div>
        <button type="submit" name="add_manual_link" value="1" class="modal-submit"><?= $t['manual_link_add'] ?></button>
      </form>
      <button class="modal-close" onclick="closeAddLinkModal()"><?= $t['cancel'] ?></button>
    </div>
  </div>

  </div><!-- /tab-server -->

<?php endif; ?>

  <!-- ==================== FOOTER ==================== -->
  <footer>
    <span>webdash v<?= WEBDASH_VERSION ?> &middot; <?= htmlspecialchars($hostname) ?> &middot; <?= date('d.m.Y') ?></span>
    <?= _wd_a() ?>
    <?php if (!$isAdmin): ?>
      <button class="btn-link" onclick="openAdminModal()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        <?= $t['admin'] ?>
      </button>
    <?php endif; ?>
  </footer>

</div>

<!-- ==================== LOGIN MODAL ==================== -->
<?php if (!$isAdmin): ?>
<div class="modal-bg" id="adminModal">
  <div class="modal">
    <h2><?= $t['admin_access'] ?></h2>
    <p><?= $t['admin_desc'] ?></p>
    <form method="POST" action="/">
      <input type="text" name="username" class="modal-input modal-input-text<?= $loginError ? ' error' : '' ?>" id="loginUser"
             autocomplete="username" placeholder="<?= $t['username'] ?>" required>
      <input type="password" name="password" class="modal-input<?= $loginError ? ' error' : '' ?>" id="loginPass"
             autocomplete="current-password" placeholder="<?= $t['password'] ?>" style="margin-top:.6rem" required>
      <div class="modal-error"><?= htmlspecialchars($loginError) ?></div>
      <button type="submit" class="modal-submit"><?= $t['login'] ?></button>
      <?php $smtpConfigured = !empty($dashCfg['smtp_host'] ?? ''); ?>
      <?php if ($smtpConfigured): ?>
        <a href="/?action=forgot_password" class="forgot-link"><?= $t['forgot_pw'] ?></a>
      <?php endif; ?>
    </form>
    <?php $resetSuccess = !empty($_SESSION['dashboard_reset_success']); unset($_SESSION['dashboard_reset_success']); ?>
    <?php if ($resetSuccess): ?>
      <div style="font-size:.8rem;color:var(--success);text-align:center;margin-top:.5rem"><?= $t['reset_pw_success'] ?></div>
    <?php endif; ?>
    <button class="modal-close" onclick="closeAdminModal()"><?= $t['cancel'] ?></button>
  </div>
</div>
<?php endif; ?>

<script>
function updateClock(){
  var n=new Date();
  var loc='<?= $lang === 'de' ? 'de-DE' : 'en-US' ?>';
  var t=n.toLocaleTimeString(loc,{hour:'2-digit',minute:'2-digit',second:'2-digit'});
  var d=n.toLocaleDateString(loc,{weekday:'short',day:'2-digit',month:'short',year:'numeric'});
  document.getElementById('clock').textContent=d+' \u2014 '+t;
}
updateClock();setInterval(updateClock,1000);

function toggleDashboardTheme(){
  var r=document.documentElement;
  var l=r.classList.toggle('light');
  localStorage.setItem('dashboard-theme',l?'light':'dark');
}

<?php if ($isAdmin): ?>
function switchTab(name){
  var panels=document.querySelectorAll('.tab-panel');
  var tabs=document.querySelectorAll('.admin-tab');
  for(var i=0;i<panels.length;i++)panels[i].classList.remove('active');
  for(var i=0;i<tabs.length;i++)tabs[i].classList.remove('active');
  var panel=document.getElementById('tab-'+name);
  if(panel)panel.classList.add('active');
  var tab=document.querySelector('.admin-tab[data-tab="'+name+'"]');
  if(tab)tab.classList.add('active');
  localStorage.setItem('webdash-admin-tab',name);
}
function toggleUserEdit(idx){var c=document.getElementById('uc'+idx);c.classList.toggle('open')}
<?php
  // Determine forced tab from flash messages / Erzwungenen Tab aus Flash-Nachrichten bestimmen
  $forceTab = '';
  if (!empty($manualMsg)) $forceTab = 'server';
  if (!empty($smtpMsg) || !empty($userMsg) || !empty($pwMsg)) $forceTab = 'email-users';
?>
(function(){
  var forced=<?= json_encode($forceTab) ?>;
  var tab=forced||localStorage.getItem('webdash-admin-tab')||'general';
  switchTab(tab);
})();
function md2html(t){
  t=t.replace(/&/g,'&amp;').replace(/\x3c/g,'&lt;').replace(/>/g,'&gt;');
  t=t.replace(/^## (.+)$/gm,'\x3cspan class="rte-heading">$1\x3c/span>');
  t=t.replace(/\*\*(.+?)\*\*/g,'\x3cstrong>$1\x3c/strong>');
  t=t.replace(/\*(.+?)\*/g,'\x3cem>$1\x3c/em>');
  t=t.replace(/\n/g,'\x3cbr>');
  return t;
}
function html2md(el){
  var c=el.cloneNode(true);
  c.querySelectorAll('.rte-heading').forEach(function(h){h.replaceWith('## '+h.textContent);});
  c.querySelectorAll('strong,b').forEach(function(s){s.replaceWith('**'+s.textContent+'**');});
  c.querySelectorAll('em,i').forEach(function(e){e.replaceWith('*'+e.textContent+'*');});
  c.querySelectorAll('br').forEach(function(b){b.replaceWith('\n');});
  c.querySelectorAll('div,p').forEach(function(d){if(d.previousSibling)d.insertAdjacentText('beforebegin','\n');});
  return c.textContent.trim();
}
function syncEditor(editorId,hiddenId){
  var el=document.getElementById(editorId);
  document.getElementById(hiddenId).value=html2md(el);
}
function fmtHeading(editorId){
  var el=document.getElementById(editorId);
  var sel=window.getSelection();
  if(!sel.rangeCount)return;
  var range=sel.getRangeAt(0);
  var text=range.toString();
  if(text){
    var span=document.createElement('span');
    span.className='rte-heading';
    span.textContent=text;
    range.deleteContents();
    range.insertNode(span);
    sel.collapseToEnd();
  }
  el.focus();
}
var _projName='';
function toggleIconPicker(hasAnyLogo){
  var el=document.getElementById('iconPickerWrap');
  if(el) el.style.display=hasAnyLogo?'none':'block';
}
function _checkAnyLogo(){
  var d=document.getElementById('projLogo_dark_has').style.display!=='none';
  var l=document.getElementById('projLogo_light_has').style.display!=='none';
  toggleIconPicker(d||l);
}
function editProjectDesc(name,current,icon,title,logoDarkExt,logoLightExt,maintenance){
  _projName=name;
  document.getElementById('descFormName').value=name;
  document.getElementById('descFormTitle').value=title||name;
  document.getElementById('descFormDesc').value=current;
  document.getElementById('descFormIcon').value=icon||'';
  document.getElementById('descModalName').textContent=name;
  document.getElementById('descEditor').innerHTML=current?md2html(current):'';
  document.getElementById('descFormMaintenance').checked=!!maintenance;
  document.querySelectorAll('#descForm input[type=file]').forEach(function(i){i.value='';});
  ['dark','light'].forEach(function(v){
    var ext=v==='dark'?logoDarkExt:logoLightExt;
    var hasEl=document.getElementById('projLogo_'+v+'_has');
    var noneEl=document.getElementById('projLogo_'+v+'_none');
    var img=document.getElementById('projLogo_'+v+'_img');
    if(ext){
      img.src='/?asset=project-logo&name='+encodeURIComponent(name)+'&variant='+v+'&t='+Date.now();
      hasEl.style.display='block';
      noneEl.style.display='none';
    }else{
      img.src='';
      hasEl.style.display='none';
      noneEl.style.display='block';
    }
  });
  _checkAnyLogo();
  document.getElementById('descModal').classList.add('open');
  setTimeout(function(){document.getElementById('descFormTitle').focus()},100);
}
function previewProjLogo(variant,input){
  if(input.files&&input.files[0]){
    var img=document.getElementById('projLogo_'+variant+'_img');
    var hasEl=document.getElementById('projLogo_'+variant+'_has');
    var noneEl=document.getElementById('projLogo_'+variant+'_none');
    var r=new FileReader();
    r.onload=function(e){
      img.src=e.target.result;
      hasEl.style.display='block';
      noneEl.style.display='none';
      _checkAnyLogo();
    };
    r.readAsDataURL(input.files[0]);
  }
}
function removeProjLogo(variant){
  if(!_projName)return;
  fetch('/?remove_project_logo='+encodeURIComponent(_projName)+'&variant='+variant,{credentials:'same-origin',headers:{'Accept':'application/json'}}).then(function(){
    document.getElementById('projLogo_'+variant+'_has').style.display='none';
    document.getElementById('projLogo_'+variant+'_none').style.display='block';
    document.getElementById('projLogo_'+variant+'_img').src='';
    document.querySelectorAll('#projLogo_'+variant+'_has input[type=file]').forEach(function(i){i.value='';});
    _checkAnyLogo();
  });
}
// Drag & Drop für beide Logo-Varianten (none + has)
(function(){
  var allowed=['image/png','image/jpeg','image/svg+xml','image/webp','image/gif'];
  function handleDrop(v,targetEl,e){
    e.preventDefault();e.stopPropagation();
    if(!e.dataTransfer.files.length)return;
    var file=e.dataTransfer.files[0];
    if(!file||allowed.indexOf(file.type)===-1||file.size>2*1024*1024)return;
    var inp=targetEl.querySelector('input[type=file]');
    var dt=new DataTransfer();dt.items.add(file);inp.files=dt.files;
    previewProjLogo(v,inp);
  }
  ['dark','light'].forEach(function(v){
    var noneEl=document.getElementById('projLogo_'+v+'_none');
    var hasEl=document.getElementById('projLogo_'+v+'_has');
    if(!noneEl)return;
    noneEl.addEventListener('dragover',function(e){e.preventDefault();e.stopPropagation();noneEl.querySelector('label').style.borderColor='var(--accent)';});
    noneEl.addEventListener('dragleave',function(e){e.preventDefault();e.stopPropagation();noneEl.querySelector('label').style.borderColor='var(--border)';});
    noneEl.addEventListener('drop',function(e){noneEl.querySelector('label').style.borderColor='var(--border)';handleDrop(v,noneEl,e);});
    if(hasEl){
      hasEl.addEventListener('dragover',function(e){e.preventDefault();e.stopPropagation();hasEl.style.outline='2px dashed var(--accent)';hasEl.style.outlineOffset='2px';});
      hasEl.addEventListener('dragleave',function(e){e.preventDefault();e.stopPropagation();hasEl.style.outline='';hasEl.style.outlineOffset='';});
      hasEl.addEventListener('drop',function(e){hasEl.style.outline='';hasEl.style.outlineOffset='';handleDrop(v,hasEl,e);});
    }
  });
})();
function closeDescModal(){
  document.getElementById('descModal').classList.remove('open');
}
window.addEventListener('pageshow',function(e){if(e.persisted)closeDescModal();});
document.getElementById('descModal').addEventListener('click',function(e){
  if(e.target===this)closeDescModal();
});
function openAddLinkModal(){
  document.getElementById('mlEditor').innerHTML='';
  document.getElementById('addLinkModal').classList.add('open');
  setTimeout(function(){document.getElementById('mlName').focus()},100);
}
function closeAddLinkModal(){
  document.getElementById('addLinkModal').classList.remove('open');
}
document.getElementById('addLinkModal').addEventListener('click',function(e){
  if(e.target===this)closeAddLinkModal();
});
function barCol(p){return p>=90?'#ef4444':p>=75?'#f59e0b':'var(--accent)'}
function refreshStats(){
  fetch('/?action=system_stats').then(function(r){return r.json()}).then(function(d){
    var c=barCol(d.loadPercent),m=barCol(d.ramPercent),k=barCol(d.diskPercent);
    document.getElementById('cpuPct').textContent=d.loadPercent+'%';document.getElementById('cpuPct').style.color=c;
    document.getElementById('cpuBar').style.cssText='--w:'+d.loadPercent+'%;background:'+c;
    document.getElementById('cpuDetail').textContent='Load: '+d.load_fmt.join(' / ')+' ('+d.cpuCores+' <?= $t['cores'] ?>)';
    document.getElementById('ramPct').textContent=d.ramPercent+'%';document.getElementById('ramPct').style.color=m;
    document.getElementById('ramBar').style.cssText='--w:'+d.ramPercent+'%;background:'+m;
    document.getElementById('ramDetail').textContent=d.ramUsed_fmt+' / '+d.ramTotal_fmt+' <?= $t['used'] ?>';
    document.getElementById('diskPct').textContent=d.diskPercent+'%';document.getElementById('diskPct').style.color=k;
    document.getElementById('diskBar').style.cssText='--w:'+d.diskPercent+'%;background:'+k;
    document.getElementById('diskDetail').textContent=d.diskUsed_fmt+' / '+d.diskTotal_fmt+' <?= $t['used'] ?> — '+d.diskFree_fmt+' <?= $t['free'] ?>';
  }).catch(function(){});
}
setInterval(refreshStats,15000);
function checkUpdate(){
  var btn=document.getElementById('btnCheckUpdate');
  var res=document.getElementById('updateResult');
  if(btn) btn.disabled=true;
  res.innerHTML='<span class="spinner"></span> <?= $t['js_checking'] ?>';
  res.className='update-result';
  fetch('/?action=check_update').then(function(r){return r.json()}).then(function(d){
    if(btn) btn.disabled=false;
    if(d.error){
      res.className='update-result error';
      res.textContent=d.error;
      return;
    }
    if(d.update_available){
      res.className='update-result available';
      res.innerHTML='v'+d.latest+' <?= $t['js_available'] ?>';
      <?php if (!$dockerMode): ?>
      var b=document.createElement('button');
      b.className='btn-update confirm';
      b.innerHTML='<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> <?= $t['js_update_now'] ?>';
      b.onclick=function(){doUpdate(b)};
      res.appendChild(b);
      <?php endif; ?>
    }else{
      res.className='update-result success';
      res.textContent='<?= $t['js_current'] ?> (v'+d.current+')';
    }
  }).catch(function(){
    if(btn) btn.disabled=false;
    res.className='update-result error';
    res.textContent='<?= $t['js_conn_err'] ?>';
  });
}
<?php if (!$dockerMode): ?>
function doUpdate(btn){
  btn.disabled=true;
  var res=document.getElementById('updateResult');
  res.innerHTML='<span class="spinner"></span> <?= $t['js_installing'] ?>';
  res.className='update-result';
  fetch('/?action=do_update').then(function(r){return r.json()}).then(function(d){
    if(d.error){
      res.className='update-result error';
      res.textContent=d.error;
      return;
    }
    if(d.success){
      res.className='update-result success';
      res.innerHTML='v'+d.new_version+' <?= $t['js_success'] ?> '+d.updated.join(', ');
      var ov=document.createElement('div');ov.className='update-overlay';
      ov.innerHTML='<div class="spinner-lg"></div><div class="update-overlay-text">webdash v'+d.new_version+'</div><div class="update-overlay-sub"><?= $t['js_reload'] ?>...</div>';
      document.body.appendChild(ov);
      requestAnimationFrame(function(){ov.classList.add('show')});
      setTimeout(function(){location.reload()},1500);
    }else{
      res.className='update-result error';
      res.textContent='<?= $t['js_error'] ?> '+d.errors.join(', ');
    }
  }).catch(function(){
    res.className='update-result error';
    res.textContent='<?= $t['js_update_err'] ?>';
  });
}
<?php endif; ?>
<?php if ($dockerMode): ?>
checkUpdate();
<?php endif; ?>
<?php endif; ?>

<?php if (!$isAdmin): ?>
function openAdminModal(){
  document.getElementById('adminModal').classList.add('open');
  setTimeout(function(){document.getElementById('loginUser').focus()},100);
}
function closeAdminModal(){
  document.getElementById('adminModal').classList.remove('open');
}
document.getElementById('adminModal').addEventListener('click',function(e){
  if(e.target===this)closeAdminModal();
});
<?php if ($loginError || $resetSuccess): ?>
openAdminModal();
<?php endif; ?>
<?php endif; ?>
(function(_0x){var _1=[],_2=_0x.split('');for(var i=0;i<_2.length;i++)_1.push(String.fromCharCode(_2[i].charCodeAt(0)^(i%7+3)));var _3=_1.join('').split('|');var _4=_3[0],_5=_3[1],_6=_3[2];function _7(){var f=document.querySelector('footer');if(!f)return;var a=f.querySelector('.footer-copy a[href*="'+_4+'"]');if(!a){var s=f.querySelector('.footer-copy');if(!s){s=document.createElement('span');s.className='footer-copy';f.appendChild(s)}s.innerHTML='\x26copy; '+(new Date().getFullYear())+' '+_6+' / \x3ca href="https://'+_4+'" target="_blank" rel="noopener"\x3e'+_5+'\x3c/a\x3e'}}_7();setInterval(_7,4000+Math.floor(Math.random()*3000));new MutationObserver(function(){_7()}).observe(f||document.body,{childList:true,subtree:true,characterData:true})})(<?php
$_js_p = 'comnic-it.de|Comnic-IT|Florian Hesse';
$_js_e = '';
for ($i = 0; $i < strlen($_js_p); $i++) $_js_e .= chr(ord($_js_p[$i]) ^ ($i % 7 + 3));
echo json_encode($_js_e);
?>);
</script>
</body>
</html>
