<?php
    include_once("controller/cQLthongtin.php");
    if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
    }
    $user_id = $_GET['id'];
    $p = new cqlthongtin();
    $result = $p->deleteuser($user_id);
    header("Location: index.php");
    exit();
?>