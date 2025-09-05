<?php
require_once 'model/mConnect.php';

$connect = new Connect();
$conn = $connect->connect();

if (!$conn) {
    die("Không thể kết nối database");
}

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Kiểm tra Database</title>";
echo "<style>table { border-collapse: collapse; margin: 10px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>";
echo "</head><body>";

echo "<h2>Kiểm tra cấu trúc database</h2>";

// Kiểm tra bảng users
echo "<h3>Bảng users:</h3>";
$result = $conn->query("SHOW COLUMNS FROM users");
if ($result) {
    echo "<table>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Lỗi: " . $conn->error;
}

// Kiểm tra bảng otp_verification
echo "<h3>Bảng otp_verification:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'otp_verification'");
if ($result && $result->num_rows > 0) {
    $result = $conn->query("SHOW COLUMNS FROM otp_verification");
    if ($result) {
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "Bảng otp_verification không tồn tại. Tạo bảng mới...<br>";
    
    $sql = "CREATE TABLE otp_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        otp VARCHAR(6) NOT NULL,
        expires_at DATETIME NOT NULL,
        verified TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql)) {
        echo "✅ Tạo bảng otp_verification thành công!<br>";
    } else {
        echo "❌ Lỗi tạo bảng: " . $conn->error . "<br>";
    }
}

// Kiểm tra bảng transfer_accounts
echo "<h3>Bảng transfer_accounts:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'transfer_accounts'");
if ($result && $result->num_rows > 0) {
    $result = $conn->query("SHOW COLUMNS FROM transfer_accounts");
    if ($result) {
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "Bảng transfer_accounts không tồn tại. Tạo bảng mới...<br>";
    
    $sql = "CREATE TABLE transfer_accounts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_ck INT NOT NULL,
        user_id INT NOT NULL,
        balance DECIMAL(15,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql)) {
        echo "✅ Tạo bảng transfer_accounts thành công!<br>";
    } else {
        echo "❌ Lỗi tạo bảng: " . $conn->error . "<br>";
    }
}

echo "</body></html>";

$conn->close();
?>

