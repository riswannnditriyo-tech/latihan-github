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
// 3. CEK METHOD POST
// ======================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo "Metode tidak diizinkan.";
    exit();

}


// ======================================
// 4. AMBIL USER ID
// ======================================

$user_id = $_SESSION["user_id"];


// ======================================
// 5. AMBIL DATA FORM
// ======================================

$full_name = $_POST["full_name"];

$bio = $_POST["bio"];

$address = $_POST["address"];

$phone = $_POST["phone"];

$gender = $_POST["gender"];


// ======================================
// 6. AMBIL FOTO LAMA
// ======================================

$sql_old = "
    SELECT profile_photo
    FROM users
    WHERE user_id = ?
";

$stmt_old = $conn->prepare($sql_old);

$stmt_old->bind_param("i", $user_id);

$stmt_old->execute();

$result_old = $stmt_old->get_result();

$user_old = $result_old->fetch_assoc();

$profile_photo = $user_old["profile_photo"];


// ======================================
// 7. CEK FOTO BARU
// ======================================

if (
    isset($_FILES["profile_photo"]) &&
    $_FILES["profile_photo"]["error"] == 0
) {

    $file_name = $_FILES["profile_photo"]["name"];

    $file_tmp = $_FILES["profile_photo"]["tmp_name"];

    $file_size = $_FILES["profile_photo"]["size"];


    // Ambil ekstensi file

    $extension = strtolower(
        pathinfo($file_name, PATHINFO_EXTENSION)
    );


    // Format yang diperbolehkan

    $allowed_extensions = [
        "jpg",
        "jpeg",
        "png",
        "gif"
    ];


    if (!in_array($extension, $allowed_extensions)) {

        echo "Format foto tidak diperbolehkan.";
        exit();

    }


    // Maksimal 2 MB

    if ($file_size > 2 * 1024 * 1024) {

        echo "Ukuran foto maksimal 2 MB.";
        exit();

    }


    // ==================================
    // BUAT NAMA FILE
    // ==================================

    $new_file_name =
        "profile_" .
        $user_id .
        "_" .
        time() .
        "." .
        $extension;


    $upload_folder = "../uploads/";

    $upload_path =
        $upload_folder . $new_file_name;


    // ==================================
    // UPLOAD
    // ==================================

    if (
        move_uploaded_file(
            $file_tmp,
            $upload_path
        )
    ) {

        $profile_photo = $new_file_name;

    } else {

        echo "Gagal mengupload foto.";
        exit();

    }

}


// ======================================
// 8. UPDATE DATABASE
// ======================================

$sql = "
    UPDATE users
    SET
        full_name = ?,
        profile_photo = ?,
        bio = ?,
        address = ?,
        phone = ?,
        gender = ?,
        updated_at = NOW()
    WHERE user_id = ?
";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "ssssssi",
    $full_name,
    $profile_photo,
    $bio,
    $address,
    $phone,
    $gender,
    $user_id
);


// ======================================
// 9. JALANKAN UPDATE
// ======================================

if ($stmt->execute()) {

    header("Location: profil.php");

    exit();

} else {

    echo "Gagal memperbarui profil.";

}

?>