<?php
// places.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'category' => $_POST['category'] ?? '',
        'status' => $_POST['status'] ?? 'DREAM',
        'target_date' => !empty($_POST['target_date']) ? $_POST['target_date'] : null
    ];
    if ($action === 'create') {
        createRecord('places', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('places', $_POST['id'], $data);
    }
    redirect('/eternity/places.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('places', $_GET['delete']);
    redirect('/eternity/places.php');
}

$items = getAll('places');
usort($items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Places'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>📍 Places to Visit</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Tempat</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Nama Tempat</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category">
                        <option value="Food">Food</option>
                        <option value="Cafe">Cafe</option>
                        <option value="Date">Date</option>
                        <option value="Vacation">Vacation</option>
                        <option value="Travel">Travel</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="DREAM">Dream</option>
                        <option value="PLANNED">Planned</option>
                        <option value="VISITED">Visited</option>
                    </select>
                </div>
                <div class="form-group"><label>Target Date</label><input type="date" name="target_date"></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">📍</div><p>Belum ada tempat.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <h3>📍 <?php echo escape($item['name']); ?></h3>
                            <span class="badge <?php echo $item['status'] === 'VISITED' ? 'badge-green' : 'badge-blue'; ?>">
                                <?php echo escape($item['status'] ?? 'DREAM'); ?>
                            </span>
                        </div>
                        <p><?php echo escape($item['description'] ?? ''); ?></p>
                        <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                            <?php if (!empty($item['category'])): ?>
                                <span class="badge"><?php echo escape($item['category']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['target_date'])): ?>
                                <span class="badge">📅 <?php echo formatDate($item['target_date']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Tempat</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Nama Tempat</label><input type="text" name="name" value="' . escape($item['name']) . '" required></div>
                                    <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . escape($item['description'] ?? '') . '</textarea></div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="category">
                                            <option value="Food" ' . ($item['category'] === 'Food' ? 'selected' : '') . '>Food</option>
                                            <option value="Cafe" ' . ($item['category'] === 'Cafe' ? 'selected' : '') . '>Cafe</option>
                                            <option value="Date" ' . ($item['category'] === 'Date' ? 'selected' : '') . '>Date</option>
                                            <option value="Vacation" ' . ($item['category'] === 'Vacation' ? 'selected' : '') . '>Vacation</option>
                                            <option value="Travel" ' . ($item['category'] === 'Travel' ? 'selected' : '') . '>Travel</option>
                                            <option value="Other" ' . ($item['category'] === 'Other' ? 'selected' : '') . '>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status">
                                            <option value="DREAM" ' . ($item['status'] === 'DREAM' ? 'selected' : '') . '>Dream</option>
                                            <option value="PLANNED" ' . ($item['status'] === 'PLANNED' ? 'selected' : '') . '>Planned</option>
                                            <option value="VISITED" ' . ($item['status'] === 'VISITED' ? 'selected' : '') . '>Visited</option>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Target Date</label><input type="date" name="target_date" value="' . ($item['target_date'] ?? '') . '"></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/places.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>