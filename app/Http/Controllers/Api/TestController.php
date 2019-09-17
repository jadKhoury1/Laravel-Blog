<?php

namespace  App\Http\Controllers\Api;

use App\Base\BaseController;
use Illuminate\Support\Facades\Auth;

class TestController extends BaseController
{
    public function test()
    {
        $this->getAuthUser()->getUserTokenInstance()->revoke();

        return $this->response->statusOk([]);
    }
}