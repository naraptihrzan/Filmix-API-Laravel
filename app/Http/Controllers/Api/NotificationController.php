<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->get();
        return response()->json(['data' => $notifications]);
    }

    public function store(Request $request)
    {
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
        ]);
        return response()->json(['data' => $notification], 201);
    }
}