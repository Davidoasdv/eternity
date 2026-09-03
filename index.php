<?php
// index.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

$relData = getAll('relationship');
$started_at = '2023-11-02';
$anniversaryMonth = 11;
$anniversaryDay = 2;
$quote = '"A digital archive of a story."';

if (!empty($relData)) {
    $started_at = $relData[0]['started_at'] ?? '2023-11-02';
    $anniversary = $relData[0]['anniversary_date'] ?? '2023-11-02';
    $anniversaryMonth = date('m', strtotime($anniversary));
    $anniversaryDay = date('d', strtotime($anniversary));
    $quote = $relData[0]['quote'] ?? '"A digital archive of a story."';
}

$duration = getRelationshipDuration($started_at);
$anniversaryInfo = getAnniversaryCountdown($anniversaryMonth, $anniversaryDay);
$memories = getAll('memories');
$wishlist = getAll('wishlist');
$totalMemories = count($memories);
$totalWishlist = count($wishlist);
?>
<?php $page_title = 'Home'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div class="hero-counter">
        <div class="brand">ETERNITY</div>
        <div class="subtitle">OUR LITTLE UNIVERSE</div>
        <div class="days" id="counterDays"><?php echo number_format($duration['days']); ?> DAYS</div>
        <div class="time-detail">
            <span id="counterHours"><?php echo str_pad($duration['hours'], 2, '0', STR_PAD_LEFT); ?> HOURS</span>
            <span id="counterMinutes"><?php echo str_pad($duration['minutes'], 2, '0', STR_PAD_LEFT); ?> MINUTES</span>
            <span id="counterSeconds"><?php echo str_pad($duration['seconds'], 2, '0', STR_PAD_LEFT); ?> SECONDS</span>
        </div>
        <div class="since">since <?php echo formatDate($started_at); ?></div>
        <div style="margin-top:20px; font-size:1.2rem;">
            <?php if ($anniversaryInfo['is_today']): ?>
                <span style="background:var(--primary); color:white; padding:10px; border:var(--border-thick);">HAPPY ANNIVERSARY ❤️</span>
            <?php else: ?>
                <span>Next Anniversary: <?php echo $anniversaryInfo['date']->format('d F Y'); ?> — <?php echo $anniversaryInfo['days']; ?> days left</span>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px; font-style:italic;"><?php echo $quote; ?></div>
        <div style="margin-top:20px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
            <span class="badge">📸 <?php echo $totalMemories; ?> Memories</span>
            <span class="badge">⭐ <?php echo $totalWishlist; ?> Wishlist</span>
        </div>
        <div style="margin-top:30px; display:flex; gap:15px; justify-content:center; flex-wrap:wrap;">
    <a href="story.php" class="btn btn-blue">Our Story</a>
    <a href="letters.php" class="btn">Letters</a>
    <a href="wishlist.php" class="btn btn-secondary">Wishlist</a>
</div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
