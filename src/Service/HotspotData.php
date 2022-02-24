<?php

namespace App\Service;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HotspotData
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getWitnessed($hotspot)
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.helium.io/v1/hotspots/%s/witnessed',
                $hotspot
            )
        );

        return $response->toArray();
    }

    public function getWitnesses($hotspot)
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.helium.io/v1/hotspots/%s/witnesses',
                $hotspot
            )
        );

        return $response->toArray();
    }

    public function getRolesCount($hotspot)
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.helium.io/v1/hotspots/%s/roles/count',
                $hotspot
            )
        );

        return $response->toArray();
    }

    public function getRewardsSum($hotspot)
    {
        $tomorrow = new \DateTime();
        $tomorrow->modify('+1 day');

        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.helium.io/v1/hotspots/%s/rewards/sum?min_time=2020-01-01&max_time=%s',
                $hotspot,
                $tomorrow->format('Y-m-d')
            )
        );

        return $response->toArray();
    }
}
