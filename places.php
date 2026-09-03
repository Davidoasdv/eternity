<?php
// places.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'category' => $_POST['category'] ?? '',
        'status' => $_POST['status'] ?? 'DREAM',
        'target_date' => $_POST['target_date'] ?? null
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('places', $_POST['id'], $data);
    } else {
        createRecord('places', $data);
    }
    redirect('places.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('places', $_GET['delete']);
    redirect('places.php');
}

$places = getAll('places');
?>
<?php $page_title = 'Places'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>Places to Visit</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Tambah Tempat</h2>
            <form method="POST" action="places.php">
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
        '); ?>`)">Tambah Tempat</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($places)): ?>
            <div class="empty-state">
                <div class="emoji">🌍</div>
                <p>Belum ada tempat. Mulai rencanakan petualanganmu.</p>
            </div>
        <?php else: ?>
            <?php foreach ($places as $place): ?>
                <div class="card" style="margin-bottom:15px;">
                    <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                    <p><?php echo htmlspecialchars($place['description'] ?? ''); ?></p>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <?php if (!empty($place['category'])): ?>
                            <span class="badge"><?php echo htmlspecialchars($place['category']); ?></span>
                        <?php endif; ?>
                        <span class="badge badge-<?php echo strtolower($place['status'] ?? 'DREAM'); ?>">
                            <?php echo htmlspecialchars($place['status'] ?? 'DREAM'); ?>
                        </span>
                        <?php if (!empty($place['target_date'])): ?>
                            <span class="badge">Target: <?php echo formatDate($place['target_date']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                            <h2>Edit Tempat</h2>
                            <form method="POST" action="places.php">
                                <input type="hidden" name="id" value="' . $place['id'] . '">
                                <div class="form-group"><label>Nama Tempat</label><input type="text" name="name" value="' . htmlspecialchars($place['name']) . '" required></div>
                                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . htmlspecialchars($place['description'] ?? '') . '</textarea></div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="category">
                                        <option value="Food" ' . ($place['category'] == 'Food' ? 'selected' : '') . '>Food</option>
                                        <option value="Cafe" ' . ($place['category'] == 'Cafe' ? 'selected' : '') . '>Cafe</option>
                                        <option value="Date" ' . ($place['category'] == 'Date' ? 'selected' : '') . '>Date</option>
                                        <option value="Vacation" ' . ($place['category'] == 'Vacation' ? 'selected' : '') . '>Vacation</option>
                                        <option value="Travel" ' . ($place['category'] == 'Travel' ? 'selected' : '') . '>Travel</option>
                                        <option value="Other" ' . ($place['category'] == 'Other' ? 'selected' : '') . '>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="DREAM" ' . ($place['status'] == 'DREAM' ? 'selected' : '') . '>Dream</option>
                                        <option value="PLANNED" ' . ($place['status'] == 'PLANNED' ? 'selected' : '') . '>Planned</option>
                                        <option value="VISITED" ' . ($place['status'] == 'VISITED' ? 'selected' : '') . '>Visited</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Target Date</label><input type="date" name="target_date" value="' . ($place['target_date'] ?? '') . '"></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="places.php?delete=<?php echo $place['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
