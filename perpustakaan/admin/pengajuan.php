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
// CEK ROLE
// ===============================

if ($_SESSION["role"] != "admin") {

    echo "Akses ditolak.";
    exit();

}


// ===============================
// AMBIL DATA PENGAJUAN
// ===============================

$sql = "
    SELECT
        book_requests.*,
        users.username,
        users.full_name
    FROM book_requests
    INNER JOIN users
        ON book_requests.user_id = users.user_id
    ORDER BY book_requests.created_at DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Pengajuan - Perpustakaan</title>


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


                <!-- Dashboard -->

                <li class="nav-item">

                    <a class="nav-link" href="dashboard.php">

                        <i class="bi bi-speedometer2"></i>

                        Dashboard

                    </a>

                </li>


                <!-- Daftar Pengajuan -->

                <li class="nav-item">

                    <a class="nav-link active" href="pengajuan.php">

                        <i class="bi bi-journal-text"></i>

                        Daftar Pengajuan

                    </a>

                </li>


                <!-- Pengguna -->

                <li class="nav-item">

                    <a class="nav-link" href="pengguna.php">

                        <i class="bi bi-person-plus"></i>

                        Pengguna

                    </a>

                </li>


                <!-- Profil -->

                <li class="nav-item">

                    <a class="nav-link" href="profil.php">

                        <i class="bi bi-person-circle"></i>

                        Profil

                    </a>

                </li>


                <!-- Logout -->

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

            <i class="bi bi-journal-text"></i>

            Daftar Pengajuan Buku

        </h2>

        <p class="text-muted">

            Kelola dan proses pengajuan buku dari pengguna.

        </p>

    </div>



    <!-- =============================== -->
    <!-- TABLE -->
    <!-- =============================== -->

    <div class="card shadow-sm border-0">


        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-list-ul"></i>

                Data Pengajuan

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

                            <th>Tahun Terbit</th>

                            <th>Status</th>

                            <th>Tanggal Pengajuan</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $no = 1;

                        while ($request = $result->fetch_assoc()):

                        ?>


                        <tr>


                            <!-- Nomor -->

                            <td>

                                <?php echo $no++; ?>

                            </td>


                            <!-- User -->

                            <td>

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $request["full_name"]
                                    );

                                    ?>

                                </strong>

                                <br>

                                <small class="text-muted">

                                    @<?php

                                    echo htmlspecialchars(
                                        $request["username"]
                                    );

                                    ?>

                                </small>

                            </td>


                            <!-- Judul -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $request["book_title"]
                                );

                                ?>

                            </td>


                            <!-- Penulis -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $request["author"]
                                );

                                ?>

                            </td>


                            <!-- Kategori -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $request["category"]
                                );

                                ?>

                            </td>


                            <!-- Tahun -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $request["publication_year"]
                                );

                                ?>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <?php

                                if ($request["status"] == "pending") {

                                    echo '
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split"></i>
                                        Pending
                                    </span>
                                    ';

                                } elseif ($request["status"] == "accepted") {

                                    echo '
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i>
                                        Accepted
                                    </span>
                                    ';

                                } elseif ($request["status"] == "rejected") {

                                    echo '
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i>
                                        Rejected
                                    </span>
                                    ';

                                } else {

                                    echo '
                                    <span class="badge bg-secondary">
                                        Unknown
                                    </span>
                                    ';

                                }

                                ?>

                            </td>


                            <!-- TANGGAL -->

                            <td>

                                <small>

                                    <?php

                                    echo htmlspecialchars(
                                        $request["created_at"]
                                    );

                                    ?>

                                </small>

                            </td>


                            <!-- AKSI -->

                            <td>

                                <a
                                    href="proses_pengajuan.php?id=<?php echo $request["request_id"]; ?>"
                                    class="btn btn-sm btn-primary"
                                >

                                    <i class="bi bi-pencil-square"></i>

                                    Proses

                                </a>

                            </td>


                        </tr>


                        <?php endwhile; ?>


                        <!-- JIKA TIDAK ADA DATA -->

                        <?php if ($result->num_rows == 0): ?>

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center text-muted py-5"
                                >

                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>

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