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

    public function paginated(Request $request)
    {
        $perPage = $request->query('per_page', 1);

        $notifications = Notification::latest() // otomatis urut created_at desc
        ->paginate($perPage);

        return response()->json([
        'data'         => $notifications->items(),
        'current_page' => $notifications->currentPage(),
        'total'        => $notifications->total(),
        'has_more'     => $notifications->hasMorePages(),
        ]);
    }


}
