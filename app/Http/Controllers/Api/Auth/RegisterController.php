<?php

namespace App\Http\Controllers\Api\Auth;

use App\Base\BaseResponse;
use App\Base\BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation.
    |
    */
    public function __construct(Request $request, BaseResponse $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Method that handles user registration
     */
    public function register()
    {
        if ($this->makeValidation() === false) {
            return $this->response->statusFail(['message' => $this->errorMessage]);
        }

        $token = $this->create()->createUserToken();

        return $this->response->statusOk(['user' => $this->user, 'token' => $token->accessToken]);
    }

    /**
     * Set Registration validation rules
     *
     * @return array
     */
    protected function setValidationRules()
    {
       return [
           'email'    => 'required|string|email|max:191|unique:users',
           'name'     => 'required|string|min:3|max:191',
           'password' => 'required|min :8|max: 60|regex :"/^(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,60}$/"|confirmed',
       ];
    }

    /**
     * Set Custom Registration error messages
     *
     * @return array
     */
    protected function setCustomErrorMessages()
    {
        return [
            'password.regex' => 'Password must at least contain one small letter, one big letter '
                . ',one digit and one special character',
        ];
    }

    /**
     * Create User Record
     *
     * @param array $data
     *
     * @return $this
     */
    private function create()
    {
        $data = $this->request->all();

        $this->user = User::query()->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return $this;
    }

    /**
     * create user token
     *
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    private function createUserToken()
    {
        return $this->user->createToken('MyApp');
    }


}