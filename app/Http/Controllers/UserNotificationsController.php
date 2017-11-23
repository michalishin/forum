<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index (User $user) {
        return auth()->user()->unreadNotifications;
    }

    public function destroy (User $user, DatabaseNotification $notification) {
        $this->authorize($notification);
        $notification->markAsRead();
    }
}
