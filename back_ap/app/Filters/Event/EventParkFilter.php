<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventParkFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('park_id')) {
            $events->where('park_id', request()->query('park_id'));
        }
        elseif(request()->has('park_name')){
            $parkId = Park::where('name', request()->query('park_name'))->value('id');
            $events->where('park_id', $parkId);
        }

        return $next($events);
    }
}