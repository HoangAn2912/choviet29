<?php
class Connect {
    public function connect() {
        $con = mysqli_connect("localhost", "choviet", "123456", "choviet");
        if (!$con) {
            echo "Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error();
            exit();
        } else {
            mysqli_query($con, "SET NAMES 'utf8'");
            return $con;
        }
    }
}
?>

