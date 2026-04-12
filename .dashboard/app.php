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

define('WEBDASH_VERSION', '1.76');

// --- Basispfad / Base path ---
// Erkennt automatisch, ob webdash in einem Unterverzeichnis läuft
// Automatically detects if webdash runs in a subdirectory
$_dashBase = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
define('DASH_BASE', $_dashBase === '' ? '/' : $_dashBase . '/');

// --- Sprache / Language ---
if (isset($_GET['lang']) && in_array($_GET['lang'], ['de', 'en'], true)) {
    setcookie('webdash_lang', $_GET['lang'], time() + 31536000, DASH_BASE);
    header('Location: ' . DASH_BASE);
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
    'manual_link_added'=>'Link hinzugefügt','manual_link_deleted'=>'Link gelöscht',
    'project_edit'=>'Projekt bearbeiten','project_edit_desc'=>'Beschreibung','project_edit_icon'=>'Icon',
    'url_mode'=>'URL-Modus','url_mode_auto'=>'Automatisch','url_mode_ip_port'=>'IP + Port','url_mode_dns'=>'DNS-Name','url_mode_custom'=>'Eigene URL',
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
    'url_mode'=>'URL Mode','url_mode_auto'=>'Automatic','url_mode_ip_port'=>'IP + Port','url_mode_dns'=>'DNS Name','url_mode_custom'=>'Custom URL',
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
$dockerHostIp = getenv('WEBDASH_HOST_IP') ?: ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost');
$dockerHealthMode = strtolower(trim((string)(getenv('WEBDASH_DOCKER_HEALTH_MODE') ?: 'state')));
if (!in_array($dockerHealthMode, ['state', 'http', 'off'], true)) $dockerHealthMode = 'state';
$dockerAllowPrivatePorts = filter_var(getenv('WEBDASH_DOCKER_ALLOW_PRIVATE_PORTS') ?: 'false', FILTER_VALIDATE_BOOLEAN);

// Homelab-Icons: Direkt gerenderte SVG-Logos für gängige Homelab-Dienste
// Homelab icons: Directly rendered SVG logos for common homelab services
define('HOMELAB_SVG_ICONS', [
    'adguard' => ['label'=>'AdGuard Home', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M244-12C165.9-12 71.8 6.4-5.8 46.8c0 87.2-1 304.6 249.8 453.2C494.8 351.4 493.8 134 493.8 46.8 416.2 6.4 322.1-12 244-12" style="fill-rule:evenodd;clip-rule:evenodd;fill:#68bc71" transform="translate(12 12)"/><path d="M243.7 499.8C-6.8 351.3-5.8 134-5.8 46.8 71.7 6.4 165.7-12 243.7-12z" style="fill-rule:evenodd;clip-rule:evenodd;fill:#67b279" transform="translate(12 12)"/><path d="m234.9 329.6 151-203.6c-11.1-8.9-20.8-2.6-26.1 2.2h-.2l-125.9 131-47.4-57.1c-22.6-26.2-53.4-6.2-60.6-.9z" style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff" transform="translate(12 12)"/></svg>'],
    'authelia' => ['label'=>'Authelia', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><linearGradient id="hl1952_a" x1="-7.464" x2="485.846" y1="259.217" y2="256.579" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#3f51b4"/><stop offset="1" style="stop-color:#123156"/></linearGradient><path d="M256.7 128c67.4-1.9 128.3 52.3 128.6 127.9.3 72.7-58.1 128.6-129.3 128.5-70.2-.1-128.5-58.2-128-128.5.4-74.2 61.9-130.2 128.7-127.9m33.3 77.4c0-16.3-9.6-29.3-24.7-33.2-14.8-3.8-30.2 2.8-37.7 16-7.8 13.9-5.6 29.4 6.8 40.6 5.7 5.1 6.7 9.9 4.8 16.9-5.7 21.9-10.9 43.9-16.2 65.9-2 8.4.3 15.4 7.1 20.6 13.3 10.3 34.4 11.4 48.9 2.8 10.7-6.4 13.9-12.9 11.1-25-5.2-22.6-10.7-45.1-16.3-67.5-1.3-5.1-.7-8.4 3.8-11.9 7.9-6.3 13-14.5 12.4-25.2" style="fill:url(#hl1952_a)"/><linearGradient id="hl1952_b" x1="-7.511" x2="485.799" y1="250.455" y2="247.817" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#3f51b4"/><stop offset="1" style="stop-color:#123156"/></linearGradient><path d="M254.9 447.7C163.1 450.2 76.7 380.2 65 279.1c-7.6-65.8 12.4-122.5 62-167.3 11.8-10.7 26.3-18.7 40.1-27 9.8-5.9 18.6-3 23.9 5.9 5.4 9.3 3.1 17.8-6.7 24.5-9.7 6.7-20.1 12.6-29.1 20.2-32.7 27.4-50.9 62.7-54.9 105.1-3.6 38.7 5 74.9 28.2 106.3C158.8 387.9 200 410 251.3 412c45.7 1.7 85.1-14.5 116.6-46.9 12.8-13.2 21.7-30.3 31.8-46.1 5.4-8.5 12.7-13 20.8-10.9 11.2 3 17.2 12.7 11.9 23.9-7.2 15.2-15.2 30.6-25.6 43.7-33.4 42.1-78 65-131.1 71.7-.8.1-1.5.2-2.3.2-6.2.1-12.4.1-18.5.1" style="fill:url(#hl1952_b)"/><linearGradient id="hl1952_c" x1="-7.285" x2="486.025" y1="292.737" y2="290.099" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#3f51b4"/><stop offset="1" style="stop-color:#123156"/></linearGradient><path d="M226.6 4.5c4.1 0 8.2.1 12.3 0 7.4-.1 13.2 2 15 10.2 1.7 7.7-1.7 16.9-8.5 19.7-5.3 2.2-11.3 3.2-17.1 4-41.8 5.5-79.6 20.8-112 47.8C71 124 43.4 172 36.9 231.2c-6.4 58.4 8.9 111 43.5 158.1 5.6 7.6 12.4 14.4 18.2 22 6.8 8.8 5.7 17-2.6 24.5-6.9 6.3-16.4 6.1-23.5-1.2-42-43.2-66.3-94.5-71.4-154.9-2.2-26.1-1.2-51.8 4.1-77.4 8.1-39 25.1-73.9 49.8-104.9 39-48.8 89.4-79.6 150.9-91.7 6.7-1.3 13.8-1.1 20.7-1.6z" style="fill:url(#hl1952_c)"/><linearGradient id="hl1952_d" x1="-7.857" x2="485.453" y1="185.72" y2="183.082" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#3f51b4"/><stop offset="1" style="stop-color:#123156"/></linearGradient><path d="M282.8 507.8c-2.8 0-5.7.2-8.5 0-9.2-.8-15.5-6.5-16.4-14.6-1-8.7 4.4-16.6 13.7-18.8 7.2-1.7 14.7-2.4 22-3.7 68.3-12.6 120-48.6 154.5-109.2C466 330 475.4 295.9 476.3 260c.7-27.3-3.7-54.3-13.8-80.1-1.6-4-2.5-9.2-1.4-13.2 2.1-7.5 7.2-13.1 15.8-13.8 8-.6 14.9 3 17.9 11.8 4.9 14.3 9.7 28.8 12.5 43.7 10 53.6 4.1 105.5-19.3 155-19.1 40.2-46.7 73.6-83.1 99.5-32.4 23-68.1 38.1-107.5 44.1-1.8.3-3.5.8-5.3.9-3.1 0-6.2-.1-9.3-.1" style="fill:url(#hl1952_d)"/><linearGradient id="hl1952_e" x1="-6.927" x2="486.383" y1="359.57" y2="356.932" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#3f51b4"/><stop offset="1" style="stop-color:#123156"/></linearGradient><path d="M444.5 215.5c0 11.9-5.2 18.6-14.3 20.3-8.8 1.6-16.2-2.9-19.6-12.3-4.5-12.3-8.1-25-13.6-36.8-14.9-32.2-39.1-55.6-70.8-71.3-2.8-1.4-5.6-2.6-8.2-4.2-8.4-5.2-11.6-14.9-7.8-23.4 4-9 12.9-13.8 22.3-9.2 14.5 7 29 14.6 41.8 24.3 34.8 26.3 57.1 61.4 68.5 103.4.9 3.7 1.4 7.5 1.7 9.2" style="fill:url(#hl1952_e)"/></svg>'],
    'caddy' => ['label'=>'Caddy', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hlaeaf_Layer_1" x="0" y="0" version="1.1" viewBox="0 0 512 512"><g id="hlaeaf_Icon" transform="matrix(.85801 0 0 .90748 -3224.99 -1435.83)"><path d="M4314.693 1795.227c7.42 33.993 6.673 67.565.563 99.736-6.21 32.657-18.722 63.552-36.641 91.445a307 307 0 0 1-23.638 32.035c-8.585 10.048-17.877 19.75-27.99 28.674-29.593 26.418-65.388 47.44-106.907 59.409-32.91 9.739-66.139 13.426-98.6 11.383-34.19-2.193-67.223-10.373-97.775-23.898l-3 5.407c30.402 16.403 63.896 27.452 99.068 32.45 34.47 4.957 70.331 3.94 106.424-3.659 46.796-10.314 88.463-30.608 122.545-58.306a256 256 0 0 0 32.365-31.534c9.748-11.435 18.53-23.608 25.99-36.424 18.502-31.732 30.346-67.127 33.7-103.853 3.152-34.907-1.335-70.836-13.773-106.092zm-472.603 235.266c-13.593-14.08-25.756-29.693-35.858-46.78-9.843-16.695-17.64-34.629-23.516-53.692-2.245-9.525-3.932-18.97-4.936-28.444-4.104-38.793 2.524-76.473 15.595-111.583 6.999-18.83 16.334-36.675 26.958-53.714 14.179-22.672 30.97-43.896 51.35-62.322 29.959-27.053 66.32-48.534 108.59-60.857 34.38-9.98 69.207-13.711 102.997-10.72 23.493 2.067 46.492 6.875 68.073 14.978 24.403 9.188 46.916 22.489 67.189 38.308 19.89 15.537 37.278 34.047 51.934 54.67 1.95 2.7 3.171 5.824 5.585 8.246 1.864 1.803 3.98 2.54 6.124 3.96l8.72-5.252c-.548-2.615-.512-4.773-1.613-7.163-1.526-3.12-4.317-5.518-6.645-8.193-23.863-28.359-53.37-51.446-86.252-68.796-14.019-7.368-28.667-13.647-43.695-19.056-16.566-5.995-33.618-10.939-51.184-14.364-40.092-7.9-82.38-8.537-124.862.698-46.119 9.98-87.185 29.652-120.863 56.858-23.39 18.926-43.58 41.214-58.633 66.345-11.296 18.83-19.544 39.299-25.013 60.404-9.82 38.075-9.935 78.306-1.08 118.511 2.083 9.492 4.543 18.96 7.705 28.47 5.112 19.65 12.524 38.148 21.643 55.575 9.454 18.103 20.72 34.955 33.741 50.338z" fill="#1f88c0" fill-rule="evenodd" clip-rule="evenodd"/><path d="M-51.7 26.4c.1-6.4-5.5-11.6-12.2-11.6-3.6 0-6.8 1.5-9.1 3.9-2 2.1-3.3 4.9-3.3 7.9-.1 6.4 5.5 11.6 12.2 11.6 6.7-.1 12.3-5.4 12.4-11.8z" style="fill:none;stroke:#1f88c0;stroke-width:5.7819;stroke-linejoin:round;stroke-miterlimit:22.0264" transform="rotate(-171.321 2107.672 748.746)scale(3.41014)"/><path d="M4217.164 1832.067s7.23-6.484 16.677-14.671c15.922-15.027 37.43-36.213 37.43-36.213l-7.031-7.707s-25.336 16.637-43.393 29.559c-9.982 7.66-18.28 13.092-18.28 13.092z" fill="#1f88c0" fill-rule="evenodd" clip-rule="evenodd"/><g id="hlaeaf_Padlock"><path d="M24.1 91.2h51c2.9 0 5.2-2.2 5.2-4.9V37.9c0-2.7-2.3-4.9-5.2-4.9H-.8C-3.7 33-6 35.2-6 37.9v28.6" style="fill:none;stroke:#22b638;stroke-width:9.5045;stroke-miterlimit:20.1153" transform="translate(3938.31 1737.25)scale(3.11426)"/><path d="M12.6 33V9.7c0-12.8 11.1-23.3 24.6-23.3S61.8-3.1 61.8 9.7V33" style="fill:none;stroke:#22b638;stroke-width:9.5045;stroke-linejoin:round;stroke-miterlimit:20.1153" transform="translate(3938.31 1737.25)scale(3.11426)"/></g><path d="M28.5 56.3c0-2-.5-3.9-1.4-5.5-2.1-3.6-6.1-6.1-10.8-6.1C9.6 44.7 4 50 3.9 56.4 3.9 62.8 9.4 68 16.1 68s12.3-5.3 12.4-11.7z" style="fill:none;stroke:#1f88c0;stroke-width:8.4748;stroke-linejoin:round;stroke-miterlimit:34.5908" transform="rotate(7.483 -11286.051 30322.67)scale(5.35538)"/><path d="M4036.657 1923.344s-40.417 23.032-69.58 40.521c-16.492 10.221-28.941 18.58-28.941 18.58l25.805 31.141s11.845-9.152 26.031-22.006c25.046-22.506 58.435-53.981 58.435-53.981z" fill="#1f88c0" fill-rule="evenodd" clip-rule="evenodd"/></g></svg>'],
    'docker' => ['label'=>'Docker', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="#2496ED" d="M349.9 236.3h-66.1v-59.2h66.1v59.2zm0-204.3h-66.1v60.1h66.1V32zm78.2 144.8H362v59.2h66.1v-59.2zm-156.3-72.7h-66.1v60.1h66.1v-60.1zm78.1 0h-66.1v60.1h66.1v-60.1zm201.4 118.9s-27.2-16.2-66.5-2.1c-6.2-40.8-35-62.4-35-62.4s28.1 34.4 30.3 73.4c-20.8 8.3-56.9 20.6-79.2 20.6H11.3C3.9 334.6 14.5 414.2 67.7 465.1c51 48.6 118.1 55.8 175.6 55.8 129 0 242-54.6 303-175.3 26.3.6 83.7 4 107.2-46.6l-31.4-17.5c-15.2 23.3-52.7 15.4-52.7 15.4zM349.9 176.8h-66.1v-60.1h66.1v60.1zm-78.1 0h-66.1v-60.1h66.1v60.1zm-78.2 0h-66.1v-60.1h66.1v60.1zm0-72.7h-66.1V44h66.1v60.1z"/></svg>'],

    'emby' => ['label'=>'Emby', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="m97.1 229.4 26.5 26.5L0 379.5l132.4 132.4 26.5-26.5L282.5 609l141.2-141.2-26.5-26.5L512 326.5 379.6 194.1l-26.5 26.5L229.5 97z" style="fill:#52b54b" transform="translate(0 -97)"/><path d="M196.8 351.2v-193L366 254.7 281.4 303z" style="fill:#fff"/></svg>'],
    'gitea' => ['label'=>'Gitea', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.05 98.32 512.15 315.48"><path d="m317.8 376-103.4-49.7c-10.2-4.9-14.6-17.3-9.6-27.5l49.7-103.4c4.9-10.2 17.3-14.6 27.5-9.6 14 6.8 22.1 10.6 22.1 10.6l-.1-88.9 13.6-.1.1 95.4s46.8 19.7 67.7 32.7c3 1.9 8.3 5.5 10.5 11.7 1.7 5 1.6 10.7-.8 15.7l-49.7 103.4c-5 10.3-17.4 14.7-27.6 9.7" fill="#87C540"/><path fill="#609926" d="M502.6 103.7c-3.3-3.3-7.8-3.3-7.8-3.3s-95.5 5.4-144.9 6.5c-10.8.2-21.6.5-32.3.6V203c-4.5-2.1-9-4.3-13.5-6.4 0-29.6-.1-88.9-.1-88.9-23.6.3-72.7-1.8-72.7-1.8s-115.2-5.8-127.7-6.9c-8-.5-18.3-1.7-31.8 1.2-7.1 1.5-27.3 6-43.8 21.9C-8.7 154.8.7 206.7 1.9 214.5c1.4 9.5 5.6 36 25.8 59 37.3 45.7 117.6 44.6 117.6 44.6s9.9 23.5 24.9 45.2c20.4 27 41.3 48 61.7 50.5 51.3 0 153.9-.1 153.9-.1s9.8.1 23-8.4c11.4-6.9 21.6-19.1 21.6-19.1s10.5-11.2 25.2-36.9c4.5-7.9 8.2-15.6 11.5-22.8 0 0 45-95.4 45-188.2-1-28-7.9-33-9.5-34.6M97.7 269.9c-21.1-6.9-30.1-15.2-30.1-15.2S52 243.8 44.2 222.3c-13.4-36-1.1-58-1.1-58s6.8-18.3 31.4-24.4c11.2-3 25.2-2.5 25.2-2.5s5.8 48.4 12.8 76.7c5.9 23.8 20.2 63.3 20.2 63.3s-21.3-2.6-35-7.5m244.6 87.6s-5 11.8-16 12.5c-4.7.3-8.4-1-8.4-1s-.2-.1-4.3-1.7l-92-44.8s-8.9-4.6-10.4-12.7c-1.8-6.6 2.2-14.7 2.2-14.7l44.2-91.1s3.9-7.9 9.9-10.6c.5-.2 1.9-.8 3.7-1.2 6.6-1.7 14.7 2.3 14.7 2.3l90.2 43.7s10.3 4.6 12.5 13.2c1.5 6-.4 11.4-1.5 14-5.2 12.6-44.8 92.1-44.8 92.1"/><path fill="#609926" d="M261.6 291.2c-6.7.1-12.5 4.7-14.1 11.2-1.5 6.5 1.6 13.3 7.4 16.3 6.3 3.3 14.3 1.5 18.5-4.4 4.2-5.8 3.5-13.8-1.5-18.8l19.5-40c1.2.1 3 .2 5-.4 3.3-.7 5.8-2.9 5.8-2.9 3.4 1.5 7 3.1 10.8 5 3.9 2 7.6 4 10.9 5.9.7.4 1.5.9 2.3 1.5 1.3 1.1 2.8 2.5 3.8 4.5 1.5 4.5-1.5 12.1-1.5 12.1-1.9 6.2-15 33.1-15 33.1-6.6-.2-12.5 4.1-14.4 10.2-2.1 6.6.9 14.1 7.2 17.3 6.4 3.3 14.2 1.4 18.3-4.3 4.1-5.5 3.7-13.3-.9-18.4l4.6-9.2c4.1-8.5 11-24.8 11-24.8.7-1.4 4.6-8.4 2.2-17.3-2-9.3-10.3-13.6-10.3-13.6-9.9-6.4-23.8-12.4-23.8-12.4s0-3.3-.9-5.8-2.3-4.2-3.2-5.1c3.8-7.9 7.7-15.7 11.5-23.6-3.3-1.6-6.6-3.3-9.9-5-3.9 8-7.9 16-11.8 24-5.5-.1-10.5 2.9-13.1 7.7-2.8 5.1-2.2 11.5 1.5 16.1-6.6 13.8-13.3 27.5-19.9 41.1"/></svg>'],
    'gitlab' => ['label'=>'GitLab', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="93.97 97.52 192.05 184.99"><g id="hld049_LOGO"><path d="m282.83 170.73-.27-.69-26.14-68.22a6.8 6.8 0 0 0-2.69-3.24 7 7 0 0 0-8 .43 7 7 0 0 0-2.32 3.52l-17.65 54h-71.47l-17.65-54a6.86 6.86 0 0 0-2.32-3.53 7 7 0 0 0-8-.43 6.87 6.87 0 0 0-2.69 3.24L97.44 170l-.26.69a48.54 48.54 0 0 0 16.1 56.1l.09.07.24.17 39.82 29.82 19.7 14.91 12 9.06a8.07 8.07 0 0 0 9.76 0l12-9.06 19.7-14.91 40.06-30 .1-.08a48.56 48.56 0 0 0 16.08-56.04" style="fill:#e24329"/><path d="m282.83 170.73-.27-.69a88.3 88.3 0 0 0-35.15 15.8L190 229.25c19.55 14.79 36.57 27.64 36.57 27.64l40.06-30 .1-.08a48.56 48.56 0 0 0 16.1-56.08" fill="#fc6d26"/><path d="m153.43 256.89 19.7 14.91 12 9.06a8.07 8.07 0 0 0 9.76 0l12-9.06 19.7-14.91S209.55 244 190 229.25c-19.55 14.75-36.57 27.64-36.57 27.64" style="fill:#fca326"/><path d="M132.58 185.84A88.2 88.2 0 0 0 97.44 170l-.26.69a48.54 48.54 0 0 0 16.1 56.1l.09.07.24.17 39.82 29.82L190 229.21Z" fill="#fc6d26"/></g></svg>'],
    'grafana' => ['label'=>'Grafana', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><linearGradient id="hld9c0_a" x1="256" x2="256" y1="359.964" y2="4.738" gradientTransform="matrix(1 0 0 -1 0 513)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#f05a28"/><stop offset="1" style="stop-color:#fbca0a"/></linearGradient><path d="M490.8 226c-.8-8.6-2.3-18.5-5.1-29.5-2.8-10.9-7.1-22.8-13.3-35.3-6.2-12.4-14.2-25.2-24.7-37.8-4.1-4.9-8.6-9.7-13.4-14.4 7.2-28.6-8.7-53.5-8.7-53.5-27.5-1.7-45 8.6-51.5 13.3-1.1-.4-2.1-1-3.2-1.4-4.7-1.8-9.5-3.7-14.5-5.2-4.9-1.6-10-3-15.2-4.2-5.2-1.3-10.4-2.3-15.8-3.1-1-.1-1.8-.3-2.8-.4C310.6 16.1 276.1 0 276.1 0c-38.5 24.4-45.7 58.5-45.7 58.5s-.1.7-.4 2c-2.1.6-4.2 1.3-6.3 1.8-3 .8-5.9 2-8.7 3.1-3 1.1-5.8 2.3-8.7 3.5-5.8 2.5-11.6 5.4-17.2 8.5-5.5 3.1-10.9 6.5-16.1 10l-1.4-.6C118.2 66.6 70.9 91 70.9 91c-4.4 56.7 21.3 92.4 26.4 98.9-1.3 3.5-2.4 7.1-3.5 10.6-3.9 12.8-6.9 26-8.7 39.6-.3 2-.6 3.9-.7 5.9-49.4 24.4-63.9 74.2-63.9 74.2 41 47.3 89 50.2 89 50.2l.1-.1q9.15 16.35 21 30.9c3.4 4.1 6.8 7.9 10.4 11.7-15 42.9 2.1 78.4 2.1 78.4 45.7 1.7 75.7-20 82.1-25 4.5 1.6 9.2 3 13.8 4.1 14.1 3.7 28.5 5.8 42.9 6.3 3.5.1 7.2.3 10.7.1h5.1l2.3-.1v.1c21.6 30.7 59.4 35.1 59.4 35.1 26.9-28.4 28.5-56.6 28.5-62.6v-2.5c5.6-3.9 11-8.2 16.1-12.8 10.7-9.7 20.2-20.9 28.1-32.9.7-1.1 1.4-2.3 2.1-3.4 30.5 1.7 52-18.9 52-18.9-5.1-31.7-23.1-47.3-26.9-50.2 0 0-.1-.1-.4-.3-.3-.1-.3-.3-.3-.3-.1-.1-.4-.3-.7-.4.1-2 .3-3.8.4-5.8.3-3.4.3-6.9.3-10.3v-5.3l-.1-2.1-.1-2.8c0-1-.1-1.8-.3-2.7s-.1-1.8-.3-2.7l-.3-2.7-.4-2.7c-.6-3.5-1.1-6.9-2-10.4-3.2-13.7-8.6-26.7-15.5-38.4-7.1-11.7-15.8-22-25.8-30.7-9.9-8.7-21-15.8-32.6-21-11.7-5.2-23.8-8.6-36-10.2-6.1-.8-12.1-1.1-18.2-1h-4.6l-2.3.1c-.8 0-1.7.1-2.4.1-3.1.3-6.2.7-9.2 1.3-12.1 2.3-23.6 6.6-33.6 12.7s-18.8 13.5-25.8 22c-7.1 8.5-12.6 17.9-16.4 27.6s-5.9 19.9-6.5 29.6c-.1 2.4-.1 4.9-.1 7.3v1.8l.1 2c.1 1.1.1 2.4.3 3.5.4 4.9 1.4 9.7 2.7 14.2 2.7 9.2 6.9 17.5 12.1 24.5 5.2 7.1 11.6 12.8 18.2 17.5 6.6 4.5 13.8 7.8 20.9 9.9s14.1 3 20.7 3h3.7c.5 0 .8 0 1.3-.1.7 0 1.4-.1 2.1-.1.1 0 .4 0 .6-.1l.7-.1c.4 0 .8-.1 1.3-.1.8-.1 1.6-.3 2.4-.4s1.6-.3 2.3-.6c1.6-.3 3-.8 4.4-1.3 2.8-1 5.6-2.1 8-3.4 2.5-1.3 4.8-2.8 7.1-4.2.6-.4 1.3-.8 1.8-1.4 2.3-1.8 2.7-5.2.8-7.5-1.6-2-4.4-2.5-6.6-1.3-.6.3-1.1.6-1.7.8-2 1-3.9 1.8-6.1 2.5-2.1.7-4.4 1.3-6.6 1.7-1.1.1-2.3.3-3.5.4-.6 0-1.1.1-1.8.1h-3.4c-.7 0-1.4 0-2.1-.1h-.8c-.3 0-.7 0-1-.1-.7-.1-1.3-.1-2-.3-5.2-.7-10.4-2.3-15.4-4.5-5.1-2.3-9.9-5.4-14.2-9.3s-8.2-8.6-11.1-14c-3-5.4-5.1-11.3-6.1-17.5-.4-3.1-.7-6.3-.6-9.5 0-.8.1-1.7.1-2.5v-1.1c0-.4.1-.8.1-1.3.1-1.7.4-3.4.7-5.1 2.4-13.5 9.2-26.8 19.6-36.8 2.7-2.5 5.5-4.8 8.5-6.9s6.2-3.9 9.6-5.5 6.8-2.8 10.4-3.8c3.5-1 7.2-1.6 11-2 1.8-.1 3.7-.3 5.6-.3h4.4l1.6.1c4.1.3 8 .8 12 1.8 7.9 1.7 15.7 4.7 22.8 8.6 14.4 8 26.7 20.5 34.1 35.4 3.8 7.5 6.5 15.5 7.8 23.8.3 2.1.6 4.2.7 6.3l.1 1.6.1 1.6v6.2c0 1-.1 2.7-.1 3.7-.1 2.3-.4 4.7-.7 6.9-.3 2.3-.7 4.5-1.1 6.8s-1 4.5-1.6 6.6c-1.1 4.4-2.5 8.7-4.2 13.1-3.4 8.5-7.9 16.6-13.3 24.1-10.9 15-25.7 27.1-42.6 34.8-8.5 3.8-17.3 6.6-26.5 8-4.5.8-9.2 1.3-13.8 1.4h-7c-2.5 0-4.9-.1-7.5-.4-9.9-.7-19.6-2.5-29.2-5.2-9.5-2.7-18.6-6.5-27.4-11-17.3-9.3-33-22-45.1-37.4-6.1-7.6-11.4-15.9-15.8-24.5s-7.9-17.8-10.4-26.9c-2.5-9.3-4.1-18.8-4.8-28.4l-.1-1.8v-12.2c.1-4.7.6-9.6 1.1-14.4.6-4.8 1.4-9.7 2.4-14.5s2.1-9.6 3.5-14.4c2.7-9.5 6.1-18.6 10-27.2 8-17.2 18.5-32.6 31-44.9 3.1-3.1 6.3-5.9 9.7-8.7 3.4-2.7 6.9-5.2 10.6-7.6 3.5-2.4 7.3-4.5 11.1-6.5 1.8-1 3.8-2 5.8-2.8 1-.4 2-.8 3-1.3 1-.4 2-.8 3-1.3 3.9-1.7 8-3.1 12.3-4.4 1-.3 2.1-.6 3.1-1 1-.3 2.1-.6 3.1-.8 2.1-.6 4.2-1.1 6.3-1.6 1-.3 2.1-.4 3.2-.7s2.1-.4 3.2-.7c1.1-.1 2.1-.4 3.2-.6l1.6-.3 1.7-.3c1.1-.1 2.1-.3 3.2-.4 1.3-.1 2.4-.3 3.7-.4 1-.1 2.7-.3 3.7-.4.7-.1 1.6-.1 2.3-.3l1.6-.1.7-.1h.8c1.3-.1 2.4-.1 3.7-.3l1.8-.1h1.3c1 0 2.1-.1 3.1-.1 4.1-.1 8.3-.1 12.4 0 8.2.3 16.2 1.3 24 2.7 15.7 3 30.3 7.9 43.7 14.5 13.4 6.5 25.2 14.5 35.7 23.3.7.6 1.3 1.1 2 1.7.6.6 1.3 1.1 1.8 1.7 1.3 1.1 2.4 2.3 3.7 3.4s2.4 2.3 3.5 3.4 2.3 2.3 3.4 3.5c4.4 4.7 8.5 9.3 12.1 14.1 7.3 9.5 13.3 19 17.9 28.1.3.6.6 1.1.8 1.7.3.6.6 1.1.8 1.7.6 1.1 1.1 2.3 1.6 3.4.6 1.1 1 2.1 1.6 3.2.4 1.1 1 2.1 1.4 3.2 1.7 4.2 3.4 8.3 4.7 12.1 2.1 6.2 3.7 11.7 4.9 16.5.4 2 2.3 3.2 4.2 3 2.1-.1 3.7-1.8 3.7-3.9.4-5.4.2-11.4-.5-18.3" style="fill:url(#hld9c0_a)"/></svg>'],
    'haproxy' => ['label'=>'HAProxy', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hl75cb_Layer_1" x="0" y="0" version="1.1" viewBox="0 0 512 512"><path d="m255.8 189.5-53.2-60.1" fill="none" stroke="#284a6a" stroke-width=".26" stroke-miterlimit="10"/><path d="m88 143.3 41.6 60.8" fill="none" stroke="#284a6a" stroke-width=".13" stroke-miterlimit="10"/><path d="m255.8 189.6 52.6-60.8m11 127.9 62.5-52.6m-62.5 52.5 62.5 54.9M309 381l-53.2-59m-53.2 59 53.2-59m-126.1-12.8 60.1-52.6M129.7 204l60.1 52.6m66-67.1L129.7 204m126.1-14.5L381.9 204m-62.5 52.6-11-127.8m11 127.8L309 381m72.8-69.4-126 10.4m-126.1-12.8L255.8 322m-53.2 59-12.8-124.3m12.8-127.3-12.8 127.2" fill="none" stroke="#284a6a" stroke-width=".26" stroke-miterlimit="10"/><path d="m71.9 220.2 57.8-16.2m12.1-118 60.8 43.4m13.9-70.6-13.9 70.6m13.9-70.6 91.9 70m-13.9-70 13.9 70M369.1 86l-60.8 42.8m115.2 14.5-115.1-14.5m73.4 75.2 41.6-60.8M381.8 204l59 16.2m-59-16.2 57.8 87.9M294.5 58.8l-92 70.6M381.8 204 369 86m12.8 225.6 57.8-19.7m-57.8 19.7 59-91.4m-59 91.4 41.6 56.1m-41.6-56.1L369 424.9M309 381l60.1 44M309 381l114.5-13.3M309 381l-13.8 71m13.8-71-92.5 71m-13.9-71 13.9 71.2M202.6 381l92.6 71m-92.5-71-60.8 44m60.8-44L88 368.2m41.7-59-41.7 59m41.7-59 12.1 115.7m-12.1-115.7-58.4-17.9m58.4-87.3-58.4 87.3m58.4 17.9-57.8-89.1M141.8 86l-12.1 118M88 143.3l114.5-13.9" fill="none" stroke="#284a6a" stroke-width=".13" stroke-miterlimit="10"/><path d="M141.8 86 97.3 65.2M141.8 86l-3.5-48.6m78.2 21.4-78.1-21.4m78.1 21.4L183 17.7m33.5 41.1 13.9-49.2M141.8 86l41.1-68.2m111.6 41L230.3 9.6m64.2 49.2L280 7.9m-63.6 50.9L280 7.9m14.5 50.9L328 17.7M369.1 86 328 17.8M369.1 86l4-48.6m-78.6 21.4 78.6-21.4m-4 48.6 45.6-23.1M369.1 86l79.2 12.1m-24.8 45.2 24.9-45.1m-24.9 45.1-8.7-80.4m8.7 80.4 52-4m-52 4 71.2 40.5m-53.9 36.4 34.7-81m-34.7 81 53.8-36.4m-53.8 36.4 60.1 12.1m-61.2 59.6 61.3-59.6m-61.3 59.6 63-11.6m-61.9-60.1 61.9 60.1m-63 11.6 53.8 35.9m-70 39.8 70-39.9m-70 39.9 51.5 5.2m-35.2-80.9 35.2 81m-51.4-5.3 24.3 45.6m-24.3-45.6-9.3 79.8m-.1.1-45.1-22.6m0 0 3.5 50.4m-3.5-50.4 78.6-11.6m-78.6 11.6L328 494.3M295.2 452l32.8 42.2M295.2 452l77.5 23.1M295.2 452 280 504m15.2-52-64.8 52m-13.9-52 13.9 52m-13.9-52 63.5 52m-63.5-52-33 42.2m-41.7-69.3 41.6 69.4m33.1-42.3-78.6 24.3m3.9-51.4-4 51.5m4-51.5-45.1 24.9M88 368.2l8.7 81.5m45.1-24.8-79.2-11M88 368.2l-25.4 45.6M88 368.2l-52 5.2m52-5.2-71.6-39.9m54.9-37-54.9 37m54.9-37-62.5-11m62.5 11-35.2 82.1m35.8-153.2-63 60.1m62.4 11-62.5-59m63.1-12.1-53.8-35.9m53.8 35.9-63 12.1m63-12.1-35.9-81m52 4.1-52-4m52 4-69.9 41.1M88 143.3l-24.8-42.8M88 143.3l9.3-78.1M141.8 86l-78.7 14.5" style="fill:none;stroke-miterlimit:10;stroke:#24405d;stroke-width:.101"/><path d="m158.9 286.7.4-60.8 60.8.4-.4 60.8zm65.8-67 .4-60.8 60.8.4-.4 60.8zm0 133.4.4-60.8 60.8.4-.4 60.8zm66.4-67 .4-60.8 60.8.4-.4 60.8z" style="fill:#256ea5"/><path d="m287.6 150 .3-42.2 42.4.3-.3 42.2zm-106-.1.3-42.2 42.4.3-.3 42.2zm-73.4 75.2.3-42.2 42.4.3-.3 42.2zm0 106.1.4-42.2 42.4.4-.4 42.2zm252.8-.8.3-42.2 42.4.3-.3 42.2zm.8-105 .4-42.2 42.4.4-.4 42.2z" fill="#3378bc"/><path d="m73.9 157 .2-27.8 27.7.2-.2 27.8zm54.1-56.5.2-27.8 27.7.2-.2 27.8zm74.6-27.9.2-27.8 27.7.2-.2 27.8zM57.5 233.9l.2-27.8 27.5.2-.2 27.8zm352-104.2 27.8-.2.2 27.7-27.8.2zm-54.2-57.5 27.8-.2.2 27.7-27.8.2zM280.5 45l27.8-.2.2 27.7-27.8.2zm145.1 161.5 27.8-.2.2 27.7-27.8.2z" fill="#169bd6"/><path d="m181 403.2.3-42.2 42.4.3-.3 42.2zm106 0 .3-42.2 42.4.3-.3 42.2z" fill="#3378bc"/><path d="m409.3 381.8.2-27.8 27.7.2-.2 27.8zm-53.6 57.1.2-27.8 27.7.2-.2 27.8zm-74.5 27.4.2-27.8 27.7.2-.2 27.8zM425.6 305l.2-27.8 27.7.2-.2 27.8zM73.9 354.1l27.8-.2.2 27.7-27.8.2zm54.2 56.8 27.8-.2.2 27.7-27.8.2zm74.2 27.3 27.8-.2.2 27.7-27.8.2zM57.8 277.3l27.8-.2.2 27.7-27.9.2z" fill="#169bd6"/><path id="hl75cb_B" d="m467.5 146.7.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000097501457920659881830000007613161561513566362_" d="m487.3 192.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000071547014281121237290000002545514919948145049_" d="m440.8 106.5.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000108309099993383869620000014834865271147237535_" d="m89.6 72.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000036253792323724161050000017025648922934108033_" d="m1.6 239.8.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000003079909837249441410000011739665190993049492_" d="m407 72.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000026124844826931063100000010440354873156949909_" d="m10.4 192.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000170984863259660582880000017542866024396890781_" d="m175.8 25.5.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000047038855529388422380000016917034070735864999_" d="m29 146.6.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000152972174886506039780000005477888439546508442_" d="m56.4 105.9.1-15 15.3.1-.1 15z" fill="#00a8da"/><path d="m130.6 29.8 15-.1.1 15.3-15 .1zM223.5.3l15-.1.1 15.3-15 .1z" fill="#00a8da"/><path id="hl75cb_B_00000057136687109295219670000015391813752341540739_" d="m366.2 45.3.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000052065239389821694060000018327473300432699039_" d="m494.9 239.2.1-15 15.3.1-.1 15z" fill="#00a8da"/><path d="m272.4 15 .1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000103242960986684411540000014521877990177126306_" d="m320.2 24.9.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000106830210758259549100000012271491575892626322_" d="m407 455.3.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000016756049568836926160000004593568279343019911_" d="m494.9 287.6.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000134928594214955112640000001735054422448489644_" d="m89.6 455.3.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000064341922540891846050000002759780245424861593_" d="m487.3 336 .1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000114756557076627815260000012139680473946634135_" d="m320.2 502.5.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000088852300520418550050000012981349405070857374_" d="m467.5 381.3.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000107565796560585250970000000771644178080779668_" d="m440.8 422.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path d="m365.6 468.3 15-.1.1 15.3-15 .1zm-92.3 28.4 15-.1.1 15.3-15 .1z" fill="#00a8da"/><path id="hl75cb_B_00000046302001409149730030000001058363891885835174_" d="m130.9 483.8.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000007391695294084511430000001585176684106240930_" d="m28.4 380.8.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000106867634139900025310000010728328781746264241_" d="m10.4 335.9.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000160151161497024061790000009953166547806292877_" d="m55.8 422.1.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000058562238711190495490000006481508153496747946_" d="m1.6 287.6.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000022520615080886752770000012018997074303154580_" d="m224.1 511.8.1-15 15.3.1-.1 15z" fill="#00a8da"/><path id="hl75cb_B_00000112613330749112891540000009885944769618053561_" d="m175.2 501.9.1-15 15.3.1-.1 15z" fill="#00a8da"/></svg>'],
    'homarr' => ['label'=>'Homarr', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 82.9 512 346.2"><path d="M169.3 102.7c28.8 0 52.2 23.4 52.2 52.2v66.8c1.8-.6 3.6-1 5.5-1 5.9 0 11.1 2.9 14.2 7.4v-73.2c0-39.7-32.3-72-72-72-24.8 0-46.8 12.6-59.7 31.8 5.6 3.6 10.9 7.5 16 11.8 9.4-14.3 25.5-23.8 43.8-23.8m115.6 118.1c1.9 0 3.8.4 5.5 1V155c0-28.8 23.4-52.2 52.2-52.2 18.3 0 34.4 9.5 43.8 23.8 5.1-4.2 10.4-8.2 16-11.8C389.5 95.6 367.5 83 342.7 83c-39.7 0-72 32.3-72 72v73.2c3.1-4.5 8.3-7.4 14.2-7.4m-69.3 104.8c-35.7 8.6-66 28.1-88.1 54-12.2 14.3-21.9 30.6-28.6 48l46.6.3 265.5 1.2v-.1c-29.2-77.8-112.5-123.4-195.4-103.4m11.5-61.9c-9.7 0-17.5 7.8-17.5 17.5s7.8 17.5 17.5 17.5 17.5-7.8 17.5-17.5-7.8-17.5-17.5-17.5m57.8 35.1c9.7 0 17.5-7.8 17.5-17.5s-7.8-17.5-17.5-17.5-17.5 7.8-17.5 17.5 7.8 17.5 17.5 17.5m162.5-75.6 26.7-108.8c-22.6 2.8-43.5 11.1-61.5 23.4-6.3 4.3-12.3 9.2-17.8 14.4-26.4 25.4-42.9 61.1-42.9 100.6 0 30.7 9.9 59.1 26.7 82.1 9.2 12.7 20.6 23.7 33.4 32.6l9.3-37.8c47.9-14.3 84.1-55.7 90.7-106.5zM133.5 334.9c16.8-23 26.7-51.4 26.7-82.1 0-39.5-16.5-75.2-42.9-100.6-5.5-5.3-11.5-10.1-17.8-14.4-17.9-12.3-38.8-20.6-61.5-23.4l26.7 108.8H0c6.6 50.8 42.8 92.2 90.7 106.5l9.3 37.8c12.9-8.9 24.2-19.9 33.5-32.6" style="fill:#fa5252"/></svg>'],
    'homeassistant' => ['label'=>'Home Assistant', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 473.3c0 17.6-14.4 32-32 32H32c-17.6 0-32-14.4-32-32v-192c0-17.6 10.2-42.2 22.6-54.6L233.4 16c12.4-12.4 32.8-12.4 45.2 0l210.8 210.8c12.4 12.4 22.6 37 22.6 54.6z" style="fill:#f2f4f9"/><path d="M489.4 226.7 278.6 16c-12.4-12.4-32.8-12.4-45.2 0L22.6 226.7C10.2 239.1 0 263.7 0 281.3v192c0 17.6 14.4 32 32 32h196.8l-86.7-86.7c-4.5 1.5-9.2 2.4-14.2 2.4-24.1 0-43.7-19.6-43.7-43.7s19.6-43.7 43.7-43.7 43.7 19.6 43.7 43.7c0 5-.9 9.7-2.4 14.2l67.5 67.5V211.8c-14.5-7.1-24.5-22-24.5-39.2 0-24.1 19.6-43.7 43.7-43.7s43.7 19.6 43.7 43.7c0 17.2-10 32.1-24.5 39.2v173.4l67.1-67.1c-1.3-4.2-2-8.6-2-13.2 0-24.1 19.6-43.7 43.7-43.7s43.7 19.6 43.7 43.7-19.6 43.7-43.7 43.7c-5.3 0-10.4-1-15.1-2.8l-93.7 93.7v65.9H480c17.6 0 32-14.4 32-32v-192c0-17.6-10.2-42.2-22.6-54.7" style="fill:#18bcf2"/></svg>'],
    'immich' => ['label'=>'Immich', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M238.8 155.5c33.5 29.7 60.5 61.5 77.9 91.5 29.9-53.4 49.8-116.9 50.1-157.3v-.8c0-59.8-59.7-83.1-111.1-83.1S144.6 29 144.6 88.8V92c28.7 12.8 62.6 35.6 94.2 63.5" style="fill:#fa2921"/><path d="M55.9 318.6c21-23.3 53.1-48.6 89.4-69.9 38.6-22.7 77.2-38.6 111.1-45.8-41.6-44.9-95.8-83.5-134.1-96.2-.3-.1-.5-.2-.7-.2-57-18.7-97.6 30.9-113.5 79.8S-4.1 299.1 52.8 317.6c.8.2 1.8.6 3.1 1" style="fill:#ed79b5"/><path d="M503.9 185.4C488 136.6 447.4 87 390.5 105.5c-.8.3-1.8.6-3.1 1-3.3 31.2-14.4 70.5-31.2 109.1-17.9 41.1-39.8 76.6-62.9 102.4 60 11.9 126.5 11.3 165.1-1 .3-.1.5-.2.7-.2 57-18.6 60.6-82.5 44.8-131.4" style="fill:#ffb400"/><path d="M205 366.3c-9.7-43.7-12.8-85.3-9.3-119.8-55.5 25.7-109 65.3-133 97.8-.2.2-.3.4-.5.6-35.2 48.4-.6 102.3 41 132.5s103.5 46.4 138.7-1.9c.5-.7 1.1-1.5 1.9-2.6-15.6-27.1-29.7-65.5-38.8-106.6" style="fill:#1e83f7"/><path d="M448.8 341.9c-30.7 6.5-71.5 8.1-113.4 4-44.6-4.3-85.1-14.2-116.8-28.2 7.2 60.8 28.4 123.8 51.9 156.7.2.2.3.4.5.6 35.2 48.4 97.1 32.2 138.7 1.9 41.6-30.2 76.2-84.1 41-132.5-.5-.6-1.1-1.4-1.9-2.5" style="fill:#18c249"/></svg>'],
    'jellyfin' => ['label'=>'Jellyfin', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><linearGradient id="hl7e89_a" x1="97.508" x2="522.069" y1="308.135" y2="63.019" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#aa5cc3"/><stop offset="1" style="stop-color:#00a4dc"/></linearGradient><path d="M256 196.2c-22.4 0-94.8 131.3-83.8 153.4s156.8 21.9 167.7 0-61.3-153.4-83.9-153.4" style="fill:url(#hl7e89_a)"/><linearGradient id="hl7e89_b" x1="94.193" x2="518.754" y1="302.394" y2="57.278" gradientTransform="matrix(1 0 0 -1 0 514)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#aa5cc3"/><stop offset="1" style="stop-color:#00a4dc"/></linearGradient><path d="M256 0C188.3 0-29.8 395.4 3.4 462.2s472.3 66 505.2 0S323.8 0 256 0m165.6 404.3c-21.6 43.2-309.3 43.8-331.1 0S211.7 101.4 256 101.4 443.2 361 421.6 404.3" style="fill:url(#hl7e89_b)"/></svg>'],
    'mariadb' => ['label'=>'MariaDB', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hl4aab_Layer_1" x="0" y="0" version="1.1" viewBox="0 85.79 512.01 340.29"><path d="M500.7 86.5c-7.9.3-5.4 2.5-22.5 6.7-17.3 4.2-38.4 2.9-57 10.7-55.5 23.3-66.6 102.8-117.1 131.3-37.6 21.3-75.8 23-109.9 33.6-22.5 7.1-47 21.5-67.5 39-15.8 13.6-16.2 25.6-32.8 42.7C76.3 368.9 23.6 351 0 379c7.7 7.7 11.1 9.9 26.2 7.9-3.1 5.9-21.6 10.9-18 19.7 3.8 9.2 48.4 15.4 88.9-9.1 18.9-11.4 33.9-27.9 63.2-31.9 38.2-5.1 82 3.2 125.9 9.6-6.6 19.5-19.7 32.5-30.2 47.8-3.2 3.5 6.6 3.9 17.7 1.8 20.1-5 34.5-9 49.8-17.8 18.6-10.9 21.4-38.7 44.4-44.7 12.7 19.6 47.3 24.2 68.9 8.5-18.9-5.4-24.1-45.6-17.7-63.2 6-16.7 12-43.6 18.1-65.8 6.5-23.8 8.9-53.8 16.8-65.8 11.9-18.2 25-24.5 36.5-34.7C501.9 131 512.3 121 512 97.4c-.1-7.6-4-11.8-11.2-11.6z" fill="#002b64" fill-rule="evenodd" clip-rule="evenodd"/><path d="M16.5 405.5c28.8 4.1 46.4 0 69.8-10.1 19.7-8.6 38.7-26.2 62.1-33.6 34.2-11 71.8 0 108.5 2.2 8.9.5 17.8.5 26.5-.4 13.6-8.4 13.4-39.6 26.6-42.7-.4 43.9-18.4 70.3-37.3 95.7 39.9-7 63.5-29.9 79.5-60.7 4.9-9.3 9-19.3 12.7-29.9 5.7 4.4 2.5 17.7 5.3 24.8 27.4-15.2 43-50.1 53.5-85.2 12-40.7 16.9-82 24.7-94 7.5-11.8 19.3-19 30.2-26.5 12.2-8.6 23.1-17.5 24.9-33.9-12.9-1.2-15.8-4.2-17.7-10.7-6.4 3.6-12.4 4.4-19.1 4.6-5.8.2-12.2-.1-20 .7-64.4 6.6-72.6 77.8-113.9 117.9-3 2.9-6.3 5.7-9.7 8.2-14.5 10.8-32.2 18.5-48.4 24.7-26.4 10.1-51.5 10.8-76.3 19.5-18.2 6.4-36.7 15.7-51.5 25.9-3.7 2.6-7.3 5.2-10.5 7.8-8.8 7.2-14.5 15.1-20.1 23.3-5.8 8.4-11.3 17.1-19.7 25.4-13.7 13.4-64.9 3.9-82.9 16.4-2 1.4-3.6 3-4.7 5 9.8 4.5 16.4 1.7 27.7 3 1.5 10.7-23.3 17.1-19.7 22z" style="fill:#c49a6c"/><path d="M406.7 325.8c.8 12.3 7.9 36.7 14.2 42.7-12.4 3-33.6-2-39-10.7 2.8-12.6 17.4-24.1 24.9-31.9z" style="fill-rule:evenodd;clip-rule:evenodd;fill:#c49a6c"/><path d="M423.8 132.1c9.1 7.9 28.3 1.6 24.8-14.2-14.1-1.2-22.3 3.7-24.8 14.2" fill="#002b64" fill-rule="evenodd" clip-rule="evenodd"/><path d="M486.5 112.2c-2.4 5.1-7.1 11.6-7.1 24.6 0 2.2-1.7 3.8-1.7.3.1-12.7 3.5-18.1 7-25.3 1.7-3 2.7-1.8 1.8.4" fill="#002b64"/><path d="M486.5 112.2c-2.8 4.8-9.7 13.7-10.9 26.6-.2 2.2-2 3.6-1.7.2 1.2-12.6 6.7-20.5 10.9-27.3 1.9-2.9 2.8-1.6 1.7.5m-2.9-2.9c-3.2 4.6-13.9 15.2-16.1 28-.4 2.2-2.3 3.4-1.7 0 2.3-12.4 11.4-22.2 16.2-28.8 2.1-2.6 2.9-1.2 1.6.8m-2.8-2.8c-3.9 4.1-16.5 17.7-20.5 29.9-.7 2.1-2.8 3-1.7-.2 4-12 15.1-24.9 20.7-30.8 2.5-2.3 3-.8 1.5.9z" fill="#002b64"/></svg>'],
    'mysql' => ['label'=>'MySQL', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M471.6 393.4c-27.9-.7-49.4 2.1-67.6 9.8-5.2 2.1-13.5 2.1-14.3 8.7 2.8 2.8 3.2 7.3 5.6 11.2 4.2 7 11.5 16.3 18.1 21.2 7.3 5.6 14.6 11.1 22.3 16 13.5 8.4 28.9 13.3 42.1 21.6 7.7 4.9 15.3 11.1 23 16.4 3.8 2.8 6.2 7.3 11.1 9.1v-1.1c-2.5-3.1-3.2-7.7-5.6-11.1L496 485c-10.1-13.6-22.7-25.4-36.2-35.2-11.1-7.7-35.5-18.1-40.1-31l-.7-.7c7.7-.7 16.8-3.5 24-5.6 11.8-3.1 22.7-2.4 34.8-5.5 5.6-1.4 11.1-3.2 16.8-4.9V399c-6.3-6.3-10.8-14.6-17.4-20.5-17.7-15.3-37.3-30.3-57.5-42.8-10.8-7-24.7-11.5-36.2-17.4-4.1-2.1-11.1-3.1-13.6-6.6-6.2-7.7-9.8-17.7-14.3-26.8-10.1-19.1-19.8-40.4-28.5-60.6-6.3-13.6-10.1-27.1-17.8-39.7-35.9-59.2-74.9-95-134.8-130.2-12.8-7.5-28.1-10.6-44.5-14.4l-26.1-1.4c-5.6-2.4-11.2-9.1-16-12.2C68 13.9 16.8-13.3 2.2 22.6-7.2 45.2 16.1 67.5 24.1 79c5.9 8 13.6 17.1 17.7 26.1 2.5 5.9 3.1 12.2 5.6 18.5 5.6 15.3 10.8 32.4 18.1 46.7 3.8 7.3 8 15 12.9 21.6 2.8 3.9 7.7 5.6 8.7 11.9-4.9 6.9-5.2 17.4-8 26.1-12.5 39.3-7.6 88.1 10.1 117 5.6 8.7 18.8 27.9 36.5 20.5 15.7-6.3 12.2-26.1 16.7-43.5 1-4.2.4-7 2.4-9.7v.7c4.9 9.7 9.8 19.1 14.3 28.9 10.8 17 29.6 34.8 45.3 46.6 8.3 6.3 15 17.1 25.4 20.9v-1h-.7c-2.1-3.1-5.2-4.5-8-6.9-6.3-6.3-13.2-13.9-18.1-20.9-14.6-19.5-27.5-41.1-39-63.4-5.6-10.8-10.4-22.6-15-33.4-2.1-4.2-2.1-10.4-5.6-12.5-5.2 7.7-12.9 14.3-16.7 23.7-6.6 15-7.3 33.4-9.8 52.6l-1.4.7c-11.1-2.8-15-14.3-19.2-24-10.4-24.7-12.2-64.4-3.1-93 2.4-7.3 12.9-30.3 8.7-37.2-2.1-6.7-9.1-10.5-12.9-15.7-4.5-6.6-9.4-15-12.5-22.3-8.4-19.5-12.6-41.1-21.6-60.6C51 88 43.7 78.6 37.8 70.3c-6.6-9.4-13.9-16-19.2-27.2-1.7-3.8-4.2-10.1-1.4-14.3.7-2.8 2.1-3.8 4.9-4.5 4.5-3.8 17.4 1 21.9 3.1 12.9 5.2 23.7 10.1 34.5 17.4 4.9 3.5 10.1 10.1 16.4 11.9h7.3c11.1 2.4 23.7.7 34.1 3.8 18.4 5.9 35.2 14.6 50.1 24 45.6 28.9 83.2 70 108.6 119.1 4.2 8 5.9 15.3 9.7 23.7 7.3 17.1 16.4 34.5 23.7 51.2 7.3 16.4 14.3 33.1 24.7 46.7 5.2 7.3 26.1 11.1 35.5 15 6.9 3.1 17.8 5.9 24 9.7 11.8 7.3 23.6 15.7 34.8 23.7 5.7 4.1 23.1 12.9 24.2 19.8M116.4 90.8c-4.8 0-9.6.5-14.3 1.8v.7h.7c2.8 5.6 7.7 9.4 11.2 14.3 2.8 5.6 5.2 11.1 8 16.7l.7-.7c4.9-3.5 7.4-9.1 7.4-17.4-2.1-2.5-2.4-4.9-4.2-7.3-2.2-3.6-6.7-5.3-9.5-8.1" style="fill:#5d87a1"/></svg>'],
    'n8n' => ['label'=>'n8n', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 121.3 512.1 269.6"><path d="M458.1 229.1c-25.1 0-46.2-17.2-52.2-40.4h-61.8c-13.2 0-24.4 9.5-26.6 22.5l-2.2 13.3c-2 12.2-8.2 23.4-17.5 31.6 9.3 8.2 15.5 19.3 17.5 31.6l2.2 13.3c2.2 13 13.4 22.5 26.6 22.5h7.9c6-23.2 27.1-40.4 52.2-40.4 29.8 0 53.9 24.1 53.9 53.9s-24.1 53.9-53.9 53.9c-25.1 0-46.2-17.2-52.2-40.4h-7.9c-26.3 0-48.8-19-53.2-45l-2.2-13.3c-2.2-13-13.4-22.5-26.6-22.5h-21.4c-6 23.2-27.1 40.4-52.2 40.4s-46.2-17.2-52.2-40.4H106c-6 23.2-27.1 40.4-52.2 40.4C24.1 309.9 0 285.8 0 256s24.1-53.9 53.9-53.9c25.1 0 46.2 17.2 52.2 40.4h30.3c6-23.2 27.1-40.4 52.2-40.4s46.2 17.2 52.2 40.4h21.4c13.2 0 24.4-9.5 26.6-22.5l2.2-13.3c4.3-26 26.8-45 53.2-45H406c6-23.2 27.1-40.4 52.2-40.4 29.8 0 53.9 24.1 53.9 53.9s-24.2 53.9-54 53.9m0-27c14.9 0 26.9-12.1 26.9-26.9s-12.1-26.9-26.9-26.9-26.9 12.1-26.9 26.9 12 26.9 26.9 26.9M53.9 282.9c14.9 0 26.9-12.1 26.9-26.9s-12.1-26.9-26.9-26.9-27 12-27 26.9 12.1 26.9 27 26.9M215.6 256c0 14.9-12.1 26.9-26.9 26.9s-26.9-12.1-26.9-26.9 12.1-26.9 26.9-26.9 26.9 12 26.9 26.9m215.6 80.8c0 14.9-12.1 26.9-26.9 26.9-14.9 0-26.9-12.1-26.9-26.9s12.1-26.9 26.9-26.9 26.9 12.1 26.9 26.9" style="fill-rule:evenodd;clip-rule:evenodd;fill:#ea4b71"/></svg>'],
    'nextcloud' => ['label'=>'Nextcloud', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 139.3 512.2 233.5"><path d="M256 139.3c-53 0-97.9 35.8-112.1 84.4-12.2-25.5-38.2-43.4-68.2-43.4C34.2 180.2 0 214.4 0 256s34.2 75.8 75.8 75.8c30 0 55.9-17.9 68.2-43.4 14.1 48.6 59.1 84.4 112.1 84.4S354 337 368.2 288.4c12.2 25.5 38.2 43.4 68.2 43.4 41.6 0 75.8-34.2 75.8-75.8s-34.2-75.8-75.8-75.8c-30 0-55.9 17.9-68.2 43.4-14.3-48.5-59.2-84.3-112.2-84.3m0 45c39.9 0 71.7 31.8 71.7 71.7s-31.8 71.7-71.7 71.7-71.7-31.8-71.7-71.7 31.8-71.7 71.7-71.7m-180.2 41c17.2 0 30.7 13.5 30.7 30.7S93 286.7 75.8 286.7 45.1 273.2 45.1 256s13.4-30.7 30.7-30.7m360.4 0c17.2 0 30.7 13.5 30.7 30.7s-13.5 30.7-30.7 30.7-30.7-13.5-30.7-30.7 13.5-30.7 30.7-30.7" style="fill:#3784c9"/></svg>'],
    'nginx' => ['label'=>'Nginx', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="31.76 0 448.51 512"><path d="M255 0h1.2c6.1 2.9 12 6.1 17.8 9.7 66.2 38.1 132.4 76.1 198.6 114.2 5.2 2.8 8.2 8.5 7.6 14.4-.1 80.3 0 160.5-.1 240.7-.9 3.8-3.2 7.2-6.5 9.3L262.4 509.8c-3.5 2.7-8.2 2.9-12 .7-70.5-40.4-140.9-80.9-211.3-121.5-4.7-2.2-7.6-7-7.3-12.2V136.1c-.4-5.1 2.4-10 7.1-12.3 66.1-37.9 132.2-76 198.3-114 5.9-3.4 11.7-6.9 17.8-9.8" style="fill:#019639"/><path d="M123.7 156.1v198.6c-.2 7.4 2.6 14.6 7.8 19.9 10.1 10 25.8 11.5 37.7 3.8 7.8-5.3 12.5-14.2 12.5-23.6 0-47.9-.1-95.8 0-143.7 43.7 52.3 87.5 104.5 131.3 156.7 12.4 12.5 30.3 17.9 47.5 14.3 12.3-2.4 21.6-12.6 22.9-25.1.1-67.6.1-135.1 0-202.6-1.4-15.9-15.4-27.7-31.3-26.3-14 1.2-25.1 12.3-26.3 26.3 0 48.7-.3 97.3 0 146-42.9-50.6-85.3-101.6-128-152.4-11.5-14.7-30-22.1-48.4-19.2-14.2 1.4-25.1 13.1-25.7 27.3" style="fill:#fff"/></svg>'],
    'nodered' => ['label'=>'Node-RED', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 572.4h384c35.3 0 64 28.7 64 64v384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64v-384c0-35.4 28.7-64 64-64" style="fill:#8f0000" transform="translate(0 -572.36)"/><path d="M104 322.8c0-2.7-2.2-4.9-4.9-4.9H92v3.3h7.1c.9 0 1.7.7 1.7 1.6v4.8c0 .9-.8 1.7-1.7 1.7H92v3.2h7.1c2.7 0 4.9-2.2 4.9-4.9v-2.3c11.3.2 14.6 3.1 18.1 6.2 3.5 3 7.3 6.3 17.4 6.4v1.1c0 2.7 2.2 5 5 5h7.3v-3.5h-7.3c-.9 0-1.6-.6-1.6-1.5v-4.8c0-.9.7-1.6 1.6-1.6h7.3v-3.2h-7.3c-2.7 0-5 2.1-5 4.9v1.1c-9.5 0-12.3-2.7-15.7-5.7-3-2.6-6.4-5.4-14.1-6.5l.2-.2c1.5-1.4 2.3-3 3.1-4.4s1.5-2.6 2.7-3.4c1-.7 2.4-1.2 4.5-1.3v1.1c0 2.7 2.2 5.1 4.9 5.1h19.7c2.7 0 4.9-2.3 4.9-5.1v-4.8c0-2.7-2.2-4.9-4.9-4.9h-19.7c-2.7 0-4.9 2.2-4.9 4.9v1.1c-2.6.1-4.5.7-6 1.7-1.7 1.2-2.7 2.8-3.5 4.3s-1.5 2.8-2.5 3.7c-.9 1-2.1 1.6-4.2 1.8m21.1-14.1h19.7c.9 0 1.6.7 1.6 1.6v4.8c0 .9-.7 1.6-1.6 1.6h-19.7c-.9 0-1.7-.7-1.7-1.6v-4.8c0-1 .8-1.6 1.7-1.6" style="fill:#fff" transform="translate(-786.19 -2522.16)scale(8.545)"/></svg>'],
    'opnsense' => ['label'=>'OPNsense', 'svg'=>'<svg version="1.1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><image width="200" height="200" display="none" image-rendering="optimizeSpeed" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAFeklEQVR4nOzdP48VVRjA4VlCQgIm 2EChhSQWGgpzGxtiAvoBoLQE1I5CKNhSxUr3FrLFlrhraedSWemSGBobYiIGE+NujERZGwtJMDHX XMJNXCCv98/MnHNmnucDjKfwl/Mezuzc/aPRqCrF7tebx29/8M73qdfRN4cHJ64OVjcvpV5HCvtS L4D8/Xnr5sXb77+9knodKQiEqexuXb98690zn6ReR9sEwtT6uJMIhJn0bScRCDMb7yR9iUQgzKUv 45ZAmFsfxi2BsJCuj1sCYWFdHrcEQi26Om4JhNp0cScRCLXq2k4iEGrXpYO7QGhEV8YtgdCYLoxb AqFR451ke33lbOp1zEsgNG5nY7hWaiQCoQ2HdjaGGyWOWwKhNSUe3AVCq0o7uAuE1pV0TyIQkihl 3BIIyZQwbgmEpHK/JxEIyeV8TyIQcpDtPYlAyEaOB3eBkJXcDu4CITs53ZMIhCzlMm4JhGzlMG4J hKyl3kkEQvZS7iQCoQipDu4CoRgpxi2BUJS2xy2BUJw2X3AUCEXa2RiutTFuCYRSHWpj3BIIRWv6 4C4QitfkTiIQOqGpe5Kl37/6YvDH1vUzdT+4CQ/u3T144Ohzz6ReB/P5a/vOy/e377zR5H/jyKnT w+NXri3X9bylnz/9+NzOxnC9rgc2bPfkjd2jqRfBfLbXV1r5f+3w4MTVwermpTqeZcSic+q8JxEI nVTXhyAEQlfV8iEIgdBpi96TCITOW+SeRCD0wrz3JAKhN+YZtwRCr8w6bgmE3pllJxEIvTTtTiIQ emuag7tA6LX/G7cEQu9F45ZAIBi3BAKPPG3cEgj8x+PjlkDgMY92ko8qgcDT7W5df6sSCMQEAgGB QEAgEBAIBAQCAYFAQCAQEAgEBAIBgUBAIBAQCAQEAgGBQEAgEBAIBAQCAYFAQCAQEAgEBAIBgUBA IBAQCAQEAgGBQEAgEBAIBAQCAYFAQCAQEAgEBAIBgUBAIBAQCAQEAgGBQEAgEBAIBAQCAYFAQCAQ EAgEBAIBgUBAIBAQCAQEAgGBQEAgEBAIBAQCAYFAQCAQEAgEBAIBgUBAIBAQCAQEAgGBQEAgEBAI BAQCAYFAQCAQEAgEBAIBgUBAIBAQCATGgfyWehGQq33Hzi9/efDYSxupFwI5ejhivTL8/IJI4EkP Azlw9Pn7r372zXmRwF57Dul2EthrTyCTneTw4MTFdEuCfDz1n3kHq5urdhII7kGMWxAEYtyCKW7S jVv02VSvmhi36KupAnFPQl/N9LKinYS+mSkQB3f6Zq7X3R3c6Yu5/x7EuEUfzB2IcYs+WPgvCsfj 1pFTp5frWQ7kpZY/uT1+5drQTkIXLY1Go9oe9u3Z19bvb985V9sDn/RPVVW/NPh8mvXDC+cur6Re xJT+PnZ++WatgTy49+vB7y6/udZwJJRr6+SN3ddTL2IWtX7VxMGdrmnksz/uSeiKxr6L5Z6ELmgs EOMWXdD4lxXdk1CyVj496p6EUrX2bV4Hd0rU6serHdwpTauBOLhTmiQ/f2DcohTJfh/EuEUJkgXi QxCUIPkvTNlJyFnyQBzcyVnyQCYc3MlRNoFUxi0ylFUgxi1yk1UgE15wJBdZBjL24oUP14xbpJZt IO5JyEG2gUw4uJNS9oE4uJNS9oFMuCchhWICqYxbJFBUIMYt2lZUIBPuSWhLkYFUPgRBS4oNpHJw pwVFB1I5uNOw4gNxcKdJxQcyYdyiCZ0JpDJu0YBOBeIFR+rWqUAm7CTUpZOBOLhTl04GMuHgzqI6 HUhl3GJBnQ/EuMUiOh/IhHGLefQmkMq4xRx6FYh7Ema1P/UCUhjvJD+tvXe3qqpnU6+lZ35MvYBZ LY1Go9RrgGz9GwAA//8/Z0LNgEyjNwAAAABJRU5ErkJggg== "/><path d="m44 0v44h112v112h44v-92.93l-63.1-63.07zm112 156h-112v-112h-44v92l64 64h92z" fill="#c03e14"/></svg>'],
    'paperless' => ['label'=>'Paperless', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="43.38 0.14 425.32 512.02"><path d="M136.8 459.6c-2.3-10.8-6.9-32.5-7.4-32.5-96.5-57.7-85.1-157.6-53.1-214.7 6.9 71.9 134.2 121.6 60 209.6-.6 1.1 3.4 14.8 6.9 27.4 14.8-25.1 37.1-55.4 36-58.2-91.4-222.7 194.1-239.8 253.5-378 26.8 133.6-13.7 340.3-243.3 392.9-1.1.6-41.7 71.9-43.4 72.5 0-1.1-17.1-.6-14.8-6.3 1.1-3.6 3.3-8.1 5.6-12.7m56.6-98.8c-36-85.1 69.7-178.7 122.2-202.1C208.2 254.6 189.9 326 193.4 360.8M134 405.9c29.1-33.7-5.1-91.4-25.7-110.2 34.8 60 32.5 94.8 25.7 110.2" style="fill:#17541f" transform="translate(-15.306 -14.379)scale(1.10017)"/></svg>'],
    'pfsense' => ['label'=>'pfSense', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#4A6FA5" d="M148 361.4c19.2 0 35.7-5.9 49.5-17.2 13.9-11.2 23.8-26.4 29.1-44.9s4-33.7-3.3-44.9-21.1-17.2-40.3-17.2-35.7 5.9-49.5 17.2c-13.9 11.2-23.1 26.4-28.4 44.9s-4.6 33.7 3.3 44.9c6.6 11.9 19.8 17.2 39.6 17.2"/><path fill="#4A6FA5" d="m311.8 237.2 17.2-60.8h30.4l11.9-43.6c4-13.2 8.6-26.4 14.5-38.3 5.3-11.9 13.2-22.5 22.5-31.7 9.2-9.2 21.1-16.5 35-21.8s31.7-8 52.2-8c5.3 0 9.9 0 15.2.7-4.6-19.2-21.8-33-41.6-33.7H42.9C19.2 0 0 19.8 0 43.6v379.2l69.4-246.4h70l-9.2 32.4h1.3c4-4.6 9.2-8.6 15.9-13.2 6.6-4.6 13.2-8.6 20.5-12.6s15.2-6.6 23.8-9.2 17.2-3.3 25.8-3.3c18.5 0 33.7 3.3 46.9 9.2s23.1 15.2 31.1 26.4c6.6 9.9 10.6 21.8 12.6 35.7l5.3-4h-1.3v-.6z"/><path fill="#4A6FA5" d="M506.7 98.4c-3.3-.7-7.3-1.3-11.9-1.3-11.9 0-21.8 2.6-29.7 7.9-7.3 5.3-13.9 15.9-18.5 32.4l-11.2 39h42.9l10.6 33.7-27.7 27.1h-42.9l-52.2 185h-75.3l52.2-185h-29.1l-5.9 4c0 1.3.7 3.3.7 4.6 1.3 15.2-.7 32.4-5.9 50.9-4.6 17.2-11.9 33.7-21.8 49.5s-21.1 29.7-33.7 41.6c-13.2 11.9-27.7 21.8-43.6 29.1s-32.4 10.6-50.2 10.6c-15.9 0-29.7-2.6-42.3-7.3-12.6-4.6-21.1-13.2-26.4-25.1h-1.3L50.2 512h418.2c23.8 0 43.6-19.2 43.6-43.6v-368c-2-.6-4-1.3-5.3-2"/></svg>'],
    'pihole' => ['label'=>'Pi-hole', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hl8080_Layer_1" x="0" y="0" version="1.1" viewBox="82 0 348 512"><linearGradient id="hl8080_SVGID_1_" x1="313.659" x2="1028.964" y1="62.878" y2="62.878" gradientTransform="matrix(.3694 0 0 -.3694 -23.212 102.238)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#12b212"/><stop offset="1" style="stop-color:#0f0"/></linearGradient><path d="M226.1 157.3C162.1 150.5 97.7 102.2 92.7 0 191.8 0 244.9 58.7 250 151.8c18.8-111.6 106.7-98.5 106.7-98.5 4.2 63.2-47.8 101.6-106.7 104.8-16.5-35-115.7-120.5-115.7-120.5-.1-.1-.3-.1-.4 0q-.15.15 0 .3s95.7 83.4 92.2 119.4" style="fill:url(#hl8080_SVGID_1_)"/><path d="M256 512c-6.2-.4-63.9-2.6-67.4-67.4-2.8-39.4 28.3-68.5 28.3-106.7-7.1-95.4-134.9-83.6-134.9 0-.1 20.9 8.2 40.9 23 55.7l95.2 95.3c14.8 14.8 34.8 23 55.7 23" fill="#91180c"/><path d="M430 337.9c-.4 6.2-2.6 63.9-67.4 67.4-39.4 2.8-68.5-28.3-106.7-28.3-95.4 7.1-83.6 134.8 0 134.8 20.9.1 40.9-8.2 55.7-23l95.3-95.2c14.8-14.8 23-34.8 23-55.7" fill="#f42e1c"/><path d="M256 163.9c6.2.4 63.9 2.6 67.4 67.4 2.8 39.4-28.3 68.5-28.3 106.7 7.1 95.4 134.8 83.6 134.8 0 .1-20.9-8.2-40.9-23-55.7L311.7 187c-14.8-14.8-34.8-23-55.7-23" fill="#91180c"/><path d="M82.3 337.9c.4-6.2 2.6-63.9 67.4-67.4 39.4-2.8 68.5 28.3 106.7 28.3 95.4-7.2 83.6-134.8 0-134.8-20.9-.1-40.9 8.2-55.7 23l-95.3 95.3c-14.8 14.8-23 34.8-23 55.7" fill="#f42e1c"/></svg>'],
    'plex' => ['label'=>'Plex', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><rect width="512" height="512" fill="#282a2d" rx="15%"/><path fill="#e5a00d" d="M256 70H148l108 186-108 186h108l108-186z"/></svg>'],
    'portainer' => ['label'=>'Portainer', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0.72 0 168.18 218.62"><g clip-path="url(#hl1a49_clip0_261_120684)"><path d="M0.724609 0H80.925C136.261 0 168.905 17.2947 168.905 73.6996V75.627C168.905 132.207 136.331 149.327 80.9951 149.327H63.4901V218.05H0.724609V0ZM77.2453 101.14C94.3823 101.14 103.406 94.7791 103.406 75.7672V73.5769C103.406 54.6526 94.3823 48.1693 77.2453 48.1693H63.5077V101.122H77.2453V101.14Z" fill="#13BEF9"/><path d="M117.222 167.329H168.642V218.618H117.222V167.329Z" fill="#FF80F2"/></g><defs><clipPath id="hl1a49_clip0_261_120684"><rect width="168.414" height="218.719" fill="white" transform="translate(0.724609)"/></clipPath></defs></svg>'],
    'postgresql' => ['label'=>'PostgreSQL', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hle472_Layer_1" x="0" y="0" version="1.1" viewBox="0 0 512 512"><g id="hle472_Layer_x0020_3"><path d="M378.5 372.5c3.2-26.9 2.3-30.8 22.3-26.5l5.1.4c15.4.7 35.5-2.5 47.4-8 25.5-11.8 40.6-31.5 15.5-26.4-57.3 11.8-61.2-7.6-61.2-7.6 60.5-89.7 85.8-203.6 63.9-231.5C411.9-3 308.8 33 307.1 33.9l-.5.1c-11.3-2.3-24-3.8-38.2-4-25.9-.4-45.6 6.8-60.5 18.1 0 0-183.8-75.7-175.2 95.2 1.8 36.4 52.1 275.2 112.1 203 21.9-26.4 43.1-48.7 43.1-48.7 10.5 7 23.1 10.6 36.3 9.3l1-.9c-.3 3.3-.2 6.5.4 10.3-15.5 17.3-10.9 20.3-41.8 26.7-31.3 6.4-12.9 17.9-.9 20.9 14.5 3.6 48.2 8.8 70.9-23l-.9 3.6c6.1 4.9 5.7 34.9 6.5 56.3.9 21.4 2.3 41.5 6.7 53.3s9.5 42.2 50.1 33.5c34-7.3 59.9-17.7 62.3-115.1" style="stroke:#000;stroke-width:37.3953"/><path d="M468.7 312.1c-57.3 11.8-61.2-7.6-61.2-7.6C468 214.8 493.3 100.9 471.4 73 411.9-3 308.8 33 307.1 33.9l-.6.1c-11.3-2.3-24-3.7-38.2-4-25.9-.4-45.6 6.8-60.5 18.1 0 0-183.8-75.7-175.2 95.2 1.8 36.4 52.1 275.2 112.1 203 21.9-26.4 43.1-48.7 43.1-48.7 10.5 7 23.1 10.6 36.3 9.3l1-.9c-.3 3.3-.2 6.5.4 10.3-15.5 17.3-10.9 20.3-41.8 26.7-31.3 6.4-12.9 17.9-.9 20.9 14.5 3.6 48.2 8.8 70.9-23l-.9 3.6c6.1 4.9 10.3 31.6 9.6 55.8s-1.2 40.8 3.6 53.8 9.5 42.2 50.1 33.5c33.9-7.3 51.5-26.1 54-57.6 1.7-22.4 5.7-19 5.9-39l3.2-9.5c3.6-30.3.6-40.1 21.5-35.5l5.1.4c15.4.7 35.5-2.5 47.4-8 25.5-11.7 40.5-31.4 15.5-26.3" style="fill:#336791"/><path d="M256.3 329.5c-1.6 56.4.4 113.2 5.9 126.9 5.5 13.8 17.3 40.6 58 31.9 33.9-7.3 46.3-21.4 51.6-52.4 3.9-22.9 11.6-86.4 12.5-99.4M207.6 46.9S23.7-28.3 32.2 142.7c1.8 36.4 52.1 275.2 112.1 203 21.9-26.4 41.8-47.1 41.8-47.1M306.9 33.2c-6.4 2 102.3-39.7 164.1 39.2 21.8 27.9-3.5 141.8-63.9 231.5" fill="none" stroke="#fff" stroke-width="12.4651" stroke-linecap="round" stroke-linejoin="round"/><path d="M407 303.9s3.9 19.4 61.2 7.6c25.1-5.2 10 14.5-15.5 26.4-20.9 9.7-67.7 12.2-68.5-1.2-1.9-34.7 24.8-24.2 22.8-32.8-1.7-7.8-13.6-15.5-21.5-34.5-6.9-16.7-94.3-144.4 24.2-125.5 4.3-.9-30.9-112.7-141.8-114.5S160.7 165.8 160.7 165.8" style="fill:none;stroke:#fff;stroke-width:12.4651;stroke-linecap:round;stroke-linejoin:bevel"/><path d="M225.2 315.7c-15.5 17.3-10.9 20.3-41.8 26.7-31.3 6.4-12.9 17.9-.9 20.9 14.5 3.6 48.2 8.8 70.9-23 6.9-9.7 0-25.1-9.5-29.1-4.6-2-10.8-4.4-18.7 4.5" fill="none" stroke="#fff" stroke-width="12.4651" stroke-linecap="round" stroke-linejoin="round"/><path d="M224.2 315.4c-1.6-10.2 3.3-22.2 8.6-36.4 7.9-21.2 26.1-42.4 11.5-109.7-10.8-50.1-83.6-10.4-83.6-3.6s3.3 34.5-1.2 66.7c-5.9 42 26.7 77.6 64.3 73.9" fill="none" stroke="#fff" stroke-width="12.4651" stroke-linecap="round" stroke-linejoin="round"/><path d="M206.9 164.7c-.3 2.3 4.3 8.5 10.2 9.3 6 .8 11.1-4 11.4-6.3s-4.2-4.9-10.2-5.7c-6-.9-11.1.3-11.4 2.7z" style="fill:#fff;stroke:#fff;stroke-width:4.155"/><path d="M388.4 159.9c.3 2.3-4.2 8.5-10.2 9.3s-11.1-4-11.4-6.3 4.3-4.9 10.2-5.7 11.1.4 11.4 2.7z" style="fill:#fff;stroke:#fff;stroke-width:2.0775"/><path d="M409.8 143.9c1 18.2-3.9 30.6-4.5 50-.9 28.2 13.4 60.4-8.2 92.7" fill="none" stroke="#fff" stroke-width="12.4651" stroke-linecap="round" stroke-linejoin="round"/></g></svg>'],
    'prometheus' => ['label'=>'Prometheus', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0m0 479.1c-40.2 0-72.8-26.9-72.8-60h145.7c-.1 33.1-32.7 60-72.9 60m120.3-79.9H135.7v-43.6h240.6zm-.9-66h-239c-.8-.9-1.6-1.8-2.4-2.8-24.6-29.9-30.4-45.5-36.1-61.4-.1-.5 29.9 6.1 51.1 10.9 0 0 10.9 2.5 26.9 5.4-15.3-18-24.5-40.9-24.5-64.2 0-51.3 39.4-96.2 25.2-132.4 13.8 1.1 28.6 29.2 29.6 73 14.7-20.3 20.8-57.4 20.8-80.1 0-23.5 15.5-50.9 31-51.8-13.8 22.8 3.6 42.3 19.1 90.8 5.8 18.2 5.1 48.8 9.5 68.3 1.5-40.4 8.4-99.2 34-119.6-11.3 25.6 1.7 57.6 10.5 73 14.3 24.8 23 43.7 23 79.3 0 23.9-8.8 46.3-23.7 63.9 16.9-3.2 28.6-6 28.6-6l54.9-10.7c.2 0-7.8 32.8-38.5 64.4" style="fill:#e6522c"/></svg>'],
    'proxmox' => ['label'=>'Proxmox', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 34 512 444"><path d="M137.9 34.1c-10.5 0-19.7 1.9-28.5 5.7-8.6 3.8-16.2 8.9-22.9 15.6l170 186.4L426.1 55.3c-6.7-6.7-14.3-11.8-23.4-15.6-8.3-3.8-18-5.7-28-5.7-10.5 0-20.5 2.2-29.4 6.2-9.2 4-16.7 10-23.7 17l-65.2 72.2-66-72.2c-6.7-7-14.3-12.9-23.7-17-8.3-4-18.3-6.1-28.8-6.1M256.4 270l-170 186.7c6.7 6.5 14.3 11.8 22.9 15.6 8.9 3.8 18.1 5.7 28 5.7 11 0 20.5-2.4 29.4-6.2 9.4-4.3 17.5-10 24.2-17l65.5-72.2 65.4 72.2c6.7 7 14.3 12.7 23.4 17 8.9 3.8 18.6 6.2 29.4 6.2 10 0 19.7-1.9 28-5.7 9.2-3.8 16.7-9.2 23.4-15.6z" style="fill-rule:evenodd;clip-rule:evenodd"/><path d="M56 90.1c-10.8.3-21.3 2.4-30.7 6.5-9.7 4-18 9.7-25.3 16.7L129.8 256 0 398.5c7.3 7.3 15.6 12.9 25.3 17.2 9.4 4.3 19.9 6.2 30.7 6.7 11.6-.5 22.4-2.4 32.3-7.3q15-6.9 25.8-18.6l128-140.5-127.9-140.3c-7.8-7.5-16.2-13.7-26.1-18.6-10-4.6-20.5-6.7-32.1-7m399.7 0c-11.6.3-21.8 2.4-31.8 7-10 4.8-18.6 11-26.1 18.6L270.4 256l127.4 140.6q11.25 11.7 26.1 18.6c10 4.8 20.2 6.7 31.8 7.3 11.6-.5 21.5-2.4 31-6.7 10.2-4.3 18-10 25.3-17.2L382.5 256 512 113.3c-7.3-7-15.1-12.7-25.3-16.7-9.4-4.1-19.4-6.2-31-6.5" style="fill-rule:evenodd;clip-rule:evenodd;fill:#e57000"/></svg>'],
    'redis' => ['label'=>'Redis', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin meet" viewBox="0 0.58 256 218.59"><path fill="#912626" d="M245.97 168.943c-13.662 7.121-84.434 36.22-99.501 44.075s-23.437 7.78-35.34 2.09c-11.902-5.69-87.216-36.112-100.783-42.597C3.566 169.271 0 166.535 0 163.951v-25.876s98.05-21.345 113.879-27.024c15.828-5.679 21.32-5.884 34.79-.95 13.472 4.936 94.018 19.468 107.331 24.344l-.006 25.51c.002 2.558-3.07 5.364-10.024 8.988"/><path fill="#C6302B" d="M245.965 143.22c-13.661 7.118-84.431 36.218-99.498 44.072-15.066 7.857-23.436 7.78-35.338 2.09-11.903-5.686-87.214-36.113-100.78-42.594-13.566-6.485-13.85-10.948-.524-16.166 13.326-5.22 88.224-34.605 104.055-40.284 15.828-5.677 21.319-5.884 34.789-.948 13.471 4.934 83.819 32.935 97.13 37.81 13.316 4.881 13.827 8.9.166 16.02"/><path fill="#912626" d="M245.97 127.074c-13.662 7.122-84.434 36.22-99.501 44.078-15.067 7.853-23.437 7.777-35.34 2.087-11.903-5.687-87.216-36.112-100.783-42.597C3.566 127.402 0 124.67 0 122.085V96.206s98.05-21.344 113.879-27.023c15.828-5.679 21.32-5.885 34.79-.95C162.142 73.168 242.688 87.697 256 92.574l-.006 25.513c.002 2.557-3.07 5.363-10.024 8.987"/><path fill="#C6302B" d="M245.965 101.351c-13.661 7.12-84.431 36.218-99.498 44.075-15.066 7.854-23.436 7.777-35.338 2.087-11.903-5.686-87.214-36.112-100.78-42.594-13.566-6.483-13.85-10.947-.524-16.167C23.151 83.535 98.05 54.148 113.88 48.47c15.828-5.678 21.319-5.884 34.789-.949 13.471 4.934 83.819 32.933 97.13 37.81 13.316 4.88 13.827 8.9.166 16.02"/><path fill="#912626" d="M245.97 83.653c-13.662 7.12-84.434 36.22-99.501 44.078-15.067 7.854-23.437 7.777-35.34 2.087-11.903-5.687-87.216-36.113-100.783-42.595C3.566 83.98 0 81.247 0 78.665v-25.88s98.05-21.343 113.879-27.021c15.828-5.68 21.32-5.884 34.79-.95C162.142 29.749 242.688 44.278 256 49.155l-.006 25.512c.002 2.555-3.07 5.361-10.024 8.986"/><path fill="#C6302B" d="M245.965 57.93c-13.661 7.12-84.431 36.22-99.498 44.074-15.066 7.854-23.436 7.777-35.338 2.09C99.227 98.404 23.915 67.98 10.35 61.497-3.217 55.015-3.5 50.55 9.825 45.331 23.151 40.113 98.05 10.73 113.88 5.05c15.828-5.679 21.319-5.883 34.789-.948s83.819 32.934 97.13 37.811c13.316 4.876 13.827 8.897.166 16.017"/><path fill="#FFF" d="m159.283 32.757-22.01 2.285-4.927 11.856-7.958-13.23-25.415-2.284 18.964-6.839-5.69-10.498 17.755 6.944 16.738-5.48-4.524 10.855zm-28.251 57.518L89.955 73.238l58.86-9.035zm-56.95-50.928c17.375 0 31.46 5.46 31.46 12.194 0 6.736-14.085 12.195-31.46 12.195s-31.46-5.46-31.46-12.195c0-6.734 14.085-12.194 31.46-12.194"/><path fill="#621B1C" d="m185.295 35.998 34.836 13.766-34.806 13.753z"/><path fill="#9A2928" d="m146.755 51.243 38.54-15.245.03 27.519-3.779 1.478z"/></svg>'],
    'syncthing' => ['label'=>'Syncthing', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" id="hlc180_Layer_1" x="0" y="0" version="1.1" viewBox="0 0 118 118"><linearGradient id="hlc180_SVGID_1_" x1="59.05" x2="59.05" y1="2.95" y2="120.35" gradientTransform="matrix(1 0 0 -1 0 120.7)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#0882c8"/><stop offset="1" style="stop-color:#26b6db"/></linearGradient><circle cx="59" cy="59" r="58.7" style="fill:url(#hlc180_SVGID_1_)"/><circle cx="59" cy="58.8" r="43.7" fill="none" stroke="#fff" stroke-width="6" stroke-miterlimit="10"/><path d="M95 48.1c4.7 1.6 9.8-.9 11.4-5.6s-.9-9.8-5.6-11.4-9.8.9-11.4 5.7.9 9.7 5.6 11.3" fill="#fff"/><path d="m97.9 39.8-30.1 25" fill="none" stroke="#fff" stroke-width="6" stroke-miterlimit="10"/><path d="M77.9 91.3c-.4 4.9 3.2 9.3 8.2 9.8 5 .4 9.3-3.2 9.8-8.2.4-4.9-3.2-9.3-8.2-9.8-4.9-.3-9.4 3.2-9.8 8.2" fill="#fff"/><path d="m86.8 92.2-19-27.4" fill="none" stroke="#fff" stroke-width="6" stroke-miterlimit="10"/><path d="M60.3 69.7c2.7 4.2 8.3 5.4 12.4 2.7 4.2-2.7 5.4-8.3 2.7-12.4-2.7-4.2-8.3-5.4-12.4-2.7-4.2 2.5-5.4 8.1-2.7 12.4m-38.8-7.9c-4.3-2.5-9.8-1.1-12.3 3.1-2.5 4.3-1.1 9.8 3.1 12.3 4.3 2.5 9.8 1.1 12.3-3.1 2.5-4.3 1.1-9.8-3.1-12.3" fill="#fff"/><path d="m16.9 69.4 50.9-4.6" fill="none" stroke="#fff" stroke-width="6" stroke-miterlimit="10"/></svg>'],
    'tailscale' => ['label'=>'Tailscale', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4552E5"><path d="M65.6 127.7c35.3 0 63.9-28.6 63.9-63.9S100.9 0 65.6 0 1.8 28.6 1.8 63.9s28.6 63.8 63.8 63.8" opacity=".2"/><path d="M65.6 318.1c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9S1.8 219 1.8 254.2s28.6 63.9 63.8 63.9"/><path d="M65.6 512c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9-63.8 28.7-63.8 63.9S30.4 512 65.6 512" opacity=".2"/><path d="M257.2 318.1c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9-63.9 28.6-63.9 63.9 28.6 63.9 63.9 63.9m0 193.9c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9-63.9 28.6-63.9 63.9 28.6 63.9 63.9 63.9"/><path d="M257.2 127.7c35.3 0 63.9-28.6 63.9-63.9S292.5 0 257.2 0s-63.9 28.6-63.9 63.9 28.6 63.8 63.9 63.8m189.2 0c35.3 0 63.9-28.6 63.9-63.9S481.6 0 446.4 0c-35.3 0-63.9 28.6-63.9 63.9s28.6 63.8 63.9 63.8" opacity=".2"/><path d="M446.4 318.1c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9-63.9 28.6-63.9 63.9 28.6 63.9 63.9 63.9"/><path d="M446.4 512c35.3 0 63.9-28.6 63.9-63.9s-28.6-63.9-63.9-63.9-63.9 28.6-63.9 63.9 28.6 63.9 63.9 63.9" opacity=".2"/></svg>'],
    'traefik' => ['label'=>'Traefik', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.06 83.4 512.16 345.26"><path d="M116.8 428.5c-38.3-3.3-67.5-14.9-88.8-35.2-21.6-20.6-30.9-47.5-27.3-79 4.2-36.2 21.3-61.7 51.4-76.6 23.1-11.5 46.2-15.9 83.8-15.9 26.7 0 49.1 2.2 83.2 8.1 8.4 1.5 15.8 2.7 16.2 2.7.7 0 1.3-2.7 2.8-13.5 2.8-19.5 3.3-26.2 2.5-34.1-.9-9.1-2-13.4-5.5-20.5-8.9-18.2-29.9-29.3-63.7-33.7-9.7-1.2-31.9-1.4-41.6-.3-16.2 1.8-25.3 4.2-45.4 11.8-11.8 4.4-11.9 4.5-16.6 4.2-8.1-.5-13.6-4.2-17.2-11.4-1.5-3-1.7-4.2-1.7-8.3 0-6.1 1.6-9.3 7-14.4C68.5 101 89.2 91.2 110 87c13.3-2.7 19.9-3.3 37.5-3.2 18.2 0 29.8 1.1 46 4.1 26.9 5 47.3 13.9 62.7 27.2 4.6 3.9 11.5 11.7 14.4 16.2.9 1.4 1.8 2.5 2.1 2.5.2 0 1.7-1.7 3.3-3.7 23.3-29.8 62.6-46.7 108.6-46.7 32.5 0 62.8 8.3 85.3 23.3 17.9 11.9 32.4 30.3 38.3 48.4 3.4 10.4 3.9 14.2 3.9 29.3 0 9.4-.2 15.7-.8 18.7-5.3 29.7-17.8 50.2-39.6 64.9-17.5 11.8-39.6 18.7-68.4 21.4-14.5 1.4-41 1.2-58.2-.3-14.6-1.3-32.5-3.6-40.1-5.1-6.4-1.3-28.3-5-28.5-4.8-.1.1-1.1 6.7-2.3 14.6-1.9 12.7-2.2 15.9-2.2 26 0 10.3.1 12.1 1.3 16.9 4 15.3 12.8 25.6 28.7 33.5 15.7 7.8 32.1 11.2 57 11.7 11 .2 16.6.1 23.5-.7 16.7-1.8 24.3-3.7 46.4-11.9 5.5-2 11.2-3.9 12.5-4.1 2.9-.5 8.4.5 11.6 2.1 5.7 2.9 10.6 10.7 10.5 17 0 7.4-2.5 11.8-9.8 17.7-17.4 13.9-39.6 22.4-66.1 25.4-9.5 1.1-31 1.2-41.5.3-29.4-2.6-49.7-7.6-68.4-16.9-14.5-7.3-25.6-16.3-33.9-27.5-2-2.8-3.8-5.1-3.8-5.2-.1-.1-1.4 1.5-3 3.6-7 9-17.6 18.7-28 25.5-15 9.8-36.6 17.3-58.4 20.3-6.7.7-28.3 1.5-33.8 1m37.5-42.2c17.8-4 33.7-12.9 45.2-25.5 10.5-11.4 19.1-28.6 23.9-47.5 2.7-10.7 7.1-40.3 6.1-40.9-.4-.2-2.7-.6-5.2-.8s-9.6-1.1-15.7-1.9c-36.6-4.8-59.1-6.6-71.7-5.9-24 1.3-33.7 3.4-48.1 10.3-7.4 3.5-12.5 7.4-16.5 12.4-5.8 7.3-9.3 14.5-11.7 24.2-1.8 7.1-1.9 25.6-.3 31.5 2.8 10 6 15.7 13.4 23.3 10.1 10.3 23.4 17.2 39.9 20.6 8.4 1.8 8.2 1.7 22.3 1.5 10.3-.1 14.2-.4 18.4-1.3m233.5-139.1c14.9-1.3 24.1-3.6 35.3-9 9.4-4.4 14.8-8.9 20-16.6 7.6-11.2 10.5-21.8 10.5-37.6-.1-11.2-1-15.9-4.9-24-8.6-18-28.9-31-55.6-35.4-6.6-1.1-24.5-.9-31.3.3-13.1 2.4-24.2 6.8-33.9 13.4-18 12.3-30.5 30.7-37.4 55.5-2.7 9.7-3.5 13.5-5.9 30.3l-2.2 15.5 3 .3c9.1 1 29.5 3.5 41.4 5 7.5 1 16.6 2 20.4 2.3 10.6 1 30 1 40.6 0" style="fill:#24a1c1"/></svg>'],
    'truenas' => ['label'=>'TrueNAS', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 10 90 71"><g fill="none"><path fill="#31BEEC" d="M90 38.197v19.137L48.942 80.999V61.864z"/><path fill="#0095D5" d="M41.086 61.863V81L0 57.333V38.197l18.566 10.687q.03.025.067.04z"/><path fill="#AEADAE" d="m61.621 45.506-16.607 9.576-16.622-9.576 16.622-9.575z"/><path fill="#0095D5" d="M86.086 31.416 69.464 40.99 48.942 29.15V10z"/><path fill="#31BEEC" d="M41.086 10v19.15l-20.55 11.827-16.621-9.561z"/></g></svg>'],
    'unraid' => ['label'=>'Unraid', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 108.3 512 295.4"><linearGradient id="hlaec4_a" x1="91.058" x2="420.942" y1="93.45" y2="423.333" gradientTransform="matrix(1 0 0 -1 0 514.2)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#e32929"/><stop offset="1" style="stop-color:#ff8d30"/></linearGradient><path d="M243.3 181.9h24.9v147.8h-24.9zM24.9 329.7H0V181.9h24.9zm96.8 17.6h24.9v56.4h-24.9zM60.6 284h24.9v91.3H60.6zm121.7 0h24.9v91.3h-24.9zm304.8-102.1H512v147.8h-24.9zm-96.8-17.2h-24.9v-56.4h24.9zm61.1 62.9h-24.9v-91h24.9zm-122.1 0h-24.9v-91h24.9z" style="fill:url(#hlaec4_a)"/></svg>'],
    'uptimekuma' => ['label'=>'Uptime Kuma', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 622 622"><g transform="translate(320 320)"><linearGradient id="hl77bf_a" x1="-82.404" x2="121.666" y1="38.077" y2="-157.263" gradientTransform="matrix(1 0 0 -1 .001 -16)" gradientUnits="userSpaceOnUse"><stop offset="0" style="stop-color:#5cdd8b"/><stop offset="1" style="stop-color:#86e6a9"/></linearGradient><path d="M161.4-93.4c53.7 122.7 53.7 199.7 0 230.9-80.5 46.7-290.4 61-350.9-10.9-40.3-47.9-40.3-121.2 0-220 41-67.5 99.2-101.2 174.6-101.2 75.5 0 134.3 33.8 176.3 101.2z" style="fill:url(#hl77bf_a);stroke:#f2f2f2;stroke-width:200;stroke-opacity:.51"/></g></svg>'],
    'vaultwarden' => ['label'=>'Vaultwarden', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="#175DDC"><path d="m254.25 124.86-10.747-6.653q-.136-1.567-.306-3.13l9.236-8.615a3.69 3.69 0 0 0 1.105-3.427 3.69 3.69 0 0 0-2.33-2.744l-11.807-4.415q-.445-1.53-.925-3.048l7.365-10.229c1.609-2.23.308-5.374-2.407-5.814l-12.45-2.025c-.484-.944-.988-1.874-1.496-2.796l5.231-11.483a3.68 3.68 0 0 0-.288-3.59 3.68 3.68 0 0 0-3.204-1.642l-12.636.44a100 100 0 0 0-1.996-2.421l2.904-12.308a3.7 3.7 0 0 0-.986-3.466 3.7 3.7 0 0 0-3.464-.986l-12.305 2.901a106 106 0 0 0-2.426-1.996l.442-12.635a3.68 3.68 0 0 0-1.64-3.205 3.69 3.69 0 0 0-3.59-.29l-11.48 5.234a133 133 0 0 0-2.796-1.5l-2.03-12.452c-.443-2.711-3.582-4.011-5.812-2.407l-10.236 7.365q-1.51-.481-3.042-.922l-4.415-11.809a3.69 3.69 0 0 0-2.745-2.336 3.71 3.71 0 0 0-3.424 1.106l-8.615 9.243a111 111 0 0 0-3.13-.306l-6.653-10.75c-1.446-2.336-4.843-2.336-6.289 0l-6.653 10.75q-1.569.131-3.133.306l-8.617-9.243c-1.873-2.014-5.211-1.348-6.169 1.23l-4.414 11.809c-1.023.293-2.035.604-3.045.922l-10.234-7.365a3.69 3.69 0 0 0-3.579-.415 3.7 3.7 0 0 0-2.235 2.822l-2.03 12.452c-.94.487-1.869.988-2.796 1.5l-11.481-5.235a3.69 3.69 0 0 0-3.588.291 3.68 3.68 0 0 0-1.642 3.205l.44 12.635q-1.226.982-2.426 1.996l-12.305-2.9a3.71 3.71 0 0 0-3.466.985 3.7 3.7 0 0 0-.986 3.466l2.899 12.308q-1.01 1.195-1.991 2.421l-12.636-.44c-1.28-.04-2.49.58-3.204 1.641a3.7 3.7 0 0 0-.291 3.59l5.234 11.484c-.509.922-1.012 1.852-1.5 2.796l-12.449 2.025c-2.713.442-4.013 3.584-2.407 5.814l7.365 10.23c-.32 1.01-.631 2.024-.925 3.047l-11.808 4.415c-2.57.966-3.232 4.296-1.225 6.171l9.237 8.614c-.115 1.04-.217 2.087-.305 3.131L1.749 124.86a3.7 3.7 0 0 0-1.75 3.145c0 1.284.663 2.473 1.751 3.143l10.748 6.653q.132 1.572.305 3.131l-9.238 8.617c-2.011 1.873-1.349 5.208 1.226 6.169l11.808 4.415c.294 1.022.605 2.037.925 3.047l-7.365 10.231c-1.61 2.23-.306 5.375 2.41 5.812l12.447 2.025c.487.944.986 1.874 1.5 2.8l-5.235 11.48a3.69 3.69 0 0 0 .291 3.59 3.68 3.68 0 0 0 3.204 1.641l12.63-.442c.659.821 1.322 1.626 1.997 2.426l-2.899 12.31a3.68 3.68 0 0 0 .986 3.459 3.68 3.68 0 0 0 3.466.983l12.305-2.898c.8.68 1.61 1.34 2.427 1.99l-.44 12.639c-.1 2.747 2.73 4.636 5.229 3.492l11.481-5.231q1.386.77 2.796 1.499l2.03 12.445a3.69 3.69 0 0 0 2.235 2.825 3.7 3.7 0 0 0 3.579-.413l10.229-7.37c1.01.32 2.025.633 3.047.927l4.415 11.804a3.69 3.69 0 0 0 2.744 2.331 3.68 3.68 0 0 0 3.425-1.106l8.617-9.238c1.04.12 2.086.22 3.133.313l6.653 10.748a3.7 3.7 0 0 0 3.143 1.75c1.28 0 2.47-.662 3.145-1.75l6.653-10.748c1.047-.093 2.092-.193 3.131-.313l8.615 9.238a3.68 3.68 0 0 0 3.424 1.106 3.69 3.69 0 0 0 2.744-2.331l4.415-11.804c1.022-.294 2.038-.607 3.048-.927l10.231 7.37c2.232 1.604 5.372.301 5.812-2.412l2.03-12.445c.939-.487 1.868-.993 2.795-1.5l11.481 5.232c2.5 1.148 5.332-.743 5.23-3.492l-.44-12.638a99 99 0 0 0 2.423-1.991l12.306 2.898c1.25.294 2.56-.07 3.463-.983a3.68 3.68 0 0 0 .986-3.459l-2.898-12.31c.675-.8 1.34-1.605 1.99-2.426l12.636.442a3.68 3.68 0 0 0 3.204-1.64 3.69 3.69 0 0 0 .289-3.592l-5.232-11.478c.511-.927 1.013-1.857 1.497-2.8l12.45-2.026a3.68 3.68 0 0 0 2.822-2.236 3.7 3.7 0 0 0-.415-3.576l-7.365-10.23q.479-1.516.925-3.048l11.806-4.415a3.68 3.68 0 0 0 2.331-2.745 3.68 3.68 0 0 0-1.106-3.424l-9.235-8.617c.112-1.04.215-2.086.305-3.13l10.748-6.654a3.69 3.69 0 0 0 1.751-3.143c0-1.281-.66-2.472-1.749-3.145m-71.932 89.156c-4.104-.885-6.714-4.93-5.833-9.047.878-4.112 4.92-6.729 9.023-5.844 4.104.879 6.718 4.931 5.838 9.04-.88 4.11-4.926 6.73-9.028 5.851m-131-104.17a6.94 6.94 0 0 0 3.524-9.158s-7.25-16.112-7.032-17.824c1.522-11.97 10.589-23.092 20.547-31.809 10.245-8.968 29.05-12.642 41.033-15.888.844-.229 12.957 12.302 12.957 12.302a6.923 6.923 0 0 0 9.799.226l13.098-12.528c27.445 5.11 50.682 22.194 64.073 45.633l-8.967 20.253c-1.548 3.505.032 7.604 3.527 9.157l17.264 7.668c.298 3.065.455 6.161.455 9.3 2.401 22.942-8.165 47.083-26.846 65.594l-16.082-3.456h-.001a6.93 6.93 0 0 0-8.23 5.332l-3.816 17.807c-11.775 5.344-24.85 8.313-38.621 8.313-14.086 0-27.446-3.116-39.43-8.688l-3.814-17.806c-.802-3.747-4.486-6.134-8.228-5.33l-15.72 3.376c-15.66-17.659-25.993-45.09-26.401-65.142 0-3.398.183-6.756.535-10.056v.001zm21.559 103.8c-4.105.886-8.146-1.731-9.03-5.843-.877-4.119 1.733-8.162 5.837-9.047a7.607 7.607 0 0 1 9.028 5.85c.878 4.11-1.734 8.16-5.836 9.04zM43.86 95.986c1.703 3.842-.03 8.345-3.867 10.045-3.837 1.705-8.328-.03-10.03-3.875a7.615 7.615 0 0 1 3.867-10.045 7.6 7.6 0 0 1 10.03 3.874zm78.492-71.241c3.033-2.905 7.844-2.79 10.748.247 2.898 3.046 2.788 7.862-.252 10.765-3.033 2.906-7.844 2.793-10.748-.25a7.62 7.62 0 0 1 .252-10.762m88.983 71.61a7.594 7.594 0 0 1 10.028-3.872c3.838 1.702 5.57 6.203 3.867 10.045a7.595 7.595 0 0 1-10.03 3.875c-3.833-1.703-5.565-6.2-3.865-10.048"/><path d="m40.368 56.262 35.459-17.039 51.802 139.85 47.083-133.45 40.067 10.639-61.629 166.39h-51.153z"/></svg>'],
    'watchtower' => ['label'=>'Watchtower', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5" viewBox="77.71 77.71 1774.13 1768.07"><g transform="matrix(0.212251,0,0,0.212251,-24.2494,-702.116)"><circle cx="4637.19" cy="7830.89" r="3948.73" style="fill:none"/><clipPath id="hl2275__clip1"><circle cx="4637.19" cy="7830.89" r="3948.73"/></clipPath><g clip-path="url(#hl2275__clip1)"><circle cx="4637.19" cy="7830.89" r="3948.73" style="fill:#406170"/><g><g><path d="M8247.06,8165.71L5510.1,8172.87L6406.04,7148.08L7105.13,7146.89L8247.06,8165.71Z" style="fill:#acbfc7" transform="matrix(1,0,0,1,24.4559,-57.0841) matrix(-2.36532e-17,-0.863998,-3.73934,5.12128e-16,31065.9,13973.8)"/></g><g transform="matrix(1,0,0,1,24.4559,-57.0841) matrix(-0.561358,0,0,0.770447,7255.99,2443.4)"><ellipse cx="5203.24" cy="7389.29" rx="173.813" ry="391.607" style="fill:#acbfc7"/></g></g><g><g><path d="M8247.06,8165.71L5510.1,8172.87L6406.04,7148.08L7105.13,7146.89L8247.06,8165.71Z" style="fill:#acbfc7" transform="matrix(1,0,0,1,24.4559,-38.1071) matrix(2.36532e-17,-0.863998,3.73934,5.12128e-16,-21746.6,13954.9)"/></g><g transform="matrix(1,0,0,1,24.4559,-38.1071) matrix(0.561358,0,0,0.770447,2063.28,2424.43)"><ellipse cx="5203.24" cy="7389.29" rx="173.813" ry="391.607" style="fill:#acbfc7"/></g></g><g><path d="M5355.12,8036.04L4686.77,8036.04L4768.99,7119.34L5272.91,7119.34L5355.12,8036.04Z" style="fill:#003343" transform="matrix(1.34973,0,0,1.30833,-2089.03,-1687.25)"/></g><g><path d="M6620.47,6410.82L3401.93,6410.82L3401.93,8436.26L6620.47,8436.26L6620.47,6410.82ZM6536.35,6524.68L5625.27,6524.68L5625.27,7957.77L6536.35,7957.77L6536.35,6524.68ZM4399.16,6524.68L3488.08,6524.68L3488.08,7956.01L4399.16,7956.01L4399.16,6524.68ZM5531.79,6524.68L4493.88,6524.68L4493.88,7956.01L5531.79,7956.01L5531.79,6524.68Z" style="fill:#fff" transform="matrix(0.997174,0,0,0.997174,-315.312,829.075)"/></g><g><path d="M2113.47,9142.23L3019.92,9622.83L2801.72,11189.7C2824.57,11200.4 3343.39,11651.8 4654.7,11673.1C4675.73,11673.5 4670.49,9057.34 4670.49,9057.34L2115.03,9069.63L2113.47,9142.23Z" style="fill:#003343" transform="matrix(-0.993484,0,0,0.958612,9294.8,545.424)"/></g><g><path d="M2113.47,9142.23L3019.92,9622.83L2801.72,11189.7C2824.57,11200.4 3343.39,11651.8 4654.7,11673.1C4675.73,11673.5 4670.49,9057.34 4670.49,9057.34L2115.03,9069.63L2113.47,9142.23Z" style="fill:#003343" transform="matrix(0.994692,0,0,0.966055,60.6784,477.843)"/></g><g id="hl2275__-Triangle-"><path d="M4653.57,5342.87L6434.56,6886.04L2872.58,6886.04L4653.57,5342.87Z" style="fill:#003343" transform="matrix(0.997174,0,0,0.565134,37.0656,3298.6)"/></g><g transform="matrix(0.997174,0,0,0.997174,17.0834,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.997174,0,0,0.997174,804.851,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.997174,0,0,0.997174,4006.77,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.997174,0,0,0.997174,2885.98,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.997174,0,0,0.997174,1941.29,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.997174,0,0,0.997174,4794.54,195.597)"><rect width="72.367" height="397.142" x="2242.94" y="8697.8" style="fill:#003343"/></g><g transform="matrix(0.996678,0,0,0.997174,18.1958,190.14)"><rect width="4864.36" height="84.48" x="2244.59" y="8703.27" style="fill:#003343"/></g><g transform="matrix(0.99974,0,0,1.06349,-3.2116,-273.7)"><rect width="3555.18" height="59.635" x="2902.63" y="7016.56" style="fill:#003343"/></g><g><path d="M5929.64,11166.2L3390.05,11166.2L3312.53,11902L6008.2,11902L5929.64,11166.2Z" style="fill:#fff" transform="matrix(1.27611,0,0,0.983565,-1266.89,-1191.68)"/></g><g><path d="M6069.48,11166.2L3219.12,11166.2L3141.6,11902L6131.64,11902L6069.48,11166.2Z" style="fill:#fff" transform="matrix(1.28347,0,0,0.997174,-1276.87,135.767)"/></g></g><circle cx="4637.19" cy="7830.89" r="3948.73" style="fill:none;stroke:#304954;stroke-width:88.34px"/></g></svg>'],
    'wireguard' => ['label'=>'WireGuard', 'svg'=>'<svg version="1.1" viewBox="0 0 300.00378 299.99999" id="hl3604_svg39" width="300.00378" height="300" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><defs id="hl3604_defs1"></defs><title id="hl3604_title1">wireguard</title><path d="m 299.74526,145.56 c 0,0 6.9396,-145.56 -153.04,-145.56 C 5.2252602,0 0.80526022,139.63 0.80526022,139.63 c 0,0 -20.81100022,160.37 149.15999978,160.37 163.02,0 149.78,-154.44 149.78,-154.44 z" id="hl3604_path37" style="fill:#88171a;fill-opacity:1" /><path id="hl3604_path1" style="fill:#ffffff;fill-opacity:1" d="m 101.94526,94.697 c 30.017,-18.364 68.366,-7.1401 82.735,20.476 2.7233,5.2338 3.0694,13.291 1.3447,18.782 -5.9546,18.956 -20.014,29.587 -39.312,34.103 5.6892,-4.8707 10.218,-10.394 11.659,-18.025 a 26.402,26.402 0 0 0 -4.5425,-20.956 26.76,26.76 0 0 0 -30.811,-9.3892 c -11.881,4.5111 -18.389,15.354 -17.216,28.683 1.0898,12.381 10.484,20.405 28.061,23.453 -2.627,1.3904 -4.6503,2.4144 -6.6299,3.5172 a 63.918,63.918 0 0 0 -20.544,17.868 c -1.7839,2.4084 -3.0104,2.6024 -5.727,0.94116 -35.338,-21.61 -37.609,-75.844 0.98226,-99.453 z m -26.449,133.53 c -5.6769,1.441 -11.178,3.5742 -16.981,5.4775 2.8385,-19.151 25.265,-36.788 44.23,-34.776 a 48.881,48.881 0 0 0 -9.242,25.893 c -6.302,1.1606 -12.241,1.9414 -18.007,3.405 z m 120.79,-186.98 c 5.6099,0.20612 11.23,0.12091 16.844,0.25378 a 29.052,29.052 0 0 1 4.1674,0.58069 40.607,40.607 0 0 1 -4.2357,5.4332 c -2.007,1.8701 -4.2745,3.6986 -7.1661,0.856 -0.6955,-0.68372 -2.3386,-0.52679 -3.5487,-0.54272 -5.5823,-0.07336 -11.172,-0.25177 -16.746,-0.04132 a 104.04,104.04 0 0 0 -14.425,1.473 c -0.89368,0.16046 -2.2299,3.1315 -1.8191,4.227 0.9693,2.5853 2.3833,5.4363 4.4779,7.0898 7.7403,6.11 15.972,11.596 23.748,17.664 7.556,5.8966 14.589,12.358 18.875,21.253 5.5843,11.59 5.747,23.743 3.3388,35.95 -4.0203,20.378 -14.333,37.261 -31.032,49.524 -6.7288,4.941 -15.06,7.7451 -22.767,11.295 -6.778,3.1225 -13.755,5.8115 -20.549,8.9008 -12.249,5.5695 -19.133,18.865 -17.108,32.688 1.8585,12.685 12.987,23.271 25.735,25.456 15.292,2.6216 31.071,-7.3163 34.812,-22.86 4.2067,-17.478 -5.2898,-33.083 -23.065,-37.813 -0.78271,-0.20831 -1.5684,-0.40552 -3.2012,-0.8269 4.7549,-2.1245 8.8614,-3.6381 12.653,-5.7244 q 9.9213,-5.4594 19.481,-11.562 c 1.8742,-1.199 2.8868,-1.1996 4.4852,0.18225 12.225,10.57 19.518,23.718 21.563,39.839 3.3845,26.684 -9.2471,51.198 -33.072,63.762 -36.86,19.439 -81.965,-2.6864 -90.106,-43.552 -6.9738,-35.003 17.73,-66.754 47.462,-72.884 12.787,-2.6364 24.48,-7.9596 33.57,-17.807 5.8652,-6.3541 8.7084,-11.806 9.6772,-14.266 a 39.565,39.565 0 0 0 2.7211,-14.469 33.867,33.867 0 0 0 -2.9654,-12.398 c -3.104,-7.075 -14.995,-18.33 -17.939,-20.704 l -28,-21.921 c -0.98761,-0.81256 -2.0994,-0.75366 -4.5079,-0.59045 -2.8611,0.19391 -10.175,0.59888 -13.331,-0.22815 2.553,-1.9321 9.5132,-4.7451 12.502,-7.007 -9.0734,-6.1297 -19.43,-3.9158 -28.941,-5.7461 2.1992,-4.0959 13.081,-10.39 19.27,-11.091 a 91.533,91.533 0 0 0 -1.6876,-10.281 c -0.37781,-1.3917 -1.9312,-2.7408 -3.2864,-3.5355 -3.286,-1.9267 -6.7694,-3.5167 -10.549,-5.4327 a 21.936,21.936 0 0 1 11.332,-3.5055 42.316,42.316 0 0 1 11.348,1.1056 c 6.7422,1.5405 12.124,0.53491 17.488,-4.048 -4.222,-1.7002 -8.4435,-3.2535 -12.538,-5.0907 a 123.04,123.04 0 0 1 -11.779,-6.1583 c 10.622,1.4755 20.896,5.4585 31.757,4.0034 q 0.1387,-0.74048 0.27728,-1.4809 c -8.1194,-1.8899 -16.239,-3.7798 -25.229,-5.8724 15.04,-1.3769 29.042,-1.604 42.301,4.8541 3.731,1.8173 7.6348,3.3215 11.211,5.3972 1.7443,1.0124 2.9186,3.0078 4.3496,4.5594 1.1366,1.2325 2.0495,2.8837 3.446,3.6264 5.3,2.8184 11.134,2.9291 17.078,2.7879 0.0444,-0.67694 0.0861,-1.3114 0.1308,-1.9933 5.9821,1.8693 12.715,8.7679 12.704,13.806 -9.6911,0 -19.374,-0.037 -29.056,0.05389 -1.0348,0.0097 -2.0626,0.76563 -3.0936,1.1754 0.97986,0.57067 1.9428,1.5994 2.9423,1.6362 z" /><path fill="#88171a" d="m 183.78526,26.906 a 1.4806,1.4806 0 0 0 -0.18927,2.3686 2.2326,2.2326 0 0 0 3.0724,0.8219 c 0.9328,-0.47052 1.8478,-0.97137 2.975,-1.5665 -0.9079,-0.775 -1.6362,-1.4148 -2.3857,-2.0324 -1.318,-1.086 -2.411,-0.40386 -3.4724,0.40833 z" id="hl3604_path38" style="display:inline" /></svg>'],
    'wordpress' => ['label'=>'WordPress', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M36.4 256c0 87.2 50.4 162.4 124 197.6l-105-287C43.2 193.6 36 224 36 256zm367.8-11.1c0-27.2-9.8-45.9-18.1-60.6-11.1-18.1-21.6-33.4-21.6-51.5 0-20.2 15.3-39 36.9-39 1 0 1.6 0 3.2.2-39.4-35.6-91.8-58-148.6-57.6-76.7 0-144.2 39.4-183.5 99.2 5.2 0 10.4 0 14.4.3 23 0 58.5-2.8 58.5-2.8 11.8-.7 13.6 16.8 1.6 18.4 0 0-11.9 1.4-25.1 2.1l80 237.9L250 247.4l-34.2-93.7c-11.8-.7-23-2.1-23-2.1-11.8-.7-10.4-18.8 1.4-18.1 0 0 36 2.4 57.6 2.8 23 0 58.5-2.8 58.5-2.8 11.8-.7 13.6 16.8 1.6 18.1 0 0-11.9 1.4-25.1 2.1l79.4 236.1 21.9-73.2c9.1-30.8 16-52.4 16-71.5h.1zm-144.4 30.3L194 466.7c19.7 5.6 40.8 8.8 62.4 8.8 25.6 0 50.1-4.4 72.9-12.4-.6-.9-1.1-1.9-1.6-3zm188.9-124.8c1 7.2 1.6 14.4 1.6 22.6 0 22.3-4.2 47.3-16.7 78.7l-67.1 193.9c65.3-38.1 109.2-108.8 109.2-189.8-.1-38.2-10.1-74.2-26.9-105.1zM256 0C114.8 0 0 114.8 0 256c0 140.8 115.2 256 256 256s256-114.8 256-256C512 115.2 396.8 0 256 0m0 500.3C121.3 500.3 11.7 390.7 11.7 256 11.7 121.6 121.6 12 256 12s244.3 109.6 244 244.3c.3 134.1-109.6 243.7-244 244" style="fill:#0073aa"/></svg>'],
    'taskflow' => ['label'=>'TaskFlow', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 96 86"><image width="96" height="86" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABWCAYAAAAwu5OIAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAgAElEQVR42u19eZwV1bH/t6qXu8zCMAzDIrKJiICIgAqoqIgRQY0rRk2Mxi2J0cQYoz6f8anPZ4wxPo3+jEaNSxY17rgguKIssomIiGyyM8wMM8PM3K27T9Xvj+575w6Cmoho8jh87ufe6T7dt2/VOd+q+ladA7C7/eu1iceO3i2E3W132912t91td/t3aBc88AoueOCVL3WPgyfdjIMn3fIv99vpm/AQA19urcoo2Z9MKKn5R64bOv6aypSPc5TsUZZtVxgjraSyyoH/9wMPHjr74ZsmfeMVwLviS1a/eNgOzx3+ymak3fglGo9dctrUxi98z73GXD642eOPyEncxrZzqoLHsWWfSLb7c99Ovj3r3cU//7LPfe453/33UEBXd/m4lqlDP3X8B68usVdzxR0BWf/pq3XVHCq99aSXN9hfaOraJVey7VarKlQViuhdFUzMsNzLzrryr1/quf/00J+/ctnY/8xFV1x+Em697Zkv1LfpnZPh8LQrWTatBrCi+NyA9Npgk1N5xYfcudwosvtq+spnjt1Dtnefc657krc2pypiiRhKEnG8P2fRa0Hgz2cKUZSIWQFR1fCHxS3Pcd3Ks//z78hlc4i7Vvrh/zkj+3/OBgRvdTnRSqae0oD/kMXoi5Ojpnyqz76vZfunPZNdc2zJ2u3d48RLH7KXLd/wMscSY4mo3UOrKkAAgaDIK6NwFkThJA+8XGtrQ/3xa2b8Zvr/CQXkpvdL2qj7AcXMzWRpEgDUt+8y0uFm55C17Yzt5U+HMrnt5DEAgJ7DL+7nlHUeYGBc8r3AcbjJ7dD5DbYszgtYFSAiAPrZPyXq4ucyYnLZxyw39jYTMQNMeRhWAVsMEAWSS60a3o+mPHjXf/3rKiA1uVu/WFnqb1bCDAOBAQgIDFJRDw1+utM1q/Cd+/Ydd+unrj3vumd5+sz3P0mUV/YwYuCnW9cb33+ALOs6KDgUuLZJtvid2n5W0UcwW/CzKSnv3COyFWAikuhSBlSIiNmyJMilIanW8xa/cu1Du0IB9ldx09X2/iv6uNmDXe/D8exm7iXWHlB44jnXmaDrXbFxH7cCt2732pUfLqz2slkYNK4HsZAJXoiz7J/2PG43aqiAQRHmUJEy2qYJEUGIpGNZDJUl8mxNXUsDAVASQAEVAUBs7NjJ5RUdy7duboBD1qBtn6vu5XPdjnhuhor11LKt5b8edObKb64CBh07BQAEwEvB9OrjOZF6V337xrXBmF/3OXzy5/j2x9R8Z+DevX44uip0BX/7Km44+6D+FvN8iygBAgJRWEwQKcZ/LZoItI2NIDDTB+vrUk+M6N/50+7mVX/B7PdX9/AyqXFkWfDS3vJt+5Sb6SO4Y26EZO1XrVbnmz0DitsW68iFnXLTng1QdVex8C95ezNeq4sNzsCNs2XDUYVFRqaCMKVWMfDpVtgWB+8zyUmzBLbq0zYjIKiwIGBLwSoChoiBwGJYCjGkoipQoyAomAnEKqoAk1P5rZe2wlGDUhLp7WabbhnXAz26d2ZevH6o72UBWCi1zYJtf4flNo4GiWgm+bcBpy/95irgLzPuwMfs2jeM+lEAAF0OeRyZN/pfszx5fBNwW6Hf5LoOPzeOc2vkuXAOEIUDEBgKwIYwAGO0mS0kwWQD4AjqJTSeoW1RqwiREJ238/Yiwvoo5lFASMGsRt5dWvcHABfPX/BxTyGuggQgaPawg6qWLHprG2/Ocx+jRlPjJfdeBGwqHG99aYzLvKUiOf7D2l0WiF15x+U7PHdr2T49/lLa5zvFxxJHLlsx5ODb2v8gsr8NEEcvAMQEYlICg8BEzL48X20He9lGrmAiZiKEbigxEYE4fM8fR9s7h58ZROF3EKJ/RExMUD+HhGbP/Kgm9auDh/W5kQkMVVgki39+yLp08Er1mWZa+aPyaunjMrXDTbYt/blsn7+WjQ09Nn/6YTCvVI9OlHwwN55Y/3Hmxb7Vu0wBt/z0tu0eH7JgWulmi2/PWHTjoEUv99vR9d97vckV0FBVQEWFAvOqCbQhH0SpatbygysGdpJTGlu8Uk9wVn5056F+e69t/bri40rtz0m6FQdUueVEfJ2SdWYsFkMmnYalsqAHnh9jlTY/SiX+mdSh5FTq0uMq7lw5zcIn75mpVaMBwPIXXkpl2bfIkQEqfJ8g0bTLFPCzP1z3qWO/mPkEGlgPFeYRhri6me3v/HT+5O1C3Lxmd4gwl4IIEHnn8O7+0a7xjhVB2hhZbxlz1MAK/7fvr2wZ7znufCthH6QocnSK8QbtBZ4/tq3AC6ei45JN896VCcRtwsrVm2ECH2xZCHLyLidajwbAcKqhyX5QtwtrogdQNWgwlbnTgqkdxxvjLtAMT5Vm+8hW0/vKkokfervMBvzvD6//1LHfjp4EAFN6LH79NMM67rxM/a//68DTt3t9q6ejmTOsBKiI/cE6z11zauWcno9ljlSWtVVOS/38ZXQLd0j8wnKUxcsWSU93+FyajwUiz1Tbuantox/T3CQD9uuKhMNYvmYz+zkDhYXKZG4Bs38uhKCUBKkAKvmbMUp7xSm3+E/qdR5kHbVqYuRqfL1G+OxZL+KRUeGz9A+yCzYZa8WOhA8AuZbmo1R9qAJgHllD/Oghz2z63oyTEnP6/HFl9zXiToPrHCHplASp1rbQtxB36Q6UQeHIj/x/qH5K8AoIEbG9tRGDu/VHXX0ztmzNhN4SKH3DqevWkh0MhQKU3giYNBCrBNiNtCwg267mXNOZAO76RnhBb8QSP5+08PXfPTF0LF4fOkEA7BAPb56x3L59STBaNeLcBGyAScu3qNv1vtWPt5B1OxzuCgnacwmR0FU10kGe+VEoKNKRgoqVpLpdDNJcGnt1TCBmMxYuWgd2HKjvwbZ40aHdXh1IlimHEiA+KL2ZkaoFnHLALQGCLGByIMveHwCap41hB+juW2X1tVtrs6rA3ifP3TUKOHHem5gHc2LG4RvfERMcveC1/zdt2FHBZ10zY1Oup5/TSovaQ4chOtEQnUgiUJX2ME9twzfEd41AXrcDQ9vyLJGCIutNAGtLPYb0SYIZWPHJZma2kfFTiDmxBWz7g/PfZVYy7L4qUDC8ZpDfDBBBQ3zLAkAit+QnVjJzuwsn3bO05Hn36A1n7bIZ8EGA3pk4nWdB4gFZp6+weB6AmZ8Z6ZY0r+/SURc2ZmmoyUNrYZTnqUzNv4kW5E+ACBAJvzATigQdCq6Ymoi8qjxlrQCY0KG7jVP364aYbWHZ6s0QY8BsSZD156tnmuAqNEvA1uixPIKmCVQpod4BSOC+Ft6cXxU/+SDgjyDjNX/01Cjse8qsXUfGHT7nVXtxzHqut4/T5o84Mv1FrtlQt9pNxEr7k6orYBuiTFAhBQuDWSHKFod8DbHFDIgyyAJBoESwOKSfNYTlMC4ISR4I5ecVQUOJMUAwSqxKaEr7Pepb/Qf6VJfixPPu4LrGlIgyqp3c/i9fOnVZGX+0hjKmq9RbYvc2LMssoCeES4WVIBo4Mz1r/yMTR04vzPb1L5+A5nQaA0959atnQ3/60C2445wrC3/3X/Bq9bJh4z4VCX4y+2Z0a7zzB04sM4qKyTOmUGwRdkcjV0DKIBaABCyCkKgECKJtDI8o8naAQNAgP8rDCFmjPqHFVLVz2Wz/OZmRr0ypKisPbdaHtePLS5wXYyo46YLfs6iCyG49f5zdcXTFlODgPRY+Y7f4JyqzkC8MZlA3FbCyivO6ZEvPcCZsqN0Z9vOfgiA27WF+e8IHgKraxwe4Zak/whKmEIBDPCUtQA1AUN9apNA0OzIyjEwIYG3nQRYzoFQE9KHgi9RT/KYEYh/Y/KHQxrm/Wbdq1tV79h0FJYyIOxaWLFoNO+bCy+aE1F901VXXBiufu8AWem+wgkFlgG5ksfoaNoG7SgP7lqyz90NlE2YHX2tO+PbzrvlC/eLO5pGwhCnvQqq2eTSq0AAiKet3GvQ+WP2KiepLtmBKC8LXyJC2XR8GWRrFVNvEw1oU/5ICpDApA7+kx6VWomsSoRkYHnMsLP+kJqKUFGpkHgBc9Jc9x1z60pl9n900RJr9GLiPIPBij/nafz9nwpb7y44Ohf/I1D9/fQr4xb1fLFvEljcqEoxohp6WJus69RFAAM2iCanYaew6l2vThh6Ua3iNLMRVi4SuEOg2pIK2cRFRcqVIucVcRagMk1MY6gQ/0a2eNEjP+LiWjeiImGNhxdq6kK4GQQJvPgBs9ayj5m3shWumHImRt/8Ip95/tsz6ZPA1iWPmFvLJd0x+GrfSt8+8c8qLX48CROVz+9TPuhyg4CBAoUahmcRtXN75v2Wre5608my0uoeoK0+bNcHJlMjO5Y46FFYeX4peVDSaCyNb2v4mQd6/bH9dFPGmFKZiP4jykpZMAM+gh2tb3RmKpSs3w5gAAqAsrgu+fe6tSGeyo5mZO1ZVc2nnDljZ2n3xhtLvrcr/rvEv1vDdzsRTG6nk3tudcad+97X1X0Mgpp/fxdn8boX6ZmAI5MowmatNc9Up9slNj5hp/R4RE9iyfsutVlnwcyYwGiKYIc07jwARa94lLbiYUTBVlPnSyJZQcTYsMh6mEQi6DAITL+m/17547cO6g5KuhcamFNc3pUTJAilah44YvKyprh6Z1tR0A+uIoKkJsUSJxG1r6hlnX1T4XZvd0p4BrO8zUdIE1sUfcPlSAIt36Qz43Y9u+HzNtq4eDaitkWsIO5hA6Q1/yT0/yPVrMlWmvu5lq8z/BTlgNcX+PBU7aFIwrro9By4KzpTaHdMi5y7IEoLyoVCV+dH8Pdi1mT9auk7YjTExw7Zo0cO3fN977sHLUZHgB1SQdd34Iq+p9vSBe3W+uvhb3zu6dPU+VvokW4K/7h9LHfP+0eWLdzkEXXLf9Z95fs6U80FBboQaMEzEZQXECv9k3rr2LTaN79nlMo4AaKCAUeT7wWiYbglfrAKoCf/WfB+JHM0IffL9C+yDKFQAMYDXAmjHwQiC3KIP19SCiIbFXUuWrd4MIhYAUGPm5Z99v5EHrO1ge4MOPbDvActn3Pn0o7+78FN4+/LY8qCnZH86+YgOX4gB/cnlP9y5EPT7C6/73D7NmdKslU4JGWVqN269kUQAWtsGcYG5bOdyasHHoWJ2nyOB5zG/6OZtnm0IS0GaEdAe0A69m8lLLUsFlq2qQ2M288rVtcJsgS0VDbLz8/d4+L/PAoBVc14GLr1/Ju48P1wNdNHzn2CZ2zH+xviKLAC8eUxZwzc2J3zQ+PtRs+7tu7yl8/Ymjh0UZmYRUbvbpko+hSzchjjEEe4XooMwmECe7eQCz0Phucg5ZRBDYAt6H5pmtm/q2b2b98aSuoEJlytUFR+vqYMxBkSMjqX2vO09SrZbX3z/7Rae3hg79AXi89RHFsBF/6g87rrtD7s+Kd91z8PSAC74rD5vL/o7DhtyGgDgpaWvYMKAY9qdX77iZcShCNiBJ4SAFB2cEgQhjw0iCxJFy6qEuJ2EwoKqQsgBQPCNoF9lMk/LDYtZFpqaUlLfmAI5cVgqzcdMGLNq9ks3F773wlcb8X42VvWCcc7UrXweuTxYFHBy5lgA+OGLy/CHif2/sCzOveyn+NPtd3w1Chj99vPJT2K4ERZXhMhRoNcEpGAiCY1pmJnViMhkgnxXCT0XvgAilR97Bj0XvRBm3QEhBr6VFggISgE4RHkGbYUAQmxBRfNjXiAKSnubftyx931X7LN/MwAsWVtXIUQ2gPromQ50bMaHi9bCisUAsuASFl//k2M9ABj7YktyqR//1uQMn2GYj7NjiFM+mW907aiO5vV1O3mgfmkFrCM9M4gnfwZGviaHI2yXaJiCABgTrGXix5j5QrW4ooD3hHZ8AxEhMq9MRKIgLrihxX0LREboR1hMyG5oggbOWbNWbzxkVO/uaU/5uoRjDwNw+KJ19ahvNkNti7BqbR0ACyCGDa8AP8uy9nEV5dY9jQFVhkWQEaekAIw+ETPpAMA/NPoBwCjtXC8o30bMeBIZxx4lAKuCNSxPhihgBGwUMEbEz+SmOi25A0e6VVf7nv+mtHN0qOhz+IsV4OhvzvdRbHsNFTJgSgRYNlJbUugXLxviOLGuIeXAQzUa/dmcugAGE4Dlq2sBVbBlIYHcXABoTEt85Qmx3m8fCvuAsjDS1Db+UJzA/M3if05cj/zv/341Chi750CAaHQU+Aiy3rPSmvutMSKikMAYT9K5a/to4tiO8dL09Pp1f9KYe4IWlRLqNv57gWIOP0tbbNt2vpB+LMrSqwDa6mOvkjIP0JoPNjQxgAEEWhLloQcwaYUxBks/2QwjAmMCDNy7eqEXyHfLYnh/XiPdPH42lb+XCkuqI7dW2Oi843vpgoeO6LDTbeWXgqB569dWBTHuZ4MQGIM+cC/r1aP72jfWrMgiZv+QfTmrcdTxUze89mS/1mTsmWSn8oFKBYgCba9sgcLcd/sC0PaKysMRFfmxQUsOfZPliLG1fktra7rSLe3JQJUCHwCACeQgkMGWhhbUbmnheLJUJo7qKzddfsIzTT71u205+JGNeRobiBNwRV/F4xuANZ7ec8/w2FfirHwpBXyUah3tOyUceAEUitVZ/9QBGv9dJ1jXckpuWnH48dkOU/5+XFPMedgtjVfmcn5b5UK7Ymb6VIZCoyIqjWhRanddEQEdFWOZpjQGJcpgES0e268H5q9uGkyk7AVmMQCkPW+ULT7WrK7Bj888XM44bjhKOlbwnSvQ/561QKu0IcKIEsXv9iPpW6KrMkZvXBTz//zIV+QtfikFZJhGpbN+lCcFZyC3vrTs/dbjevW5b0ldXVAy5YnrW0rd/4Rjcy7ltQm3SODF6V4qzk5GxlsLo71oJQBRYX1AgeloTmFAeTVsomUhfJghArS6Nq9oyQQ9P9rUOLpLWUccPawP6j3Cw2uABxcBW6VtDHS3IJfvpTi9Jwmgf8565qe/2NdtxlfYvpQCDPHIMOqMWBZibo7h7mfXrtnLFzPUL4t/S4lBRvOJ7LaKBaJPUTy6zQctKkHZXr88lUwWQ1p87NOtDEZ0eSoXHLR5a/pYx2LuWpF8l4DBg/fsZL9VR3LdQmBqPdhHocYUvVzgol6KM3sCDmNeIHJlY0be7F7u4qtu/3RO+MJ5b7mPtjRuzjlWhUYcgH7qtpGAtqEatl1MsS2dkBdsu3SYbgtVVMRGKOiDOsw5ZiL6dSzLJmOWKyBevlUxt4kwoxHyRj24ua26BRUMjKuCnN4dOKQaYMXMwMitac9/obI0LjtTyCf/6BI8fc/vd+4McIUk3pitDVTLRcFRLQm3G8GRK0F5V1GL7SoVpNH2MZ9Ib8P94gVI2kZBhBAV+aWUCbC3UyLljtu0tMm/7PZl3hULGqyBW9hmWIQEg7vEGUOTiuEdCId3AkZ0ghhj0JzxsGazvzaVC+aJapwIlfnAbWe1HQn/S1dF/L85Cwc3pXO3xR2rB1Qj4StTG33GRcUhUSzcTg/hysaoJL0dEU3EbcJv4//1048vTCyju3dr7pQsualfl7Ina5tTbsx1ztmawzExm0dUxLiSCchkcvE5Cz/hWQtX48QJByKWiBXZHWVRiBEJjGJOzgv+GIg+MXLv6uw3EoK21+6fOROWbSPwBCO7dO0bs6zeeaymYqhRgAu2I4pnozLzfOgZZR6FCkQcIV8NEXqyKqLRkcKMCe8b3YFXb9zCz0x+p/STzekDa7a0HpHK6ghyYjb5Hp558KeQ9i4wa1QEqhp+V8YLpgeqR43s10W+UUb44vvvx93nn/+p4+ePDqnbWx5/Fadce89Ndiw5SYyg4Kq0FUqFkKWhzAFi1TxVWlRcRYCKMiha0BV+LhJalBst1HUpRylhibhUsOOAyQLYYaEA4udk5KA9kPF8iGib5SqalhS5tqraV8B5Avybo4DtCb+4+bkAlm2/BtuZZLkRq9IGS1KIwovJHM1HX1pc3SZRBRyjqNa2bZlqkc2h4uq6fISveYMuxs/B+B5iDuM7Jx+CnOfnQYDDSrzQ6HOoeA5E57R6wblHDtwj+JeBoOL2l1cW2C+/OOvlOUs2jYXlIFyam480I1uQL9Iqrmpo56aSaGRbCv3zOeF8cW67cvTCLAuJwEghJvDEBB5Xd0jgiku+Lb17dSusqqFwGY0wE4toINA5onoHLH66bmtTMHFof3zjFHDx7X/E3Zdd8Ln91tY1xZcsWf2z9z9ad9HqDY09tzQ0o7k1Cz/n50l8Dou1ihIrEbQoEVPI7XF793RHVQJRwKZR1V24NAnEjM5V5TJsvz444vChsB0bBMqniCCqWaM6xzfysoh5vjQeWzqkVyfsqrZLtqt5a/FarihNDGbiQ4n5AItoAAj9GVRBDDuaEULUfj1F3u3Mj2uOqG7Nl0/rNsFZpCxVDUk8VYgqjJF8DVGzKFYodImIvG9E5il0wcH9urXia2pf235Bs5dtgs1U6tpWJRF1VdEqi7lKiaoYiBNTR0BLo0V2tqq6zOyKKCuUmTg/I4LQe4EnqgFAHlQbBNiiqg1GtUFE1gp0o+M49QfsWfmFDep97yzAhYcO+0rl8E8Z4fNvuBf3/+qiL/XFI/t3Q5SabwWw3U06Zq6sBZHCZiuihn0c3Lca8z6phUUclh1G5CoADO+5c6GjxQTY3b7Gdv3rs7+ZD3bmL/+wWzv/Du3Op2btFsIXbUddEJZVjL/oLpzwk3t3C2Rne0HX/nUeHv/jE0/Bjg8Oo0IOAxotomiiSoX8ci3jeei3V6fDptx/2U5hDw8+4boJrYFzL4crZWCzufG953+1Q7zr+sysh9MxZ8y2a4GLowXbD7xv9+q0/0ura6/OxNxzoIBtTHBgaWLfqUfu733dCih4QS2bNkEtZxjY6h1moUii8DLia/IcGoVRJgPkOEFlddVOcxW2tvhVXFbSI1AFMcPLtHzmmrMW2xqQskPCr/2Y0gJfEVdNpw2CZqUeWcvuCSK4xPXd9+z8jXBx2qoiBLaAK1XzqZVoFUvbK6zxLFp/K8Zke/fpld5ZD6PMVVGdCVQgMMEOZ9ak2SugouWF5zEGlPMAzwM8X5DzAM8HfK9ZbUcsRnW+L6s2PdSv++fGAyNfmb3rZkAm24SgZctpwjFhBWzXZbjxM8hyzokGlmSamv+HLXrbhLtMwWIJ3nr7/Z02jQPljtGCIYgxHLP8dnvLnXbDE/j7ryYVxnkgphJqAyB0SOceHFJKt3hqospphTEGrlAwt75RjGoyHy6rkR0usBs0dQ5AFj48ejhmHzNy1yng3mu/FwCYWnyy31HXHpvfa4cAidk0a8Xbt0wd/d3b0bS5roen9qS62qZL+o+/sXfUp16MmWeL/6fqPnsufev+sCz73Jufxcxp746FkzgNzMNEUWoxZQEsM7nctPI4PzH/xf9qtSzuquEoFRME7CZLawBgv+N+VZH17DsWzVpWve9xN4tnvKb6GUsu016V5Yh2zYKa9W8fM2bZ9n7kHlPmgom65jkjC1JYyX/kjEW8qGbrCdl47HQBBq70kSRIU9nk2fNKMtkHaiYdMW//yTPKV/vyqDgOBECHdOZv8dLEY6smhgrq8ezbP2xm+1h1LHRoSd24ftKRhWq7rk9OPzttW6cogWN+rnVwgr//5sQjvC8WCVtcFVUni4iCJVsLAFs2bhxgnA5zLcdO5qEsTHATLAdjA+P+ZNOq9d8D8PTZ1/4V77w2/x5OdLiQosoyK6KXoRhmlbiTmlqaBh1y7t2X16zdXEp5SBHxBg0b2CzmP8q3Zq3JbiJxqKrCM9LMxp+4pTqZNaJxiADMUKM73DHjwKoKvLy+rrTAuBqtBYA+f3+jYlZD+u/ZsrJxbXtQ5KltZ0Sr456ffG72nZVdKq/4uGZL92wiOQIKEebyMd27PLYKwMRZS+1pDc1XerF4TxCgsWAZgHkAcMg7H/C85uzVOccdACikVe5/c+Jh3vZtwPYwWbQycis48APsuUd1AwDsNaDfChV/o4jCeF7aeLkVgZerMWKgImDmuLHsew4/45bS2e8smsCx5IUgsJgAJpeeHmRTT/qZ9AI/l80az/Msk7l3xp8uBhF1VRWoCgOaXrFkmb01bU+2Y/FDxRh4nteEdMMxy6f+9ztu4FULaeSOBcjYdHXsyekfxZ5862P3qekfxZ6c/lHpE29+OP6dD+I5Jii0qmADIPVHv7sUNY77aNZxx4X3EDhZrymezS6zsrksjEABTsfdn83dUPdLN5d7CSKAGPYUI2vXbaoAgAWr1o71bKd3/h6BZR131sp6BoD1G+tGesT9YQwoCKRTOn3Pjo3wdp3UcNoqJNxeMJ6oBYC6Lc2BSbdcFjRvOWbo4C6d9hvSb+9hw/vsyV7qYgn3emB2Y1WbN9QNdeLxwyOKGWJMcwzBBf337nLa2LEHDC+LeXuaVNMphx8zZtlh5/weokhCIxeAqHljfW6yFU+MgQqCwG+wc83HrHzzN7MBYH1LLgkrWsgtAt+2u+ZcZ0DOcft7jjMgF3MHeJbVk2zbM74pDRRuvvA0Fpi6RZ+sG5tx3Akw4bKbsnT6oUEu7TGqzx777JVJ7x3PZJdAhCHKOcu+str33yDPA4zAWJa7tjU1DgBa4smzohkLGAOPrf7zFyweAABbHPdcBRjGIJ7Nzt5nj84LvjAZN/y0G9HU6Fep5FcqSuvA/QdkXwunBmIxa1FO7FMXLNp4ETFXqigsKyYiIlZIHCOV9UtdK7fGSrj5YtjyrHE/XrqspmbZ8s1LxOhbrq2P/PGaE3Her1/Cxg311fndD8ninjY7PaACI4q4pE9b8uZv5hQe0OHqsLIntAFOrmhfy3wXP2hobEmhqb6pQuNxO9xrgiSXzdV6cfckSOgI2V7QPCDpXDJ3/EFpAKiaMnt9h81N12WrKh+HEvu2VZ5Wiru+tyrnuH1BhEzMPXbU5DenzAv4RBgBabiiVtjizYGM3wY5GAUAAAZuSURBVP+p11Z/qDgVgQGgSKZSd0876VB84Rmw76B9IGLKNVp8ZRFaZ8xeHAw//RZs2Vz/Xd9KfmQnSm5jxzmZLOsItq0jlHgsEVhVWEUQsxFUVcQe8tOtM0VFVAwTEyzH7U6OO9aKJ2/07NIP+o755Rhbc6yKEKdVhACBClTC2uisoe+fe2ebj5BSSkYbfwJG0CsIDhnZrars0O5VZSO7VJYdWN2xw/CunfY22Yx4gV8R5snC2ZJUaSKj/SDCUIWjsnbu+IMLOYH68SPRUYL1MGF/qLKvKHc8//kIajggHreqJTjRt6xSGCMVzc2P5N3hrOtMXN/qTQhsuwJi4GYzNft2LH36H6KjSy1TqqLJqDoTKlIfiKBl7Zp+WtrpAYvIFROIyeWesC3+i21joxgcFLBzt8XMxhiUxJ16xMvTe/XWI1es2HgcO+7xSjwEbA2wHDeZnxXixK6vWb/xGBWpVAl3JBERIQnmkeWOBBSwY99988kpLwJ4AgDgWhX5EUyiskdJov6tMftt1yXu/sALFcgrAGA35zekbDudv16MVhz2+nx+e+xwAYBOL81GbX1j3/x5GEEsl1lVEtD61ljsUgDsE/VIJ5JXQwRu4NcMqCy7fH5L9lTPdUs9wmgkEjaMAQiI5XIPvnPaEdnPDsSK2gEnX4/33p1froAdJj1UVKXBAiGw4mOZLVtVoSYI9u5TfdHK1296oVPXzgt8z6sMnQmB+B66dO3UUL9u45CVq2q+NXBInxdKykrOq+hYemC3CrtzkElP0WhvIFWpiqmpMsawQsPdbP3sn7t2Shxt/OyKMP+rrHby7n2O+FmPMGbQChgDiIDFSCbn7XCTKENUUcBp35cyx2pwA//t/IjNMXVfXNPww9PnLEfnybNg19RVt8Ti18AYhghcz1s/rHevhUP79Znjet56iIiqcovqQIjA8bxnZ40/uMEy5iUYA6OIZ5jHwBiw53ldNbjn8yPhovbe09ch5/nV+TKq0DeX2veeuhJkvKZ8lMyWZa9YUzet12FX3rZxTc00cuM3AsqqIlCRWDxWK45zDcdLJy9dumlTqiU9OdXU8ujmLZm/kWWNDStRVNQEsxcvXllOTBIphU3gf+I7yVb2mn8kgS8KBVtWVQbxB4678iF2LKtzHlLYqNe79547LKDKMFVGHgzI91BRmmzoRHjEyWRqontwk+3+/rnlq99t3doyeUs88bFv8UCICIyRkmzmmuePGhI8e+igwPG8F0LjbAATvjqkWh8FgERL6zP5YyF0CWK+//zy701c/w8pAAAaG1sriLloDwxpGH7KDejWpewF8bJLAIiKgG17hBWP/cyKxcZCJK1RvY9tUXrj5sYqAU4EBGTZVeQ4EwJ2zlTbPZGY4wBgvMyyJGWvTTU2VbNt5feCkBib1rl/vgyHTTjqVZNL3Zf3pKxY/FsLZy68NICGQVg4E1u5rGSHCiDb6hphOVg06Nm1U1NDzG3o0NJ6vOPlahC5vlnbOijj2McFdghvbAKvLJ26+uC9ehWq08tbW56DMQITCTjwVgzfZ685ALBn0ppq53Je3jODiFRks/f8wynJoSf9B1o21MJLN8+EaFgiGGQ+mP/U7wAg3e+wnxxtcuY6tu3xRrUCoFqIPA0vPdkn5xYCWAKvZuLxE9a/MvmtUb4XnAXQoYGiB4PiIGSJaDGCYHJVhfvQvMl3tHYdeGZfN9UyO1/5YFFmBQA8/B8n4YBjL7umbmtTf2U7DmYYMRMTTVsXebHYTFjMMZHauW/N2OGPNOl0vZPLzYSCHZXWTQ0t6YbTxgLAvEEPPr/fhsC/JGc7E4yR3gqALd5o+8HMDqR3bzznuMXF/+PBXt27vtm0ue5Nn+0kESFu/EcnjxknALAJ1BDPZB7JedZgqMKVoPaA/n3f3PRlk/IHnn4D5j7+K4w4/XrMe7xtkfZ5v34O3ffoYtdu2BTce9VJAIAhJ12PZEkCs//8y0K/YZOuh2XHcMqkb6M04TAQyIN/ehYLHruq3fcMO+NmEClILbCrmPPwVdt9ngOuuh/ZuAVNurBtG4gzFv/4tC/Ny5ywYCVXlpfghZnvSv3Z395hv54PPBvG8zYgxsP6H5xSOLfn/U8BBKw775TdyY5/23bASVfsFsJOzwf8I2k0ye2W3NepAAl2K2B3+7/cBn/re7uF8LXaAOPtltzutrvtbjuh/X++IgQ14hEakgAAAABJRU5ErkJggg=="/></svg>'],
    'dmarcreport' => ['label'=>'DMARC Report', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 16L48 112v144c0 132.4 89 249.2 208 288 119-38.8 208-155.6 208-288V112z" fill="#2196F3"/><path d="M200 224h112c8.8 0 16 7.2 16 16v96c0 8.8-7.2 16-16 16H200c-8.8 0-16-7.2-16-16v-96c0-8.8 7.2-16 16-16z" fill="none" stroke="#fff" stroke-width="20"/><path d="M184 224l72 56 72-56" fill="none" stroke="#fff" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/><circle cx="368" cy="176" r="48" fill="#4CAF50"/><path d="M348 176l14 14 28-28" fill="none" stroke="#fff" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
    'patchmon' => ['label'=>'Patchmon', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 333 350"><path d="M303.2 302.8c-22.4 22.4-51 37.7-82.2 43.9-31.1 6.2-63.4 3-92.8-9.1-29.3-12.2-54.4-32.7-72-59.1C38.6 252 29.2 221 29.2 189.2s9.4-62.8 27-89.2c17.6-26.4 42.7-47 72-59.1 29.3-12.2 61.6-15.4 92.8-9.2 31.1 6.2 59.8 21.5 82.2 43.9L189.7 189.2z" fill="#ff751f"/><path d="M303.1 302.6c-22.4 22.4-51 37.7-82.1 43.9-31.1 6.2-63.4 3-92.7-9.1C99 325.2 73.9 304.6 56.3 278.2 38.7 251.9 29.3 220.8 29.3 189.1s9.4-62.8 27-89.1c17.6-26.4 42.7-47 72-59.1 29.3-12.1 61.6-15.3 92.7-9.1 31.1 6.2 59.8 21.5 82.1 43.9L189.7 189.1z" fill="#61b33a"/><circle cx="187" cy="100" r="25" fill="#ff751f"/></svg>'],
    'stirlingpdf' => ['label'=>'Stirling-PDF', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.5 -0.5 192 192"><path fill="#8e3131" d="M30.5-.5h130c16 4.667 26.333 15 31 31v130c-4.667 16-15 26.333-31 31h-130c-16-4.667-26.333-15-31-31v-130c4.667-16 15-26.333 31-31z"/><path fill="#fdfcfc" d="M114.5 24.5c1.21 21.83 1.71 43.83 1.5 66a4142 4142 0 0 1-41.5 35 2346 2346 0 0 0-45.5 37 1122 1122 0 0 1 0-67 8209 8209 0 0 1 85.5-71z"/><path fill="#d1abab" d="M114.5 24.5c.06-.543.393-.876 1-1a884 884 0 0 1 1.5 42 2284 2284 0 0 0 43.5-36 1090 1090 0 0 1 1 66 11605 11605 0 0 1-86.5 72 882 882 0 0 1-.5-42 4142 4142 0 0 0 41.5-35c.21-22.17-.29-44.17-1.5-66z"/><path fill="#af6e6e" d="M160.5 29.5c.06-.543.393-.876 1-1 1.328 22.496 1.328 44.83 0 67 .331-22.173-.003-44.173-1-66z"/></svg>'],
    'weddingshare' => ['label'=>'WeddingShare', 'svg'=>'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 96 96"><image width="96" height="96" href="data:image/png;base64,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"/></svg>'],
]);

// Homelab-Icon als SVG rendern / Render homelab icon as SVG
function homelabIconSvg(string $key): string {
    return HOMELAB_SVG_ICONS[$key]['svg'] ?? '';
}


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

function normalizeDockerHost(string $host): string {
    $host = trim(explode(',', $host)[0] ?? '');
    if ($host === '') return 'localhost';
    if (!preg_match('#^[a-z][a-z0-9+.\-]*://#i', $host)) $host = '//' . ltrim($host, '/');
    $parsed = parse_url($host);
    return $parsed['host'] ?? trim($host, '/');
}

function currentRequestScheme(): string {
    return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
}

function dockerFirstPublishedPort(array $ports): ?array {
    foreach ($ports as $p) {
        if (!empty($p['PublicPort'])) {
            return [
                'public' => (int)$p['PublicPort'],
                'private' => (int)($p['PrivatePort'] ?? 0),
            ];
        }
    }
    return null;
}

function dockerFirstPrivatePort(array $ports): ?int {
    foreach ($ports as $p) {
        $privPort = (int)($p['PrivatePort'] ?? 0);
        if ($privPort > 0) return $privPort;
    }
    return null;
}

function dockerTraefikRouteUrl(array $labels): string {
    foreach ($labels as $key => $value) {
        if (!preg_match('/^traefik\.http\.routers\.([^.]+)\.rule$/', $key, $m)) continue;
        $router = $m[1];
        $rule = (string)$value;
        if (!preg_match('/Host\(`([^`]+)`\)/', $rule, $hostMatch)) continue;
        $host = trim(explode(',', $hostMatch[1])[0] ?? '');
        if ($host === '') continue;
        $path = '';
        if (preg_match('/PathPrefix\(`([^`]+)`\)/', $rule, $pathMatch)) {
            $path = '/' . ltrim($pathMatch[1], '/');
        }
        $tls = strtolower((string)($labels["traefik.http.routers.$router.tls"] ?? ''));
        $entrypoints = strtolower((string)($labels["traefik.http.routers.$router.entrypoints"] ?? ''));
        $scheme = ($tls === 'true' || str_contains($entrypoints, 'websecure')) ? 'https' : 'http';
        return $scheme . '://' . $host . ($path === '/' ? '' : $path);
    }
    return '';
}

function dockerBuildUrl(array $labels, array $container, string $defaultHost, bool $allowPrivatePorts): string {
    if (!empty($labels['webdash.url'])) {
        return str_replace('{HOST_IP}', $defaultHost, $labels['webdash.url']);
    }

    $host = !empty($labels['webdash.host']) ? normalizeDockerHost((string)$labels['webdash.host']) : normalizeDockerHost($defaultHost);
    $scheme = strtolower(trim((string)($labels['webdash.scheme'] ?? '')));
    $scheme = in_array($scheme, ['http', 'https'], true) ? $scheme : currentRequestScheme();
    $path = trim((string)($labels['webdash.path'] ?? ''), '/');
    $path = $path !== '' ? '/' . $path : '';

    if (!empty($labels['webdash.host'])) {
        if (!empty($labels['webdash.port'])) {
            return $scheme . '://' . $host . ':' . (int)$labels['webdash.port'] . $path . '/';
        }
        return $scheme . '://' . $host . $path . ($path === '' ? '/' : '');
    }

    $traefikUrl = dockerTraefikRouteUrl($labels);
    if ($traefikUrl !== '') return $traefikUrl;

    if (!empty($labels['webdash.port'])) {
        $labelPort = (int)$labels['webdash.port'];
        $labelScheme = in_array($labelPort, [443, 8443, 9443], true) ? 'https' : $scheme;
        return $labelScheme . '://' . $host . ':' . $labelPort . $path . '/';
    }

    $ports = $container['Ports'] ?? [];
    $published = dockerFirstPublishedPort($ports);
    if ($published) {
        $port = $published['public'];
        $proto = in_array($port, [443, 8443, 9443], true) || $published['private'] === 443 ? 'https' : $scheme;
        return $proto . '://' . $host . ':' . $port . $path . '/';
    }

    if (!$allowPrivatePorts) return '';

    $privatePort = dockerFirstPrivatePort($ports);
    if ($privatePort) {
        $proto = in_array($privatePort, [443, 8443, 9443], true) ? 'https' : $scheme;
        return $proto . '://' . $host . ':' . $privatePort . $path . '/';
    }

    if (strtolower($container['State'] ?? '') !== 'running') return '';

    $inspect = dockerApiGet('/containers/' . ($container['Id'] ?? '') . '/json');
    if (!$inspect) return '';
    $exposed = $inspect['Config']['ExposedPorts'] ?? [];
    $bestPort = 0;
    foreach (array_keys($exposed) as $ep) {
        $epNum = (int)preg_replace('/\/.*/', '', $ep);
        if ($epNum > $bestPort) $bestPort = $epNum;
    }
    if (!$bestPort) return '';
    $proto = in_array($bestPort, [443, 8443, 9443], true) ? 'https' : $scheme;
    return $proto . '://' . $host . ':' . $bestPort . $path . '/';
}

function dockerProjectStatus(string $state, string $url, string $healthMode): array {
    $stateStatus = match($state) {
        'running'  => 'online',
        'paused'   => 'gesperrt',
        'exited', 'dead', 'removing' => 'offline',
        default    => 'offline',
    };

    if ($healthMode === 'off') {
        return ['status' => $stateStatus, 'statusCode' => $stateStatus === 'online' ? 200 : 0];
    }

    if ($state !== 'running') {
        return ['status' => $stateStatus, 'statusCode' => 0];
    }

    if ($url === '') {
        return ['status' => 'offline', 'statusCode' => 0];
    }

    if ($healthMode === 'http') {
        $code = httpHealthCheck($url);
        return ['status' => httpCodeToStatus($code), 'statusCode' => $code];
    }

    return ['status' => $stateStatus, 'statusCode' => 200];
}

function discoverDockerContainers(): array {
    global $dockerHostIp, $dockerMode, $dockerHealthMode, $dockerAllowPrivatePorts;
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
        $url = dockerBuildUrl($labels, $c, $dockerHostIp, $dockerAllowPrivatePorts);
        $resolvedStatus = dockerProjectStatus($state, $url, $dockerHealthMode);
        $projects[] = [
            'name'         => $rawName,
            'display_name' => $name !== $rawName ? $name : '',
            'url'          => $url,
            'lastModified' => time(),
            'type'         => $image,
            'description'  => $labels['webdash.description'] ?? '',
            'icon'         => $labels['webdash.icon'] ?? '',
            'status'       => $resolvedStatus['status'],
            'statusCode'   => $resolvedStatus['statusCode'],
            'docker'       => true,
            'container_id' => $containerId,
            'docker_status'=> $statusText,
            'docker_state' => $state,
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

// --- Setup (POST) — Ersteinrichtung / First-run setup ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup_username'], $_POST['setup_password'], $_POST['setup_confirm'])) {
    $setupUser = trim($_POST['setup_username']);
    $pw  = $_POST['setup_password'];
    $pw2 = $_POST['setup_confirm'];
    $setupInputData = [
        'username' => $setupUser,
        'email'    => trim($_POST['setup_email'] ?? ''),
        'scan_dir' => $_POST['setup_scan_dir'] ?? '',
    ];
    if (!$setupUser) {
        $_SESSION['dashboard_setup_error'] = $lang === 'de' ? 'Benutzername erforderlich' : 'Username required';
        $_SESSION['dashboard_setup_input'] = $setupInputData;
    } elseif (strlen($pw) < 4) {
        $_SESSION['dashboard_setup_error'] = $t['pw_short'];
        $_SESSION['dashboard_setup_input'] = $setupInputData;
    } elseif ($pw !== $pw2) {
        $_SESSION['dashboard_setup_error'] = $t['pw_mismatch'];
        $_SESSION['dashboard_setup_input'] = $setupInputData;
    } else {
        $cfg = dashConfig();
        $pwHash = password_hash($pw, PASSWORD_DEFAULT);
        $cfg['admin_pass'] = $pwHash;
        $setupEmail = trim($_POST['setup_email'] ?? '');
        if ($setupEmail) $cfg['admin_email'] = $setupEmail;
        // Benutzer anlegen / Create user
        $cfg['users'] = [['username' => $setupUser, 'password' => $pwHash, 'name' => ucfirst($setupUser), 'email' => $setupEmail]];
        // Scan-Verzeichnis aus Setup speichern (nicht im Docker-Modus)
        if (!$dockerMode && !empty($_POST['setup_scan_dir'])) {
            $dir = rtrim(trim($_POST['setup_scan_dir']), '/\\');
            if ($dir && is_dir($dir)) $cfg['scan_dir'] = $dir;
        }
        saveDashConfig($cfg);
        $_SESSION['dashboard_user'] = ['id'=>0,'username'=>$setupUser,'name'=>ucfirst($setupUser),'role'=>'admin'];
    }
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    if ($uploaded) { header('Location: ' . DASH_BASE); exit; }
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
            header('Location: ' . DASH_BASE);
            exit;
        } elseif ($pw !== $pw2) {
            $_SESSION['dashboard_pw_msg'] = ['fail', $t['pw_mismatch']];
            saveDashConfig($cfg);
            header('Location: ' . DASH_BASE);
            exit;
        }
        $cfg['admin_pass'] = password_hash($pw, PASSWORD_DEFAULT);
    }
    saveDashConfig($cfg);
    $_SESSION['dashboard_pw_msg'] = ['ok', $lang === 'de' ? 'Gespeichert' : 'Saved'];
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Sichtbare Verzeichnisse speichern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_include_dirs']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['include_dirs'] = isset($_POST['include_dirs']) && is_array($_POST['include_dirs']) ? array_values($_POST['include_dirs']) : [];
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Google-Suche ein-/ausschalten / Toggle Google Search ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_google_search']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['google_search'] = !empty($_POST['google_search_enabled']);
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Hintergrund-Modus umschalten / Toggle background mode ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bg_mode']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $mode = $_POST['bg_mode'] ?? '';
    $cfg['bg_mode'] = in_array($mode, ['preset', 'custom', ''], true) ? $mode : '';
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Hintergrundbild-Effekte speichern / Save background image effects ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bg_effects']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['bg_blur'] = max(0, min(100, (int)($_POST['bg_blur'] ?? 0)));
    $cfg['bg_brightness'] = max(0, min(100, (int)($_POST['bg_brightness'] ?? 28)));
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Dashboard-Sektionen ein-/ausschalten / Toggle dashboard sections ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_dashboard_sections']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['show_stats'] = !empty($_POST['show_stats']);
    $cfg['show_resources'] = !empty($_POST['show_resources']);
    $cfg['show_services'] = !empty($_POST['show_services']);
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Sichtbare Container speichern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_include_containers']) && !empty($_SESSION['dashboard_user'])) {
    $cfg = dashConfig();
    $cfg['include_containers'] = isset($_POST['include_containers']) && is_array($_POST['include_containers']) ? array_values($_POST['include_containers']) : [];
    saveDashConfig($cfg);
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Projekt-Details speichern (Beschreibung + Icon + Logo) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_project_desc']) && !empty($_SESSION['dashboard_user'])) {
    $projName = trim($_POST['project_name'] ?? '');
    $projDesc  = trim($_POST['project_desc'] ?? '');
    $projIcon  = trim($_POST['project_icon'] ?? '');
    $projTitle = trim($_POST['project_title'] ?? '');
    $projHomelabIcon = trim($_POST['project_homelab_icon'] ?? '');
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
        // Homelab-Icon speichern / Save homelab icon
        if (!isset($cfg['project_homelab_icon'])) $cfg['project_homelab_icon'] = [];
        if ($projHomelabIcon !== '' && isset(HOMELAB_SVG_ICONS[$projHomelabIcon])) {
            $cfg['project_homelab_icon'][$projName] = $projHomelabIcon;
        } else {
            unset($cfg['project_homelab_icon'][$projName]);
        }
        if ($projTitle !== '' && $projTitle !== $projName) {
            $cfg['project_titles'][$projName] = $projTitle;
        } else {
            unset($cfg['project_titles'][$projName]);
        }
        // URL-Modus / URL mode
        $urlMode = trim($_POST['project_url_mode'] ?? 'auto');
        if (!isset($cfg['project_url_mode'])) $cfg['project_url_mode'] = [];
        if ($urlMode !== '' && $urlMode !== 'auto') {
            $cfg['project_url_mode'][$projName] = $urlMode;
        } else {
            unset($cfg['project_url_mode'][$projName]);
        }
        // Custom URL speichern / Save custom URL
        if ($urlMode === 'custom') {
            $customUrl = trim($_POST['project_custom_url'] ?? '');
            if (!isset($cfg['project_custom_url'])) $cfg['project_custom_url'] = [];
            if ($customUrl !== '') {
                $cfg['project_custom_url'][$projName] = $customUrl;
            } else {
                unset($cfg['project_custom_url'][$projName]);
            }
        }
        // Wartungsmodus / Maintenance mode
        if (!isset($cfg['project_maintenance'])) $cfg['project_maintenance'] = [];
        if (!empty($_POST['project_maintenance'])) {
            $cfg['project_maintenance'][$projName] = true;
        } else {
            unset($cfg['project_maintenance'][$projName]);
        }
        // Manuellen Link aktualisieren / Update manual link
        $manualIdx = (int)($_POST['project_manual_index'] ?? -1);
        if ($manualIdx >= 0 && isset($cfg['manual_links'][$manualIdx])) {
            $newUrl = trim($_POST['project_url'] ?? '');
            if ($newUrl !== '' && filter_var($newUrl, FILTER_VALIDATE_URL)) {
                $cfg['manual_links'][$manualIdx]['url'] = $newUrl;
            }
            $cfg['manual_links'][$manualIdx]['description'] = $projDesc;
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
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
        header('Location: ' . DASH_BASE);
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
    header('Location: ' . DASH_BASE);
    exit;
}

// --- Abmelden ---
if (isset($_GET['logout'])) {
    unset($_SESSION['dashboard_user']);
    header('Location: ' . DASH_BASE);
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


// --- Setup-Modus: kein Admin-Passwort → Ersteinrichtung ---
// Setup mode: no admin password → first-run setup
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
        $resetUrl = $proto . '://' . $host . DASH_BASE . '?action=reset_password&token=' . $token;
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
    header('Location: ' . DASH_BASE . '?action=forgot_password');
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
            header('Location: ' . DASH_BASE);
            exit;
        }
    }
    $_SESSION['dashboard_reset_error'] = $error;
    header('Location: ' . DASH_BASE . '?action=reset_password&token=' . urlencode($token));
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
    <form method="POST" action="<?= DASH_BASE ?>?action=forgot_password">
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
    <form method="POST" action="<?= DASH_BASE ?>?action=reset_password">
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
        $portChecks = [80=>'Apache'];
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
        foreach (['apache2'=>'Apache','ssh'=>'SSH','cron'=>'Cron'] as $svc => $label) {
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
    // Host+Port+Schema für Health-Check extrahieren / Extract host+port+scheme for health check
    $mlParsed = $mlUrl ? parse_url($mlUrl) : [];
    $mlHost = $mlParsed['host'] ?? '';
    $mlScheme = $mlParsed['scheme'] ?? 'http';
    $mlPort = $mlParsed['port'] ?? '';
    $mlCheckUrl = $mlHost ? ($mlScheme . '://' . $mlHost . ($mlPort ? ':' . $mlPort : '') . '/') : '';
    $mlCode = $mlCheckUrl ? httpHealthCheck($mlCheckUrl) : 0;
    $mlName = $ml['name'] ?? '';
    // Manuelle Links: nur Maintenance oder explizite Fehlercodes anzeigen, sonst "online"
    // Manual links: only show maintenance or explicit error codes, otherwise "online"
    if (!empty($dashCfg['project_maintenance'][$mlName])) {
        $mlStatus = 'maintenance';
    } elseif ($mlCode >= 200 && $mlCode < 400) {
        $mlStatus = 'online';
    } elseif ($mlCode === 403) {
        $mlStatus = 'gesperrt';
    } elseif ($mlCode >= 400) {
        $mlStatus = 'fehler';
    } else {
        // Code 0 oder nicht erreichbar → trotzdem als online anzeigen (User hat URL selbst eingetragen)
        // Code 0 or unreachable → still show as online (user added URL themselves)
        $mlStatus = 'online';
    }
    $projects[] = [
        'name'         => $mlName,
        'url'          => $mlUrl,
        'lastModified' => time(),
        'type'         => 'Link',
        'description'  => $ml['description'] ?? '',
        'display_name' => $dashCfg['project_titles'][$mlName] ?? '',
        'icon'         => $dashCfg['project_icons'][$mlName] ?? '',
        'homelab_icon' => $dashCfg['project_homelab_icon'][$mlName] ?? '',
        'logo_dark_ext'  => $dashCfg['project_logo_dark_ext'][$mlName] ?? '',
        'logo_light_ext' => $dashCfg['project_logo_light_ext'][$mlName] ?? '',
        'status'       => $mlStatus,
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
    if (!empty($cfg['project_homelab_icon'][$name])) $project['homelab_icon'] = $cfg['project_homelab_icon'][$name];
    if (!empty($cfg['project_logo_dark_ext'][$name])) $project['logo_dark_ext'] = $cfg['project_logo_dark_ext'][$name];
    if (!empty($cfg['project_logo_light_ext'][$name])) $project['logo_light_ext'] = $cfg['project_logo_light_ext'][$name];
    if (!empty($cfg['project_maintenance'][$name])) $project['status'] = 'maintenance';
    // URL-Modus anwenden / Apply URL mode
    $urlMode = $cfg['project_url_mode'][$name] ?? 'auto';
    if ($urlMode !== 'auto') {
        $project['url_mode'] = $urlMode;
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $hostOnly = explode(':', $host)[0];
        $proto = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
        if ($urlMode === 'ip_port') {
            $serverIp = $_SERVER['SERVER_ADDR'] ?? $hostOnly;
            $port = $_SERVER['SERVER_PORT'] ?? '80';
            $project['url'] = "$proto://$serverIp:$port/" . $name . '/';
        } elseif ($urlMode === 'dns') {
            $project['url'] = "$proto://$hostOnly/" . $name . '/';
        } elseif ($urlMode === 'custom' && !empty($cfg['project_custom_url'][$name])) {
            $project['url'] = $cfg['project_custom_url'][$name];
        }
    }
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

// --- Logo-Check für Setup-Seite ---
// Logo check for setup page
$_setupLogoDark  = !empty($_dashCfgCheck['logo_dark_ext']) && file_exists(DASH_LOGO_DARK . '.' . $_dashCfgCheck['logo_dark_ext']);
$_setupLogoLight = !empty($_dashCfgCheck['logo_light_ext']) && file_exists(DASH_LOGO_LIGHT . '.' . $_dashCfgCheck['logo_light_ext']);

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
.setup-logo{max-height:64px;max-width:280px;margin-bottom:.5rem}
.setup-logo-light{display:none}
@media(prefers-color-scheme:light){.setup-logo-dark{display:none}.setup-logo-light{display:inline}}
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
  <a href="<?= DASH_BASE ?>?lang=<?= $lang === 'de' ? 'en' : 'de' ?>" class="lang-toggle"><?= $lang === 'de' ? flagDE(16) . ' DE' : flagUS(16) . ' EN' ?></a>
  <div class="logo">
    <?php if ($_setupLogoDark || $_setupLogoLight): ?>
      <?php if ($_setupLogoDark): ?><img src="<?= DASH_BASE ?>?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $_dashCfgCheck['logo_dark_ext']) ?>" alt="Logo" class="setup-logo setup-logo-dark"><?php endif; ?>
      <?php if ($_setupLogoLight): ?><img src="<?= DASH_BASE ?>?asset=logo-light&v=<?= filemtime(DASH_LOGO_LIGHT . '.' . $_dashCfgCheck['logo_light_ext']) ?>" alt="Logo" class="setup-logo setup-logo-light"><?php endif; ?>
    <?php else: ?>
      <h1>webdash</h1>
    <?php endif; ?>
    <p><?= $t['setup'] ?></p>
  </div>
  <p style="font-size:.85rem;color:var(--text-muted);text-align:center;margin-bottom:1rem"><?= $t['setup_desc'] ?></p>
  <?php if ($setupError): ?><div class="error-msg"><?= htmlspecialchars($setupError) ?></div><?php endif; ?>
  <form method="POST" action="<?= DASH_BASE ?>">
    <div class="field">
      <label><?= $t['username'] ?></label>
      <input type="text" name="setup_username" value="<?= htmlspecialchars($setupInput['username'] ?? '') ?>" required placeholder="admin" autocomplete="username" autofocus>
    </div>
    <div class="field">
      <label><?= $t['email'] ?> <span style="font-size:.7rem;color:var(--text-dim)">(<?= $lang === 'de' ? 'optional' : 'optional' ?>)</span></label>
      <input type="email" name="setup_email" value="<?= htmlspecialchars($setupInput['email'] ?? '') ?>" placeholder="admin@example.com" autocomplete="email">
      <div class="field-hint"><?= $lang === 'de' ? 'F&uuml;r Passwort-Zur&uuml;cksetzung per E-Mail' : 'For password reset via email' ?></div>
    </div>
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
<link rel="icon" type="image/png" href="<?= DASH_BASE ?>?asset=favicon-dark&v=<?= filemtime($favDark) ?>" media="(prefers-color-scheme: dark)">
<link rel="icon" type="image/png" href="<?= DASH_BASE ?>?asset=favicon-light&v=<?= filemtime($favLight) ?>" media="(prefers-color-scheme: light)">
<link rel="icon" type="image/png" href="<?= DASH_BASE ?>?asset=favicon-light&v=<?= filemtime($favLight) ?>">
<?php elseif ($hasLogoDark): ?>
<link rel="icon" type="image/<?= $dashCfg['logo_dark_ext'] ?>" href="<?= DASH_BASE ?>?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>">
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
body.has-bg.bg-custom{background-image:url('<?= DASH_BASE ?>?asset=bg-image') !important}
body.has-bg.bg-preset{background-image:url('<?= DASH_BASE ?>?asset=wallpaper&theme=dark') !important}
.light body.has-bg.bg-preset,body.has-bg.bg-preset.light{background-image:url('<?= DASH_BASE ?>?asset=wallpaper&theme=light') !important}
body.has-bg::after{
  content:'';position:fixed;inset:0;z-index:0;
  backdrop-filter:blur(var(--bg-blur,0px)) brightness(var(--bg-brightness,.55));
  -webkit-backdrop-filter:blur(var(--bg-blur,0px)) brightness(var(--bg-brightness,.55));
  pointer-events:none;
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
.desc-collapse{border:1px solid var(--border);border-radius:10px;margin-bottom:.75rem;overflow:hidden}
.desc-collapse summary{padding:.6rem .85rem;font-size:.78rem;font-weight:600;cursor:pointer;background:var(--surface-2);display:flex;align-items:center;gap:.5rem;list-style:none;user-select:none}
.desc-collapse summary::-webkit-details-marker{display:none}
.desc-collapse summary::before{content:'';display:inline-block;width:0;height:0;border-left:5px solid var(--text-dim);border-top:4px solid transparent;border-bottom:4px solid transparent;transition:transform .2s}
.desc-collapse[open] summary::before{transform:rotate(90deg)}
.desc-collapse summary:hover{background:var(--surface)}
.desc-collapse-body{padding:.75rem .85rem;border-top:1px solid var(--border)}
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
.proj-edit-btn{background:transparent;border:1px solid transparent;color:var(--text-dim);cursor:pointer;display:flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:50%;opacity:.5;transition:all .25s ease}
.proj-edit-btn:hover{opacity:1;color:var(--accent);background:var(--accent-dim);border-color:var(--border)}
.proj-delete-btn{color:var(--danger);display:flex;align-items:center;opacity:.6;transition:opacity .2s}
.proj-delete-btn:hover{opacity:1}
.proj-add{border:2px dashed var(--text-dim);background:var(--surface-2);cursor:pointer}.proj-add:hover{border-color:var(--accent);background:var(--surface)}.proj-add .proj-link{opacity:.7;transition:opacity .2s;text-decoration:none}.proj-add:hover .proj-link{opacity:1}
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
.homelab-icon{display:flex;align-items:center;justify-content:center;width:100%;height:100%}
.homelab-icon svg,.homelab-icon img{width:85%;height:85%;border-radius:10px;display:block;object-fit:contain}
.proj-icon.has-homelab,.uproj-icon.has-homelab{background:none;overflow:hidden;display:flex;align-items:center;justify-content:center}
.homelab-grid{display:flex;flex-wrap:wrap;gap:.35rem;max-height:180px;overflow-y:auto;padding:.25rem 0}
.homelab-pick-btn{background:var(--surface-2);border:2px solid var(--border);border-radius:10px;width:44px;height:44px;padding:3px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:border-color .2s,transform .15s}
.homelab-pick-btn:hover{border-color:var(--accent);transform:scale(1.08)}
.homelab-pick-btn.selected{border-color:var(--accent);box-shadow:0 0 0 2px var(--accent-dim)}
.homelab-pick-btn svg,.homelab-pick-btn img{width:100%;height:100%;border-radius:7px;display:block;object-fit:contain}
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
body.has-bg footer{
  color:var(--text);border-top:none;margin-top:2rem;padding:.75rem 1.5rem;
  background:var(--surface);border-radius:12px;
  backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
  position:relative;z-index:50;
}
body.has-bg .footer-copy a{color:var(--text-muted)}

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
  $bgBlurPct  = (int)($dashCfg['bg_blur'] ?? 0);
  $bgBright   = (int)($dashCfg['bg_brightness'] ?? 28);
  $bgBlurPx   = round($bgBlurPct * 0.2, 1);
  $bgBrightV  = round($bgBright / 50, 2);
?><body<?php if ($hasBgAny): ?> class="has-bg <?= $hasBgPreset ? 'bg-preset' : 'bg-custom' ?>" style="--bg-blur:<?= $bgBlurPx ?>px;--bg-brightness:<?= $bgBrightV ?>"<?php endif; ?>>
<div class="wrap">

  <!-- ==================== HEADER ==================== -->
  <header>
    <div class="hdr-left">
      <div class="hdr-logos">
        <?php if ($hasAppLogoDark): ?>
          <img src="<?= DASH_BASE ?>?asset=app-logo-dark&v=<?= filemtime(DASH_APP_LOGO_DARK) ?>" alt="webdash" class="hdr-logo hdr-logo-dark">
        <?php endif; ?>
        <?php if ($hasAppLogoLight): ?>
          <img src="<?= DASH_BASE ?>?asset=app-logo-light&v=<?= filemtime(DASH_APP_LOGO_LIGHT) ?>" alt="webdash" class="hdr-logo hdr-logo-light">
        <?php endif; ?>
        <?php if ($hasAppLogoDark || $hasAppLogoLight): ?>
          <span class="hdr-logo-sep"></span>
        <?php endif; ?>
        <?php if ($hasLogoDark): ?>
          <img src="<?= DASH_BASE ?>?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>" alt="Logo" class="hdr-logo hdr-custom hdr-logo-dark">
        <?php endif; ?>
        <?php if ($hasLogoLight): ?>
          <img src="<?= DASH_BASE ?>?asset=logo-light&v=<?= filemtime(DASH_LOGO_LIGHT . '.' . $dashCfg['logo_light_ext']) ?>" alt="Logo" class="hdr-logo hdr-custom hdr-logo-light">
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
      <a href="<?= DASH_BASE ?>?lang=<?= $lang === 'de' ? 'en' : 'de' ?>" class="btn-icon" title="<?= $t['toggle_lang'] ?>" style="line-height:1">
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
        <a href="<?= DASH_BASE ?>?logout" class="btn-link danger" title="<?= $t['logout'] ?>">
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
      $hasUrl = !empty($proj['url']);
      $isManual = !empty($proj['manual']);
      // Manuelle Links immer klickbar wenn URL vorhanden / Manual links always clickable if URL exists
      $clickable = $hasUrl && ($isOnline || $isManual);
    ?>
    <a href="<?= $clickable ? htmlspecialchars($proj['url']) : '#' ?>"
       class="uproj<?= $clickable ? '' : ' disabled' ?>"
       style="animation-delay:<?= 0.15 + $i * 0.08 ?>s"
       <?= $clickable ? 'target="_blank" rel="noopener"' : '' ?>>
      <div class="uproj-inner">
        <div class="uproj-top">
          <?php $uHasLogo = !empty($proj['logo_dark_ext']) || !empty($proj['logo_light_ext']); $uHlIcon = $proj['homelab_icon'] ?? ''; ?>
          <div class="uproj-icon<?= $uHasLogo ? ' has-logo' : ($uHlIcon && isset(HOMELAB_SVG_ICONS[$uHlIcon]) ? ' has-homelab' : '') ?>"><?php if ($uHasLogo): ?><img src="<?= DASH_BASE ?>?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=dark" alt="" class="proj-logo-dark"><img src="<?= DASH_BASE ?>?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=light" alt="" class="proj-logo-light"><?php elseif ($uHlIcon && isset(HOMELAB_SVG_ICONS[$uHlIcon])): ?><span class="homelab-icon"><?= homelabIconSvg($uHlIcon) ?></span><?php else: ?><?= !empty($proj['icon']) ? $proj['icon'] : (!empty($proj['docker']) ? "\xf0\x9f\x90\xb3" : match($proj['type']) { 'Node.js'=>"\xe2\xac\xa2",'Python'=>"\xf0\x9f\x90\x8d",'Link'=>"\xf0\x9f\x94\x97",default=>"\xe2\x9a\x99" }) ?><?php endif; ?></div>
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
      <form method="POST" action="<?= DASH_BASE ?>" enctype="multipart/form-data" id="logoDarkForm" class="settings-item">
        <label class="settings-label"><?= $t['logo_dark'] ?></label>
        <div class="settings-logo-actions">
          <?php if ($hasLogoDark): ?>
            <img src="<?= DASH_BASE ?>?asset=logo-dark&v=<?= filemtime(DASH_LOGO_DARK . '.' . $dashCfg['logo_dark_ext']) ?>" alt="Logo" class="settings-logo-preview">
          <?php endif; ?>
          <label class="btn-link" style="cursor:pointer">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <?= $hasLogoDark ? $t['change'] : $t['upload_btn'] ?>
            <input type="file" name="logo_dark" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" onchange="this.form.submit()" style="display:none">
          </label>
          <?php if ($hasLogoDark): ?>
            <a href="<?= DASH_BASE ?>?remove_logo=dark" class="btn-link danger"><?= $t['remove'] ?></a>
          <?php endif; ?>
        </div>
      </form>
      <form method="POST" action="<?= DASH_BASE ?>" enctype="multipart/form-data" id="logoLightForm" class="settings-item">
        <label class="settings-label"><?= $t['logo_light'] ?></label>
        <div class="settings-logo-actions">
          <?php if ($hasLogoLight): ?>
            <img src="<?= DASH_BASE ?>?asset=logo-light&v=<?= filemtime(DASH_LOGO_LIGHT . '.' . $dashCfg['logo_light_ext']) ?>" alt="Logo" class="settings-logo-preview">
          <?php endif; ?>
          <label class="btn-link" style="cursor:pointer">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <?= $hasLogoLight ? $t['change'] : $t['upload_btn'] ?>
            <input type="file" name="logo_light" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" onchange="this.form.submit()" style="display:none">
          </label>
          <?php if ($hasLogoLight): ?>
            <a href="<?= DASH_BASE ?>?remove_logo=light" class="btn-link danger"><?= $t['remove'] ?></a>
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
          <form method="POST" action="<?= DASH_BASE ?>" style="display:inline">
            <input type="hidden" name="save_bg_mode" value="1">
            <input type="hidden" name="bg_mode" value="<?= $hasBgPreset ? '' : 'preset' ?>">
            <button type="submit" style="cursor:pointer;border:2px solid <?= $hasBgPreset ? 'var(--accent)' : 'var(--border)' ?>;border-radius:8px;padding:2px;background:none;position:relative;transition:border-color .25s" title="<?= $lang === 'de' ? 'Standardbilder' : 'Default wallpapers' ?>">
              <div style="display:flex;gap:2px">
                <img src="<?= DASH_BASE ?>?asset=wallpaper&theme=dark" alt="Dark" style="height:36px;width:52px;object-fit:cover;border-radius:5px 0 0 5px">
                <img src="<?= DASH_BASE ?>?asset=wallpaper&theme=light" alt="Light" style="height:36px;width:52px;object-fit:cover;border-radius:0 5px 5px 0">
              </div>
              <?php if ($hasBgPreset): ?><span style="position:absolute;top:-6px;right:-6px;width:14px;height:14px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center"><svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span><?php endif; ?>
            </button>
          </form>
          <form method="POST" action="<?= DASH_BASE ?>" enctype="multipart/form-data" id="bgImageForm" style="display:inline">
            <?php if ($hasBgCustom): ?>
              <div style="display:inline-flex;border:2px solid var(--accent);border-radius:8px;padding:2px;position:relative">
                <img src="<?= DASH_BASE ?>?asset=bg-image&v=<?= filemtime(DASH_BG_IMAGE . '.' . $dashCfg['bg_image_ext']) ?>" alt="BG" style="height:36px;width:52px;object-fit:cover;border-radius:6px">
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
            <form method="POST" action="<?= DASH_BASE ?>" style="display:inline">
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
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item" id="bgBlurForm">
        <input type="hidden" name="save_bg_effects" value="1">
        <input type="hidden" name="bg_brightness" value="<?= $sBgBright ?>">
        <label class="settings-label"><?= $t['bg_blur'] ?></label>
        <div style="display:flex;align-items:center;gap:.5rem">
          <input type="range" name="bg_blur" min="0" max="100" value="<?= $sBgBlur ?>" style="width:110px;accent-color:var(--accent)" oninput="document.body.style.setProperty('--bg-blur',(this.value*0.2)+'px');this.nextElementSibling.textContent=this.value+'%'" onchange="this.form.submit()">
          <span style="font-size:.75rem;color:var(--text-muted);min-width:30px"><?= $sBgBlur ?>%</span>
        </div>
      </form>
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item" id="bgBrightForm">
        <input type="hidden" name="save_bg_effects" value="1">
        <input type="hidden" name="bg_blur" value="<?= $sBgBlur ?>">
        <label class="settings-label"><?= $t['bg_brightness'] ?></label>
        <div style="display:flex;align-items:center;gap:.5rem">
          <input type="range" name="bg_brightness" min="0" max="100" value="<?= $sBgBright ?>" style="width:110px;accent-color:var(--accent)" oninput="document.body.style.setProperty('--bg-brightness',this.value/50);this.nextElementSibling.textContent=this.value+'%'" onchange="this.form.submit()">
          <span style="font-size:.75rem;color:var(--text-muted);min-width:30px"><?= $sBgBright ?>%</span>
        </div>
      </form>
      <?php endif; ?>
    </div>

    <!-- Funktionen / Features -->
    <div class="settings-group-label"><?= $lang === 'de' ? 'Funktionen' : 'Features' ?></div>
    <div class="settings-row">
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item">
        <label class="settings-label"><?= $t['google_search'] ?></label>
        <input type="hidden" name="save_google_search" value="1">
        <label class="dir-toggle" style="margin:0">
          <input type="checkbox" name="google_search_enabled" value="1"
                 onchange="this.form.submit()" <?= $googleSearchEnabled ? 'checked' : '' ?>>
          <span class="dir-toggle-switch"></span>
        </label>
      </form>
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item">
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
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item">
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
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item">
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
      <?php if (!$dockerMode): ?>
      <form method="POST" action="<?= DASH_BASE ?>" class="settings-item" style="flex-basis:100%">
        <label class="settings-label"><?= $t['scan_dir'] ?></label>
        <div style="display:flex;gap:.5rem;align-items:center;width:100%">
          <input type="text" name="scan_dir" value="<?= htmlspecialchars($scanDir) ?>" class="modal-input modal-input-text" style="flex:1;font-size:.78rem" placeholder="<?= htmlspecialchars(dirname(__DIR__)) ?>">
          <button type="submit" class="btn-update" style="white-space:nowrap"><?= $t['save'] ?></button>
        </div>
      </form>
      <?php endif; ?>
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
      <form method="POST" action="<?= DASH_BASE ?>" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;width:100%">
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
    <form method="POST" action="<?= DASH_BASE ?>" style="margin-top:.75rem">
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
            <form method="POST" action="<?= DASH_BASE ?>" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
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
            <form method="POST" action="<?= DASH_BASE ?>" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
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
              <a href="<?= DASH_BASE ?>?delete_user=<?= $idx ?>" class="btn-link danger" style="font-size:.72rem;margin-left:auto" onclick="return confirm('<?= $t['users_delete_confirm'] ?>')"><?= $t['users_delete'] ?></a>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <div class="section-title" style="margin-top:1rem;margin-bottom:.5rem"><?= $t['users_add'] ?></div>
    <form method="POST" action="<?= DASH_BASE ?>" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
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
    <form method="POST" action="<?= DASH_BASE ?>" id="containerForm">
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
    <form method="POST" action="<?= DASH_BASE ?>" id="dirForm">
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
      $projHasUrl = !empty($proj['url']);
      $projClickable = $projOnline && $projHasUrl;
    ?>
    <div class="proj" style="animation-delay:<?= 0.1 + $i * 0.08 ?>s">
      <a href="<?= $projClickable ? htmlspecialchars($proj['url']) : '#' ?>" class="proj-link<?= $projClickable ? '' : ' proj-maintenance' ?>" <?= $projClickable ? 'target="_blank" rel="noopener"' : '' ?>>
        <div class="proj-head">
          <?php $hasLogo = !empty($proj['logo_dark_ext']) || !empty($proj['logo_light_ext']); $aHlIcon = $proj['homelab_icon'] ?? ''; ?>
          <div class="proj-icon<?= $hasLogo ? ' has-logo' : ($aHlIcon && isset(HOMELAB_SVG_ICONS[$aHlIcon]) ? ' has-homelab' : '') ?>"><?php if ($hasLogo): ?><img src="<?= DASH_BASE ?>?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=dark" alt="" class="proj-logo-dark"><img src="<?= DASH_BASE ?>?asset=project-logo&name=<?= urlencode($proj['name']) ?>&variant=light" alt="" class="proj-logo-light"><?php elseif ($aHlIcon && isset(HOMELAB_SVG_ICONS[$aHlIcon])): ?><span class="homelab-icon"><?= homelabIconSvg($aHlIcon) ?></span><?php else: ?><?= !empty($proj['icon']) ? $proj['icon'] : (!empty($proj['docker']) ? "\xf0\x9f\x90\xb3" : match($proj['type']) { 'Node.js'=>"\xe2\xac\xa2",'Python'=>"\xf0\x9f\x90\x8d",'Link'=>"\xf0\x9f\x94\x97",default=>"\xe2\x9a\x99" }) ?><?php endif; ?></div>
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
        <button type="button" class="proj-edit-btn" onclick="editProjectDesc(<?= htmlspecialchars(json_encode($proj['name']), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['description'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['icon'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['display_name'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode(!empty($proj['logo_dark_ext']) ? $proj['logo_dark_ext'] : ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode(!empty($proj['logo_light_ext']) ? $proj['logo_light_ext'] : ''), ENT_QUOTES) ?>,<?= $proj['status'] === 'maintenance' ? 'true' : 'false' ?>,<?= htmlspecialchars(json_encode($proj['homelab_icon'] ?? ''), ENT_QUOTES) ?>,<?= (int)($proj['manual_index'] ?? -1) ?>,<?= htmlspecialchars(json_encode($proj['url'] ?? ''), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($proj['url_mode'] ?? 'auto'), ENT_QUOTES) ?>,<?= htmlspecialchars(json_encode($dashCfg['project_custom_url'][$proj['name']] ?? ''), ENT_QUOTES) ?>)" title="<?= $t['project_edit'] ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
        </button>
        <?php if (!empty($proj['manual'])): ?>
          <a href="<?= DASH_BASE ?>?delete_manual_link=<?= $proj['manual_index'] ?>" class="proj-delete-btn" onclick="return confirm('<?= $t['manual_link_delete_confirm'] ?>')" title="<?= $t['remove'] ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if ($isAdmin): ?>
    <div class="proj proj-add" style="animation-delay:<?= 0.1 + count($projects) * 0.08 ?>s">
      <a href="#" class="proj-link" onclick="event.preventDefault();openAddLinkModal()" style="display:flex;align-items:center;justify-content:center;min-height:100%">
        <div style="text-align:center;color:var(--text-dim)">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <div style="font-size:.75rem;margin-top:.35rem"><?= $t['manual_link_add'] ?></div>
        </div>
      </a>
    </div>
    <?php endif; ?>
  </div>
  <?php else: ?>
    <div class="empty" style="font-size:.85rem;color:var(--text-dim);margin:.75rem 0"><?= $dockerMode ? $t['docker_no_containers'] : ($lang === 'de' ? 'Keine Verzeichnisse ausgew&auml;hlt. W&auml;hle unter &bdquo;Allgemein&ldquo; die anzuzeigenden Verzeichnisse aus.' : 'No directories selected. Choose which directories to display in the General tab.') ?></div>
  <?php endif; ?>

  <?php $manualMsg = $_SESSION['dashboard_manual_msg'] ?? null; unset($_SESSION['dashboard_manual_msg']); ?>
  <?php if ($manualMsg): ?>
    <div style="margin-bottom:.5rem;font-size:.78rem;color:<?= $manualMsg[0] === 'ok' ? 'var(--success)' : 'var(--danger)' ?>"><?= htmlspecialchars($manualMsg[1]) ?></div>
  <?php endif; ?>

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

<!-- ==================== PROJEKT-MODALS (außerhalb tab-server für korrekten z-index) ==================== -->
<?php if ($isAdmin): ?>
<div class="modal-bg" id="descModal">
  <div class="modal" style="text-align:left;max-width:500px;max-height:88vh;display:flex;flex-direction:column;padding:0">
    <div style="padding:2rem 2rem 0;flex-shrink:0">
      <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
        <h2 style="margin:0;flex:1"><?= $t['project_edit'] ?></h2>
        <span id="descModalName" style="font-size:.72rem;color:var(--text-dim);font-family:var(--mono)"></span>
      </div>
    </div>
    <form method="POST" action="<?= DASH_BASE ?>" id="descForm" enctype="multipart/form-data" target="_self" onsubmit="syncEditor('descEditor','descFormDesc');closeDescModal()" style="overflow-y:auto;padding:0 2rem 2rem;flex:1;min-height:0">
      <input type="hidden" name="save_project_desc" value="1">
      <input type="hidden" name="project_name" id="descFormName">
      <input type="hidden" name="project_homelab_icon" id="descFormHomelabIcon">
      <input type="hidden" name="project_manual_index" id="descFormManualIndex" value="-1">

      <!-- Name -->
      <label class="proj-edit-label"><?= $t['manual_link_name'] ?></label>
      <input type="text" name="project_title" id="descFormTitle" class="modal-input modal-input-text" style="margin-bottom:1.25rem">

      <!-- URL (nur manuelle Links / only manual links) -->
      <div id="descFormUrlWrap" style="display:none;margin-bottom:1.25rem">
        <label class="proj-edit-label"><?= $t['manual_link_url'] ?></label>
        <input type="url" name="project_url" id="descFormUrl" class="modal-input modal-input-text" placeholder="https://example.com">
      </div>

      <!-- URL-Modus / URL Mode (nicht für manuelle Links) -->
      <div id="descFormUrlModeWrap" style="margin-bottom:1.25rem">
        <label class="proj-edit-label"><?= $t['url_mode'] ?></label>
        <div style="display:flex;gap:.5rem;align-items:center">
          <select name="project_url_mode" id="descFormUrlMode" class="modal-input modal-input-text" style="flex:1" onchange="toggleCustomUrlInput(this.value)">
            <option value="auto"><?= $t['url_mode_auto'] ?></option>
            <option value="ip_port"><?= $t['url_mode_ip_port'] ?></option>
            <option value="dns"><?= $t['url_mode_dns'] ?></option>
            <option value="custom"><?= $t['url_mode_custom'] ?></option>
          </select>
        </div>
        <input type="url" name="project_custom_url" id="descFormCustomUrl" class="modal-input modal-input-text" placeholder="https://example.com" style="display:none;margin-top:.5rem">
      </div>

      <!-- Logo / Icon — aufklappbar / collapsible -->
      <details class="desc-collapse" id="descCollapseIcon">
        <summary>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
          <?= $lang === 'de' ? 'Logo &amp; Icon' : 'Logo &amp; Icon' ?>
          <span id="descIconPreview" style="margin-left:auto;font-size:1rem"></span>
        </summary>
        <div class="desc-collapse-body">
          <!-- Logos (Dark + Light) -->
          <div style="font-size:.72rem;font-weight:600;margin-bottom:.4rem"><?= $lang === 'de' ? 'Eigenes Logo' : 'Custom Logo' ?></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.6rem">
            <?php foreach (['dark' => 'Dark', 'light' => 'Light'] as $lv => $ll): ?>
            <div class="proj-edit-section" style="padding:.6rem">
              <div style="font-size:.65rem;color:var(--text-dim);margin-bottom:.4rem;display:flex;align-items:center;gap:.3rem">
                <?= $lv === 'dark' ? '🌙' : '☀️' ?> <?= $ll ?>
              </div>
              <div id="projLogo_<?= $lv ?>_has" style="display:none;text-align:center">
                <div style="width:48px;height:48px;border-radius:10px;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;background:<?= $lv === 'dark' ? '#1a1a2e' : '#f0f0f4' ?>;margin-bottom:.3rem">
                  <img id="projLogo_<?= $lv ?>_img" src="" alt="" style="width:48px;height:48px;object-fit:contain;display:block">
                </div>
                <div style="display:flex;gap:.3rem;justify-content:center">
                  <label class="proj-edit-btn-sm proj-edit-btn-accent" style="font-size:.58rem;padding:.15rem .35rem">
                    <?= $lang === 'de' ? 'Ändern' : 'Change' ?>
                    <input type="file" name="project_logo_<?= $lv ?>" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" style="display:none" onchange="previewProjLogo('<?= $lv ?>',this)">
                  </label>
                  <button type="button" class="proj-edit-btn-sm proj-edit-btn-danger" style="font-size:.58rem;padding:.15rem .35rem" onclick="removeProjLogo('<?= $lv ?>')">
                    <?= $lang === 'de' ? 'Entf.' : 'Del' ?>
                  </button>
                </div>
              </div>
              <div id="projLogo_<?= $lv ?>_none" style="display:none">
                <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.2rem;padding:.5rem .4rem;border:2px dashed var(--border);border-radius:8px;cursor:pointer;transition:border-color .2s" onmouseenter="this.style.borderColor='var(--accent)'" onmouseleave="this.style.borderColor='var(--border)'">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  <span style="font-size:.6rem;color:var(--text-dim)"><?= $lang === 'de' ? 'Hochladen' : 'Upload' ?></span>
                  <input type="file" name="project_logo_<?= $lv ?>" accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" style="display:none" onchange="previewProjLogo('<?= $lv ?>',this)">
                </label>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <div style="font-size:.6rem;color:var(--text-dim);text-align:center;margin-bottom:.75rem"><?= $lang === 'de' ? 'Nur ein Logo nötig — das andere wird automatisch übernommen.' : 'Only one logo needed — the other is used automatically.' ?></div>

          <!-- Homelab Icons -->
          <div id="homelabPickerWrap">
            <div style="font-size:.72rem;font-weight:600;margin-bottom:.4rem"><?= $lang === 'de' ? 'Homelab-Logo' : 'Homelab Logo' ?></div>
            <input type="text" id="homelabSearch" class="modal-input modal-input-text" style="margin-bottom:.4rem;font-size:.72rem;padding:.35rem .55rem" placeholder="<?= $lang === 'de' ? 'Suchen...' : 'Search...' ?>" oninput="filterHomelabIcons(this.value)">
            <div class="homelab-grid" id="homelabGrid">
              <?php foreach (HOMELAB_SVG_ICONS as $hlKey => $hlData): ?>
                <button type="button" class="homelab-pick-btn" data-key="<?= $hlKey ?>" data-label="<?= htmlspecialchars(strtolower($hlData['label'])) ?>" onclick="pickHomelabIcon('<?= $hlKey ?>')" title="<?= htmlspecialchars($hlData['label']) ?>"><?= homelabIconSvg($hlKey) ?></button>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Emoji Icon -->
          <div id="iconPickerWrap" style="margin-top:.75rem">
            <div style="font-size:.72rem;font-weight:600;margin-bottom:.4rem"><?= $t['project_edit_icon'] ?></div>
            <div style="display:flex;gap:.5rem;align-items:center">
              <input type="text" name="project_icon" id="descFormIcon" class="modal-input modal-input-text" style="width:3rem;text-align:center;font-size:1.2rem;padding:.4rem;flex-shrink:0" maxlength="4" oninput="if(this.value)clearHomelabIcon()">
              <div style="display:flex;flex-wrap:wrap;gap:.25rem">
                <?php foreach (['⚙','🔗','📊','📁','🛒','💬','📧','🏠','🔒','📝','🌐','📱','🎯','💾','☁'] as $em): ?>
                  <button type="button" class="icon-pick-btn" onclick="document.getElementById('descFormIcon').value='<?= $em ?>';clearHomelabIcon()"><?= $em ?></button>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </details>

      <!-- Beschreibung — aufklappbar / collapsible -->
      <details class="desc-collapse" id="descCollapseDesc">
        <summary>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <?= $t['project_edit_desc'] ?>
          <span id="descDescPreview" style="margin-left:auto;font-size:.65rem;color:var(--text-dim);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
        </summary>
        <div class="desc-collapse-body">
          <div class="fmt-toolbar">
            <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('bold')" title="<?= $lang === 'de' ? 'Fett' : 'Bold' ?>"><strong>B</strong></button>
            <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="document.execCommand('italic')" title="<?= $lang === 'de' ? 'Kursiv' : 'Italic' ?>"><em>I</em></button>
            <button type="button" class="fmt-btn" onmousedown="event.preventDefault()" onclick="fmtHeading('descEditor')" title="<?= $lang === 'de' ? '&Uuml;berschrift' : 'Heading' ?>">H</button>
          </div>
          <input type="hidden" name="project_desc" id="descFormDesc">
          <div class="rte-editor" id="descEditor" contenteditable="true" data-placeholder="<?= $lang === 'de' ? 'Beschreibung eingeben...' : 'Enter description...' ?>"></div>
        </div>
      </details>

      <label style="display:flex;align-items:center;gap:.5rem;margin:.5rem 0 .75rem;font-size:.8rem;cursor:pointer"><input type="checkbox" name="project_maintenance" id="descFormMaintenance" value="1"> <?= $lang === 'de' ? 'Wartungsmodus' : 'Maintenance mode' ?></label>
      <button type="submit" class="modal-submit"><?= $t['save'] ?></button>
    </form>
    <button class="modal-close" onclick="closeDescModal()" style="padding:.6rem 2rem"><?= $t['cancel'] ?></button>
  </div>
</div>

<div class="modal-bg" id="addLinkModal">
  <div class="modal" style="text-align:left;max-width:440px">
    <h2><?= $t['manual_link_add'] ?></h2>
    <form method="POST" action="<?= DASH_BASE ?>" id="addLinkForm" onsubmit="syncEditor('mlEditor','mlDesc')">
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
<?php endif; ?>

</div>

<!-- ==================== LOGIN MODAL ==================== -->
<?php if (!$isAdmin): ?>
<div class="modal-bg" id="adminModal">
  <div class="modal">
    <h2><?= $t['admin_access'] ?></h2>
    <p><?= $t['admin_desc'] ?></p>
    <form method="POST" action="<?= DASH_BASE ?>">
      <input type="text" name="username" class="modal-input modal-input-text<?= $loginError ? ' error' : '' ?>" id="loginUser"
             autocomplete="username" placeholder="<?= $t['username'] ?>" required>
      <input type="password" name="password" class="modal-input<?= $loginError ? ' error' : '' ?>" id="loginPass"
             autocomplete="current-password" placeholder="<?= $t['password'] ?>" style="margin-top:.6rem" required>
      <div class="modal-error"><?= htmlspecialchars($loginError) ?></div>
      <button type="submit" class="modal-submit"><?= $t['login'] ?></button>
      <?php $smtpConfigured = !empty($dashCfg['smtp_host'] ?? ''); ?>
      <?php if ($smtpConfigured): ?>
        <a href="<?= DASH_BASE ?>?action=forgot_password" class="forgot-link"><?= $t['forgot_pw'] ?></a>
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
var DASH_BASE='<?= DASH_BASE ?>';
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
  var hlWrap=document.getElementById('homelabPickerWrap');
  var emWrap=document.getElementById('iconPickerWrap');
  var hasHl=document.getElementById('descFormHomelabIcon').value!=='';
  if(hlWrap) hlWrap.style.display=hasAnyLogo?'none':'block';
  if(emWrap) emWrap.style.display=(hasAnyLogo||hasHl)?'none':'block';
}
function _checkAnyLogo(){
  var d=document.getElementById('projLogo_dark_has').style.display!=='none';
  var l=document.getElementById('projLogo_light_has').style.display!=='none';
  toggleIconPicker(d||l);
}
function pickHomelabIcon(key){
  var input=document.getElementById('descFormHomelabIcon');
  var btns=document.querySelectorAll('.homelab-pick-btn');
  if(input.value===key){
    input.value='';
    btns.forEach(function(b){b.classList.remove('selected')});
  }else{
    input.value=key;
    btns.forEach(function(b){b.classList.toggle('selected',b.dataset.key===key)});
    document.getElementById('descFormIcon').value='';
  }
  var emWrap=document.getElementById('iconPickerWrap');
  if(emWrap) emWrap.style.display=input.value?'none':'block';
}
function clearHomelabIcon(){
  document.getElementById('descFormHomelabIcon').value='';
  document.querySelectorAll('.homelab-pick-btn').forEach(function(b){b.classList.remove('selected')});
  var emWrap=document.getElementById('iconPickerWrap');
  if(emWrap) emWrap.style.display='block';
}
function filterHomelabIcons(q){
  q=q.toLowerCase();
  document.querySelectorAll('.homelab-pick-btn').forEach(function(b){
    var key=b.dataset.key||'';
    var label=b.dataset.label||'';
    b.style.display=(key.indexOf(q)!==-1||label.indexOf(q)!==-1)?'':'none';
  });
}
function toggleCustomUrlInput(mode){
  var ci=document.getElementById('descFormCustomUrl');
  if(ci) ci.style.display=mode==='custom'?'block':'none';
}
function editProjectDesc(name,current,icon,title,logoDarkExt,logoLightExt,maintenance,homelabIcon,manualIndex,manualUrl,urlMode,customUrl){
  _projName=name;
  document.getElementById('descFormName').value=name;
  document.getElementById('descFormTitle').value=title||name;
  document.getElementById('descFormManualIndex').value=(manualIndex!=null&&manualIndex>=0)?manualIndex:-1;
  var urlWrap=document.getElementById('descFormUrlWrap');var urlInput=document.getElementById('descFormUrl');
  var urlModeWrap=document.getElementById('descFormUrlModeWrap');
  if(manualIndex!=null&&manualIndex>=0){urlWrap.style.display='block';urlInput.value=manualUrl||'';if(urlModeWrap)urlModeWrap.style.display='none'}else{urlWrap.style.display='none';urlInput.value='';if(urlModeWrap)urlModeWrap.style.display='block'}
  var modeSelect=document.getElementById('descFormUrlMode');
  if(modeSelect){modeSelect.value=urlMode||'auto';toggleCustomUrlInput(urlMode||'auto')}
  var customInput=document.getElementById('descFormCustomUrl');
  if(customInput) customInput.value=customUrl||'';
  document.getElementById('descFormDesc').value=current;
  document.getElementById('descFormIcon').value=icon||'';
  document.getElementById('descFormHomelabIcon').value=homelabIcon||'';
  document.querySelectorAll('.homelab-pick-btn').forEach(function(b){b.classList.toggle('selected',b.dataset.key===(homelabIcon||''));b.style.display=''});
  var hlSearch=document.getElementById('homelabSearch');if(hlSearch)hlSearch.value='';
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
      img.src=DASH_BASE+'?asset=project-logo&name='+encodeURIComponent(name)+'&variant='+v+'&t='+Date.now();
      hasEl.style.display='block';
      noneEl.style.display='none';
    }else{
      img.src='';
      hasEl.style.display='none';
      noneEl.style.display='block';
    }
  });
  _checkAnyLogo();
  // Collapse-Sektionen: immer geschlossen starten, Previews setzen
  var collapseIcon=document.getElementById('descCollapseIcon');
  var collapseDesc=document.getElementById('descCollapseDesc');
  if(collapseIcon) collapseIcon.open=false;
  if(collapseDesc) collapseDesc.open=false;
  var iconPrev=document.getElementById('descIconPreview');
  if(iconPrev) iconPrev.textContent=(logoDarkExt||logoLightExt)?'📎 Logo':(homelabIcon||icon||'');
  var descPrev=document.getElementById('descDescPreview');
  if(descPrev) descPrev.textContent=current?current.replace(/[*#]/g,'').substring(0,40):'';
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
  fetch(DASH_BASE+'?remove_project_logo='+encodeURIComponent(_projName)+'&variant='+variant,{credentials:'same-origin',headers:{'Accept':'application/json'}}).then(function(){
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
  fetch(DASH_BASE+'?action=system_stats').then(function(r){return r.json()}).then(function(d){
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
  fetch(DASH_BASE+'?action=check_update').then(function(r){return r.json()}).then(function(d){
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
  fetch(DASH_BASE+'?action=do_update').then(function(r){return r.json()}).then(function(d){
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
