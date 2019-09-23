<?php

namespace App\Base;


use App\Post;
use App\User;
use App\UserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as LaravelBaseController;


class BaseController extends LaravelBaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Stores Error Messages
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Array that stores validation rules
     *
     * @var array
     */
    protected $rules;


    /**
     * Stores the Request Object
     *
     * @var Request
     */
    protected $request;

    /**
     * Stores the response object
     *
     * @var BaseResponse
     */
    protected $response;

    /**
     * Stores auth user data
     *
     * @var User
     */
    protected $user;

    /**
     * Stores post data
     *
     * @var Post
     */
    protected $post;


    public function __construct(Request $request, BaseResponse $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function callAction($method, $parameters)
    {
        $this->user = $this->guard()->user();
        return parent::callAction($method, $parameters);
    }

    /**
     * Apply validation rules on request params
     *
     * @return bool
     */
    protected function makeValidation()
    {
        $validation = Validator::make($this->request->all(), $this->setValidationRules(), $this->setCustomErrorMessages());

        if ($validation->fails()) {
            $this->errorMessage = $validation->errors()->first();
            return false;
        }

        return true;
    }

    /**
     * Set Custom Error Messages for validation rules
     *
     * @return array
     */
    protected function setCustomErrorMessages()
    {
        return [];
    }

    /**
     * Set Validation Rules
     *
     * @return array
     */
    protected function setValidationRules()
    {
        return [];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @param bool $api
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard($api = true)
    {
        return $api == true ? Auth::guard('api') : Auth::guard();
    }

    /**
     * Get Authenticated user instance
     *
     * @return $this
     */
    protected function getAuthUser()
    {
        $this->user =  $this->guard(false)->user();
        $this->user->load('role');
        return $this;
    }


    /**
     * Get User Token Instance
     *
     * @return \Laravel\Passport\Token|null
     */
    protected function getUserTokenInstance()
    {
        return $this->user->token();
    }

    /**
     * Tis method will check if the user has already a pending transaction
     *
     * @param $action
     * @param string $model
     * @param int $id
     *
     * @return bool
     */
    protected function checkIfUserHasPendingAction($action, $model = 'posts', $id = null)
    {

        $query = UserAction::query()
            ->where('status', UserAction::STATUS_PENDING)
            ->where('item_type', $model);

        if ($this->user->role_key !== 'admin') {
            $query->where('user_id', $this->user->id);
            if ($action === UserAction::ACTION_ADD) {
                $query->where('action', $action);
            }
        }

        if ($action !== UserAction::ACTION_ADD) {
            $query->where('item_id', $id);
        }


        $userAction = $query->first();

        // check if user as a similar pending action
        if ($userAction !== null ) {
            $this->errorMessage = 'You already have a pending transaction';
            return false;
        }

        return true;
    }

    /**
     * Check if Post Id is valid
     *
     * @param $id
     *
     * @return bool
     */
    protected function checkIfPostExists($id)
    {
        $this->post = Post::query()->where('id', $id)->first();

        if ($this->post === null) {
            $this->errorMessage = 'Invalid ID';
            return false;
        }

        return true;
    }
}