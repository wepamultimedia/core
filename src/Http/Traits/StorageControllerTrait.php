<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Wepa\Core\Http\Helpers\InterventionImageHelper;

trait StorageControllerTrait
{
    protected function basePath(string $path): string
    {
        $root = env('DO_ROOT', '');
        $root = preg_replace(['/^\//', '/\/$/'], '', $root);
        $path = preg_replace(['/^\//', '/\/$/'], '', $path);

        return $root.'/'.$path;
    }

    protected function storageDelete(string $path, string $fileName): bool
    {
        $path = preg_replace(['/^\//', '/\/$/'], '', $path);
        if (Storage::disk($this->fileSystemDisk())->exists($path.'/'.$fileName)) {
            return Storage::disk($this->fileSystemDisk())->delete($path.'/'.$fileName);
        }

        return false;
    }

    protected function fileSystemDisk(): string
    {
        return config('filesystems.default', 'local') === 'local' ? 'public' : config('filesystems.default');
    }

    /**
     * @return array|false
     */
    protected function storageImage(UploadedFile $file,
                                    string $path,
                                    string $name = null,
                                    int $maxSize = 0,
                                    string $options = 'public'): bool|array
    {
        $name = $name ?? time().'.webp';

        if ($file->extension() === 'jpg' or $file->extension() === 'png' or $file->extension() === 'jpeg' or $file->extension() === 'webp') {
            $image = new InterventionImageHelper($file);
            $image->asWebp();
            if ($maxSize > 0) {
                $image->resize($maxSize);
            }

            if ($storage = Storage::disk($this->fileSystemDisk())
                ->putFileAs($path, $maxSize > 0 ? $image->toStorage() : $file, $name, $options)) {
                $image->destroy();

                $url = Storage::disk($this->fileSystemDisk())->url($storage);
                $url = str_replace(['\\', '%5C'], '/', $url);

                return [
                    'name' => $name,
                    'url' => $url,
                    'extension' => 'webp',
                ];
            }
        }

        return false;
    }

    /**
     * @return array|false
     */
    protected function storageSave(UploadedFile $file,
                                   string $path,
                                   string $name = null,
                                   string $options = 'public'): bool|array
    {
        $name = $name ? $name.'.'.$file->extension() : time().'.'.$file->extension();

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
