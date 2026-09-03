<?php
// open-when.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = ['title' => $_POST['title'] ?? '', 'content' => $_POST['content'] ?? ''];
    if ($action === 'create') {
        createRecord('open_when', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('open_when', $_POST['id'], $data);
    }
    redirect('/eternity/open-when.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('open_when', $_GET['delete']);
    redirect('/eternity/open-when.php');
}

$items = getAll('open_when');
usort($items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Open When'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>💌 Open When</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Open When</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul (misal: You\'re Sad)</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Isi Pesan</label><textarea name="content" rows="6" required></textarea></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">💌</div><p>Belum ada pesan Open When.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <h3>📬 <?php echo escape($item['title']); ?></h3>
                        <div style="background:#f5f5f5; padding:15px; border:2px solid var(--black); border-radius:var(--radius); margin:10px 0;">
                            <?php echo nl2br(escape($item['content'])); ?>
                        </div>
                        <div class="badge"><?php echo formatDateTime($item['created_at']); ?></div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Open When</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                    <div class="form-group"><label>Isi Pesan</label><textarea name="content" rows="6" required>' . escape($item['content']) . '</textarea></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/open-when.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>