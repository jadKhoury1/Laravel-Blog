<?php

namespace  App\Http\Controllers\Api;

use App\Base\BaseController;
use Illuminate\Support\Facades\Auth;

class TestController extends BaseController
{
    public function test()
    {
        return $this->response->statusOk(['user' => Auth::user()]);
    }
}