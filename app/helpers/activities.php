<?php

use App\Models\Activity;

if (! function_exists('activities')) {
    function activities($action, string $title, string $description): Activity
    {
        return Activity::create(compact('action', 'title', 'description'));
    }
}
