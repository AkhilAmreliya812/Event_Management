<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventBooking extends Controller {
    
    public function bookEvent($id) {
        return view('user.bookEvent',['id' => $id]);
    }

}
