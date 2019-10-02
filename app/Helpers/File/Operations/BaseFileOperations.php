<?php


namespace App\Helpers\File\Operations;


abstract class BaseFileOperations implements FileOperationsInterface
{
    private $fullFilePath;
    private $relativeFilePath;


    /**
     * @return string
     */
    public function getFullFilePath()
    {
        return $this->fullFilePath;
    }

    /**
     * @param string $fullFilePath
     * @return $this
     */
    protected function setFullFilePath($fullFilePath)
    {
        $this->fullFilePath = $fullFilePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelativeFilePath()
    {
        return $this->relativeFilePath;
    }

    /**
     * @param $relativeFilePath
     * @return $this
     */
    protected function setRelativeFilePath($relativeFilePath)
    {
        $this->relativeFilePath = $relativeFilePath;
        return$this;
    }


}