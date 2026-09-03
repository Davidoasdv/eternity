<?php
// songs.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'title' => $_POST['title'] ?? '',
        'artist' => $_POST['artist'] ?? '',
        'url' => $_POST['url'] ?? '',
        'note' => $_POST['note'] ?? ''
    ];
    if ($action === 'create') {
        createRecord('songs', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('songs', $_POST['id'], $data);
    }
    redirect('/eternity/songs.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('songs', $_GET['delete']);
    redirect('/eternity/songs.php');
}

$items = getAll('songs');
usort($items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Our Songs'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>🎵 Our Songs</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Lagu</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul Lagu</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Artis</label><input type="text" name="artist"></div>
                <div class="form-group"><label>URL (Spotify/Youtube)</label><input type="url" name="url" placeholder="https://open.spotify.com/..."></div>
                <div class="form-group"><label>Catatan</label><textarea name="note" rows="3"></textarea></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">🎵</div><p>Belum ada lagu.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <h3>🎵 <?php echo escape($item['title']); ?></h3>
                        <?php if (!empty($item['artist'])): ?>
                            <p style="color:#666;">by <?php echo escape($item['artist']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['note'])): ?>
                            <div style="background:#f5f5f5; padding:10px; border:2px solid var(--black); border-radius:var(--radius); margin:10px 0;">
                                <?php echo escape($item['note']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['url'])): ?>
                            <a href="<?php echo escape($item['url']); ?>" target="_blank" class="btn btn-blue" style="padding:8px 16px;font-size:0.9rem;display:inline-block;margin-top:5px;">▶️ Listen</a>
                        <?php endif; ?>
                        <div class="badge" style="margin-top:10px;"><?php echo formatDateTime($item['created_at']); ?></div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Lagu</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Judul Lagu</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                    <div class="form-group"><label>Artis</label><input type="text" name="artist" value="' . escape($item['artist'] ?? '') . '"></div>
                                    <div class="form-group"><label>URL</label><input type="url" name="url" value="' . escape($item['url'] ?? '') . '"></div>
                                    <div class="form-group"><label>Catatan</label><textarea name="note" rows="3">' . escape($item['note'] ?? '') . '</textarea></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/songs.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>