<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('location:login.php');
    exit;
}

// Update status aktif admin sendiri
mysqli_query($koneksi, "UPDATE user SET last_active = NOW() WHERE id_user = '".$_SESSION['user']['id_user']."'");

// Ambil semua user, urutkan yang paling baru aktif di atas
$users = mysqli_query($koneksi, "SELECT * FROM user ORDER BY last_active DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #f4f5f9;
            min-height: 100vh;
        }

        .topbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 22px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .topbar h1 {
            font-size: 20px;
        }

        .topbar .nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
            opacity: 0.9;
        }

        .topbar .nav a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome {
            margin-bottom: 24px;
        }

        .welcome h2 {
            font-size: 22px;
            color: #333;
        }

        .welcome p {
            color: #888;
            font-size: 14px;
            margin-top: 4px;
        }

        .stats {
            display: flex;
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            padding: 20px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            flex: 1;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: 700;
            color: #764ba2;
        }

        .stat-card .label {
            font-size: 13px;
            color: #999;
            margin-top: 4px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 24px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #fafafa;
            color: #888;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-top: 1px solid #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-online {
            background: #e3f9e5;
            color: #1a7f37;
        }

        .badge-offline {
            background: #f1f1f1;
            color: #999;
        }

        .badge-role {
            background: #eee3fb;
            color: #764ba2;
        }

        .password-text {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 3px 8px;
            border-radius: 4px;
            color: #c0392b;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <h1>Dashboard Admin</h1>
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Selamat datang, <?php echo $_SESSION['user']['nama']; ?> 👋</h2>
            <p>Berikut daftar seluruh pengguna dan status aktivitas mereka.</p>
        </div>

        <?php
            $totalUser = mysqli_num_rows($users);
            $onlineCount = 0;
            $tempRows = [];
            while($row = mysqli_fetch_array($users)) {
                $isOnline = false;
                if($row['last_active']) {
                    $lastActive = strtotime($row['last_active']);
                    if((time() - $lastActive) <= 120) {
                        $isOnline = true;
                        $onlineCount++;
                    }
                }
                $row['is_online'] = $isOnline;
                $tempRows[] = $row;
            }
        ?>

        <div class="stats">
            <div class="stat-card">
                <div class="number"><?php echo $totalUser; ?></div>
                <div class="label">Total Pengguna</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $onlineCount; ?></div>
                <div class="label">Sedang Online</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Daftar Pengguna</div>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Terakhir Aktif</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tempRows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><span class="password-text"><?php echo htmlspecialchars($row['password']); ?></span></td>
                        <td><span class="badge badge-role"><?php echo htmlspecialchars($row['role']); ?></span></td>
                        <td><?php echo $row['last_active'] ? date('d M Y, H:i', strtotime($row['last_active'])) : '-'; ?></td>
                        <td>
                            <?php if($row['is_online']): ?>
                                <span class="badge badge-online">🟢 Online</span>
                            <?php else: ?>
                                <span class="badge badge-offline">⚪ Offline</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>