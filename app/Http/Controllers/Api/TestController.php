<?php

namespace  App\Http\Controllers\Api;

use App\Base\BaseController;

class TestController extends BaseController
{
    public function test()
    {
        return $this->response->statusOk();
    }
}