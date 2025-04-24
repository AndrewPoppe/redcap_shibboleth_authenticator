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

    public function authenticate(bool $refresh = false, string $originUrl = '')
    {
        // $state = encrypt($this->sessionId . EntraIdAuthenticator::ENTRAID_STATE_SEPARATOR . $originUrl);
        // $url = "https://login.microsoftonline.com/" . $this->adTenant . "/oauth2/v2.0/authorize?";
        // $url .= "state=" . base64_encode($state);
        // $url .= "&scope=User.Read";
        // $url .= "&response_type=code";
        // $url .= "&approval_prompt=auto";
        // $url .= "&client_id=" . $this->clientId;
        // $url .= "&redirect_uri=" . urlencode($this->redirectUri);
        // $url .= $refresh ? "&prompt=login" : "";
        // $url .= "&domain_hint=" . $this->domain;

        //header("Location: " . $url);
        $loginUrl = $this->module->getUrl('login.php' . (empty($originUrl) ? '' : '?return='.urlencode($originUrl)), true, true);
        header("Location: " . $loginUrl);
    }

    public function getAuthData($sessionId, $code)
    {
        //Checking if the state matches the session ID
        $stateMatches = strcmp(session_id(), $sessionId) == 0;
        if ( !$stateMatches ) {
            $this->module->framework->log(self::ERROR_MESSAGE_AUTHENTICATION, [ 'error' => 'State does not match session ID' ]);
            return null;
        }

        //Verifying the received tokens with Azure and finalizing the authentication part
        $content = "grant_type=authorization_code";
        $content .= "&client_id=" . $this->clientId;
        $content .= "&redirect_uri=" . urlencode($this->redirectUri);
        $content .= "&code=" . $code;
        $content .= "&client_secret=" . urlencode($this->clientSecret);
        $options = array(
            "http" => array(  //Use "http" even if you send the request with https
                "method"  => "POST",
                "header"  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($content) . "\r\n",
                "content" => $content
            )
        );
        $context = stream_context_create($options);
        $json    = file_get_contents("https://login.microsoftonline.com/" . $this->adTenant . "/oauth2/v2.0/token", false, $context);
        if ( $json === false ) {
            $this->module->framework->log(self::ERROR_MESSAGE_AUTHENTICATION, [ 'error' => 'Error received during Bearer token fetch.' ]);
            return null;
        }
        $authdata = json_decode($json, true);
        if ( isset($authdata["error"]) ) {
            $this->module->framework->log(self::ERROR_MESSAGE_AUTHENTICATION, [ 'error' => 'Bearer token fetch contained an error.' ]);
            return null;
        }

        return $authdata;
    }

    public function getUserData($accessToken) : array
    {

        //Fetching the basic user information that is likely needed by your application
        $options = array(
            "http" => array(  //Use "http" even if you send the request with https
                "method" => "GET",
                "header" => "Accept: application/json\r\n" .
                    "Authorization: Bearer " . $accessToken . "\r\n"
            )
        );
        $context = stream_context_create($options);
        $json    = file_get_contents("https://graph.microsoft.com/v1.0/me?\$select=id,userPrincipalName,mail,givenName,surname,onPremisesSamAccountName,companyName,department,jobTitle,userType,accountEnabled", false, $context);
        if ( $json === false ) {
            $this->module->framework->log(self::ERROR_MESSAGE_AUTHENTICATION, [ 'error' => 'Error received during user data fetch.' ]);
            return [];
        }

        $userdata = json_decode($json, true);  //This should now contain your logged on user information
        if ( isset($userdata["error"]) ) {
            $this->module->framework->log(self::ERROR_MESSAGE_AUTHENTICATION, [ 'error' => 'User data fetch contained an error.' ]);
            return [];
        }

        $username       = $userdata['onPremisesSamAccountName'] ?? $userdata['userPrincipalName'];
        $username_clean = Utilities::toLowerCase($username);
        $email          = $userdata['mail'] ?? $userdata['userPrincipalName'];
        $email_clean    = Utilities::toLowerCase(filter_var($email, FILTER_VALIDATE_EMAIL));

        return [
            'user_email'     => $email_clean,
            'user_firstname' => $userdata['givenName'],
            'user_lastname'  => $userdata['surname'],
            'username'       => $username_clean,
            'company'        => $userdata['companyName'],
            'department'     => $userdata['department'],
            'job_title'      => $userdata['jobTitle'],
            'type'           => $userdata['userType'],
            'accountEnabled' => $userdata['accountEnabled'],
            'id'             => $userdata['id']
        ];
    }

    public function setSiteAttributes()
    {
        $this->domain         = $this->settings['domain'];
        $this->clientId       = $this->settings['clientId'];
        $this->adTenant       = $this->settings['adTenantId'];
        $this->clientSecret   = $this->settings['clientSecret'];
        $this->redirectUri    = $this->settings['redirectUrl'];
    }

    public function handleAuth($url)
    {
        try {
            session_start();
            $sessionId = session_id();
            \Session::savecookie(ShibbolethAuthenticator::SHIBBOLETH_SESSION_ID_COOKIE, $sessionId, 0, true);
            $this->authenticate(false, $url);
            return true;
        } catch ( \Throwable $e ) {
            $this->module->framework->log('Entra ID REDCap Authenticator: Error 1', [ 'error' => $e->getMessage() ]);
            session_unset();
            session_destroy();
            return false;
        }
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getAdTenant()
    {
        return $this->adTenant;
    }

}