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
     * Append extra attributes to the Post array
     *
     * @var array
     */
    protected $appends = [
        'image_path'
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

    /**
     * Get latest pending action related to the blog
     */
    public function action(){
        return $this->morphOne('App\UserAction', 'item')
            ->where('status', UserAction::STATUS_PENDING)
            ->latest();
    }

    /**
     * Get Image Path Attribute
     */
    public function getImagePathAttribute() {
        return url($this->image);
    }

}