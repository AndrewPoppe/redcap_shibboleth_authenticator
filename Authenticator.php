<?php

namespace YaleREDCap\ShibbolethAuthenticator;

class Authenticator
{

    private $module;

    const ERROR_MESSAGE_AUTHENTICATION = 'ShibbolethAuthenticator Authentication Error';
    public function __construct(ShibbolethAuthenticator $module)
    {
        $this->module          = $module;
    }

    public function handleAuth($url)
    {
        try {
            session_start();
            $sessionId = session_id();
            \Session::savecookie(ShibbolethAuthenticator::SHIBBOLETH_SESSION_ID_COOKIE, $sessionId, 0, true);
            $loginUrl = $this->module->getLoginUrl($url);
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