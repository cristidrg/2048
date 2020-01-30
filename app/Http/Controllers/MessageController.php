<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\BroadcastMessageCreation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'content'=>'required',
        ]);
        $message = Message::create(request(['content']));
        
        BroadcastMessageCreation::dispatch($message);
    }
}
