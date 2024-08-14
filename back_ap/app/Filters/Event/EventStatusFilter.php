<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventStatusFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('status')) {
            $events->where('status', request()->query('status'));
        }

        return $next($events);
    }
}