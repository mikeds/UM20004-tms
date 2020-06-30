<?php
require APPPATH . 'libraries/OAuth2/Autoloader.php';
OAuth2\Autoloader::register();

class OAuth2 {
    var $server;

    function __construct() {
        $this->init();
    }

    public function init() {
        $dsn = "mysql:dbname=". DB_NAME .";host=localhost";
        $username = DB_USERNAME;
        $password = DB_PWD;

        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->server = new OAuth2\Server($storage, array(
            'always_issue_new_refresh_token' => true
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    }

    public function get_token() {
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    public function get_resource() {
        // Handle a request to a resource and authenticate the access token
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
            die;
        }
    }
}

?>