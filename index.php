<?php
session_start();
require_once 'config/convex.php';

$convex = new ConvexDB();

// Fetch items (optional status filter)
$status = $_GET['status'] ?? 'lost';
$items = $convex->getItems($status);
$error = null;

if (isset($items['error'])) {
    $error = $items['error'];
    $items = [];
} else {
    // Bonus: Search/Filter
    $search = $_GET['search'] ?? '';
    if (!empty($search)) {
        $items = array_filter($items, function($item) use ($search) {
            return is_array($item) && (stripos($item['item_name'] ?? '', $search) !== false || stripos($item['location'] ?? '', $search) !== false);
        });
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundly - Campus Intelligence Portal</title>
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
        <!-- Hero Section -->
        <section class="row align-items-center mb-10 py-10" data-animate>
            <div class="col-lg-7">
                <span class="status-pill found mb-4 d-inline-block">Next-Gen Recovery System</span>
                <h1 class="display-2 mb-4 text-white">Reconnect with what you <span class="text-gradient">Lost.</span></h1>
                <p class="lead text-mid mb-5 fs-4" style="max-width: 600px;">The definitive campus intelligence platform for identifying and reclaiming lost assets. Seamless, secure, and powered by the community.</p>
                <div class="d-flex gap-3 mb-8">
                    <a href="report_item.php" class="btn-aurora fs-5">Report an Asset</a>
                    <a href="#registry" class="btn-ghost fs-5">Browse Registry</a>
                </div>
                <!-- Impact Stats -->
                <div class="row g-4 pt-4 border-top border-glass border-opacity-10">
                    <div class="col-4">
                        <div class="h3 text-white mb-0 font-heading">500+</div>
                        <div class="text-low small fw-700 text-uppercase">Items Recovered</div>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-white mb-0 font-heading">98%</div>
                        <div class="text-low small fw-700 text-uppercase">Success Rate</div>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-white mb-0 font-heading">2k+</div>
                        <div class="text-low small fw-700 text-uppercase">Daily Users</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="glass-panel p-4 overflow-hidden position-relative">
                    <img src="https://images.unsplash.com/photo-1586769852836-bc069f19e1b6?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-4 shadow-2xl" alt="Intelligence" style="filter: contrast(1.1) brightness(0.9);">
                    <div class="position-absolute bottom-0 start-0 w-100 p-5 bg-gradient-to-t from-black opacity-60"></div>
                </div>
            </div>
        </section>

        <!-- Stats & Action Bar -->
        <section class="glass-panel p-5 mb-10" id="registry">
            <div class="row align-items-center g-4">
                <div class="col-md-4">
                    <h3 class="mb-0 text-white">Campus <span class="text-mid fw-300">Registry</span></h3>
                </div>
                <div class="col-md-8">
                    <div class="d-flex gap-3">
                        <div class="position-relative flex-grow-1">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                            <input type="text" id="live-search" class="form-input ps-10" placeholder="Search assets instantly..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feed Controls -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div class="d-flex gap-2">
                <button onclick="updateStatusFilter('lost')" id="btn-lost" class="status-pill lost <?php echo $status === 'lost' ? 'opacity-100 border-accent' : 'opacity-40'; ?>">Lost Assets</button>
                <button onclick="updateStatusFilter('found')" id="btn-found" class="status-pill found <?php echo $status === 'found' ? 'opacity-100 border-accent' : 'opacity-40'; ?>">Recovered Items</button>
            </div>
            <div id="item-count" class="text-low small fw-700 text-uppercase ls-wider"><?php echo count($items); ?> Assets Recorded</div>
        </div>

        <!-- Registry Feed -->
        <div class="row g-6" id="registry-feed">
            <?php if ($error): ?>
                <div class="col-12 glass-panel p-10 text-center border-accent">
                    <i class="bi bi-cloud-slash-fill display-1 text-accent-tertiary mb-4 d-block"></i>
                    <h2 class="text-white">Neural Uplink Failure</h2>
                    <p class="text-mid"><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php elseif (empty($items)): ?>
                <div class="col-12 text-center py-10 opacity-60">
                    <i class="bi bi-folder-x display-1 mb-4 d-block text-mid"></i>
                    <h3 class="text-mid">No records match your criteria.</h3>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="col-xl-4 col-md-6 item-container" data-animate>
                        <div class="item-card">
                            <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" class="item-card-image" alt="Evidence">
                            <div class="item-card-content">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <span class="status-pill <?php echo $item['status']; ?>"><?php echo $item['status']; ?></span>
                                    <div class="text-low small fw-700"><i class="bi bi-clock me-2"></i><?php echo date('M d', ($item['created_at'] ?? time()*1000) / 1000); ?></div>
                                </div>
                                <h4 class="text-white mb-3"><?php echo htmlspecialchars($item['item_name']); ?></h4>
                                <div class="space-y-3 mb-6">
                                    <div class="d-flex align-items-center text-mid small">
                                        <i class="bi bi-geo-alt-fill me-3 text-accent-tertiary"></i>
                                        <?php echo htmlspecialchars($item['location']); ?>
                                    </div>
                                    <div class="d-flex align-items-center text-mid small">
                                        <i class="bi bi-grid-fill me-3 text-accent-secondary"></i>
                                        <?php echo htmlspecialchars($item['category'] ?? 'Miscellaneous'); ?>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="claim_item.php?id=<?php echo $item['_id']; ?>" class="btn-aurora text-center text-decoration-none">View Asset Integrity</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="container py-10 mt-10 border-top border-glass text-center">
        <div class="mb-4">
            <span class="text-gradient font-heading fs-4">Foundly</span>
        </div>
        <div class="text-low small fw-600 mb-2">Designed for the Next Generation by Sourish Dey</div>
        <div class="text-low opacity-60 extra-small">CSE 02 • 23051223</div>
    </footer>

    <script>
        let currentStatus = '<?php echo $status; ?>';
        const searchInput = document.querySelector('#live-search');
        const feed = document.querySelector('#registry-feed');
        const countDisplay = document.querySelector('#item-count');

        // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        const reObserve = () => {
            document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
        };

        reObserve();

        async function performSearch() {
            const query = searchInput.value;
            const res = await fetch(`api/search_items.php?status=${currentStatus}&search=${encodeURIComponent(query)}`);
            const data = await res.json();
            
            if (data.error) {
                feed.innerHTML = `<div class="col-12 glass-panel p-10 text-center border-accent border-opacity-50"><h2 class="text-white">Neural Uplink Failure</h2><p class="text-mid">${data.error}</p></div>`;
                return;
            }

            if (data.items.length === 0) {
                feed.innerHTML = `<div class="col-12 text-center py-10 opacity-60"><i class="bi bi-folder-x display-1 mb-4 d-block text-mid"></i><h3 class="text-mid">No records match your criteria.</h3></div>`;
                countDisplay.innerText = '0 Assets Recorded';
                return;
            }

            countDisplay.innerText = `${data.items.length} Assets Recorded`;
            feed.innerHTML = data.items.map(item => `
                <div class="col-xl-4 col-md-6" data-animate>
                    <div class="item-card">
                        <img src="uploads/${item.image_path}" class="item-card-image" alt="Evidence">
                        <div class="item-card-content">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <span class="status-pill ${item.status}">${item.status}</span>
                                <div class="text-low small fw-700"><i class="bi bi-clock me-2"></i>${item.created_at_formatted}</div>
                            </div>
                            <h4 class="text-white mb-3">${item.item_name}</h4>
                            <div class="space-y-3 mb-6">
                                <div class="d-flex align-items-center text-mid small">
                                    <i class="bi bi-geo-alt-fill me-3 text-accent-tertiary"></i>
                                    ${item.location}
                                </div>
                                <div class="d-flex align-items-center text-mid small">
                                    <i class="bi bi-grid-fill me-3 text-accent-secondary"></i>
                                    ${item.category}
                                </div>
                            </div>
                            <div class="d-grid">
                                <a href="claim_item.php?id=${item._id}" class="btn-aurora text-center text-decoration-none">View Asset Integrity</a>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            reObserve();
        }

        function updateStatusFilter(status) {
            currentStatus = status;
            document.querySelector('#btn-lost').classList.toggle('opacity-100', status === 'lost');
            document.querySelector('#btn-lost').classList.toggle('opacity-40', status !== 'lost');
            document.querySelector('#btn-found').classList.toggle('opacity-100', status === 'found');
            document.querySelector('#btn-found').classList.toggle('opacity-40', status !== 'found');
            performSearch();
        }

        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performSearch, 300);
        });
    </script>
</body>
</html>
