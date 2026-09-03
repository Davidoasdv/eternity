<?php
// future-letters.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'unlock_date' => $_POST['unlock_date'] ?? date('Y-m-d', strtotime('+1 year'))
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('future_letters', $_POST['id'], $data);
    } else {
        createRecord('future_letters', $data);
    }
    redirect('future-letters.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('future_letters', $_GET['delete']);
    redirect('future-letters.php');
}

$futureLetters = getAll('future_letters');
$today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
?>
<?php $page_title = 'Future Letters'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>🔒 Future Letters</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Buat Future Letter</h2>
            <form method="POST" action="future-letters.php">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required></textarea></div>
                <div class="form-group"><label>Tanggal Buka</label><input type="date" name="unlock_date" required></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Buat Future Letter</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($futureLetters)): ?>
            <div class="empty-state">
                <div class="emoji">📮</div>
                <p>Belum ada future letters. Kirim pesan untuk masa depan.</p>
            </div>
        <?php else: ?>
            <?php foreach ($futureLetters as $letter): 
                $unlockDate = new DateTime($letter['unlock_date'], new DateTimeZone('Asia/Jakarta'));
                $isUnlocked = ($today >= $unlockDate);
            ?>
                <div class="card" style="margin-bottom:15px; <?php echo $isUnlocked ? 'border-color:var(--green);' : ''; ?>">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h3><?php echo $isUnlocked ? '📬' : '🔒'; ?> <?php echo htmlspecialchars($letter['title']); ?></h3>
                        <span class="badge <?php echo $isUnlocked ? 'badge-green' : ''; ?>">
                            <?php echo $isUnlocked ? 'UNLOCKED' : 'LOCKED'; ?>
                        </span>
                    </div>
                    <p><small>Buka pada: <?php echo formatDate($letter['unlock_date']); ?></small></p>
                    <?php if ($isUnlocked): ?>
                        <p><?php echo nl2br(htmlspecialchars($letter['content'])); ?></p>
                    <?php else: ?>
                        <div style="background:#f0f0f0; padding:20px; border:var(--border-thick); border-radius:var(--radius); text-align:center;">
                            🔒 Surat ini terkunci sampai <?php echo formatDate($letter['unlock_date']); ?>
                        </div>
                    <?php endif; ?>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                            <h2>Edit Future Letter</h2>
                            <form method="POST" action="future-letters.php">
                                <input type="hidden" name="id" value="' . $letter['id'] . '">
                                <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . htmlspecialchars($letter['title']) . '" required></div>
                                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required>' . htmlspecialchars($letter['content']) . '</textarea></div>
                                <div class="form-group"><label>Tanggal Buka</label><input type="date" name="unlock_date" value="' . $letter['unlock_date'] . '" required></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="future-letters.php?delete=<?php echo $letter['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
