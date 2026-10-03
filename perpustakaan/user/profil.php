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
// 2. CEK ROLE USER
// ======================================

if ($_SESSION["role"] != "user") {

    echo "Akses ditolak.";
    exit();

}


// ======================================
// 3. AMBIL USER ID
// ======================================

$user_id = $_SESSION["user_id"];


// ======================================
// 4. AMBIL DATA USER
// ======================================

$sql = "
    SELECT
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

$user = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil User</title>

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


<h2>Profil Saya</h2>


<!-- ======================================
     FOTO PROFIL
====================================== -->

<h3>Foto Profil</h3>


<?php if (!empty($user["profile_photo"])): ?>

    <img
        src="../uploads/<?php echo htmlspecialchars($user["profile_photo"]); ?>"
        width="150"
        height="150"
        style="object-fit: cover;"
    >

<?php else: ?>

    <p>
        Belum ada foto profil.
    </p>

<?php endif; ?>


<br><br>


<!-- ======================================
     FORM PROFIL
====================================== -->

<form
    method="POST"
    action="update_profil.php"
    enctype="multipart/form-data"
>


    <label>
        Username
    </label>

    <br>

    <input
        type="text"
        value="<?php echo htmlspecialchars($user["username"]); ?>"
        readonly
    >

    <br><br>


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


    <label>
        Bio
    </label>

    <br>

    <textarea
        name="bio"
        rows="4"
        cols="40"
    ><?php echo htmlspecialchars($user["bio"] ?? ""); ?></textarea>

    <br><br>


    <label>
        Alamat
    </label>

    <br>

    <textarea
        name="address"
        rows="3"
        cols="40"
    ><?php echo htmlspecialchars($user["address"] ?? ""); ?></textarea>

    <br><br>


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
            echo ($user["gender"] == "Laki-laki")
                ? "selected"
                : "";
            ?>
        >
            Laki-laki
        </option>

        <option
            value="Perempuan"
            <?php
            echo ($user["gender"] == "Perempuan")
                ? "selected"
                : "";
            ?>
        >
            Perempuan
        </option>

    </select>

    <br><br>


    <label>
        Pilih Foto Profil
    </label>

    <br>

    <input
        type="file"
        name="profile_photo"
        accept=".jpg,.jpeg,.png,.gif"
    >

    <br><br>


    <button type="submit">
        Update Profil
    </button>


</form>


</body>

</html>