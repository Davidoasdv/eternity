<?php
// settings.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

// Proses update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'relationship') {
        $data = [
            'started_at' => $_POST['started_at'] ?? '2023-11-02',
            'anniversary_date' => $_POST['anniversary_date'] ?? '2023-11-02',
            'quote' => $_POST['quote'] ?? '"A digital archive of a story."'
        ];
        // Update relationship (ambil id pertama)
        $relData = getAll('relationship');
        if (!empty($relData)) {
            updateRecord('relationship', $relData[0]['id'], $data);
        } else {
            createRecord('relationship', $data);
        }
        redirect('settings.php');
    }
}

// Ambil data relationship
$relData = getAll('relationship');
$started_at = '2023-11-02';
$anniversary_date = '2023-11-02';
$quote = '"A digital archive of a story."';
if (!empty($relData)) {
    $started_at = $relData[0]['started_at'] ?? '2023-11-02';
    $anniversary_date = $relData[0]['anniversary_date'] ?? '2023-11-02';
    $quote = $relData[0]['quote'] ?? '"A digital archive of a story."';
}
?>
<?php $page_title = 'Settings'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <h1 style="font-family:var(--font-display);">⚙️ Settings</h1>
    
    <div class="card" style="margin-top:20px;">
        <h2>📊 Relationship Settings</h2>
        <form method="POST" action="settings.php">
            <input type="hidden" name="action" value="relationship">
            
            <div class="form-group">
                <label>Started At</label>
                <input type="date" name="started_at" value="<?php echo $started_at; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Anniversary Date</label>
                <input type="date" name="anniversary_date" value="<?php echo $anniversary_date; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Quote</label>
                <input type="text" name="quote" value="<?php echo htmlspecialchars($quote); ?>" style="width:100%;">
            </div>
            
            <button type="submit" class="btn btn-blue">Update</button>
        </form>
    </div>
    
    <div class="card" style="margin-top:20px;">
        <h2>🔒 Account</h2>
        <a href="logout.php" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin logout?')">Logout</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
