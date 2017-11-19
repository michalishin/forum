<?php

use Illuminate\Database\Seeder;

class ThreadsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Channel::class,5)->create()->map(function ($channel) {
            factory(\App\Thread::class,10)->create([
                'channel_id' => $channel
            ])->map(function ($thread) {
                factory(App\Reply::class,20)->create([
                    'thread_id' => $thread
                ]);
            });
        });
    }
}
