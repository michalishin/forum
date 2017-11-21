<?php


namespace App\Filters;


use App\User;
use Illuminate\Http\Request;

class ThreadsFilters extends Filters
{

    protected $filters = ['by', 'popularity', 'unanswered'];

    /**
     * Filter the query by username
     * @param string $username
     */
    public function by(string $username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $this->builder->where('user_id', $user->id);
    }

    public function popularity(int $direction) {
        $this->builder->getQuery()->orders = [];
        $this->builder->orderBy('replies_count', $direction ? 'DESC' : 'ASC');
    }

    public function unanswered(int $direction) {
        $this->builder->whereDoesntHave('replies');
    }
}