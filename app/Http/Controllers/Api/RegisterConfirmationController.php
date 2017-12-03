<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index (Request $request) {
        User::where('confirmation_token', $request->token)
            ->firstOrFail()
            ->confirm();

        return redirect(route('threads.index'))
            ->with('flash', 'You account is now confirmed! You may post to the forum.');
    }
}
