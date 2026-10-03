<?php

session_start();

require_once "../config/database.php";


// ===============================
// CEK LOGIN
// ===============================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// ===============================
// CEK ROLE ADMIN
// ===============================

if ($_SESSION["role"] != "admin") {

    echo "Akses ditolak.";
    exit();

}


// ===============================
// MENGAMBIL DATA STATISTIK
// ===============================

// Total seluruh pengajuan
$sql_total = "SELECT COUNT(*) AS total FROM book_requests";

$result_total = $conn->query($sql_total);

$data_total = $result_total->fetch_assoc();

$total_pengajuan = $data_total["total"];


// Total Pending
$sql_pending = "SELECT COUNT(*) AS total 
                FROM book_requests 
                WHERE status = 'pending'";

$result_pending = $conn->query($sql_pending);

$data_pending = $result_pending->fetch_assoc();

$total_pending = $data_pending["total"];


// Total Accepted
$sql_accepted = "SELECT COUNT(*) AS total 
                 FROM book_requests 
                 WHERE status = 'accepted'";

$result_accepted = $conn->query($sql_accepted);

$data_accepted = $result_accepted->fetch_assoc();

$total_accepted = $data_accepted["total"];


// Total Rejected
$sql_rejected = "SELECT COUNT(*) AS total 
                 FROM book_requests 
                 WHERE status = 'rejected'";

$result_rejected = $conn->query($sql_rejected);

$data_rejected = $result_rejected->fetch_assoc();

$total_rejected = $data_rejected["total"];


// ===============================
// MENGAMBIL RIWAYAT PENGAJUAN
// ===============================

$sql_requests = "
    SELECT
        book_requests.*,
        users.username,
        users.full_name
    FROM book_requests
    INNER JOIN users
        ON book_requests.user_id = users.user_id
    ORDER BY book_requests.created_at DESC
";

$result_requests = $conn->query($sql_requests);

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Perpustakaan</title>


    <!-- Bootstrap -->

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- CSS Custom -->

    <link rel="stylesheet" href="../css/style.css">

</head>


<body class="admin-body">


<!-- =============================== -->
<!-- NAVBAR -->
<!-- =============================== -->

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="dashboard.php">

            <i class="bi bi-book-half"></i>

            Perpustakaan

        </a>


        <button 
            class="navbar-toggler" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarAdmin"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse" id="navbarAdmin">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link active" href="dashboard.php">

                        <i class="bi bi-speedometer2"></i>

                        Dashboard

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="pengajuan.php">

                        <i class="bi bi-journal-text"></i>

                        Daftar Pengajuan

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="pengguna.php">

                        <i class="bi bi-person-plus"></i>

                        Pengguna

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="profil.php">

                        <i class="bi bi-person-circle"></i>

                        Profil

                    </a>

                </li>


                <li class="nav-item">

                    <a 
                        class="nav-link text-warning fw-bold" 
                        href="../logout.php"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- =============================== -->
<!-- CONTENT -->
<!-- =============================== -->

<div class="container-fluid py-4">


    <!-- HEADER -->

    <div class="mb-4">

        <h2 class="fw-bold">

            <i class="bi bi-speedometer2"></i>

            Dashboard Admin

        </h2>

        <p class="text-muted mb-0">

            Selamat datang,

            <strong>
                <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </strong>

        </p>

    </div>



    <!-- =============================== -->
    <!-- STATISTIK -->
    <!-- =============================== -->

    <div class="row g-4 mb-4">


        <!-- TOTAL -->

        <div class="col-md-6 col-lg-3">

            <div class="card dashboard-card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Pengajuan
                            </p>

                            <h2 class="fw-bold mb-0">

                                <?php echo $total_pengajuan; ?>

                            </h2>

                        </div>


                        <div class="dashboard-icon">

                            <i class="bi bi-journal-bookmark-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- PENDING -->

        <div class="col-md-6 col-lg-3">

            <div class="card dashboard-card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending
                            </p>

                            <h2 class="fw-bold mb-0">

                                <?php echo $total_pending; ?>

                            </h2>

                        </div>


                        <div class="dashboard-icon">

                            <i class="bi bi-hourglass-split"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- ACCEPTED -->

        <div class="col-md-6 col-lg-3">

            <div class="card dashboard-card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Accepted
                            </p>

                            <h2 class="fw-bold mb-0">

                                <?php echo $total_accepted; ?>

                            </h2>

                        </div>


                        <div class="dashboard-icon">

                            <i class="bi bi-check-circle-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- REJECTED -->

        <div class="col-md-6 col-lg-3">

            <div class="card dashboard-card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Rejected
                            </p>

                            <h2 class="fw-bold mb-0">

                                <?php echo $total_rejected; ?>

                            </h2>

                        </div>


                        <div class="dashboard-icon">

                            <i class="bi bi-x-circle-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =============================== -->
    <!-- RIWAYAT PENGAJUAN -->
    <!-- =============================== -->

    <div class="card shadow-sm border-0">


        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-clock-history"></i>

                Riwayat Pengajuan Buku

            </h5>

        </div>


        <div class="card-body">


            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>No</th>

                            <th>User</th>

                            <th>Judul Buku</th>

                            <th>Penulis</th>

                            <th>Kategori</th>

                            <th>Tahun</th>

                            <th>Status</th>

                            <th>Balasan</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        $no = 1;

                        while ($request = $result_requests->fetch_assoc()):

                        ?>

                        <tr>

                            <td>

                                <?php echo $no++; ?>

                            </td>


                            <td>

                                <strong>

                                    <?php 
                                    echo htmlspecialchars($request["full_name"]); 
                                    ?>

                                </strong>

                                <br>

                                <small class="text-muted">

                                    @<?php 
                                    echo htmlspecialchars($request["username"]); 
                                    ?>

                                </small>

                            </td>


                            <td>

                                <?php 
                                echo htmlspecialchars($request["book_title"]); 
                                ?>

                            </td>


                            <td>

                                <?php 
                                echo htmlspecialchars($request["author"]); 
                                ?>

                            </td>


                            <td>

                                <?php 
                                echo htmlspecialchars($request["category"]); 
                                ?>

                            </td>


                            <td>

                                <?php 
                                echo htmlspecialchars($request["publication_year"]); 
                                ?>

                            </td>


                            <td>

                                <?php

                                if ($request["status"] == "pending") {

                                    echo '<span class="badge bg-warning text-dark">
                                            Pending
                                          </span>';

                                } elseif ($request["status"] == "accepted") {

                                    echo '<span class="badge bg-success">
                                            Accepted
                                          </span>';

                                } elseif ($request["status"] == "rejected") {

                                    echo '<span class="badge bg-danger">
                                            Rejected
                                          </span>';

                                } else {

                                    echo '<span class="badge bg-secondary">
                                            Unknown
                                          </span>';

                                }

                                ?>

                            </td>


                            <td>

                                <?php

                                if ($request["response"] != NULL && $request["response"] != "") {

                                    echo htmlspecialchars($request["response"]);

                                } else {

                                    echo '<span class="text-muted">-</span>';

                                }

                                ?>

                            </td>


                            <td>

                                <small>

                                    <?php 
                                    echo htmlspecialchars($request["created_at"]); 
                                    ?>

                                </small>

                            </td>

                        </tr>


                        <?php endwhile; ?>


                        <?php if ($result_requests->num_rows == 0): ?>

                            <tr>

                                <td colspan="9" class="text-center text-muted py-4">

                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                    Belum ada pengajuan buku.

                                </td>

                            </tr>

                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



<!-- Bootstrap JS -->

<script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>