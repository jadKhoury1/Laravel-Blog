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
       Log::debug('This should be reached');
       Log::debug('key: ' . $key);
       if ($request->hasFile($key)) {
           $image = $request->file($key);
           $name = time() . '-' . $image->getClientOriginalName();
           Log::debug('name: ' . $name);
           $destinationPath = public_path($this->destination);
           $image->move($destinationPath, $name);
           $this->setFullFilePath(url($this->destination . '/' . $name))
                ->setRelativeFilePath($this->destination . '/' .$name);
           return true;
       }

       return false;
   }


}