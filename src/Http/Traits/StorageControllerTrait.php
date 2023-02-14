<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Wepa\Core\Http\Helpers\InterventionImageHelper;

trait StorageControllerTrait
{
    /**
     * @param  string  $path
     * @return string
     */
    protected function basePath(string $path): string
    {
        $root = env('DO_ROOT', '');
        $root = preg_replace(['/^\//', '/\/$/'], '', $root);
        $path = preg_replace(['/^\//', '/\/$/'], '', $path);

        return $root.'/'.$path;
    }

    /**
     * @param  string  $path
     * @param  string  $fileName
     * @return bool
     */
    protected function storageDelete(string $path, string $fileName): bool
    {
        $path = preg_replace(['/^\//', '/\/$/'], '', $path);
        if (Storage::disk($this->fileSystemDisk())->exists($path.'/'.$fileName)) {
            return Storage::disk($this->fileSystemDisk())->delete($path.'/'.$fileName);
        }

        return false;
    }

    /**
     * @return string
     */
    protected function fileSystemDisk(): string
    {
        return config('filesystems.default', 'local');
    }

    /**
     * @param  UploadedFile  $file
     * @param  string  $path
     * @param  string|null  $name
     * @param  int  $maxSize
     * @param  string  $options
     * @return array|false
     */
    protected function storageImage(UploadedFile $file,
                                    string $path,
                                    string $name = null,
                                    int $maxSize = 0,
                                    string $options = 'public'): bool|array
    {
        $name = $name ?? time().'.'.$file->extension();

        if ($file->extension() === 'jpg' or $file->extension() === 'png' or $file->extension() === 'jpeg') {
            if ($maxSize > 0) {
                $image = new InterventionImageHelper($file);
                $image->resize($maxSize);
            } else {
                $image = $file;
            }

            if ($storage = Storage::disk($this->fileSystemDisk())
                ->putFileAs($path, $maxSize > 0 ? $image->toStorage() : $file, $name, $options)) {
                if ($maxSize > 0) {
                    $image->destroy();
                }

                $url = Storage::disk($this->fileSystemDisk())->url($storage);
                $url = str_replace(['\\', '%5C'], '/', $url);

                return [
                    'name' => $name,
                    'url' => $url,
                    'extension' => $file->extension(),
                ];
            }
        }

        return false;
    }

    /**
     * @param  UploadedFile  $file
     * @param  string  $path
     * @param  string|null  $name
     * @param  string  $options
     * @return array|false
     */
    protected function storageSave(UploadedFile $file,
                                   string $path,
                                   string $name = null,
                                   string $options = 'public'): bool|array
    {
        $name = $name ?? time().'.'.$file->extension();

        if ($storage = Storage::disk($this->fileSystemDisk())->putFileAs($path, $file, $name, $options)) {
            $url = Storage::disk($this->fileSystemDisk())->url($storage);
            $url = str_replace(['\\', '%5C'], '/', $url);

            return [
                'name' => $name,
                'url' => $url,
                'extension' => $file->extension(),
            ];
        }

        return false;
    }
}
