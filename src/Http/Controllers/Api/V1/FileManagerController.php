<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Wepa\Core\Http\Requests\Backend\FileManagerFileRequest;
use Wepa\Core\Http\Traits\StorageControllerTrait;
use Wepa\Core\Models\File;
use Wepa\Core\Models\FileType;

class FileManagerController extends Controller
{
    use StorageControllerTrait;

    public function destroy(File $file): void
    {
        $fileTypeName = strtolower($file->type->name);
        $fmDir = config('core.file-manager.dir', 'file-manager');

        if ($fileTypeName === 'folder') {
            $file->delete();
        } elseif (in_array($fileTypeName, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            if ($this->storageDelete($fmDir, $file->file)
                and $this->storageDelete($fmDir . '/thumbnails', $file->file)) {
                $file->delete();
            }
        } else {
            if ($this->storageDelete($fmDir, $file->file)) {
                $file->delete();
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function folderStore(Request $request): array
    {
        $this->validate($request, ['name' => 'string|required']);

        $file = File::create(array_merge($request->all(), ['type_id' => 1]));

        return $this->index((new Request()), $file->id);
    }

    public function index(Request $request, int $parentId = null): array
    {
        $files = File::when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('alt_name', 'LIKE', '%' . $search . '%');
        })
            ->where(['parent_id' => $parentId])
            ->when($request->extensions, function ($query, $extensions) {
                $query->whereHas('type', function ($query) use ($extensions) {
                    $query->whereIn('extension', $extensions)->orWhere('extension', '.');
                });
            })
            ->with('type')
            ->orderBy('type_id')
            ->orderBy('created_at', 'desc')
            ->paginate(config('core.pagination.filemanager', 50));

        $breadcrumb = $this->breadcrumb($parentId);

        return compact(['files', 'breadcrumb', 'parentId']);
    }

    public function breadcrumb(
        int   $id = null,
        array $parents = [],
        array $files = []
    ): array
    {
        $root = [];
        $firstLoop = false;

        if (!$files) {
            $root = [['id' => null, 'name' => __('core::default.root')]];
            $files = File::get()->toArray();
            $firstLoop = true;
        }

        foreach ($files as $file) {
            if ($id === $file['id']) {
                $parents[] = ['id' => $file['id'], 'name' => $file['name']];

                if ($parentId = $file['parent_id']) {
                    $parents = array_merge($this->breadcrumb($parentId,
                        $parents,
                        $files));
                }

                break;
            }
        }
        $result = array_merge($parents, $root);

        if ($firstLoop) {
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * @param string|null $parentId
     *
     * @throws ValidationException
     */
    public function folderUpdate(
        Request $request,
        File    $file,
        int     $parentId = null
    ): array
    {
        $this->validate($request, [
            'name' => 'string|required',
        ]);

        $file->update($request->all());

        return $this->index((new Request()), $parentId);
    }

    public function update(
        FileManagerFileRequest $request,
        File                   $file
    ): array
    {
        $file->update($request->all());

        return $this->index($request, $request->parent_id);
    }

    public function mimeTypes(Request $request): string
    {
        return FileType::select('extension')
            ->when($request->extensions, function ($query, $extensions) {
                $query->whereIn('extension', $extensions);
            })
            ->whereNotNull('mime')
            ->where('extension', '<>', '.')
            ->get()
            ->map(function ($type) {
                return '.' . $type->extension;
            })->implode(',');
    }

    /**
     * @return void
     */
    public function show($id)
    {
        //
    }

    public function store(FileManagerFileRequest $request): Response|array|Application|ResponseFactory
    {
        $file = $request->file('file');
        $fmDir = config('core.file-manager.dir', 'file-manager');

        if ($file->extension() === 'jpg' or $file->extension() === 'jpeg'

            or $file->extension() === 'png' or $file->extension() === 'webp') {
            $type = FileType::where('extension', 'webp')->first();
            $name = Str::slug($request->name) . '-' . time() . '.webp';

            if ($savedFile = $this->storageImage($file, $fmDir, $name, $request->max_size)) {
                $data = collect($request->all())->filter()
                    ->merge([
                        'url' => $savedFile['name'],
                        'file' => $savedFile['name'],
                        'type_id' => $type->id,
                    ])
                    ->toArray();

                File::create($data);

                $this->storageImage($file, $fmDir . '/thumbnails', $name, 400);

                return $this->index($request, $request->parent_id);
            }
        } else {

            $type = FileType::where('extension', $file->extension())->first();
            $name = Str::slug($request->name) . '-' . time() . '.' . $file->extension();

            if ($savedFile = $this->storageSave($file, $fmDir, $name)) {
                $data = collect($request->all())->filter()
                    ->merge([
                        'url' => $savedFile['url'],
                        'file' => $savedFile['name'],
                        'type_id' => $type->id,
                    ])
                    ->toArray();

                File::create($data);

                return $this->index($request, $request->parent_id);
            }
        }

        return response()->setStatusCode(500);
    }
}
