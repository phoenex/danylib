<?php

    define("DANY_USER_VAR", "_my_dany_user_");

    class DanyUser {
        public $id;
        public $userName;
        public $roleSet; // array olmali

        // ihtiyaciniz olan propertyleri ekleyin
        // usera ihtiyac duydugunuz yerde getDanyUser() ile
        // sessiondaki useri alabilirsiniz.

        public function hasRole($role) {
            if( in_array(trim($role), $this->roleSet) ) {
                return true;
            }
            return false;
        }
    }

    function setDanyUser(DanyUser $user) {
        if($user != null) {
            $_SESSION[ DANY_USER_VAR ] = serialize($user);
        }
    }

    function getDanyUser() {
        return unserialize($_SESSION[ DANY_USER_VAR ]);
    }

?>