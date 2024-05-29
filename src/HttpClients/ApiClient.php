<?php

namespace Experteam\ApiLaravelCrud\HttpClients;

use App;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiClient
{
    private array $headers = [];
    protected ?string $baseUrl;
    protected ?string $accessToken;
    protected ?string $appKey;
    protected bool $checkSuccess = false;
    protected int $timeout = 30;
    protected bool $fullResponse = false;

    public function __construct()
    {

        $this->accessToken = request()->bearerToken();
        $this->appKey = App::runningInConsole() || !request()->headers->has('AppKey') ?
            config('experteam-crud.appKey') :
            request()->headers->get('AppKey');

    }

    /**
     * @throws \Exception
     */
    protected function get(string $endpoint, array $query = [], $useLogin = true)
    {

        $query = http_build_query($query);

        $client = $this->getClient($useLogin);

        \Log::debug("Request url: {$this->baseUrl}$endpoint?$query");

        $res = $client->get("{$this->baseUrl}$endpoint?$query");

        return $this->handleResponse($res);

    }

    /**
     * @throws AuthorizationException
     */
    protected function getClient($useLogin = true)
    {

        $client = Http::withoutVerifying()->timeout($this->timeout);

        if ($useLogin) {
            if (!empty($this->accessToken))
                $client->withToken($this->accessToken);
            elseif (!empty($this->appKey))
                $this->addHeaders(['AppKey' => $this->appKey]);
            else
                throw new AuthorizationException(trans('auth.not_token_provided'));
        }

        return $client->withHeaders($this->headers);

    }

    /**
     * @throws \Exception
     */
    protected function post(string $endpoint, array $body = [], $useLogin = true)
    {

        $client = $this->getClient($useLogin);

        $res = $client->post("{$this->baseUrl}$endpoint", $body);

        return $this->handleResponse($res);

    }

    /**
     * @throws \Exception
     */
    protected function patch(string $endpoint, array $body = [], $useLogin = true)
    {

        $client = $this->getClient($useLogin);

        $res = $client->patch("{$this->baseUrl}$endpoint", $body);

        return $this->handleResponse($res);

    }

    /**
     * @throws \Exception
     */
    protected function handleResponse(Response $res)
    {
        if (($res['status'] ?? 'error') === 'error')
            throw ValidationException::withMessages([
                'client' => class_basename($this),
                'message' => ($res['message'] ?? '')
            ]);

        if ($this->checkSuccess && $res['status'] !== 'success')
            throw ValidationException::withMessages(array_merge(
                ['client' => class_basename($this)],
                $res['data']));

        return $this->fullResponse ? $res : $res['data'];

    }

    protected function addHeaders(array $headers): void
    {

        $this->headers = array_merge($this->headers, $headers);

    }

    protected function setHeaders(array $headers): void
    {

        $this->headers = $headers;

    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    protected function setAccessToken(?string $accessToken): void
    {

        $this->accessToken = $accessToken;

    }

    protected function checkSuccess(bool $checkSuccess): self
    {
        $this->checkSuccess = $checkSuccess;
        return $this;
    }

    protected function setFullResponse(bool $fullResponse): self
    {
        $this->fullResponse = $fullResponse;
        return $this;
    }

    protected function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }
}
