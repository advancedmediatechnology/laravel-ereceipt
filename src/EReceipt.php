<?php
namespace YourVendor\EReceipt;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class EReceipt
{
    protected HttpClient $http;
    protected string $token;

    public function __construct(string $baseUrl, string $apiKey, string $apiSecret)
    {
        // initial client for auth
        $this->http = new HttpClient([
            'base_uri' => $baseUrl,
            'headers'  => ['Accept' => 'application/json'],
        ]);

        $this->authenticate($apiKey, $apiSecret);
    }

    protected function authenticate(string $apiKey, string $apiSecret): void
    {
        $response = $this->http->post('/auth', [
            'json' => compact('apiKey', 'apiSecret'),
        ]);
        $data       = json_decode((string) $response->getBody(), true);
        $this->token = $data['access_token'];

        // rebind http client with auth header
        $this->http = new HttpClient([
            'base_uri' => $this->http->getConfig('base_uri'),
            'headers'  => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
    }

    public function addLogo(string $filePath): array
    {
        $response = $this->http->post(
            '/configuration/logo',
            [
                'headers' => ['Content-Type' => mime_content_type($filePath)],
                'body'    => fopen($filePath, 'r'),
            ]
        );
        return json_decode((string) $response->getBody(), true);
    }

    public function getLogoRevision(int $revision)
    {
        return (string) $this->http->get(f"/configuration/revisions/{revision}/logo")->getBody();
    }

    public function getReceiptLogo(string $receiptId)
    {
        return (string) $this->http->get(f"/receipt/{receiptId}/logo")->getBody();
    }

    public function getConfiguration(int $revision = null): array
    {
        $uri = $revision ? f"/configuration/revisions/{revision}" : '/configuration';
        $response = $this->http->get($uri);
        return json_decode((string) $response->getBody(), true);
    }

    public function listRevisions(): array
    {
        $response = $this->http->get('/configuration/revisions');
        return json_decode((string) $response->getBody(), true);
    }

    public function createReceipt(string $guid, array $payload): array
    {
        $response = $this->http->put(f"/receipt/{guid}", ['json' => $payload]);
        return json_decode((string) $response->getBody(), true);
    }

    public function retrieveReceipt(string $receiptId): array
    {
        $response = $this->http->get(f"/receipt/{receiptId}");
        return json_decode((string) $response->getBody(), true);
    }

    public function listReceipts(): array
    {
        $response = $this->http->get('/receipt');
        return json_decode((string) $response->getBody(), true);
    }

    public function retrievePdf(string $receiptId)
    {
        return (string) $this->http->get(f"/receipt/{receiptId}/pdf")->getBody();
    }
}
