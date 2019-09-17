<?php


namespace App\Http\Controllers\Api\Auth;

use App\Base\BaseController;


class LoginController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application
    |
    */

    /**
     * Get the username to be used
     *
     * @var string
     */
    private $username = 'email';

    /**
     * Get the username rules that should be applied on validation
     *
     * @var string
     */
    private $usernameRules = 'required|string|email|max:191';

    /**
     * Method that handles user authentication
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        if ($this->makeValidation() === false) {
            return $this->response->statusFail(['message' => $this->errorMessage]);
        }

        if ($this->attemptLogin() === false) {
            return $this->response->statusFail(['message' => 'Invalid Credentials']);
        }

        $token = $this->getAuthUser()->generateToken();

        return $this->response->statusOk(['user' => $this->user, 'token' => $token]);

    }

    /**
     * Set Login Validation Rules
     *
     * @return array
     */
    protected function setValidationRules()
    {
        return [
            'email'    => $this->usernameRules,
            'password' => 'required|string'
        ];
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     *
     * @return array
     */
    private function credentials()
    {
        return $this->request->only($this->username, 'password');
    }

    /**
     * Attempt to log the user into the application.
     *
     *
     * @return bool
     */
    private function attemptLogin()
    {
        return $this->guard()->attempt($this->credentials());
    }



    /**
     * Generate new token for user
     *
     * @return string
     */
    private function generateToken()
    {
        return $this->user->createToken('MyApp')->accessToken;
    }

}

