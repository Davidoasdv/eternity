<?php
// includes/functions.php
require_once 'config/supabase.php';

function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    // Hapus slash di depan jika ada
    $url = ltrim($url, '/');
    header("Location: $url");
    exit;
}

function formatDate($date) {
    return date('d F Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('d F Y H:i', strtotime($datetime));
}

function getRelationshipDuration($startDate) {
    $start = new DateTime($startDate, new DateTimeZone('Asia/Jakarta'));
    $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    $diff = $now->diff($start);
    return [
        'days' => $diff->days,
        'hours' => $diff->h,
        'minutes' => $diff->i,
        'seconds' => $diff->s,
        'total_days' => $diff->days,
    ];
}

function getAnniversaryCountdown($anniversaryMonth, $anniversaryDay) {
    $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    $year = (int)$now->format('Y');
    $anniversary = new DateTime("$year-$anniversaryMonth-$anniversaryDay", new DateTimeZone('Asia/Jakarta'));
    if ($anniversary < $now) {
        $anniversary->modify('+1 year');
    }
    $diff = $now->diff($anniversary);
    return [
        'date' => $anniversary,
        'days' => $diff->days,
        'is_today' => ($diff->days == 0 && $diff->h == 0 && $diff->i == 0 && $diff->s == 0)
    ];
}

function generateStorageUrl($path) {
    return SUPABASE_URL . '/storage/v1/object/public/' . $path;
}

// ============================================
// FUNGSI CRUD DENGAN SUPABASE REST API
// ============================================

function supabaseRequest($method, $endpoint, $body = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    $headers = [
        'apikey: ' . SUPABASE_ANON_KEY,
        'Authorization: Bearer ' . SUPABASE_ANON_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if ($body) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

// Contoh: get all from table
function getAll($table) {
    $result = supabaseRequest('GET', $table . '?select=*');
    return $result['data'] ?? [];
}

function getById($table, $id) {
    $result = supabaseRequest('GET', $table . '?id=eq.' . $id . '&select=*');
    return $result['data'][0] ?? null;
}

function createRecord($table, $data) {
    return supabaseRequest('POST', $table, $data);
}

function updateRecord($table, $id, $data) {
    return supabaseRequest('PATCH', $table . '?id=eq.' . $id, $data);
}

function deleteRecord($table, $id) {
    return supabaseRequest('DELETE', $table . '?id=eq.' . $id);
}

// Upload file ke Supabase Storage
function uploadFile($bucket, $path, $fileData, $mimeType) {
    $url = SUPABASE_URL . '/storage/v1/object/' . $bucket . '/' . $path;
    $headers = [
        'apikey: ' . SUPABASE_ANON_KEY,
        'Authorization: Bearer ' . SUPABASE_ANON_KEY,
        'Content-Type: ' . $mimeType
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'path' => $bucket . '/' . $path
    ];
}
?>
