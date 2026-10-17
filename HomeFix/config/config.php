<?php
// App Configuration for HomeFix Local Services Marketplace

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// MapTiler API Key Configuration
// Checks config/map.properties first, then environment variable, or default demo key
$mapPropsFile = APP_ROOT . '/config/map.properties';
$loadedKey = '';
if (file_exists($mapPropsFile)) {
    $parsedProps = @parse_ini_file($mapPropsFile);
    if (!empty($parsedProps['MAPTILER_API_KEY'])) {
        $candidate = trim($parsedProps['MAPTILER_API_KEY'], " \t\n\r\0\x0B\"'");
        if ($candidate !== '' && $candidate !== 'YOUR_MAPTILER_KEY_HERE') {
            $loadedKey = $candidate;
        }
    }
}
if (empty($loadedKey)) {
    $envKey = getenv('MAPTILER_API_KEY');
    if ($envKey) {
        $loadedKey = trim($envKey, " \t\n\r\0\x0B\"'");
    }
}

define('MAPTILER_API_KEY', $loadedKey);

// Mumbai default coordinates (center of coverage)
define('DEFAULT_LAT', 19.0760);
define('DEFAULT_LNG', 72.8777);
define('DEFAULT_ZOOM', 12);

// Session lifetime: 2 hours
ini_set('session.gc_maxlifetime', 7200);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine Dynamic Base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$baseUrl = rtrim($protocol . $host . $scriptName, '/');

// If accessing through HomeFix root or public subdirectory
if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl);
}

// Uploads
define('UPLOAD_DIR', APP_ROOT . '/public/uploads/avatars/');
define('UPLOAD_URL', BASE_URL . '/public/uploads/avatars/');

/**
 * Smart Helper to resolve a service provider's profile image
 * Supports manual file drop:
 * 1. Checks database column profile_image
 * 2. Checks provider_{id}.jpg/png/webp
 * 3. Checks {firstname}.jpg/png/webp (e.g. ramesh.jpg, sunita.png)
 * Returns the public image URL or null if empty (triggering the photo gap slot)
 */
function get_provider_avatar(array $provider): ?string {
    $extensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    // 1. Database specified filename
    if (!empty($provider['profile_image']) && file_exists(UPLOAD_DIR . $provider['profile_image'])) {
        return UPLOAD_URL . $provider['profile_image'];
    }

    // 2. Check provider_{id}.ext
    if (!empty($provider['provider_id'])) {
        foreach ($extensions as $ext) {
            $file = 'provider_' . (int)$provider['provider_id'] . '.' . $ext;
            if (file_exists(UPLOAD_DIR . $file)) {
                return UPLOAD_URL . $file;
            }
        }
    }

    // 3. Check {firstname}.ext (e.g. ramesh.jpg, sunita.jpg, amit.jpg)
    if (!empty($provider['name'])) {
        $firstName = strtolower(explode(' ', trim($provider['name']))[0]);
        foreach ($extensions as $ext) {
            $file = $firstName . '.' . $ext;
            if (file_exists(UPLOAD_DIR . $file)) {
                return UPLOAD_URL . $file;
            }
        }
    }

    return null;
}
