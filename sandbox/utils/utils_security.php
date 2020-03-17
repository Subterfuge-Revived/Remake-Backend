<?php


class utils_security
{
    public function rm_inject($arg) {
        return htmlspecialchars(stripslashes(trim($arg)));
    }
}