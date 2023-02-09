<?php

namespace Wepa\Core\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Wepa\Core\Http\Requests\Backend\FileManagerFileRequest;
use Wepa\Core\Http\Traits\StorageControllerTrait;
use Wepa\Core\Models\File;
use Wepa\Core\Models\FileType;


class FileManagerController extends Controller
{
	use StorageControllerTrait;
	
	
	/**
	 * @param File $file
	 *
	 * @return void
	 */
	public function destroy(File $file): void
	{
		$fileTypeName = strtolower($file->type->name);
		
		if($fileTypeName === 'folder') {
			$file->delete();
		} else if($fileTypeName === 'jpg' or $fileTypeName === 'jpeg' or $fileTypeName === 'png') {
			if($this->storageDelete('file-manager', $file->file)
				and $this->storageDelete('file-manager/thumbnails', $file->file)) {
				$file->delete();
			}
		} else {
			if($this->storageDelete('file-manager', $file->file)) {
				$file->delete();
			}
		}
	}
	
	/**
	 * @param Request $request
	 *
	 * @return array
	 * @throws ValidationException
	 */
	public function folderStore(Request $request): array
	{
		$this->validate($request, [
			'name' => 'string|required',
		]);
		
		$file = File::create(array_merge($request->all(), ['type_id' => 1]));
		
		return $this->index((new Request()), $file->id);
	}
	
	/**
	 * @param Request $request
	 * @param int|null $parentId
	 *
	 * @return array
	 */
	public function index(Request $request, int $parentId = null): array
	{
		$files = File::when($request->search, function($query, $search) {
			$query->where('name', 'LIKE', '%' . $search . '%')
				->orWhere('alt_name', 'LIKE', '%' . $search . '%');
		})
			->where(['parent_id' => $parentId])
			->with('type')
			->orderBy('type_id')
			->orderBy('created_at', 'desc')
			->paginate();
		
		$breadcrumb = $this->breadcrumb($parentId);
		
		return compact(['files', 'breadcrumb', 'parentId']);
	}
	
	/**
	 * @param int|null $id
	 * @param array $parents
	 * @param array $files
	 *
	 * @return array
	 */
	public function breadcrumb(int   $id = null,
	                           array $parents = [],
	                           array $files = []): array
	{
		$root = [];
		$firstLoop = false;
		
		if(!$files) {
			$root = [['id' => null, 'name' => __('core::default.root')]];
			$files = File::get()->toArray();
			$firstLoop = true;
		}
		
		foreach($files as $file) {
			if($id === $file['id']) {
				$parents[] = ['id' => $file['id'], 'name' => $file['name']];
				
				if($parentId = $file['parent_id'])
					$parents = array_merge($this->breadcrumb($parentId,
						$parents,
						$files));
				
				break;
			}
		}
		$result = array_merge($parents, $root);
		
		if($firstLoop) {
			$result = array_reverse($result);
		}
		
		return $result;
	}
	
	/**
	 * @param Request $request
	 * @param File $file
	 * @param string|null $parentId
	 *
	 * @return array
	 * @throws ValidationException
	 */
	public function folderUpdate(Request $request,
	                             File    $file,
	                             string  $parentId = null): array
	{
		$this->validate($request, [
			'name' => 'string|required',
		]);
		
		$file->update($request->all());
		
		return $this->index((new Request()), $parentId);
	}
	
	/**
	 * @param FileManagerFileRequest $request
	 * @param File $file
	 *
	 * @return array
	 */
	public function update(FileManagerFileRequest $request,
	                       File                   $file): array
	{
		$file->update($request->all());
		
		return $this->index($request, $request->parent_id);
	}
	
	/**
	 * @return string
	 */
	public function mimeTypes(): string
	{
		return FileType::select(['extension'])
			->whereNotNull('mime')
			->where('extension', '<>', '.')
			->get()
			->map(function($type) {
				return '.' . $type->extension;
			})->implode(',');
	}
	
	/**
	 * @param $id
	 *
	 * @return void
	 */
	public function show($id)
	{
		//
	}
	
	/**
	 * @param Request $request
	 * @param $parentId
	 *
	 * @return array|Application|ResponseFactory|Response
	 * @throws ValidationException
	 */
	public function store(FileManagerFileRequest $request): Response|array|Application|ResponseFactory
	{
		$file = $request->file('file');
		$type = FileType::where('extension', $file->extension())->first();
		
		if($file->extension() === 'jpg' or $file->extension() === 'jpeg' or $file->extension() === 'png') {
			
			$name = $name ?? time() . '.' . $file->extension();

			if($savedFile = $this->storageImage($file, 'file-manager', $name, $request->max_size)) {
				$data = collect($request->all())->filter()
					->merge([
						'url' => $savedFile['url'],
						'file' => $savedFile['name'],
						'type_id' => $type->id,
					])
					->toArray();
				
				File::create($data);
				
				$this->storageImage($file, 'file-manager/thumbnails', $name, 400);
				
				return $this->index($request, $request->parent_id);
			}
		} else {
			if($savedFile = $this->storageSave($file, 'file-manager')) {
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
