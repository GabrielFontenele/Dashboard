<?php

namespace App\Http\Controllers;

use App\Helpers\RandomGenerator;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::latest()->paginate(5);
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'document'      => 'required|size:10',
            'birthday'   => 'required|date',
            'plan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if(!in_array($request->plan, ['basic' ,'pro', 'preminum'])) {
            return response()->json('Invalid plan', 422);
        }

        if(User::where('document', $request->document)->exists()){
            return response()->json("Document already exists", 422);
        }

        $inputs = $validator->safe()->all();
        $inputs['card'] = self::newRandomCard();

        $reservation = User::create($inputs);

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
        return response()->json(User::findOrFail($id), 200);
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
        $validator = Validator::make($request->all(), [
            'name'    => '',
            'document'      => 'size:10',
            'birthday'   => 'date',
            'plan'   => '',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if($request->plan && !in_array($request->plan, ['basic' ,'pro', 'preminum'])) {
            return response()->json('Invalid plan', 422);
        }

        if($request->document && User::where('document', $request->document)->exists()){
            return response()->json("Document already exists", 422);
        }

        $user = User::findOrFail($id);
        $user->fill($validator->safe()->all());
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response('',204);
    }

    private function newRandomCard(){
        $card = RandomGenerator::instance()->randomNumberString(10); 
        $isUsed =  User::where('card', $card)->first();
        if ($isUsed) {
            return $this->newRandomCard();
        }
        return $card;
    }
}
