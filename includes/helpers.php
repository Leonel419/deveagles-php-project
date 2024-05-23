<?php
    function generateOtp(){
        $otp=mt_rand(0,999900);

        return $otp;
    }

?>