<?php
// timeline.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'event_date' => $_POST['event_date'] ?? date('Y-m-d'),
        'location' => $_POST['location'] ?? ''
    ];
    if ($action === 'create') {
        createRecord('timeline', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('timeline', $_POST['id'], $data);
    }
    redirect('/eternity/timeline.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('timeline', $_GET['delete']);
    redirect('/eternity/timeline.php');
}

$timeline = getAll('timeline');
usort($timeline, function($a, $b) {
    return strtotime($a['event_date']) - strtotime($b['event_date']);
});
?>
<?php $page_title = 'Timeline'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>📜 Timeline</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Timeline</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group"><label>Tanggal</label><input type="date" name="event_date" required></div>
                <div class="form-group"><label>Lokasi</label><input type="text" name="location"></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($timeline)): ?>
            <div class="empty-state"><div class="emoji">📖</div><p>Belum ada timeline. Mulai catat ceritamu.</p></div>
        <?php else: ?>
            <?php foreach ($timeline as $item): ?>
                <div class="card timeline-item" style="margin-bottom:20px;">
                    <div class="date"><?php echo formatDate($item['event_date']); ?></div>
                    <h3><?php echo escape($item['title']); ?></h3>
                    <p><?php echo escape($item['description'] ?? ''); ?></p>
                    <?php if (!empty($item['location'])): ?>
                        <span class="badge">📍 <?php echo escape($item['location']); ?></span>
                    <?php endif; ?>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
                            <h2>Edit Timeline</h2>
                            <form method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="' . $item['id'] . '">
                                <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . escape($item['description'] ?? '') . '</textarea></div>
                                <div class="form-group"><label>Tanggal</label><input type="date" name="event_date" value="' . $item['event_date'] . '" required></div>
                                <div class="form-group"><label>Lokasi</label><input type="text" name="location" value="' . escape($item['location'] ?? '') . '"></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="/eternity/timeline.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>