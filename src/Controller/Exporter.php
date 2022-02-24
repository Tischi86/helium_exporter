<?php

namespace App\Controller;

use App\Service\HotspotData;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class Exporter
{
    /**
     * @var HotspotData
     */
    private $hotspotData;
    private $hotspotAddresses;

    public function __construct(HotspotData $hotspotData, $hotspotAddresses)
    {
        $this->hotspotData = $hotspotData;
        $this->hotspotAddresses = explode(',', $hotspotAddresses);
    }

    /**
     * @Route("/exporter")
     */
    public function number(): Response
    {
        $registry = new CollectorRegistry(new InMemory());

        $witnessedGauge = $registry->getOrRegisterGauge('helium', 'witnessed', 'Number of witnessed over the last 5 days.', ['hotspot']);
        $witnessesGauge = $registry->getOrRegisterGauge('helium', 'witnesses', 'Number of witnesses over the last 5 days.', ['hotspot']);
        $rolesCountGauge = $registry->getOrRegisterGauge('helium', 'roles_count', 'Count transactions that indicate a Hotspot as a participant.', ['role', 'hotspot']);
        $rewardsSumGauge = $registry->getOrRegisterGauge('helium', 'rewards_sum', 'Rewards since 2020-01-01.', ['hotspot']);

        foreach ($this->hotspotAddresses as $hotspot) {
            $witnessed = $this->hotspotData->getWitnessed($hotspot);
            $witnesses = $this->hotspotData->getWitnesses($hotspot);
            $rewardsSum = $this->hotspotData->getRewardsSum($hotspot);
            $rolesCount = $this->hotspotData->getRolesCount($hotspot);

            $witnessedGauge->set(count($witnessed['data']), ['hotspot' => $hotspot]);
            $witnessesGauge->set(count($witnesses['data']), ['hotspot' => $hotspot]);
            $rewardsSumGauge->set($rewardsSum['data']['total'], ['hotspot' => $hotspot]);

            foreach ($rolesCount['data'] as $role => $value) {
                $rolesCountGauge->set($value, ['role' => $role, 'hotspot' => $hotspot]);
            }
        }

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        return new Response($result, 200, ['content-type' => RenderTextFormat::MIME_TYPE]);
    }
}
