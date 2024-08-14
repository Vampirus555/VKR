<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventTypeFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('type')) {
            $events->where('type', request()->query('type'));
        }

        return $next($events);
    }
}