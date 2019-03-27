<?php

if (! function_exists("addhttp")) {
    function addhttp($url)
    {
        if (! preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://".$url;
        }

        return $url;
    }
}

