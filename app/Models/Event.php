<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_title', 'start_date', 'end_date', 'price', 'category', 'event_image', 'document', 'description', 'orderBy'
    ];

    public static function saveEvent($data) {
        return self::create($data);
    }
}
