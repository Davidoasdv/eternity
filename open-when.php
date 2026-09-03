<?php
// open-when.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? ''
    ];
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('open_when', $_POST['id'], $data);
    } else {
        createRecord('open_when', $data);
    }
    redirect('open-when.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('open_when', $_GET['delete']);
    redirect('open-when.php');
}

$openWhens = getAll('open_when');
?>
<?php $page_title = 'Open When'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>Open When</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Tambah Open When</h2>
            <form method="POST" action="open-when.php">
                <div class="form-group"><label>Judul (contoh: You\'re Sad)</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required></textarea></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($openWhens)): ?>
            <div class="empty-state">
                <div class="emoji">📬</div>
                <p>Belum ada Open When messages.</p>
            </div>
        <?php else: ?>
            <?php foreach ($openWhens as $item): ?>
                <div class="card" style="margin-bottom:15px;">
                    <h3>💌 <?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                            <h2>Edit Open When</h2>
                            <form method="POST" action="open-when.php">
                                <input type="hidden" name="id" value="' . $item['id'] . '">
                                <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . htmlspecialchars($item['title']) . '" required></div>
                                <div class="form-group"><label>Isi</label><textarea name="content" rows="6" required>' . htmlspecialchars($item['content']) . '</textarea></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="open-when.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
