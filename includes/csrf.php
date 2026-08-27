<?php

session_start();


/* =========================
   PROTECTION CSRF
========================= */

function genererTokenCSRF()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}


function verifierTokenCSRF($token)
{
    return isset($_SESSION["csrf_token"])
        && hash_equals($_SESSION["csrf_token"], $token);
}


/* =========================
   MESSAGES FLASH
========================= */

function definirMessage($message)
{
    $_SESSION["message"] = $message;
}


function recupererMessage()
{
    if (!isset($_SESSION["message"])) {
        return null;
    }

    $message = $_SESSION["message"];

    unset($_SESSION["message"]);

    return $message;
}