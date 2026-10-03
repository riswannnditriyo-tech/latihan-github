<?php

session_start();

require_once "../config/database.php";


// ======================================
// CEK LOGIN
// ======================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// ======================================
// CEK ROLE USER
// ======================================

if ($_SESSION["role"] != "user") {

    echo "Akses ditolak.";
    exit();

}


// ======================================
// AMBIL DATA USER
// ======================================

$user_id = $_SESSION["user_id"];


$sql = "
    SELECT
        username,
        full_name,
        profile_photo
    FROM users
    WHERE user_id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


// ======================================
// AMBIL DATA STATUS PENGAJUAN
// ======================================

// Nilai awal
$pending = 0;
$accepted = 0;
$rejected = 0;


// Query mengambil jumlah pengajuan
// berdasarkan user yang sedang login

$sql_status = "
    SELECT
        status,
        COUNT(*) AS total
    FROM book_requests
    WHERE user_id = ?
    GROUP BY status
";


$stmt_status = $conn->prepare($sql_status);

$stmt_status->bind_param("i", $user_id);

$stmt_status->execute();

$result_status = $stmt_status->get_result();


// ======================================
// MASUKKAN HASIL KE VARIABEL
// ======================================

while ($row = $result_status->fetch_assoc()) {

    if ($row["status"] == "pending") {

        $pending = $row["total"];

    }

    elseif ($row["status"] == "accepted") {

        $accepted = $row["total"];

    }

    elseif ($row["status"] == "rejected") {

        $rejected = $row["total"];

    }

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard User</title>


    <!-- ======================================
         CHART.JS
    ======================================= -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>


<h1>Sistem Pengajuan Buku Perpustakaan</h1>

<hr>


<!-- ======================================
     NAVIGATION
======================================= -->

<nav>

    <a href="dashboard.php">
        Dashboard
    </a>

    |

    <a href="pengajuan.php">
        Pengajuan Buku
    </a>

    |

    <a href="riwayat.php">
        Riwayat Pengajuan
    </a>

    |

    <a href="profil.php">
        Profil
    </a>

    |

    <a href="../logout.php">
        Logout
    </a>

</nav>


<hr>


<h2>Dashboard</h2>


<!-- ======================================
     INFORMASI USER
======================================= -->

<h3>
    Selamat datang,
    <?php echo htmlspecialchars($user["full_name"]); ?>!
</h3>


<p>
    Username:
    <strong>
        <?php echo htmlspecialchars($user["username"]); ?>
    </strong>
</p>


<!-- ======================================
     GRAFIK STATUS PENGAJUAN
======================================= -->

<h3>Status Pengajuan Buku</h3>


<p>
    Berikut adalah jumlah pengajuan buku berdasarkan status Anda.
</p>


<!-- Container grafik -->

<div style="width: 400px;">

    <canvas id="statusChart"></canvas>

</div>


<!-- ======================================
     GRAFIK CHART.JS
======================================= -->

<script>

const ctx = document.getElementById('statusChart');

new Chart(ctx, {

    type: 'doughnut',

    data: {

        labels: [
            'Pending',
            'Accepted',
            'Rejected'
        ],

        datasets: [{

            label: 'Jumlah Pengajuan',

            data: [

                <?php echo $pending; ?>,

                <?php echo $accepted; ?>,

                <?php echo $rejected; ?>

            ],

            backgroundColor: [

                '#f1c40f',
                '#2ecc71',
                '#e74c3c'

            ],

            borderWidth: 1

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                position: 'bottom'

            }

        }

    }

});

</script>

</body>

</html>