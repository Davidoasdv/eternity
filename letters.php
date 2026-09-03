<?php
// letters.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? ''
    ];
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('letters', $_POST['id'], $data);
    } else {
        createRecord('letters', $data);
    }
    redirect('letters.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('letters', $_GET['delete']);
    redirect('letters.php');
}

$letters = getAll('letters');
usort($letters, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Letters'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>Letters</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Tulis Surat</h2>
            <form method="POST" action="letters.php">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required></textarea></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tulis Surat</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($letters)): ?>
            <div class="empty-state">
                <div class="emoji">💌</div>
                <p>Belum ada surat. Tulis sesuatu untuk orang tersayang.</p>
            </div>
        <?php else: ?>
            <?php foreach ($letters as $letter): ?>
                <div class="card" style="margin-bottom:15px;">
                    <h3><?php echo htmlspecialchars($letter['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($letter['content'])); ?></p>
                    <div class="badge"><?php echo formatDateTime($letter['created_at']); ?></div>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                            <h2>Edit Surat</h2>
                            <form method="POST" action="letters.php">
                                <input type="hidden" name="id" value="' . $letter['id'] . '">
                                <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . htmlspecialchars($letter['title']) . '" required></div>
                                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required>' . htmlspecialchars($letter['content']) . '</textarea></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="letters.php?delete=<?php echo $letter['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
