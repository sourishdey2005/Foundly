<?php
session_start();
require_once '../config/convex.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$convex = new ConvexDB();
$claims = $convex->getClaims();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Matrix - Foundly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light admin-body overflow-hidden">
    <div class="hero-blob animate-morph opacity-20" style="position: fixed; bottom: -10%; left: -10%; width: 500px; height: 500px; z-index: -1; background: var(--info-color);"></div>

    <div class="d-flex h-100vh">
        <!-- Sidebar Sync -->
        <div class="admin-sidebar glass-panel m-3 me-0 rounded-5 d-flex flex-column animate-up" style="width: 280px; height: calc(100vh - 2rem);">
            <div class="p-4 mb-4 border-bottom border-white border-opacity-20">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary p-3 rounded-4 shadow-lg">
                        <i class="bi bi-shield-lock-fill text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 mb-0 ls-tight">Foundly</h4>
                        <span class="small text-muted text-uppercase fw-700 ls-wider">Admin v2.0</span>
                    </div>
                </div>
            </div>

            <nav class="nav flex-column gap-2 px-3 flex-grow-1">
                <a class="nav-link admin-nav-link rounded-4 p-3 d-flex align-items-center gap-3" href="dashboard.php">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="fw-700">Analytics Hub</span>
                </a>
                <a class="nav-link admin-nav-link active rounded-4 p-3 d-flex align-items-center gap-3" href="claims.php">
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
                <a href="../api/auth.php?logout=1" class="btn btn-outline-danger w-100 rounded-4 py-3 fw-800 ls-wide">
                    <i class="bi bi-power me-2"></i>DEACTIVATE
                </a>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="flex-grow-1 p-4 overflow-auto">
            <header class="d-flex justify-content-between align-items-start mb-5 animate-up">
                <div>
                    <h1 class="display-5 fw-800 mb-1">Claim <span class="text-primary">Verification</span> Matrix</h1>
                    <p class="text-secondary fw-700 mb-0">Reviewing ownership evidence and asset transfer requests.</p>
                </div>
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success border-0 shadow-lg rounded-4 animate-up mb-0 py-3 px-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                        <span class="fw-700"><?php echo htmlspecialchars($_GET['success']); ?></span>
                        <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </header>

            <div class="glass-panel shadow-2xl overflow-hidden animate-up" style="animation-delay: 0.1s;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-table">
                        <thead class="bg-primary bg-opacity-5">
                            <tr class="text-dark fw-800 small text-uppercase ls-wide">
                                <th class="px-5 py-4">Claimant Identity</th>
                                <th class="px-4 py-4">Target Asset</th>
                                <th class="px-4 py-4">Ownership Proof</th>
                                <th class="px-4 py-4">Evidence</th>
                                <th class="px-4 py-4 text-center">Status Matrix</th>
                                <th class="px-5 py-4 text-end">Action Protocol</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white bg-opacity-30">
                            <?php if (empty($claims)): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted fw-700">No active claims in queue.</td></tr>
                            <?php else: ?>
                                <?php foreach ($claims as $claim): ?>
                                <tr class="transition-all hover:bg-white hover:bg-opacity-50">
                                    <td class="px-5 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 p-3 rounded-circle me-3">
                                                <i class="bi bi-person-badge text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-800 text-dark"><?php echo htmlspecialchars($claim['claimer_name']); ?></div>
                                                <div class="extra-small text-muted fw-700 ls-wide"><?php echo htmlspecialchars($claim['phone']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="fw-800 text-primary"><?php echo htmlspecialchars($claim['item_name']); ?></div>
                                        <div class="extra-small text-muted fw-700"><?php echo date('M d, Y', $claim['created_at'] / 1000); ?></div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="small fw-700 text-muted text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($claim['proof_text']); ?>">
                                            <?php echo htmlspecialchars($claim['proof_text']); ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if (!empty($claim['proof_image'])): ?>
                                            <div class="position-relative d-inline-block">
                                                <a href="../uploads/<?php echo $claim['proof_image']; ?>" target="_blank" class="d-block transition-all hover:scale-110">
                                                    <img src="../uploads/<?php echo $claim['proof_image']; ?>" class="rounded-3 shadow-sm border border-white border-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-muted fw-800 extra-small">NO MEDIA</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="status-badge badge-<?php echo $claim['status']; ?> shadow-sm px-4 py-2 border-0 fw-800 ls-wide text-uppercase" style="font-size: 0.65rem;">
                                            <i class="bi bi-record-circle-fill me-1"></i> <?php echo $claim['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-end">
                                        <?php if ($claim['status'] === 'pending'): ?>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="approve_claim.php?id=<?php echo $claim['_id']; ?>" class="btn btn-success btn-sm rounded-3 px-3 shadow-none border-0" title="Authorize Transfer">
                                                    <i class="bi bi-shield-check"></i>
                                                </a>
                                                <a href="reject_claim.php?id=<?php echo $claim['_id']; ?>" class="btn btn-danger btn-sm rounded-3 px-3 shadow-none border-0" title="Reject Request">
                                                    <i class="bi bi-shield-slash"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <i class="bi bi-slash-circle text-muted opacity-25"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
