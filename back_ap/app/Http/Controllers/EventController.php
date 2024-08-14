<?php

namespace App\Http\Controllers;

use App\Filters\Event\EventCategoryFilter;
use App\Filters\Event\EventDataFilter;
use App\Filters\Event\EventParkFilter;
use App\Filters\Event\EventStatusFilter;
use App\Filters\Event\EventTypeFilter;
use Illuminate\Pipeline\Pipeline;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Park;
use App\Models\Review;
use App\Models\Organizer;
use App\Models\Delegate;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    use ApiHelpers;
    
    public function index(): EventCollection
    {
        $events = Event::query();
        
        $response = app(Pipeline::class)
            ->send($events)
            ->through([
                EventStatusFilter::class,
                EventTypeFilter::class,
                EventParkFilter::class,
                EventCategoryFilter::class,
                EventDataFilter::class,
            ])
            ->via('apply')
            ->then(function ($events) {
                $events->with(['reviews', 'notes']); // Загружаем связь с отзывами
                $events = $events->get(); // Получаем коллекцию мероприятий
                $events->each(function ($event) {
                    $event->average_rating = $event->reviews->avg('rating') ?? 0; // Вычисляем средний рейтинг
                    $event->hasNote = $event->notes->isNotEmpty();
                });
                return $events;
            });

        return new EventCollection($response);
    }

    public function show($id): EventResource
    {
        $event = Event::with(['reviews', 'notes'])->find($id);
        $viewsCount = $event->views->count() ?? 0;
        // $averageRating = $event->reviews->avg('rating');
        $event->average_rating = round($event->reviews->avg('rating'), 1) ?? 0;
        $event->hasNote = $event->notes->isNotEmpty();

        // $averageRating = 5;

        return new EventResource($event);
    }

    //получение событий, в зависимости от роли, для представления в личном кабинете
    public function indexParkEventsByRole(Request $request): EventCollection
    {
        $user = $request->user();

        if($this->isDelegate($user)){
            $delegate = Delegate::where('user_id', $user->id)->firstOrFail();
            $parkId = $delegate->park_id;
            $events = Event::where('park_id', $parkId)
                        ->whereNull('organizer_id')
                        ->get();
        }
        
        if($this->isOrganizer($user)){
            $organizer = Organizer::where('user_id', $user->id)->firstOrFail();
            $events = Event::where('organizer_id', $organizer->id)->get();
        }
        
        return new EventCollection($events);
    }


    public function indexParkEventsByOrganizer(Request $request): EventCollection
    {
        $user = $request->user();


        $organizer = Organizer::where('user_id', $user->id)->firstOrFail();
        $events = Event::where('organizer_id', $organizer->id)->get();
        
        
        return new EventCollection($events);
    }

    public function indexParkEventsByDelegate(Request $request): EventCollection
    {
        $user = $request->user();

      
            $delegate = Delegate::where('user_id', $user->id)->firstOrFail();
            $parkId = $delegate->park_id;
            $events = Event::where('park_id', $parkId)
                        ->whereNull('organizer_id')
                        ->get();
        
        
       
        
        return new EventCollection($events);
    }

    // поулучение заявок представителем парка
    public function indexApplications(Request $request): EventCollection
    {
        $user = $request->user();
        
            // $delegate = Delegate::where('user_id', $user->id)->firstOrFail();
            // $parkId = $delegate->park_id;
            $events = Event::where('park_id', 2)
                        ->where('status', 'на рассмотрении')
                        ->get();
        
        
        return new EventCollection($events);
    }
   

    public function store(Request $request): EventResource
    {
      
        // $validated = $request->validate([
        //     'park_id' => 'integer',
        //     'category_ids' => 'array',
        //     'category_ids.*' => 'integer',
        //     'name' => 'string|max:250', 
        //     'type' => 'string|in:временное,постоянное', 
        //     'limit_age' , 
        //     'desc' , 
        //     'start_date' , 
        //     'end_date' , 
        //     'schedule' , 
        //     'price', 
        //     'phone' , 
        //     'image' , 
        // ]);

        $ev = [
                'park_id' => 2,
                
                'name' => 'Концерт стихия воды', 
                'organizer_id' => 2, 
                'type'=> 'временное', 
                'limit_age' => '6+', 
                'desc' =>  'Вы познакомитесь с увлекательной историей на нашем концерте', 
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-10',
                'schedule' => 'C 5 до 6', 
                'price' => 'бесплатно', 
                'phone' => '89003233456',
                'img_path' => 'img/events/23.jpeg', 
                'status' => 'на рассмотрении',  
                
            ];
      

        // $user = $request->user();
        // $organizer_id = null;

        $organizer_id = 2;

        // if ($this->isOrganizer($user)) {
        //     $status = 'на рассмотрении';
        //     $organizer = Organizer::where('user_id', $user->id)->first();
        // } else {
        //     $status = 'одобрено';
        // }

        // $shortDesc = mb_substr($validated['desc'], 0, 100); // Обрезаем до первых 100 символов

        // if (mb_strlen($validated['desc']) > 100) {
        //     $shortDesc .= '...'; // Добавляем троеточие, если длина описания больше 100 символов
        // }

        $organizer = Organizer::where('id',2)->first();
        // $organizer = Organizer::findOrFail($validated['organizer_id']);

        $event = Event::create($ev);
        // $event->status = $status;
        // // $event->short_desc = $shortDesc;
        // $park = Park::findOrFail($validated['park_id']);
        $park = Park::where('id',2)->first();
        $event->park()->associate($park);
        $event->organizer()->associate($organizer);
        
        $validatedCategoryIds = Category::whereIn('id', $validated['category_ids'])->pluck('id')->toArray();
        $event->categories()->sync($validatedCategoryIds);
        
        // if ($request->hasFile('image')) {
        //     $event->img_path = $request->file('image')->store('img/events' . $event->id, 'public');
        // }

            // $event->img_path = 'img/events/23.jpeg' ;
        

        $event->save();

        return new EventResource($event);
    }

    public function update(Request $request, Event $event): EventResource
    {
        $validated = $request->validate([
            'category_ids' => 'array',
            'category_ids.*' => 'integer',
            'name' => 'required|string|max:100',
            'status' => 'required|string|in:одобрено,отклонено,на рассмотрении',
            'type' => 'required|string|in:временное,постоянное',
            'limit_age' => 'required|string|max:10',
            'desc' => 'required|text',
            'start_data' => 'required|data',
            'end_data' => 'required|data',
            'schedule' => 'required|text',
            'price' => 'required|string|max:25',
            'phone' => 'required|string|max:17',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $shortDesc = mb_substr($validated['desc'], 0, 100);

        if (mb_strlen($validated['desc']) > 100) {
            $shortDesc .= '...'; 
        }

        $event->update(array_merge($validated, ['short_desc' => $shortDesc]));

        $validatedCategoryIds = Category::whereIn('id', $validated['category_ids'])->pluck('id')->toArray();
        $event->categories()->sync($validatedCategoryIds);


        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($event->img_path);
            $event->img_path = $request->file('image')->store('img/events' . $event->id, 'public');
        }

        $event->save();

        return new EventResource($event);
    }

    public function changeStatus(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string|in:одобрено,отклонено',
        ]);

        $user = $request->user();

        if (!$this->isAdmin($user) || !$this->isDelegate($user)) {
            return response(['message' => 'You do not have permission to do this.'], 403); 
        } else {
            $event->update($validated);
            $event->save();
           
            
        }

        return new EventResource($event);
        
    }

    public function destroy(Event $event)
    {
        Storage::disk('public')->delete($event->img_path);

        return $event->delete();
    }
}
