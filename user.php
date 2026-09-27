<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";

if(!isset($_SESSION['user'])){
    header('location:login.php');
    exit;
}

mysqli_query($koneksi, "UPDATE user SET last_active = NOW() WHERE id_user = '".$_SESSION['user']['id_user']."'");

$user = $_SESSION['user'];

// Data contoh (dummy) - nanti akan diganti dengan data dari database
$pesanan = [
    [
        'id' => 'ORD-001',
        'produk' => 'Sepatu Sneakers Putih',
        'tanggal' => '25 Sep 2026',
        'jumlah' => 1,
        'total' => 350000,
        'status' => 'Selesai'
    ],
    [
        'id' => 'ORD-002',
        'produk' => 'Kaos Polos Hitam',
        'tanggal' => '26 Sep 2026',
        'jumlah' => 2,
        'total' => 150000,
        'status' => 'Dikirim'
    ],
    [
        'id' => 'ORD-003',
        'produk' => 'Tas Ransel Kanvas',
        'tanggal' => '27 Sep 2026',
        'jumlah' => 1,
        'total' => 275000,
        'status' => 'Diproses'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
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
            max-width: 900px;
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

        .order-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px 24px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-info .order-id {
            font-size: 12px;
            color: #aaa;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .order-info .order-product {
            font-size: 16px;
            color: #333;
            font-weight: 700;
            margin-top: 4px;
        }

        .order-info .order-date {
            font-size: 13px;
            color: #999;
            margin-top: 4px;
        }

        .order-right {
            text-align: right;
        }

        .order-total {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }

        .order-qty {
            font-size: 13px;
            color: #999;
            margin-top: 2px;
        }

        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .badge-selesai {
            background: #e3f9e5;
            color: #1a7f37;
        }

        .badge-dikirim {
            background: #e0eeff;
            color: #1a56db;
        }

        .badge-diproses {
            background: #fff4e0;
            color: #b7791f;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <h1>Pesanan Saya</h1>
        <div class="nav">
            <a href="user.php">Beranda</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h2>
            <p>Berikut riwayat pesanan kamu.</p>
        </div>

        <?php if(count($pesanan) > 0): ?>
            <?php foreach($pesanan as $item): ?>
                <?php
                    $badgeClass = 'badge-diproses';
                    if($item['status'] == 'Selesai') $badgeClass = 'badge-selesai';
                    if($item['status'] == 'Dikirim') $badgeClass = 'badge-dikirim';
                ?>
                <div class="order-card">
                    <div class="order-info">
                        <div class="order-id"><?php echo $item['id']; ?></div>
                        <div class="order-product"><?php echo $item['produk']; ?></div>
                        <div class="order-date"><?php echo $item['tanggal']; ?></div>
                    </div>
                    <div class="order-right">
                        <div class="order-total">Rp <?php echo number_format($item['total'], 0, ',', '.'); ?></div>
                        <div class="order-qty"><?php echo $item['jumlah']; ?> item</div>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $item['status']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>Kamu belum punya pesanan.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>