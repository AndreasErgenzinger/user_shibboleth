<?php
function getCurrentUrl() {
        $protocol;
        if ($_SERVER["HTTPS"] == "on")
                $protocol = 'https';
        else
                $protocol = 'http';
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];
        return $protocol . '://' . $host . $requestUri;
}
?>
