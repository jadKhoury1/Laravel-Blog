<?php

namespace App\Base;


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


    public function __construct(Request $request, BaseResponse $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function callAction($method, $parameters)
    {
        $this->user = Auth::user();
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
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get Authenticated user instance
     *
     * @return $this
     */
    protected function getAuthUser()
    {
        $this->user =  $this->guard()->user();
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
     * @return bool
     */
    protected function checkIfUserHasPendingAction($action, $model = 'App\Post')
    {
        if ($this->user->role_key === 'admin') {
            return true;
        }

        $userAction = UserAction::where('user_id', $this->user->id)
            ->where('action', $action)
            ->first();

        // check if user as a similar pending action
        if ($userAction !== null && $userAction->action == $action
            && $userAction->model == $model && $userAction->status == UserAction::STATUS_PENDING) {
            $this->errorMessage = 'You already have a pending transaction';
            return false;
        }

        return true;
    }
}