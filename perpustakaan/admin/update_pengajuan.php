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
// 2. CEK ROLE ADMIN
// ======================================

if ($_SESSION["role"] != "admin") {

    echo "Akses ditolak.";
    exit();

}


// ======================================
// 3. CEK REQUEST METHOD
// ======================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo "Metode tidak diizinkan.";
    exit();

}


// ======================================
// 4. AMBIL DATA DARI FORM
// ======================================

$request_id = $_POST["request_id"];

$status = $_POST["status"];

$response = $_POST["response"];


// ======================================
// 5. VALIDASI STATUS
// ======================================

$status_valid = ["pending", "accepted", "rejected"];

if (!in_array($status, $status_valid)) {

    echo "Status tidak valid.";
    exit();

}


// ======================================
// 6. UPDATE DATABASE
// ======================================

$sql = "
    UPDATE book_requests
    SET
        status = ?,
        response = ?,
        updated_at = NOW()
    WHERE request_id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssi",
    $status,
    $response,
    $request_id
);


// ======================================
// 7. JALANKAN UPDATE
// ======================================

if ($stmt->execute()) {

    header("Location: pengajuan.php");

    exit();

} else {

    echo "Gagal memperbarui pengajuan.";

}

?>