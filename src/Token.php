<?php

namespace Chadicus\Slim\OAuth2\Routes;

use Chadicus\Slim\OAuth2\Http\MessageBridge;
use OAuth2;
use Slim\App;

/**
 * Slim route for /token endpoint.
 */
class Token
{
    const ROUTE = '/token';

    /**
     * The slim framework application.
     *
     * @var Slim
     */
    private $slim;

    /**
     * The OAuth2 server instance.
     *
     * @var OAuth2\Server
     */
    private $server;

    /**
     * Create a new instance of the Token route.
     *
     * @param Slim          $slim   The slim framework application instance.
     * @param OAuth2\Server $server The oauth2 server imstance.
     */
    public function __construct(App $slim, OAuth2\Server $server)
    {
        $this->slim = $slim;
        $this->server = $server;
    }

    /**
     * Call this class as a function.
     *
     * @param \Psr\Http\Message\RequestInterface $res
     * @param \Psr\Http\Message\ResponseInterface $req
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($req, $res)
    {
        $request = MessageBridge::newOAuth2Request($req);
        MessageBridge::mapResponse(
            $this->server->handleTokenRequest($request), $res);
    }

    /**
     * Register this route with the given Slim application and OAuth2 server
     *
     * @param Slim          $slim   The slim framework application instance.
     * @param OAuth2\Server $server The oauth2 server imstance.
     *
     * @return void
     */
    public static function register(App $slim, OAuth2\Server $server)
    {
        $slim->post(self::ROUTE, new static($slim, $server))->setName('token');
    }
}
