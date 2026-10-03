<?php

session_start();

require_once "../config/database.php";


// ===============================
// CEK LOGIN
// ===============================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// ===============================
// CEK ROLE ADMIN
// ===============================

if ($_SESSION["role"] != "admin") {

    echo "Akses ditolak.";
    exit();

}


// ===============================
// CEK ID
// ===============================

if (!isset($_GET["id"])) {

    echo "ID pengajuan tidak ditemukan.";
    exit();

}


$request_id = $_GET["id"];


// ===============================
// AMBIL DATA PENGAJUAN
// ===============================

$sql = "
    SELECT
        book_requests.*,
        users.username,
        users.full_name
    FROM book_requests
    INNER JOIN users
        ON book_requests.user_id = users.user_id
    WHERE book_requests.request_id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $request_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    echo "Data pengajuan tidak ditemukan.";
    exit();

}


$request = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Proses Pengajuan</title>

</head>

<body>


<h1>Proses Pengajuan Buku</h1>


<hr>


<h2>Detail Pengajuan</h2>


<p>
    <strong>User:</strong>
    <?php echo htmlspecialchars($request["full_name"]); ?>
</p>


<p>
    <strong>Judul Buku:</strong>
    <?php echo htmlspecialchars($request["book_title"]); ?>
</p>


<p>
    <strong>Penulis:</strong>
    <?php echo htmlspecialchars($request["author"]); ?>
</p>


<p>
    <strong>Kategori:</strong>
    <?php echo htmlspecialchars($request["category"]); ?>
</p>


<p>
    <strong>Tahun Terbit:</strong>
    <?php echo $request["publication_year"]; ?>
</p>


<p>
    <strong>Status Saat Ini:</strong>
    <?php echo ucfirst($request["status"]); ?>
</p>


<hr>


<h2>Berikan Respon</h2>


<form method="POST" action="update_pengajuan.php">

    <input
        type="hidden"
        name="request_id"
        value="<?php echo $request["request_id"]; ?>"
    >


    <label>Status</label>

    <br>

    <select name="status" required>

        <option value="pending"
            <?php
            if ($request["status"] == "pending") echo "selected";
            ?>
        >
            Pending
        </option>

        <option value="accepted"
            <?php
            if ($request["status"] == "accepted") echo "selected";
            ?>
        >
            Accepted
        </option>

        <option value="rejected"
            <?php
            if ($request["status"] == "rejected") echo "selected";
            ?>
        >
            Rejected
        </option>

    </select>


    <br><br>


    <label>Balasan</label>

    <br>

    <textarea
        name="response"
        rows="5"
        cols="50"
    ><?php echo htmlspecialchars($request["response"] ?? ""); ?></textarea>


    <br><br>


    <button type="submit">
        Update Pengajuan
    </button>

</form>


<br>


<a href="pengajuan.php">
    Kembali
</a>


</body>

</html>