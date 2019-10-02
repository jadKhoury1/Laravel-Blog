<?php

namespace App\Helpers\File\Operations;

use Illuminate\Http\Request;

interface FileOperationsInterface
{
    public function move(Request $request, $key);
}