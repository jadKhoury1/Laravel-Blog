<?php

namespace App\Helpers\File\Operations;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocalFileOperations extends BaseFileOperations
{
    /**
     * The location where the image should be stored
     *
     * @var string
     */
    protected $destination = '/uploads';

   public function move(Request $request, $key)
   {
       if ($request->hasFile($key)) {
           $file = $request->file($key);
           $name = time() . '-' . $file->getClientOriginalName();

           $destinationPath = public_path($this->destination);
           $file->move($destinationPath, $name);
           $this->setFullFilePath(url($this->destination . '/' . $name))
                ->setRelativeFilePath($this->destination . '/' .$name);
           return true;
       }

       return false;
   }


}