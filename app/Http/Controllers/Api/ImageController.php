<?php

namespace  App\Http\Controllers\Api;

use App\Base\BaseController;
use Illuminate\Http\Request;
use App\Helpers\File\FileHelper;

class ImageController extends  BaseController
{
    /**
     * Stores Full Image Path
     *
     * @var string
     */
    private $fullImagePath;

    /**
     * Stores Image relative Path
     *
     * @var string
     */
    private $relativePath;


    /**
     * Upload Image and return Image Path
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {

        if ($this->makeValidation() === false) {
            return $this->response->statusFail([ 'message' => $this->errorMessage ]);
        }

        if ($this->moveImage() === false) {
            return $this->response->statusFail(['message' => 'Image Could not be uploaded']);
        }

        return $this->response->statusOk(['full_path' => $this->fullImagePath, 'relative_path' => $this->relativePath]);

    }

    /**
     * Validation rules fir Image Upload
     *
     *
     * @return mixed
     */
    public function setValidationRules()
    {
        return [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1500'
        ];
    }

    /**
     * Move The Image to its destination
     *
     * @return bool
     */
    private function moveImage()
    {

        $fileOperation = new FileHelper();

        if ($fileOperation->move($this->request, 'file') === false) {
            return false;
        }
        $this->fullImagePath = $fileOperation->getFullFilePath();
        $this->relativePath = $fileOperation->getRelativeFilePath();

        return true;

    }


}