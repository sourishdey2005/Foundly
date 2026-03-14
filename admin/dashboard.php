<?php
session_start();
require_once '../config/convex.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$convex = new ConvexDB();
$items = $convex->getItems();
$claims = $convex->getClaims();

$totalItems = count($items);
$totalClaims = count($claims);
$pendingClaims = count(array_filter($claims, function($c) { return $c['status'] === 'pending'; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Terminal - Foundly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light admin-body overflow-hidden">
    <div class="hero-blob animate-morph opacity-30" style="position: fixed; top: -10%; right: -10%; width: 600px; height: 600px; z-index: -1;"></div>
    
    <div class="d-flex h-100vh">
        <!-- Premium Sidebar -->
        <div class="admin-sidebar glass-panel m-3 me-0 rounded-5 d-flex flex-column animate-up" style="width: 280px; height: calc(100vh - 2rem);">
            <div class="p-4 mb-4 border-bottom border-white border-opacity-20">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary p-3 rounded-4 shadow-lg animate-pulse-soft">
                        <i class="bi bi-shield-lock-fill text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 mb-0 ls-tight">Foundly</h4>
                        <span class="small text-muted text-uppercase fw-700 ls-wider">Admin v2.0</span>
                    </div>
                </div>
            </div>

            <nav class="nav flex-column gap-2 px-3 flex-grow-1">
                <a class="nav-link admin-nav-link active rounded-4 p-3 d-flex align-items-center gap-3" href="dashboard.php">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="fw-700">Analytics Hub</span>
                </a>
                <a class="nav-link admin-nav-link rounded-4 p-3 d-flex align-items-center gap-3" href="claims.php">
                    <i class="bi bi-check-all"></i>
                    <span class="fw-700">Claim Matrix</span>
                </a>
                <hr class="my-4 opacity-5">
                <a class="nav-link admin-nav-link rounded-4 p-3 d-flex align-items-center gap-3" href="../index.php">
                    <i class="bi bi-front"></i>
                    <span class="fw-700">Public Portal</span>
                </a>
            </nav>

            <div class="p-3 mt-auto">
                <div class="glass-morph p-3 rounded-4 d-flex align-items-center gap-3 mb-3">
                    <div class="bg-info bg-opacity-20 p-2 rounded-circle">
                        <i class="bi bi-person-circle fs-4 text-info"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-800 text-truncate"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                        <div class="extra-small text-muted text-uppercase fw-700">Super Admin</div>
                    </div>
                </div>
                <a href="../api/auth.php?logout=1" class="btn btn-outline-danger w-100 rounded-4 py-3 fw-800 ls-wide">
                    <i class="bi bi-power me-2"></i>DEACTIVATE SESSION
                </a>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="flex-grow-1 p-4 overflow-auto">
            <header class="d-flex justify-content-between align-items-center mb-5 animate-up">
                <div>
                    <h1 class="display-5 fw-800 mb-1">Operational <span class="text-primary">Intelligence</span></h1>
                    <p class="text-secondary fw-700 mb-0"><i class="bi bi-calendar3 me-2"></i><?php echo date('l, F d, Y'); ?></p>
                </div>
                <div class="d-flex gap-3">
                    <div class="glass-morph py-3 px-4 rounded-4 shadow-sm">
                        <span class="small text-muted fw-700 ls-wide text-uppercase">System Health</span>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="bg-success rounded-circle shadow-success animate-pulse-soft" style="width: 8px; height: 8px;"></div>
                            <span class="fw-800 text-dark">Optimal</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Stat Grid -->
            <div class="row g-4 mb-5">
                <div class="col-xl-4 col-md-6 animate-up" style="animation-delay: 0.1s;">
                    <div class="glass-panel p-4 h-100 shadow-xl border-start border-primary border-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-box-seam-fill text-primary fs-3"></i>
                            </div>
                            <span class="small badge bg-primary bg-opacity-10 text-primary fw-800 rounded-pill">+12%</span>
                        </div>
                        <h5 class="text-muted fw-800 small text-uppercase ls-wider mb-2">Assets Reported</h5>
                        <h2 class="display-6 fw-800 mb-0"><?php echo $totalItems; ?></h2>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 animate-up" style="animation-delay: 0.2s;">
                    <div class="glass-panel p-4 h-100 shadow-xl border-start border-info border-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-arrow-left-right text-info fs-3"></i>
                            </div>
                            <span class="small badge bg-info bg-opacity-10 text-info fw-800 rounded-pill">Stable</span>
                        </div>
                        <h5 class="text-muted fw-800 small text-uppercase ls-wider mb-2">Total Exchanges</h5>
                        <h2 class="display-6 fw-800 mb-0"><?php echo $totalClaims; ?></h2>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 animate-up" style="animation-delay: 0.3s;">
                    <div class="glass-panel p-4 h-100 shadow-xl border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-hourglass-split text-warning fs-3"></i>
                            </div>
                            <span class="small badge bg-warning bg-opacity-10 text-warning fw-800 rounded-pill animate-pulse-soft">Pending</span>
                        </div>
                        <h5 class="text-muted fw-800 small text-uppercase ls-wider mb-2">Awaiting Review</h5>
                        <h2 class="display-6 fw-800 mb-0"><?php echo $pendingClaims; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Management Console -->
            <div class="row g-4">
                <div class="col-lg-8 animate-up" style="animation-delay: 0.4s;">
                    <div class="glass-panel p-5 shadow-2xl h-100">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-800 mb-0">Action Protocol</h4>
                            <div class="dropdown">
                                <button class="btn btn-light rounded-pill px-3 shadow-none border" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2">
                                    <li><a class="dropdown-item rounded-3" href="#">Refresh Logs</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#">System Export</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="p-4 rounded-5 bg-primary bg-opacity-5 border border-primary border-opacity-10 transition-all hover:bg-opacity-10">
                                    <h5 class="fw-800 mb-3">Verification</h5>
                                    <p class="small text-muted mb-4">Validate ownership claims and finalize asset transfers.</p>
                                    <a href="claims.php" class="btn btn-primary w-100 rounded-4 py-3 fw-700 shadow-sm">Process Matrix</a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-4 rounded-5 bg-dark bg-opacity-5 border border-dark border-opacity-10 transition-all hover:bg-opacity-10">
                                    <h5 class="fw-800 mb-3">Asset Entry</h5>
                                    <p class="small text-muted mb-4">Manually register found items into the operational database.</p>
                                    <a href="../report_item.php" class="btn btn-dark w-100 rounded-4 py-3 fw-700 shadow-sm">Rapid Input</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 animate-up" style="animation-delay: 0.5s;">
                    <div class="glass-panel p-5 shadow-2xl h-100 text-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-info-circle-fill text-info display-4"></i>
                        </div>
                        <h4 class="fw-800 mb-3">System Notice</h4>
                        <p class="text-muted small mb-0">Platform v2.0 includes enhanced Convex DB sync protocols and premium visual interactions. For security, all admin actions are logged with terminal precision.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
