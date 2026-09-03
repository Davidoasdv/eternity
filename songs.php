<?php
// songs.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'artist' => $_POST['artist'] ?? '',
        'url' => $_POST['url'] ?? '',
        'note' => $_POST['note'] ?? ''
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('songs', $_POST['id'], $data);
    } else {
        createRecord('songs', $data);
    }
    redirect('songs.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('songs', $_GET['delete']);
    redirect('songs.php');
}

$songs = getAll('songs');
?>
<?php $page_title = 'Our Songs'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>Our Songs</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Tambah Lagu</h2>
            <form method="POST" action="songs.php">
                <div class="form-group"><label>Judul Lagu</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Artis</label><input type="text" name="artist"></div>
                <div class="form-group"><label>URL (Spotify/YouTube)</label><input type="url" name="url"></div>
                <div class="form-group"><label>Catatan</label><textarea name="note" rows="3"></textarea></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah Lagu</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($songs)): ?>
            <div class="empty-state">
                <div class="emoji">🎵</div>
                <p>No songs yet. Maybe there\'s a song waiting to become ours.</p>
            </div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:20px;">
                <?php foreach ($songs as $song): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($song['title']); ?></h3>
                        <?php if (!empty($song['artist'])): ?>
                            <p style="color:#666;"><?php echo htmlspecialchars($song['artist']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($song['note'])): ?>
                            <p><em>"<?php echo htmlspecialchars($song['note']); ?>"</em></p>
                        <?php endif; ?>
                        <?php if (!empty($song['url'])): ?>
                            <a href="<?php echo htmlspecialchars($song['url']); ?>" target="_blank" class="btn btn-blue" style="display:inline-block; margin-top:10px; padding:6px 12px; font-size:0.9rem;">▶️ Listen</a>
                        <?php endif; ?>
                        <div style="margin-top:10px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                                <h2>Edit Lagu</h2>
                                <form method="POST" action="songs.php">
                                    <input type="hidden" name="id" value="' . $song['id'] . '">
                                    <div class="form-group"><label>Judul Lagu</label><input type="text" name="title" value="' . htmlspecialchars($song['title']) . '" required></div>
                                    <div class="form-group"><label>Artis</label><input type="text" name="artist" value="' . htmlspecialchars($song['artist'] ?? '') . '"></div>
                                    <div class="form-group"><label>URL</label><input type="url" name="url" value="' . htmlspecialchars($song['url'] ?? '') . '"></div>
                                    <div class="form-group"><label>Catatan</label><textarea name="note" rows="3">' . htmlspecialchars($song['note'] ?? '') . '</textarea></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="songs.php?delete=<?php echo $song['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
