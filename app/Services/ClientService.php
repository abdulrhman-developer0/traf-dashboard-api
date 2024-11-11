<?php

namespace App\SErvices;

use App\Models\Client;

class ClientService
{
    public function getClients()
    {
        $query = Client::query();

        $clients = $query->get();

        return $clients;
    }

    public function createClient(array $data)
    {
        $client = Client::create($data);

        return $client;
    }
}
