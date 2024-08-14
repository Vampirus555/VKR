<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventDataFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('data')) {
            $events->where('start_data', '>=', request()->query('data'))
                                ->where('end_data', '<=', request()->query('data'));
        }

        return $next($events);
    }
}