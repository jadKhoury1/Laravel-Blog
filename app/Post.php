<?php


namespace App;


use App\Base\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'description', 'image', 'active'
    ];

    /**
     * Get the user that owns the post
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get all of the post's actions
     */
    public function actions()
    {
        return $this->morphMany('App\UserAction', 'item');
    }

}