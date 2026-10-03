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
// CEK METHOD POST
// ======================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo "Metode tidak diizinkan.";
    exit();

}


// ======================================
// AMBIL USER ID
// ======================================

$user_id = $_SESSION["user_id"];



// ======================================
// AMBIL DATA FORM
// ======================================

$book_title = $_POST["book_title"];

$author = $_POST["author"];

$category = $_POST["category"];

$publication_year = $_POST["publication_year"];


// ======================================
// STATUS AWAL
// ======================================

$status = "pending";


// ======================================
// SIMPAN KE DATABASE
// ======================================

$sql = "
    INSERT INTO book_requests
    (
        user_id,
        book_title,
        author,
        category,
        publication_year,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )
";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "isssis",
    $user_id,
    $book_title,
    $author,
    $category,
    $publication_year,
    $status
);


// ======================================
// JALANKAN INSERT
// ======================================

if ($stmt->execute()) {

    echo "Pengajuan buku berhasil.";

    echo "<br><br>";

    echo '<a href="pengajuan.php">Kembali ke Pengajuan</a>';

} else {

    echo "Gagal menyimpan pengajuan.";

}

?>