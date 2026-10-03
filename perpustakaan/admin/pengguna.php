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
// CEK ROLE ADMIN
// ======================================

if ($_SESSION["role"] != "admin") {

    echo "Akses ditolak.";
    exit();

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pengguna</title>

</head>

<body>


<h1>Sistem Pengajuan Buku</h1>

<hr>


<nav>

    <a href="dashboard.php">
        Dashboard
    </a>

    |

    <a href="pengajuan.php">
        Daftar Pengajuan
    </a>

    |

    <a href="pengguna.php">
        Tambah Pengguna
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


<h2>Tambah Pengguna</h2>


<form method="POST" action="simpan_pengguna.php">


    <!-- USERNAME -->

    <label>
        Username
    </label>

    <br>

    <input
        type="text"
        name="username"
        required
    >

    <br><br>


    <!-- PASSWORD -->

    <label>
        Password
    </label>

    <br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>


    <!-- NAMA LENGKAP -->

    <label>
        Nama Lengkap
    </label>

    <br>

    <input
        type="text"
        name="full_name"
        required
    >

    <br><br>


    <!-- ROLE -->

    <label>
        Role
    </label>

    <br>

    <select name="role" required>

        <option value="user">
            User
        </option>

        <option value="admin">
            Admin
        </option>

    </select>

    <br><br>


    <button type="submit">
        Tambah Pengguna
    </button>


</form>


</body>

</html>