<?php

namespace App\Helpers\File;

use Illuminate\Http\Request;
use App\Helpers\File\Operations\AwsS3FileOperations;
use App\Helpers\File\Operations\LocalFileOperations;


class FileHelper
{
    private $strategy;

    public function __construct()
    {
        if (env('AWS_S3_ENABLED', false)) {
            $this->strategy = new AwsS3FileOperations();
        } else {
            $this->strategy = new LocalFileOperations();
        }
    }

    public function move(Request $request, $key)
    {
        $this->strategy->move($request, $key);
    }

    public function getFullFilePath()
    {
        return $this->strategy->getFullFilePath();
    }

    public function getRelativeFilePath()
    {
        return $this->strategy->getRelativeFilePath();
    }
}