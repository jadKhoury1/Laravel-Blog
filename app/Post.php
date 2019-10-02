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
    public function action()
    {
        return $this->morphOne('App\UserAction', 'item')
            ->where('status', UserAction::STATUS_PENDING)
            ->latest();
    }

    /**
     * Get Image Path Attribute
     */
    public function getImagePathAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL) === false) {
            return url($this->image);
        }

        return $this->image;
    }

    /**
     * Scope that fetches all posts and does the required filtering
     */
    public function scopeGetAll($query, $user)
    {
        // If user iis logged in and his role is admin get the latest action related to the post
        // And get the user associated with each post
        if ($user && $user->role_key === 'admin') {
            $query->with(['action.user' => function ($query) {
                $query->select(['id', 'name', 'email']);
            }]);
        } else {
            $query->where('active', 1);
            if ($user) {
                $query->orWhere('user_id', $user->id)
                    ->with(['action' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }]);
            }
        }
    }

    /**
     * Scope that fetches post details and does the required filtering
     */
    public function scopeGetDetails($query, $user)
    {
        if ($user) {
            $query->with(['action' => function ($query) use ($user) {
                if ($user->role_key === 'admin') {
                    $query->with(['user' => function ($query) {
                        $query->select(['id', 'name', 'email']);
                    }]);
                }
            }]);
        }

    }

}