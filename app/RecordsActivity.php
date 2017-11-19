<?php
/**
 * Created by PhpStorm.
 * User: vmikhalishinamd
 * Date: 25.10.17
 * Time: 0:27
 */

namespace App;


trait RecordsActivity
{
    protected static function bootRecordsActivity () {
        static::deleting(function ($model) {
            $model->activity()->delete();
        });
        if (auth()->guest()) return;
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord () {
        return ['created'];
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id()
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        $type = mb_strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}