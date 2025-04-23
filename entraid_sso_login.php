<?php

namespace YaleREDCap\EntraIdAuthenticator;

/** @var EntraIdAuthenticator $module */

session_id($_COOKIE[EntraIdAuthenticator::ENTRAID_SESSION_ID_COOKIE]);
session_start();

$state                    = decrypt(base64_decode($_GET["state"]) ?? '');
[$session_id, $originUrl] = explode(EntraIdAuthenticator::ENTRAID_STATE_SEPARATOR, $state);
$authenticator = new Authenticator($module);

$authData = $authenticator->getAuthData($session_id, $_GET["code"]);
$userData = $authenticator->getUserData($authData['access_token']);

if (isset($userData['error']) || empty($userData)) {
    $module->framework->log('Entra ID Authenticator Error', [ 'error' => json_encode($userData, JSON_PRETTY_PRINT) ]);
    exit();
}

$_SESSION[EntraIdAuthenticator::ENTRAID_USERNAME] = $userData['username'];

// Redirect to the page we were on
header("Location: " . $originUrl);
