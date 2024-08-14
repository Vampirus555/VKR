<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ParkController, EventController, ReviewController, CategoryController,NoteController, 
    OrganizerController, DelegateController, DialogController, MessageController, UserController, ViewController, RoleController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/*
|--------------------------------------------------------------------------
|                              Public routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Parks
Route::get('/parks', [ParkController::class, 'index']);
Route::get('/parks/{id}', [ParkController::class, 'show']);
Route::post('/nearest-parks', [ParkController::class, 'nearestParks']);
Route::get('/live-search', [ParkController::class, 'search']);
// Events
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
// Reviews
Route::get('/reviews/{id}', [ReviewController::class, 'indexEventReview']);
//Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

/*
|--------------------------------------------------------------------------
|                             Protected routes
|--------------------------------------------------------------------------
*/

// Authorized user routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Notes
    Route::get('/my-notes', [NoteController::class, 'indexUserNote']);
    Route::post('/note', [NoteController::class, 'store']);
    Route::put('/note/{id}', [NoteController::class, 'update']);
    Route::delete('/note/{id}', [NoteController::class, 'destroy']);
    //User
    Route::put('/user/{id}', [UserController::class, 'update']);
    //View
    Route::post('/view', [ViewController::class, 'store']);

});

// User (role) routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,user']], function () {
    // Reviews
    Route::post('/review', [ReviewController::class, 'store']);
    Route::put('/review/{id}', [ReviewController::class, 'update']);
    Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
});

// Organizer routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,organizer']], function () {
    // Organizer
    Route::post('/reg-organizer', [OrganizerController::class, 'store']);
    Route::put('/organizers/{id}', [OrganizerController::class, 'update']);
    Route::get('/my-eventsE', [EventController::class, 'indexParkEventsByOrganizer']);
    
});

// Delegate routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,delegate']], function () {
    // Delegate
    Route::post('/reg-delegate', [DelegateController::class, 'store']);
    Route::put('/delegates/{id}', [DelegateController::class, 'update']);
    //Events
    Route::get('/event-applications', [EventController::class, 'indexApplications']);
    Route::put('/event-applications/{id}', [EventController::class, 'changeStatus']);
    //Park
    Route::get('/my-park', [ParkController::class, 'showParkByDelegate']);
    Route::put('/my-park/{id}', [ParkController::class, 'update']);
    //Dialog
    Route::post('/dialog', [DialogController::class, 'store']);
});

// Delegate and organizer routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,organizer,delegate']], function () {
    //Events
    Route::get('/my-events', [EventController::class, 'indexParkEventsByRole']);
    Route::put('/my-events/{id}', [EventController::class, 'update']);
    Route::post('/add-event', [EventController::class, 'store']);
    //Dialogs&messages
    Route::get('/my-dialogs', [DialogController::class, 'indexByRole']);
    Route::get('/messages', [MessageController::class, 'indexMessagesForDialog']);
    Route::post('/message', [MessageController::class, 'store']);
    Route::put('/message/{id}', [MessageController::class, 'update']);
    Route::delete('/message/{id}', [MessageController::class, 'destroy']);
});

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    //User
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    //Park
    Route::post('/park', [ParkController::class, 'store']);
    Route::delete('/park/{id}', [ParkController::class, 'destroy']);
    //Notes
    Route::get('/notes', [NoteController::class, 'index']);
    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    //Views (пока что только админ)
    Route::get('/views', [ViewController::class, 'index']);
    Route::put('/view/{id}', [ViewController::class, 'update']);
    Route::delete('/view/{id}', [ViewController::class, 'destroy']);
    //Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/role', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
});
