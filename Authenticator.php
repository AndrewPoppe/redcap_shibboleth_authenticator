<?php

namespace YaleREDCap\ShibbolethAuthenticator;

class Authenticator
{

    private $module;
    private $sessionId;
    private $settings;

    const ERROR_MESSAGE_AUTHENTICATION = 'ShibbolethAuthenticator Authentication Error';
    public function __construct(ShibbolethAuthenticator $module, string $sessionId = null)
    {
        $this->module          = $module;
        $this->sessionId       = $sessionId ?? session_id();
    }

    public function handleAuth($url)
    {
        try {
            session_start();
            $sessionId = session_id();
            \Session::savecookie(ShibbolethAuthenticator::SHIBBOLETH_SESSION_ID_COOKIE, $sessionId, 0, true);
            $loginUrl = $this->module->getLoginUrl($originUrl);
            header("Location: " . $loginUrl);
            return true;
        } catch ( \Throwable $e ) {
            $this->module->framework->log('Entra ID REDCap Authenticator: Error 1', [ 'error' => $e->getMessage() ]);
            session_unset();
            session_destroy();
            return false;
        }
    }
}