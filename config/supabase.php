<?php

$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);

if ($isLocal) {
   
    define('SUPABASE_URL', 'https://uenyutufncnpdoyjtciq.supabase.co'); // Ganti dengan URL-mu
    define('SUPABASE_ANON_KEY', 'sb_publishable_lErZ1xVSky4lZSPm7Qggrg_sEFo-Ffo'); // Ganti dengan Anon Key-mu

    define('SUPABASE_SERVICE_ROLE_KEY', 'dummy');
} else {

    define('SUPABASE_URL', getenv('SUPABASE_URL') ?: '');
    define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: '');
    define('SUPABASE_SERVICE_ROLE_KEY', getenv('SUPABASE_SERVICE_ROLE_KEY') ?: '');
}

?>
