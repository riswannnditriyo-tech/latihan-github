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

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengajuan Buku</title>

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


<h2>Pengajuan Buku</h2>


<p>
    Silakan masukkan data buku yang ingin Anda ajukan.
</p>


<form method="POST" action="proses_pengajuan.php">


    <!-- JUDUL BUKU -->

    <label>
        Judul Buku
    </label>

    <br>

    <input
        type="text"
        name="book_title"
        required
    >

    <br><br>


    <!-- PENULIS -->

    <label>
        Penulis
    </label>

    <br>

    <input
        type="text"
        name="author"
        required
    >

    <br><br>


    <!-- KATEGORI -->

    <label>
        Kategori
    </label>

    <br>

    <input
        type="text"
        name="category"
        required
    >

    <br><br>


    <!-- TAHUN TERBIT -->

    <label>
        Tahun Terbit
    </label>

    <br>

    <input
        type="number"
        name="publication_year"
        min="1000"
        max="2100"
        required
    >

    <br><br>


    <button type="submit">
        Ajukan Buku
    </button>


</form>


</body>

</html>