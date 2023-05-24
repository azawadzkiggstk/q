<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use Statik\Deploy\Deployment\Provider\Error\ApiRequestException;
use Statik\Deploy\Deployment\Provider\Error\ApiResponseException;
use Statik\Deploy\Deployment\Provider\Provider\ProviderInterface;
use Statik\Deploy\Deployment\Provider\Settings\SettingsInterface;

/**
 * Class AbstractApi.
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * AbstractApi constructor.
     */
    public function __construct(protected ProviderInterface $provider)
    {
    }

    /**
     * Get provider.
     */
    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    /**
     * Get settings.
     */
    public function getSettings(): SettingsInterface
    {
        return $this->provider->getSettings();
    }

    /**
     * Using the Guzzle to request an API endpoint. If the request finishes
     * successfully then return JSON with the response. If the status is
     * greater than 400, then an exception is thrown.
     *
     * @throws ApiResponseException|ApiRequestException
     */
    protected function callApi(string $uri, string $method = 'GET', array $options = []): array
    {
        try {
            $options = \array_merge($options, [
                RequestOptions::CONNECT_TIMEOUT => 3,
                RequestOptions::TIMEOUT => 5,
                RequestOptions::VERSION => 3,
                RequestOptions::SYNCHRONOUS => true,
            ]);
            $client = new Client();
            $response = $client->request($method, $uri, $options);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (TransferException $e) {
            throw new ApiRequestException($e->getMessage(), $e->getCode(), $e);
        } catch (GuzzleException|\UnexpectedValueException $e) {
            throw new ApiResponseException($e->getMessage(), $e->getCode(), $e);
        }

        $body = $response->getBody()->getContents();
        $bodyArray = \json_decode($body, true);

        if (400 <= $response->getStatusCode()) {
            throw new ApiResponseException($bodyArray['message'] ?: \strip_tags($body), $response->getStatusCode());
        }

        if (false === \is_array($bodyArray)) {
            throw new ApiResponseException(
                \__('Invalid response from the API service: ', 'statik-deployment') . \strip_tags($body),
                500
            );
        }

        return $bodyArray;
    }
}
