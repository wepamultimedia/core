<?php

namespace Wepa\Core\Http\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;

class InterventionImageHelper
{
    public string $savePath;

    private UploadedFile $file;

    private bool $hasModified = false;

    private InterventionImage $image;

    private string $realPath;

    private bool $saved = false;

    private string $format;

    private int $quality;

    public function __construct(UploadedFile $file, int $quality = 80)
    {
        $this->file = $file;
        $this->image = Image::make($file);
        $this->format = $file->extension();
        $this->quality = $quality;
        $this->savePath = storage_path('app/public/'. time() . '.' . $file->extension());
        $this->realPath = $file->getRealPath();

        return $this;
    }

    public function make(): InterventionImage
    {
        return $this->image;
    }

    public function extension(): string
    {
        return $this->file->extension();
    }

    public function getRealPath(): string
    {
        if ($this->saved) {
            return $this->savePath;
        } elseif ($this->hasModified) {
            $this->save();

            return $this->savePath;
        }

        return $this->realPath;
    }

    public function save(): static
    {
        $this->image->encode($this->format, $this->quality);
        $this->savePath = storage_path('app/public/'. time() . '.' . $this->format);
        $this->image->save($this->savePath, $this->quality, $this->format);
        $this->saved = true;

        return $this;
    }

    public function destroy(): void
    {
        if (file_exists($this->getRealPath())) {
            unlink($this->getRealPath());
        }
    }

    public function asJpg(): static
    {
        $this->format = 'jpg';
    
        if($this->file->extension() !== 'jpg'){
            $this->hasModified = true;
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function asWebp(): static
    {
        $this->format = 'WebP';
        
        if($this->file->extension() !== 'WebP'){
            $this->hasModified = true;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function asPng(): static
    {
        $this->format = 'png';
    
        if($this->file->extension() !== 'png'){
            $this->hasModified = true;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function resize(int $maxSize): static
    {
        $this->image->resize($maxSize, $maxSize, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $this->hasModified = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function fit(int $maxSize): static
    {
        $this->image->fit($maxSize, $maxSize, function ($constraint) {
            $constraint->upsize();
        });
        $this->hasModified = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function square(int $maxSize): static
    {
        $canvas = Image::canvas($maxSize, $maxSize);
        $canvas->insert($this->image, 'center');
        $this->image = $canvas;
        $this->hasModified = true;

        return $this;
    }

    public function toStorage(): File
    {
        return new File($this->getRealPath());
    }
}
