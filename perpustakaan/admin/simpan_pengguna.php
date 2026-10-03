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
// CEK METHOD
// ======================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo "Metode tidak diizinkan.";
    exit();

}


// ======================================
// AMBIL DATA FORM
// ======================================

$username = $_POST["username"];

$password = $_POST["password"];

$full_name = $_POST["full_name"];

$role = $_POST["role"];


// ======================================
// VALIDASI ROLE
// ======================================

$role_valid = ["user", "admin"];

if (!in_array($role, $role_valid)) {

    echo "Role tidak valid.";
    exit();

}


// ======================================
// CEK USERNAME
// ======================================

$sql_check = "
    SELECT user_id
    FROM users
    WHERE username = ?
";

$stmt_check = $conn->prepare($sql_check);

$stmt_check->bind_param("s", $username);

$stmt_check->execute();

$result_check = $stmt_check->get_result();


if ($result_check->num_rows > 0) {

    echo "Username sudah digunakan.";
    exit();

}


// ======================================
// HASH PASSWORD
// ======================================

$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// ======================================
// SIMPAN USER
// ======================================

$sql = "
    INSERT INTO users
    (
        username,
        password,
        full_name,
        role
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?
    )
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssss",
    $username,
    $password_hash,
    $full_name,
    $role
);


// ======================================
// JALANKAN INSERT
// ======================================

if ($stmt->execute()) {

    header("Location: pengguna.php");

    exit();

} else {

    echo "Gagal menambahkan pengguna.";

}

?>