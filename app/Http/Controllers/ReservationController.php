<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Meetingroom;
use App\Exports\MeetingRoomExport;
use App\Exports\VehicleExport;
use Maatwebsite\Excel\Facades\Excel;


class ReservationController extends Controller{
    
    public function ReservationMeetingRoom()
    {
        $meetingroom = Meetingroom::all();

        return view('reservation.meetingroom', [
            'meetingroom' => $meetingroom
        ]);
    }

    public function ReservationVehicle()
    {
        $vehicle = Vehicle::all();

        return view('reservation.vehicle', [
            'vehicle' => $vehicle
        ]);
    }

    public function ExportMeetingRoom(Request $request)
    {
        $request->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $dateStart = $request->date_start;
        $dateEnd = $request->date_end;

        return Excel::download(new MeetingRoomExport($dateStart, $dateEnd), 'meeting_room_data.xlsx');
    }

    public function ExportVehicle(Request $request)
    {
        $request->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $dateStart = $request->date_start;
        $dateEnd = $request->date_end;

        return Excel::download(new VehicleExport($dateStart, $dateEnd), 'Reservation_vehicle_data.xlsx');
    }

   

}