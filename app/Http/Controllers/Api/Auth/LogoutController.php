<?php


namespace App\Http\Controllers\Api\Auth;

use App\Base\BaseController;


class LogoutController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Logout controller
    |--------------------------------------------------------------------------
    |
    | This controller handles logging out users from the application by revoking their token
    |
    */
    public function logout()
    {
        $this->getAuthUser()->getUserTokenInstance()->revoke();
        return $this->response->statusOk();
    }
}