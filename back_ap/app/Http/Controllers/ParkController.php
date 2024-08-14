<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParkCollection;
use App\Http\Resources\ParkResource;
use App\Models\Park;
use App\Models\Delegate;
use App\Models\Metrostation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParkController extends Controller
{
    public function index(): ParkCollection //получение списка парков
    {
        return new ParkCollection(Park::all());
    }


    public function show($id): ParkResource //получение конкретного парка
    {
        $park = Park::findOrFail($id);
        return new ParkResource($park);
    }

    public function showParkByDelegate(Request $request): ParkResource 
    {
        $user = $request->user();
        if(isDelegate($user)){
            $delegate = Delegate::where('user_id', $user->id)->firstOrFail();
            $parkId = $delegate->park_id;
            $park = Park::where('id', $parkId)->firstOrFail();
        }
        return new ParkResource($park);
    }

    public function nearestParks(Request $request): ParkCollection
    {
        // сведения о геопозиции
        $userLatitude = $request->input('latitude');
        $userLongitude = $request->input('longitude');
    
        $parks = Park::all();
        
        //вычисление дистанций к каждому парку
        $nearestParks = [];
        foreach ($parks as $park) {
            $parkGeolocation = json_decode($park->geolocation, true);

            $parkLatitude = $parkGeolocation[0];
            $parkLongitude = $parkGeolocation[1];
            $distance = sqrt(pow($parkLatitude - $userLatitude, 2) + pow($parkLongitude - $userLongitude, 2));
            $nearestParks[] = ['park' => $park, 'distance' => $distance];
        }
        
        //сортирвока по вычесленной дистанции
        usort($nearestParks, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        $top5NearestParks = array_slice($nearestParks, 0, 5);
        
        $top5Parks = array_map(function($item) {
            return $item['park'];
        }, $top5NearestParks);
        
        return new ParkCollection($top5Parks);
    }

    public function search(Request $request): ParkCollection // "живой поиск" по паркам
    {
        $query = $request->input('query');

        $results = cache()->remember('search_results_'.$query, 60*60, function () use ($query) {
            return Park::where('name', 'like', '%'.$query.'%')->get();
        });

        return new ParkCollection($results);
    }
    


    public function store(Request $request): ParkResource 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'desc' => 'required|text',
            'address' => 'required|string|max:100',
            'geolocation' => 'required|string|max:36',
            'work_time' => 'required|text',
            'website' => 'required|string|max:30',
            'phone' => 'required|string|max:17',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'metrostation_ids' => 'array',
            'metrostation_ids.*' => 'integer',
        ]);

        $shortDesc = mb_substr($validated['desc'], 0, 100); // Обрезаем до первых 100 символов

        if (mb_strlen($validated['desc']) > 100) {
            $shortDesc .= '...'; // Добавляем троеточие, если длина описания больше 100 символов
        }

        $park = new Park($validated);
        $park->short_desc = $shortDesc;

        if ($request->hasFile('image')) {
            $park->img_path = $request->file('image')->store('img/parks' . $park->id, 'public');
        }

        $validatedMetrostationsIds = Metrostation::whereIn('id', $validated['metrostation_ids'])->pluck('id')->toArray();
        $park->metrostations()->sync($validatedMetrostationsIds);

        $park->save();

        return new ParkResource($park);
    }

    public function update(Request $request, Park $park): ParkResource
    {
        $validated = $request->validate([
            'desc' => 'required|text',
            'work_time' => 'required|text',
            'website' => 'required|string|max:30',
            'phone' => 'required|string|max:17',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $user = $request->user();
        $delegate = Delegate::where('user_id', $user->id)->first();

        $shortDesc = mb_substr($validated['desc'], 0, 100);

        if (mb_strlen($validated['desc']) > 100) {
            $shortDesc .= '...'; 
        }

        if($park->id === $delegate->park_id){
            $park->update(array_merge($validated, ['short_desc' => $shortDesc]));
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($park->img_path);
                $park->img_path = $request->file('image')->store('img/parks' . $park->id, 'public');
            }

            $park->save();
        }else{
            return response(['message' => 'You do not have permission to do this.'], 403);
        }

        return new ParkResource($park);
    }

    public function destroy(Park $park)
    {
        Storage::disk('public')->delete($park->img_path);

        return $park->delete();
    }
}
