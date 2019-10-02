<?php

namespace App\Helpers\File\Operations;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;



class AwsS3FileOperations extends BaseFileOperations
{
    /**
     * Move File to location
     *
     * @param Request $request
     * @param $key
     *
     * @return null|string
     */
    public function move(Request $request, $key)
    {
        if ($request->has($key)) {
            $file = $request->file($key);
            $name = time() . '-' . $file->getClientOriginalName();
            $uploadedFile = $this->uploadFileToBucket($file, $name);

             if ($uploadedFile === false) {
                return false;
             }
             $this->setFullFilePath($uploadedFile)->setRelativeFilePath($uploadedFile);
             return true;
        }

        return false;
    }


    /**
     * Upload File AWS S3 Bucket
     *
     * @param array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|void|null $file
     * @param string $name
     *
     * @return string
     */
    private function uploadFileToBucket($file, $name)
    {
        $s3  = new S3Client([
            'version' => 'latest',
            'region'  => 'us-west-2'
        ]);

        $bucket = env('S3_BUCKET_NAME');

        try {
            $upload = $s3->upload($bucket, "photos/{$name}", $file->openFile('rb'), 'public-read');
            $uploadedFile = $upload->get('ObjectURL');
        }
        catch (AwsException $e) {
            Log::error('AWS FILE UPLOAD EXCEPTION: ' .  $e->getMessage());
            return false;
        }
        catch (\Exception $e) {
            Log::error('AWS FILE UPLOAD EXCEPTION: ' .  $e->getMessage());
            return false;
        }

        return $uploadedFile;
    }
}