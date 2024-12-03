<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    use APIResponses;

    public function __invoke(Request $request)
    {
        $packages = Package::get(['id', 'name', 'price', 'duration_in_days']);

        return $this->okResponse($packages, 'Packages retrieved successfuly');
    }
}
