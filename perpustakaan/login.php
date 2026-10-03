<?php

session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {

                header("Location: admin/dashboard.php");

            } else {

                header("Location: user/dashboard.php");

            }

            exit();

        } else {

            $message = "Username atau password salah.";

        }

    } else {

        $message = "Username atau password salah.";

    }
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Perpustakaan</title>


    <!-- Bootstrap 5 -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- CSS Custom -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body class="login-page">


<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-12 col-sm-10 col-md-7 col-lg-5">


            <div class="login-card">


                <!-- Header -->

                <div class="login-header">

                    <div class="login-icon">

                        📚

                    </div>

                    <h2>
                        Perpustakaan
                    </h2>

                    <p>
                        Sistem Pengajuan Buku
                    </p>

                </div>


                <!-- Form -->

                <div class="login-body">


                    <?php if ($message != ""): ?>

                        <div
                            class="alert alert-danger"
                            role="alert"
                        >

                            <?php echo htmlspecialchars($message); ?>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <!-- Username -->

                        <div class="mb-3">

                            <label
                                for="username"
                                class="form-label"
                            >
                                Username
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                placeholder="Masukkan username"
                                required
                            >

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                            >

                        </div>


                        <!-- Button -->

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-login"
                            >

                                Login

                            </button>

                        </div>


                    </form>

                </div>


                <!-- Footer -->

                <div class="login-footer">

                    <small>
                        Sistem Pengajuan Buku Perpustakaan
                    </small>

                </div>


            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>