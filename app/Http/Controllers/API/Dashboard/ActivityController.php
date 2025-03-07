<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use APIResponses;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $activityPaginator = Activity::latest()->paginate($request->input('page_size', 6));

        return $this->okResponse(
            [
                'current_page' => $activityPaginator->currentPage(),
                'last_page'    => $activityPaginator->lastPage(),
                'activities'   => $activityPaginator->items,
            ],
            __('Activities retrieved successfuly')
        );
    }
}
