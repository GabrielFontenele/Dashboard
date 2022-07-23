<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get resources
        $reservations = Reservation::latest()->paginate(5);
        return response()->json($reservations, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'start_time'    => 'required|date|after:today',
            'end_time'      => 'date|after:start_time',
            'user_id'       => 'required|exists:users,id',
            'description'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if(Reservation::where('start_time', $request->start_time)->exists()){
            return response()->json($validator->errors(), 422);
        }

        $inputs = $validator->safe()->all();
        $inputs['status'] = 'pending';

        $reservation = Reservation::create($inputs);


        return response()->json($reservation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Reservation::findOrFail($id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'start_time'    => 'date|after:today',
            'end_time'      => 'nullable|date|after:start_time',
            'user_id'       => 'exists:users,id',
            'description'   => '',
            'status'        => ''
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->status && !in_array($request->status, ['cancelled' ,'completed', 'pending'])) {
            return response()->json('Invalid status', 422);
        }

        if(Reservation::where('start_time', $request->start_time)->exists()){
            return response()->json($validator->errors(), 422);
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->fill($validator->safe()->all());
        $reservation->save();

        return response()->json($reservation, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Reservation::destroy($id);
        return response('',204);
    }
}
