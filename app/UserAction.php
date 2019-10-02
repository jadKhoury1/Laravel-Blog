<?php


namespace App;


use App\Base\BaseModel;
use Illuminate\Support\Str;

class UserAction extends BaseModel
{

    /**
     * Store User action constants
     */
    const ACTION_ADD    = 'ADD';
    const ACTION_EDIT   = 'EDIT';
    const ACTION_DELETE = 'DELETE';

    /**
     * Stores User action Status constants
     */
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = -1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'action', 'item_id', 'item_type', 'data', 'status', 'action_taken_by'
    ];

    /**
     * Append extra attributes to the User array
     *
     * @var array
     */
    protected $appends = [
        'transaction'
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

    /**
     * Get the owning commentable model.
     */
    public function item()
    {
        return $this->morphTo();
    }

    /**
     * Get the transaction phrase associated with the user action
     */
    public function getTransactionAttribute()
    {
        // The bellow code will change posts to Post or articles to Article
        $itemName = ucfirst(Str::singular($this->item_type));

        if ($this->action === UserAction::ACTION_ADD) {
            return "{$itemName} Creation Pending Transaction";
        }

        if ($this->action === UserAction::ACTION_EDIT) {
            return "{$itemName}  Editing Pending Transaction";
        }

        if ($this->action === UserAction::ACTION_DELETE) {
            return "{$itemName} Deletion Pending Transaction";
        }
    }

    /**
     * Alter Image field
     */
    public function getDataAttribute($value)
    {
        if ($value === null) {
            return $value;
        }

        $data = json_decode($value, true);
        $data['image_path'] = url($data['image']);

        return json_encode($data);

    }

    /**
     * Scope that fetches pending transactions
     */
    public function scopeGetPending($query, $user, $action, $model, $id)
    {
        $query->where('status', UserAction::STATUS_PENDING)
             ->where('item_type', $model);

        if ($user->role_key !== 'admin') {
            $query->where('user_id', $user->id);
            if ($action === self::ACTION_ADD) {
                $query->where('action', $action);
            }
        }

        if ($action !== self::ACTION_ADD) {
            $query->where('item_id', $id);
        }
    }


}