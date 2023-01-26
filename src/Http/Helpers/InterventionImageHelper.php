<?php

namespace Wepa\Core\Http\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;

class InterventionImageHelper
{
    private UploadedFile $file;

    private InterventionImage $image;

    public string $savePath;

    private string $realPath;

    private bool $saved = false;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->image = Image::make($file);
        $this->savePath = storage_path('app/public/'.time().'.'.$file->extension());
        $this->realPath = $this->image->basePath();

        return $this;
    }

    public function make(): InterventionImage
    {
        return $this->image;
    }

    /**
     * @param  int  $maxSize
     * @return void
     */
    public function resize(int $maxSize): void
    {
        $this->image->resize($maxSize, $maxSize, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($this->savePath);

        $this->saved = true;
    }

    /**
     * @return void
     */
    public function destroy(): void
    {
        if (file_exists($this->getRealPath())) {
            unlink($this->getRealPath());
        }
    }

    /**
     * @return string
     */
    public function extension(): string
    {
        return $this->file->extension();
    }

    /**
     * @return string
     */
    public function getRealPath(): string
    {
        if ($this->saved) {
            return $this->savePath;
        }

        return $this->realPath;
    }

    /**
     * @return File
     */
    public function toStorage(): File
    {
        return new File($this->getRealPath());
    }
}
