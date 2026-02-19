<?php

namespace JustCommunication\PaykeeperSDK\Service;

use GuzzleHttp\Promise\Create;
use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;
use JustCommunication\PaykeeperSDK\TokenHandler\TokenHandlerInterface;
use Psr\Http\Message\RequestInterface;

class RefreshTokenMiddleware
{
    protected PaykeeperAPIClient $apiClient;
    protected TokenHandlerInterface $tokenHandler;

    public function __construct(PaykeeperAPIClient $apiClient, TokenHandlerInterface $tokenHandler)
    {
        $this->apiClient = $apiClient;
        $this->tokenHandler = $tokenHandler;
    }

    /**
     * Guzzle middleware invocation.
     *
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (isset($options['use_token']) && $options['use_token'] === false) {
                return $handler($request, $options);
            }

            $request = $this->signRequest($request);

            return $handler($request, $options)->then(
                $this->onFulfilled($request, $options, $handler),
                $this->onRejected($request, $options, $handler)
            );
        };
    }

    /**
     * Request error event handler.
     *
     * Handles unauthorized errors by acquiring a new access token and
     * retrying the request.
     *
     * @param RequestInterface $request
     * @param array                              $options
     * @param callable                           $handler
     *
     * @return callable
     */
    private function onFulfilled(RequestInterface $request, array $options, $handler)
    {
        return function ($response) use ($request, $options, $handler) {
            $unauthorized_responses = [
                '{"result":"fail","msg":"\u0422\u043e\u043a\u0435\u043d \u0431\u0435\u0437\u043e\u043f\u0430\u0441\u043d\u043e\u0441\u0442\u0438 \u043d\u0435 \u0432\u0435\u0440\u0435\u043d."}',
                '{"result":"fail","msg":"\u0422\u043e\u043a\u0435\u043d \u0431\u0435\u0437\u043e\u043f\u0430\u0441\u043d\u043e\u0441\u0442\u0438 \u043d\u0435 \u0432\u0435\u0440\u0435\u043d.","error_code":67}'
            ];

            if ($response && !in_array((string)$response->getBody(), $unauthorized_responses)) {
                return $response;
            }

            // If we already retried once, give up.
            // This is extremely unlikely in Guzzle 6+ since we're using promises
            // to check the response - looping should be impossible, but I'm leaving
            // the code here in case something interferes with the Middleware
            if ($request->hasHeader('X-Guzzle-Retry')) {
                return $response;
            }

            try {
                $this->tokenHandler->setToken($this->apiClient->getToken());
            } catch (PaykeeperAPIException $e) {
                return $response;
            }

            $request = $request->withHeader('X-Guzzle-Retry', '1');
            $request = $this->signRequest($request);

            return $handler($request, $options);
        };
    }

    private function onRejected(RequestInterface $request, array $options, $handler)
    {
        return function ($reason) {
            return Create::rejectionFor($reason);
        };
    }

    protected function signRequest(RequestInterface $request): RequestInterface
    {
        if ($request->getMethod() === 'POST') {
            /*
            return new GuzzleHttp\Psr7\Request(
                $request->getMethod(),
                $request->getUri(),
                $request->getHeaders(),
                http_build_query([ 'token' => $this->tokenHandler->getToken() ]) . '&' . $request->getBody()->toString()
            );
            */

            $body = $request->getBody();
            $body->seek($body->getSize());
            $body->write('&token=' . $this->tokenHandler->getToken());

            return $request->withBody($body);
        }

        return $request;
    }
}
