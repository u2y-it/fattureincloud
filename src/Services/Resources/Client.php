<?php

namespace U2y\FattureInCloud\Services\Resources;

use Exception;
use Http;
use FattureInCloud\Api\ClientsApi;
use FattureInCloud\Model\ListClientsResponse;

class Client
{


    public const STATUS_ERROR = 'error';

    public $client;

    public function __construct($config)
    {
        $this->client = new ClientsApi(null,
            $config
        );
    }

    public function list($company_id, $per_page = 10, $page = 1): ListClientsResponse
    {
        return $this->client->listClients($company_id, null, null, null, $page, $per_page, null);
    }

    public function create($company_id, array $data)
    {
        return $this->client->createClient($company_id, $data);
    }

    // public function formattedListByStages(array $stages, array $options = [])
    // {
    //     return $this->formatResponse($this->listByStages($stages, $options));
    // }

    // private function filterByStages(array $stages): array
    // {
    //     return [
    //         $this->filterIn('dealstage', $stages),
    //     ];
    // }

    // private function createSearchRequest(array $filterGroups)
    // {
    //     $searchRequest = new PublicObjectSearchRequest();
    //     $searchRequest->setFilterGroups([...$filterGroups]);
    //     return $searchRequest;
    // }

    private function manageRequestErrors($result)
    {
        if (isset($result->json()['status']) && $result->json()['status'] === self::STATUS_ERROR) {
            throw new \Exception($result->json()['message']);
        }
    }
}
