<?php
// future.php
require_once 'includes/auth.php';
requireAuth();
$page_title = 'Future';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container">
    <h1 style="font-family:var(--font-display);">Future</h1>
    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">
        <a href="future-letters.php" class="btn btn-blue" style="flex:1; text-align:center;">Future Letters</a>
        <a href="dreams.php" class="btn" style="flex:1; text-align:center;">Dreams</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
