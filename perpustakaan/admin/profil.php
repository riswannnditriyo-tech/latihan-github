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


// ======================================
// AMBIL DATA ADMIN
// ======================================

$user_id = $_SESSION["user_id"];


$sql = "
    SELECT
        user_id,
        username,
        full_name,
        profile_photo,
        bio,
        address,
        phone,
        gender
    FROM users
    WHERE user_id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    echo "Data pengguna tidak ditemukan.";
    exit();

}


$user = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Admin</title>

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


<h2>Profil Admin</h2>


<!-- ================================= -->
<!-- FOTO PROFIL -->
<!-- ================================= -->

<h3>Foto Profil</h3>


<?php if (!empty($user["profile_photo"])): ?>

    <img
        src="../uploads/<?php echo htmlspecialchars($user["profile_photo"]); ?>"
        width="150"
        height="150"
        alt="Foto Profil"
    >

<?php else: ?>

    <p>
        Belum ada foto profil.
    </p>

<?php endif; ?>


<hr>


<form
    method="POST"
    action="update_profil.php"
    enctype="multipart/form-data"
>


    <!-- FOTO -->

    <label>
        Pilih Foto Profil
    </label>

    <br>

    <input
        type="file"
        name="profile_photo"
        accept="image/*"
    >

    <br><br>


    <!-- NAMA -->

    <label>
        Nama Lengkap
    </label>

    <br>

    <input
        type="text"
        name="full_name"
        value="<?php echo htmlspecialchars($user["full_name"]); ?>"
        required
    >

    <br><br>


    <!-- BIO -->

    <label>
        Bio
    </label>

    <br>

    <textarea
        name="bio"
        rows="4"
        cols="50"
    ><?php echo htmlspecialchars($user["bio"] ?? ""); ?></textarea>

    <br><br>


    <!-- ALAMAT -->

    <label>
        Alamat
    </label>

    <br>

    <textarea
        name="address"
        rows="4"
        cols="50"
    ><?php echo htmlspecialchars($user["address"] ?? ""); ?></textarea>

    <br><br>


    <!-- PHONE -->

    <label>
        Nomor HP
    </label>

    <br>

    <input
        type="text"
        name="phone"
        value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>"
    >

    <br><br>


    <!-- GENDER -->

    <label>
        Jenis Kelamin
    </label>

    <br>

    <select name="gender">

        <option value="">
            -- Pilih Jenis Kelamin --
        </option>

        <option
            value="Laki-laki"
            <?php
            if ($user["gender"] == "Laki-laki") {
                echo "selected";
            }
            ?>
        >
            Laki-laki
        </option>

        <option
            value="Perempuan"
            <?php
            if ($user["gender"] == "Perempuan") {
                echo "selected";
            }
            ?>
        >
            Perempuan
        </option>

    </select>

    <br><br>


    <button type="submit">
        Update Profil
    </button>


</form>


</body>

</html>