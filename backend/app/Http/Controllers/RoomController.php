<?php

namespace App\Http\Controllers;

use App\APIError;
use App\Hotel;
use App\Room;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function createChambre(Request $req){
        $data = $req->only([
            'hotel_id',
            'room_number',
            'room_state',
            'status',
            'amount'
        ]);

        $this->validate($data, [
            'hotel_id' => 'required|integer',
            'room_number'=> 'required|integer',
            'room_state' => 'in :SIMPLE,VIP',
            'status' => 'in :FREE,TAKEN',
            'amount'=> 'required'
        ]);

        $hotel = Hotel::find($req->hotel_id);
        if (!$hotel) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $req->hotel_id . " not found");
    
            return response()->json($apiError,404);
        }

        $hotel = Hotel::find($req->hotel_id);
        if($hotel['room_total_number'] < $req->room_number){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM-NUMBER_NOT_FOUND");
            $apiError->setMessage("Room number with id " . $req->room_number . " incorrect");
    
            return response()->json($apiError,404);
        }

       /* $room = Room::find($req->room_number);
        $hotel = Room::find($req->hotel_id)->whereRoomNumber('$req_number');
        if(isset($room) && isset($hotel)){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM_ALREADY_EXISTING");
            $apiError->setMessage("Room already existing in db with room number " . $req->room_number ." and hotel id " . $req->hotel_id);
    
            return response()->json($apiError,404);
        }*/
        
        $room = new Room();
        $room->hotel_id = $data['hotel_id'];
        $room->room_number = $data['room_number'];
        $room->room_state = $data['room_state'];
        $room->status = $data['status'];
        $room->amount = $data['amount'];
        
        $room->save();
        return response()->json($room);
    }


    public function updateChambre(Request $req, $id)
    {
        $room = Room::find($id);
        if (!$room) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROOM_NOT_FOUND");
            $apiError->setMessage("Room with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = $req->only([
            'hotel_id',
            'room_number',
            'room_state',
            'status',
            'amount'
        ]);

        $this->validate($data, [
            'hotel_id' => 'required|integer',
            'room_number'=> 'required|integer',
            'room_state' => 'in :SIMPLE,VIP',
            'status' => 'in :FREE,TAKEN',
            'amount'=> 'required'
        ]);

        $hotel = Hotel::find($req->hotel_id);
        if (!$hotel) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $req->hotel_id . " not found");
    
            return response()->json($apiError,404);
        }

        $hotel = Hotel::find($req->hotel_id);
        if($hotel['room_total_number'] <= $req->room_number){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM-NUMBER_NOT_FOUND");
            $apiError->setMessage("Room number with id " . $req->room_number . " incorrect");
    
            return response()->json($apiError,404);
        } 

        /*$room = Room::find($req->room_number);
        $hotel = Room::find($req->hotel_id)->whereRoomNumber('$req_number');
        if(isset($room) && isset($hotel) && $room->status == 'TAKEN'){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM_ALREADY_TAKEN");
            $apiError->setMessage("Room already taken with room number " . $req->room_number ." and hotel id " . $req->hotel_id);
    
            return response()->json($apiError,404);
        }*/

        if (null !== $data['hotel_id']) $room->hotel_id = $data['hotel_id'];
        if (null !== $data['room_number']) $room->room_number = $data['room_number'];
        if (null !== $data['room_state']) $room->room_state = $data['room_state'];
        if (null !== $data['status']) $room->status = $data['status'];
        if (null !== $data['amount']) $room->amount = $data['amount'];

        $room->update();
        return response()->json($room);
    }


    public function destroyChambre($id)
    {
        if (!$room = Room::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("Room_NOT_FOUND");
            $apiError->setMessage("Room with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $room->delete();      
        return response()->json();
    }


    public function allChambre(Request $req){
        $data = Room::simplePaginate($req->has('limit')?$req->limit:15);
        return response()->json($data);
    }


    public function searchChambre(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',// on cherche q dans la table sur le champ field
            'field' => 'present'
        ]);

        $data = Room::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }



}
