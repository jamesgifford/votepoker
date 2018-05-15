<?php

namespace App\Http\Controllers\Core;

use App\Database\Repositories\RoomRepository as Room;
use App\Events\UserJoinedRoom;
use App\Helpers\RouteHelper;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

/**
 * Handles voting rooms
 */
class RoomController extends Controller
{
    /**
     * Display the specified room.
     *
     * @param  App\Database\Repositories\Room  $room
     * @return Response
     */
    public function show(Room $room)
    {
        $currentUser = Auth::user();

        // Broadcast the UserJoinedRoom event if the user hasn't already joined
        if (!$room->containsUser($currentUser)) {
            $room->addUser($currentUser);

            broadcast(new UserJoinedRoom($room, $currentUser))->toOthers();
        }

        return view('core.room.show')->with(compact('room', 'currentUser'));
    }

    /**
     * Store a new room in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $room = Room::store(Auth::user());

        if ($room) {
            return redirect('/room/'.$room->getKey());
        }
    }
}
