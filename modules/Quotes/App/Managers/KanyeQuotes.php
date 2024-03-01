<?php

namespace Avrillo\Quotes\App\Managers;

use GuzzleHttp\Client;
use Illuminate\Log\Logger;
use Throwable;

class KanyeQuotes implements ProvidesQuotes
{
    private Client $client;
    private Logger $logger;

    public function __construct(Client $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function get(): ?string
    {
        try {
            $response = $this->client->get( 'https://api.kanye.rest');
            $json = json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);
            return $json['quote'];
        } catch (Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }
}
