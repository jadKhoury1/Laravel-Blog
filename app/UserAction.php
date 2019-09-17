<?php


namespace App;


use App\Base\BaseModel;

class UserAction extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'action', 'model', 'object_id', 'data', 'status', 'action_taken_by'
    ];

    /**
     * Get the user that initiated the action
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the user that responded to the action
     */
    public function actionTakenBy()
    {
        return $this->belongsTo('App\User', 'action_taken_by');
    }

}