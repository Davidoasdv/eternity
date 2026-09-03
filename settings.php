<?php
// settings.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

$rel = getAll('relationship');
$started_at = !empty($rel) ? $rel[0]['started_at'] : '2023-11-02';
$anniversary = !empty($rel) ? $rel[0]['anniversary_date'] : '2023-11-02';
$quote = !empty($rel) ? $rel[0]['quote'] : '"A digital archive of a story."';
$relId = !empty($rel) ? $rel[0]['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_relationship'])) {
    $data = [
        'started_at' => $_POST['started_at'] ?? '2023-11-02',
        'anniversary_date' => $_POST['anniversary_date'] ?? '2023-11-02',
        'quote' => $_POST['quote'] ?? '"A digital archive of a story."'
    ];
    if ($relId) {
        updateRecord('relationship', $relId, $data);
    } else {
        createRecord('relationship', $data);
    }
    redirect('/eternity/settings.php');
}
?>
<?php $page_title = 'Settings'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="max-width:700px; margin:0 auto;">
        <h1 style="font-family:var(--font-display);">⚙️ Settings</h1>
        <p style="margin-bottom:20px;">Kelola pengaturan ETERNITY</p>
        <div class="card">
            <h2>📊 Relationship Settings</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Started At</label>
                    <input type="date" name="started_at" value="<?php echo $started_at; ?>" required>
                </div>
                <div class="form-group">
                    <label>Anniversary Date</label>
                    <input type="date" name="anniversary_date" value="<?php echo $anniversary; ?>" required>
                </div>
                <div class="form-group">
                    <label>Quote</label>
                    <input type="text" name="quote" value="<?php echo escape($quote); ?>" style="width:100%;">
                </div>
                <button type="submit" name="update_relationship" class="btn">Simpan Perubahan</button>
            </form>
        </div>
        <div class="card" style="margin-top:20px;">
            <h2>👤 Account</h2>
            <p>User ID: <?php echo getUserId(); ?></p>
            <a href="/eternity/logout.php" class="btn" style="background:#000;color:#fff;display:inline-block;margin-top:10px;">🚪 Logout</a>
        </div>
        <div class="card" style="margin-top:20px;">
            <h2>💾 About ETERNITY</h2>
            <p><strong>Version:</strong> 1.0 MVP</p>
            <p><strong>Started:</strong> 2 November 2023</p>
            <p><em>"A digital archive of a story."</em></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>