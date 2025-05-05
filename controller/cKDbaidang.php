<?php
include_once("model/mKDbaidang.php");
class ckdbaidang {
    public function getallbaidang() {
        $p = new kdbaidang();
        return $p->allbaidang();
    }
    public function getonebaidang($id) {
        $p = new kdbaidang();
        return $p->onebaidang($id);
    }
    public function getAllProductTypes(){
        $p = new kdbaidang();
        $p->allloaisanpham();
    }
    public function getduyetbai($id){
        $p = new kdbaidang();
        $p->duyetBai($id);
    }
    public function gettuchoi($id, $ghichu){
        $p = new kdbaidang();
        $p->tuChoiBai($id, $ghichu);
    }
}
?>