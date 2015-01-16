<?php

        $refUrl = "/";
        if( isset($_GET["r"]) ) {
                $refUrl = str_replace('\"', '', $_GET["r"]);
        }

        //echo "This page will redirect back to " . $refUrl;
        //exit;

        header( 'Location: ' . $refUrl ) ;

?>
