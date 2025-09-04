<?php

namespace JustCommunication\PaykeeperSDK;

use BadMethodCallException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use JustCommunication\PaykeeperSDK\API\Invoice\InvoiceByIdRequest;
use JustCommunication\PaykeeperSDK\API\Invoice\InvoiceRevokeRequest;
use JustCommunication\PaykeeperSDK\API\RequestInterface;
use JustCommunication\PaykeeperSDK\API\ResponseInterface;
use JustCommunication\PaykeeperSDK\API\TokenRequest;
use JustCommunication\PaykeeperSDK\Exception\PaykeeperAPIException;
use JustCommunication\PaykeeperSDK\Service\RefreshTokenMiddleware;
use JustCommunication\PaykeeperSDK\TokenHandler\InMemoryTokenHandler;
use JustCommunication\PaykeeperSDK\TokenHandler\TokenHandlerInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * @method API\TokenResponse sendTokenRequest(API\TokenRequest $request)
 * @method API\Systems\SystemsListResponse sendSystemsListRequest(API\Systems\SystemsListRequest $request)
 * @method API\Errors\ErrorsTotalResponse sendErrorsTotalRequest(API\Errors\ErrorsTotalRequest $request)
 * @method API\Invoice\InvoicePreviewResponse sendInvoicePreviewRequest(API\Invoice\InvoicePreviewRequest $request)
 * @method API\Invoice\InvoiceRevokeResponse sendInvoiceRevokeRequest(API\Invoice\InvoiceRevokeRequest $request)
 * @method API\Invoice\InvoiceListResponse sendInvoiceListRequest(API\Invoice\InvoiceListRequest $request)
 * @method API\Invoice\InvoiceByIdResponse sendInvoiceByIdRequest(API\Invoice\InvoiceByIdRequest $request)
 */
class PaykeeperAPIClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected static array $default_http_client_options = [
        'connect_timeout' => 4,
        'timeout' => 10
    ];

    protected string $url;
    protected string $username;
    protected string $password;

    protected Client $httpClient;

    /**
     * @param Client|array|null $httpClientOrOptions
     */
    public function __construct(string $url, string $username, string $password, ?TokenHandlerInterface $tokenHandler = null, $httpClientOrOptions = null)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;

        $this->logger = new NullLogger();
        $this->httpClient = self::createHttpClient($httpClientOrOptions);
        $this->httpClient->getConfig('handler')->push(new RefreshTokenMiddleware($this, $tokenHandler ?? (new InMemoryTokenHandler())));
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @throws PaykeeperAPIException
     */
    public function getToken(): string
    {
        return $this->sendTokenRequest(new TokenRequest())->getToken();
    }

    /**
     * @throws PaykeeperAPIException
     */
    public function getInvoice(int $invoice_id): Model\Invoice
    {
        return $this->sendInvoiceByIdRequest(new InvoiceByIdRequest($invoice_id))->getInvoice();
    }

    /**
     * @throws PaykeeperAPIException
     */
    public function revokeInvoice(string $invoice_id): void
    {
        $this->sendInvoiceRevokeRequest(new InvoiceRevokeRequest($invoice_id));
    }

    public function __call($name, array $arguments)
    {
        if (0 === strpos($name, 'send')) {
            return call_user_func_array([$this, 'sendRequest'], $arguments);
        }

        throw new BadMethodCallException(sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    /**
     * @throws PaykeeperAPIException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $this->logger->info('Paykeeper API request {http_method} {uri}', [
                'http_method' => $request->getHttpMethod(),
                'uri' => $request->getUri(),
                'request_params' => $request->createHttpClientParams()
            ]);

            /** @var Response $response */
            $response = $this->createAPIRequestPromise($request)->wait();

            $this->logger->info('Paykeeper API {http_method} {uri} response {response_code}', [
                'http_method' => $request->getHttpMethod(),
                'uri' => $request->getUri(),
                'response_code' => $response->getStatusCode(),
                'response' => (string)$response->getBody()
            ]);

            return $this->createAPIResponse($response, $request->getResponseClass());
        } catch (BadResponseException $e) {
            $this->logger->error('Paykeeper API {http_method} {uri} error {response_code}', [
                'http_method' => $request->getHttpMethod(),
                'uri' => $request->getUri(),
                'response_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : '–',
                'response' => $e->hasResponse() ? (string)$e->getResponse()->getBody() : '-'
            ]);

            $this->handleErrorResponse($e->getResponse());

            throw new PaykeeperAPIException('Paykeeper API error: ' . $e->getMessage());
        }
    }

    public function createAPIRequestPromise(RequestInterface $request): PromiseInterface
    {
        $request_params = $request->createHttpClientParams();

        if (!isset($request_params['base_uri'])) {
            $request_params['base_uri'] = $this->url;
        }

        $request_params = array_merge($request_params, [
            'auth' => [ $this->username, $this->password ],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return $this->httpClient->requestAsync($request->getHttpMethod(), $request->getUri(), $request_params);
    }

    /**
     * @return ResponseInterface
     *
     * @throws PaykeeperAPIException
     */
    protected function createAPIResponse(HttpResponseInterface $response, string $apiResponseClass): ResponseInterface
    {
        if (!is_a($apiResponseClass, ResponseInterface::class, true)) {
            throw new PaykeeperAPIException('Invalid response class');
        }

        return $apiResponseClass::createFromResponse($response);
    }

    /**
     * @throws PaykeeperAPIException
     */
    protected function handleErrorResponse(?HttpResponseInterface $response)
    {
        if (!$response) {
            return;
        }

        $response_string = (string)$response->getBody();

        $response_data = json_decode($response_string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new PaykeeperAPIException('Unable to decode error response data. Error: ' . json_last_error_msg());
        }

        if (isset($response_data['result']) && $response_data['result'] === 'fail') {
            throw new PaykeeperAPIException($response_data['msg']);
        }
    }

    public function setHttpClient(Client $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @param Client|array|null $httpClientOrOptions
     */
    protected static function createHttpClient($httpClientOrOptions = null): Client
    {
        if ($httpClientOrOptions instanceof Client) {
            $httpClient = $httpClientOrOptions;
        } elseif (is_array($httpClientOrOptions)) {
            $httpClient = new Client(array_merge(self::$default_http_client_options, $httpClientOrOptions));
        } else {
            $httpClient = new Client(self::$default_http_client_options);
        }

        return $httpClient;
    }
}
