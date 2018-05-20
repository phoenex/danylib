<?php
    namespace lib\titanDb;
    
    // wrapper onemli, DB 'yi wrapper arkasina saklamak iyi bir yontem
    class AppDao extends TitanDb {
        public function getCategoryList() {
            $sql = "SELECT * FROM category ORDER BY name";
            return $this->table("category")->select("*")->orderBy("name")->getAll();
        }
    }
?>