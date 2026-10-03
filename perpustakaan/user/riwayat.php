<?php

session_start();

require_once "../config/database.php";


// ======================================
// 1. CEK LOGIN
// ======================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// ======================================
// 2. CEK ROLE
// ======================================

if ($_SESSION["role"] != "user") {

    echo "Akses ditolak.";
    exit();

}


// ======================================
// 3. AMBIL USER ID DARI SESSION
// ======================================

$user_id = $_SESSION["user_id"];


// ======================================
// 4. AMBIL DATA PENGAJUAN USER
// ======================================

$sql = "
    SELECT
        request_id,
        book_title,
        author,
        category,
        publication_year,
        status,
        response,
        created_at
    FROM book_requests
    WHERE user_id = ?
    ORDER BY created_at DESC
";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pengajuan</title>

</head>

<body>


<h1>Sistem Pengajuan Buku Perpustakaan</h1>

<hr>


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


<h2>Riwayat Pengajuan Buku</h2>


<?php if ($result->num_rows > 0): ?>


<table border="1" cellpadding="10" cellspacing="0">

    <thead>

        <tr>

            <th>No</th>

            <th>Judul Buku</th>

            <th>Penulis</th>

            <th>Kategori</th>

            <th>Tahun Terbit</th>

            <th>Status</th>

            <th>Balasan Admin</th>

            <th>Tanggal Pengajuan</th>

        </tr>

    </thead>


    <tbody>

        <?php

        $no = 1;

        while ($row = $result->fetch_assoc()):

        ?>

        <tr>

            <td>
                <?php echo $no++; ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["book_title"]);
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["author"]);
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["category"]);
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["publication_year"]);
                ?>
            </td>


            <td>

                <?php

                if ($row["status"] == "pending") {

                    echo "Pending";

                } elseif ($row["status"] == "accepted") {

                    echo "Accepted";

                } elseif ($row["status"] == "rejected") {

                    echo "Rejected";

                } else {

                    echo htmlspecialchars($row["status"]);

                }

                ?>

            </td>


            <td>

                <?php

                if (!empty($row["response"])) {

                    echo htmlspecialchars($row["response"]);

                } else {

                    echo "-";

                }

                ?>

            </td>


            <td>

                <?php

                echo htmlspecialchars($row["created_at"]);

                ?>

            </td>

        </tr>

        <?php endwhile; ?>

    </tbody>

</table>


<?php else: ?>


<p>
    Belum ada pengajuan buku.
</p>


<?php endif; ?>


</body>

</html>