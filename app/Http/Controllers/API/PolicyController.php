<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    use APIResponses;

    public function index()
    {
        $policies = Policy::get(['id', 'title', 'content']);

        return $this->okResponse($policies, 'Policies retrieved successfuly');
    }

    public function show(string $id)
    {
        $policy = Policy::whereId($id)
            ->select(['id', 'title', 'content'])
            ->first();

        if (! $policy) {
            return $this->badResponse([
                'reason' => 'invalid_id'
            ], "No policy with id $id");
        }

        return $this->okResponse($policy, 'Policy retrieved successfuly');
    }
}
