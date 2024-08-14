<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventCategoryFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('categories')) {
            $events->whereHas('categories', function (Builder $query) {
                $query->whereIn('name', request()->query('categories'));
            });
        }

        return $next($events);
    }
}