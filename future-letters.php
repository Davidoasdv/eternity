<?php
// future-letters.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'unlock_date' => $_POST['unlock_date'] ?? ''
    ];
    if ($action === 'create') {
        createRecord('future_letters', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('future_letters', $_POST['id'], $data);
    }
    redirect('/eternity/future-letters.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('future_letters', $_GET['delete']);
    redirect('/eternity/future-letters.php');
}

$items = getAll('future_letters');
usort($items, function($a, $b) {
    return strtotime($a['unlock_date']) - strtotime($b['unlock_date']);
});

function isLocked($unlockDate) {
    return strtotime($unlockDate) > time();
}
?>
<?php $page_title = 'Future Letters'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>🔮 Future Letters</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Future Letter</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Isi Surat</label><textarea name="content" rows="6" required></textarea></div>
                <div class="form-group"><label>Tanggal Buka</label><input type="date" name="unlock_date" required></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">🔮</div><p>Belum ada surat masa depan.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <?php $locked = isLocked($item['unlock_date']); ?>
                    <div class="card" style="<?php echo $locked ? 'opacity:0.7;' : ''; ?>">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <h3><?php echo $locked ? '🔒' : '📬'; ?> <?php echo escape($item['title']); ?></h3>
                            <span class="badge <?php echo $locked ? 'badge-primary' : 'badge-green'; ?>">
                                <?php echo $locked ? '🔒 LOCKED' : '✅ UNLOCKED'; ?>
                            </span>
                        </div>
                        <div style="margin:10px 0;">
                            <span class="badge">📅 Opens: <?php echo formatDate($item['unlock_date']); ?></span>
                            <?php if ($locked): ?>
                                <p style="color:#888; font-style:italic; margin-top:10px;">⏳ Surat ini akan terbuka pada <?php echo formatDate($item['unlock_date']); ?></p>
                            <?php else: ?>
                                <div style="background:#f5f5f5; padding:15px; border:2px solid var(--black); border-radius:var(--radius); margin-top:10px;">
                                    <?php echo nl2br(escape($item['content'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="badge" style="margin-top:10px;">Dibuat: <?php echo formatDateTime($item['created_at']); ?></div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Future Letter</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                    <div class="form-group"><label>Isi Surat</label><textarea name="content" rows="6" required>' . escape($item['content']) . '</textarea></div>
                                    <div class="form-group"><label>Tanggal Buka</label><input type="date" name="unlock_date" value="' . $item['unlock_date'] . '" required></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/future-letters.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>