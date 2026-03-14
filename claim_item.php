<?php
session_start();
require_once 'config/convex.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$itemId = $_GET['id'] ?? null;
if (!$itemId) {
    header("Location: index.php");
    exit();
}

$convex = new ConvexDB();
// Fetch item details to show what is being claimed
$items = $convex->getItems();
$item = null;
$error = null;

if (isset($items['error'])) {
    $error = $items['error'];
} else {
    foreach ($items as $i) {
        if (is_array($i) && isset($i['_id']) && $i['_id'] === $itemId) {
            $item = $i;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Ownership - Foundly Intel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="aurora-container">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="aurora-blob aurora-3"></div>
    </div>

    <?php include 'navbar.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <?php if ($error): ?>
                    <div class="glass-panel p-10 text-center shadow-premium" data-animate>
                        <i class="bi bi-cloud-slash-fill display-1 text-accent-tertiary mb-6 d-block"></i>
                        <h2 class="text-white mb-4">Neural Uplink Failure</h2>
                        <p class="text-mid mb-8 fs-5"><?php echo htmlspecialchars($error); ?></p>
                        <a href="index.php" class="btn-aurora">Return to Stream</a>
                    </div>
                <?php elseif (!$item): ?>
                    <div class="glass-panel p-10 text-center shadow-premium" data-animate>
                        <i class="bi bi-search display-1 text-low mb-6 d-block"></i>
                        <h2 class="text-white mb-4">Asset Not Located</h2>
                        <p class="text-mid mb-8 fs-5">The requested asset record could not be discovered in the current registry.</p>
                        <a href="index.php" class="btn-aurora">Browse Registry</a>
                    </div>
                <?php else: ?>
                    <header class="text-center mb-10" data-animate>
                        <span class="status-pill found mb-4 d-inline-block">Ownership Protocol</span>
                        <h1 class="display-3 text-white">Identity <span class="text-gradient">Verification</span></h1>
                        <p class="text-mid fs-5 mx-auto" style="max-width: 650px;">Complete the authentication sequence to claim this discovered asset.</p>
                    </header>

                    <div class="glass-panel shadow-premium overflow-hidden" data-animate style="animation-delay: 0.1s;">
                        <div class="row g-0">
                            <div class="col-lg-5 position-relative overflow-hidden" style="min-height: 500px;">
                                <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" class="w-100 h-100 object-fit-cover" alt="Asset Evidence">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-t from-black opacity-70"></div>
                                <div class="position-absolute bottom-0 start-0 p-8 text-white w-100">
                                    <div class="status-pill found mb-4 d-inline-block">Pending Verification</div>
                                    <h2 class="display-5 fw-800 mb-3"><?php echo htmlspecialchars($item['item_name']); ?></h2>
                                    <div class="d-flex align-items-center text-mid fw-600">
                                        <i class="bi bi-geo-alt-fill me-2 text-accent-tertiary"></i>
                                        <?php echo htmlspecialchars($item['location']); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-7 p-8 bg-white bg-opacity-5">
                                <div class="d-flex align-items-center mb-8">
                                    <div class="bg-indigo-500 bg-opacity-20 p-4 rounded-4 me-4">
                                        <i class="bi bi-shield-lock-fill text-accent-primary display-5"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-white mb-1">Authenticity Check</h3>
                                        <p class="text-low small text-uppercase ls-wider fw-700">Level 2 Security Clearance Required</p>
                                    </div>
                                </div>

                                <form action="api/create_claim.php" method="POST" enctype="multipart/form-data" class="row g-6">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($itemId); ?>">
                                    
                                    <div class="col-12">
                                        <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Neural Verification Evidence</label>
                                        <textarea name="proof_text" class="form-input" rows="5" required placeholder="Describe characteristics known only to the owner (contents, serials, unique markings)..."></textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Physical Documentation (Optional)</label>
                                        <div class="p-5 rounded-4 glass-panel text-center border-dashed border-glass cursor-pointer" onclick="document.querySelector('#proof-file').click();">
                                            <i class="bi bi-cloud-arrow-up-fill text-accent-primary display-6 mb-3 d-block"></i>
                                            <input type="file" name="proof_image" id="proof-file" class="d-none" accept=".jpg,.jpeg,.png" onchange="this.nextElementSibling.innerText = 'Selected: ' + this.files[0].name">
                                            <p class="text-low mb-0 small fw-600">Upload receipts or personal device logs</p>
                                        </div>
                                    </div>

                                    <div class="col-12 pt-6">
                                        <div class="d-flex gap-4">
                                            <button type="submit" class="btn-aurora flex-grow-1 fs-5 py-4">Transmit Claim Protocol</button>
                                            <a href="index.php" class="btn-ghost d-flex align-items-center px-6">Abort</a>
                                        </div>
                                        <p class="text-center mt-5 text-low small fw-500 opacity-60">
                                            <i class="bi bi-info-circle me-1"></i> Claims are subject to forensic review by campus security.
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-animate]').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 150);
        });
    </script>
</body>
</html>
