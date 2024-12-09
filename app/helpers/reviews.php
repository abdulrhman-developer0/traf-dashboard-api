<?php

if (!function_exists('get_rating_stats')) {
    /**
     * Get rating distribution statistics based on the given query
     * Returns percentages for each rating category
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return array
     */
    function get_rating_stats($query)
    {
        $reviews = (clone $query)->get();

        $stats = [
            'excellent' => 0,   // 5 stars
            'very_good' => 0,   // 4 stars
            'good' => 0,        // 3 stars
            'bad' => 0,         // 2 stars
            'very_bad' => 0     // 1 star
        ];

        if ($reviews->isEmpty()) {
            return $stats;
        }

        $total = $reviews->count();

        // Calculate distribution
        foreach ($reviews as $review) {
            switch ($review->rating) {
                case 5:
                    $stats['excellent']++;
                    break;
                case 4:
                    $stats['very_good']++;
                    break;
                case 3:
                    $stats['good']++;
                    break;
                case 2:
                    $stats['bad']++;
                    break;
                case 1:
                    $stats['very_bad']++;
                    break;
            }
        }

        // Convert counts to percentages
        foreach ($stats as $key => $count) {
            $stats[$key] = round(($count / $total) * 100);
        }

        return $stats;
    }
}
