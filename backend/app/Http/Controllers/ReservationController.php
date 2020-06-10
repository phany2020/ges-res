<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\APIError;
use App\Room;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function createReservation(Request $req){
        $data = $req->only([
            'reservation_date',
            'days',
            'client_name',
            'room_id'
        ]);

        $this->validate($data, [
            'reservation_date' => 'required|date',
            'days' => 'required|integer',
            'client_name'=> 'required|string',
            'room_id' => 'required|integer',
        ]);
        
        $room = Room::find($req->room_id);
        if (!$room) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROOM_NOT_FOUND");
            $apiError->setMessage("Room with id " . $req->room_id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation = new Reservation();
        $reservation->reservation_date = $data['reservation_date'];
        $reservation->days = $data['days'];
        $reservation->client_name = $data['client_name'];
        $reservation->room_id = $data['room_id'];
        
        $reservation->save();
        return response()->json($reservation);
    }


    public function updateReservation(Request $req, $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("RESERVATION_NOT_FOUND");
            $apiError->setMessage("Reservation with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = $req->only([
            'reservation_date',
            'days',
            'client_name',
            'room_id'
        ]);

        $this->validate($data, [
            'reservation_date' => 'required|date',
            'days' => 'required|integer',
            'client_name'=> 'required|string',
            'room_id' => 'required|integer',
        ]);
        
        $room = Room::find($req->room_id);
        if (!$room) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROOM_NOT_FOUND");
            $apiError->setMessage("Room with id " . $req->room_id . " not found");
    
            return response()->json($apiError,404);
        }

        if (null !== $data['reservation_date']) $reservation->reservation_date = $data['reservation_date'];
        if (null !== $data['days']) $reservation->days = $data['days'];
        if (null !== $data['client_name']) $reservation->client_name = $data['client_name'];
        if (null !== $data['room_id']) $reservation->room_id = $data['room_id'];

        $reservation->update();
        return response()->json($reservation);
    }


    public function destroyReservation($id)
    {
        if (!$reservation = Reservation::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("RESERVATION_NOT_FOUND");
            $apiError->setMessage("Reservation with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation->delete();      
        return response()->json();
    }


    public function allReservation(Request $req){
        $data = Reservation::simplePaginate($req->has('limit')?$req->limit:15);
        return response()->json($data);
    }


    public function searchReservation(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',// on cherche q dans la table sur le champ field
            'field' => 'present'
        ]);

        $data = Reservation::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }


}
