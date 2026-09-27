<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ke web/aplikasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-box {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 360px;
        }

        .login-box h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 6px;
        }

        .login-box p.subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #444;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #764ba2;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h1>Selamat Datang</h1>
        <p class="subtitle">Silakan login untuk melanjutkan</p>

 <?php
    if(isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 1. Cek dulu apakah username-nya ada
        $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");

        if(mysqli_num_rows($cek_user) == 0) {
            // Username tidak ditemukan sama sekali
            echo '<script>alert("Akun belum terdaftar. Silakan daftar terlebih dahulu."); location.href="daftar.php";</script>';
        } else {
            // 2. Username ada, sekarang cek passwordnya
            $query = mysqli_query($koneksi, "SELECT * FROM user where username='$username' and password='$password'");

            if(mysqli_num_rows($query) > 0) {
                $data = mysqli_fetch_array($query);
                $_SESSION['user'] = $data;

                mysqli_query($koneksi, "UPDATE user SET last_active = NOW() WHERE id_user = '".$data['id_user']."'");

                if($data['role'] == 'admin') {
                    echo '<script>alert("Selamat datang, Admin"); location.href="dashboard_admin.php";</script>';
                } else {
                    echo '<script>alert("Selamat datang, '.$data['nama'].'"); location.href="user.php";</script>';
                }
            } else {
                echo '<script>alert("Password salah. Silakan coba lagi.");</script>';
            }
        }
    }
?>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <p class="register-link">Belum punya akun? <a href="daftar.php">Daftar di sini</a></p>
    </div>

</body>
</html>