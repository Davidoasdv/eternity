<?php
// login.php
require_once 'includes/auth.php';

if (isAuthenticated()) {
    header('Location: /eternity/index.php');
    exit;
}

require_once 'config/supabase.php';

$error = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi.';
    } else {
        $url = SUPABASE_URL . '/auth/v1/token?grant_type=password';
        
        $postData = json_encode([
            'email' => $email,
            'password' => $password
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'apikey: ' . SUPABASE_ANON_KEY,
            'Authorization: Bearer ' . SUPABASE_ANON_KEY
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $debug = [
            'url' => $url,
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError
        ];

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['access_token']) && isset($result['user']['id'])) {
                setAuthSession($result['user']['id']);
                header('Location: /eternity/index.php');
                exit;
            } else {
                $error = 'Login berhasil tetapi data user tidak lengkap.';
            }
        } else {
            $result = json_decode($response, true);
            if (isset($result['error_description'])) {
                $error = $result['error_description'];
            } elseif (isset($result['msg'])) {
                $error = $result['msg'];
            } else {
                $error = "Login gagal. HTTP Code: $httpCode";
            }
        }
    }
}
?>
<?php $page_title = 'Login'; include 'includes/header.php'; ?>
<div class="container" style="max-width:450px; margin-top:80px;">
    <div class="card">
        <h1 style="font-family:var(--font-display); text-align:center; font-size:2.5rem;">ETERNITY</h1>
        <p style="text-align:center; margin-bottom:25px; color:var(--primary);">Masuk ke arsip digitalmu.</p>

        <?php if ($error): ?>
            <div style="background:#FF6B6B; color:white; padding:15px; border:3px solid #171717; border-radius:10px; margin-bottom:20px;">
                <strong>❌ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($debug) && !empty($debug['response']) && $error): ?>
            <div style="background:#f0f0f0; padding:15px; border:2px solid #171717; border-radius:10px; margin-bottom:20px; font-size:12px; overflow-x:auto;">
                <strong>🔍 Debug Info:</strong><br>
                <strong>URL:</strong> <?php echo htmlspecialchars($debug['url']); ?><br>
                <strong>HTTP Code:</strong> <?php echo $debug['http_code']; ?><br>
                <strong>Response:</strong> 
                <pre style="background:#fff;padding:10px;border:1px solid #ddd;border-radius:5px;max-height:150px;overflow:auto;font-size:11px;"><?php echo htmlspecialchars($debug['response']); ?></pre>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn" style="width:100%; padding:14px; font-size:1.1rem;">🚀 Masuk</button>
        </form>

        <div style="margin-top:20px; text-align:center; font-size:0.85rem; color:#888;">
            <p>ETERNITY — Private Digital Archive</p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>