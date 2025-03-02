<?php

namespace App\Libraries;

use Google\Client;
use Google\Service\Calendar;

class GoogleAuth
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();

        $credentialsPath = WRITEPATH . 'credentials.json'; 
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Google credentials file not found : " . $credentialsPath);
        }
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(Calendar::CALENDAR_READONLY);

        $this->client->addScope("openid");
        $this->client->addScope("email");
        $this->client->addScope("profile");

        $this->client->setRedirectUri(base_url('auth/callback'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function generateAuthUrl()
    {   
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {    
        // authorization code 
        try {
            $this->client->fetchAccessTokenWithAuthCode($code);
            return $this->client->getAccessToken();
        } catch (\Exception $e) {
            error_log("Google token fetch failed: " . $e->getMessage());
            return null;
        }
    }

    public function setAccessToken($token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException("Invalid access token.");
        }
        $this->client->setAccessToken($token);
    }
    
    public function getCalendarService()
    {
        return new Calendar($this->client);
    }
}
