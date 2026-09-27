<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
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
        <h1>Buat Akun</h1>
        <p class="subtitle">Isi data di bawah untuk mendaftar</p>

        <?php
            if(isset($_POST['username'])) {
                $nama = $_POST['nama'];
                $username = $_POST['username'];
                $password = $_POST['password'];

                $query = mysqli_query($koneksi, "INSERT INTO user(nama,username,password,role) values('$nama', '$username','$password','user')");
                if($query) {
                    echo '<script>alert("Selamat kamu berhasil daftar. Silakan login"); location.href="login.php";</script>';
                }else{
                    echo '<script>alert("Pendaftaran gagal")</script>';
                }
            }
        ?>

        <form method="post">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Daftar</button>
        </form>

        <p class="register-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

</body>
</html>