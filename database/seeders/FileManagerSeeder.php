<?php

namespace Wepa\Core\Database\seeders;


use Illuminate\Database\Seeder;
use Wepa\Core\Models\File;
use Wepa\Core\Models\FileType;


class FileManagerSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$types = [
			[
				'name'      => 'folder',
				'extension' => '.',
				'icon'      => 'folder',
				'mime'      => null,
			],
			[
				'name'      => 'Word',
				'extension' => 'doc',
				'icon'      => 'document-text',
				'mime'      => 'application/msword',
			],
			[
				'name'      => 'Word',
				'extension' => 'docx',
				'icon'      => 'document-text',
				'mime'      => 'application/msword',
			],
			[
				'name'      => 'Excel',
				'extension' => 'xls',
				'icon'      => 'document-text',
				'mime'      => 'application/vnd.ms-excel',
			],
			[
				'name'      => 'Excel',
				'extension' => 'xlsx',
				'icon'      => 'document-text',
				'mime'      => 'application/vnd.ms-excel',
			],
			[
				'name'      => 'Powerpoint',
				'extension' => 'ppt',
				'icon'      => 'document-report',
				'mime'      => 'application/vnd.ms-powerpoint',
			],
			[
				'name'      => 'Powerpoint',
				'extension' => 'pptx',
				'icon'      => 'document-report',
				'mime'      => 'application/vnd.ms-powerpoint',
			],
			[
				'name'      => 'Text',
				'extension' => 'txt',
				'icon'      => 'document-text',
				'mime'      => 'text/plain',
			],
			[
				'name'      => 'Icon',
				'extension' => 'ico',
				'icon'      => 'document',
				'mime'      => 'image/x-icon',
			],
			[
				'name'      => 'CSV',
				'extension' => 'csv',
				'icon'      => 'document',
				'mime'      => 'text/csv',
			],
			[
				'name'      => 'PDF',
				'extension' => 'pdf',
				'icon'      => 'document',
				'mime'      => 'application/pdf',
			],
			[
				'name'      => 'JPG',
				'extension' => 'jpg',
				'icon'      => 'image',
				'mime'      => 'image/jpeg',
			],
			[
				'name'      => 'JPEG',
				'extension' => 'jpeg',
				'icon'      => 'image',
				'mime'      => 'image/jpeg',
			],
			[
				'name'      => 'PNG',
				'extension' => 'png',
				'icon'      => 'image',
				'mime'      => 'image/png',
			],
			[
				'name'      => 'SVG',
				'extension' => 'svg',
				'icon'      => 'svg',
				'mime'      => 'image/svg+xml',
			],
			[
				'name'      => 'ZIP',
				'extension' => 'zip',
				'icon'      => 'document',
				'mime'      => 'application/zip',
			],
			[
				'name'      => 'RAR',
				'extension' => 'rar',
				'icon'      => 'document',
				'mime'      => 'application/x-rar-compressed',
			],
			[
				'name'      => 'Video MP4',
				'extension' => 'mp4',
				'icon'      => 'video-camera',
				'mime'      => 'video/mp4',
			],
			[
				'name'      => 'Video WEBM',
				'extension' => 'webm',
				'icon'      => 'video-camera',
				'mime'      => 'video/webm',
			],
			[
				'name'      => 'Video WEBM',
				'extension' => 'weba',
				'icon'      => 'video-camera',
				'mime'      => 'video/webm',
			],
			[
				'name'      => 'Audio MP3',
				'extension' => 'mp3',
				'icon'      => 'document',
				'mime'      => 'audio/mpeg',
			],
			[
				'name'      => 'True Type Font',
				'extension' => 'ttf',
				'icon'      => 'document',
				'mime'      => 'font/ttf',
			],
			[
				'name'      => 'Adobe Ilustrator',
				'extension' => 'ai',
				'icon'      => 'document',
				'mime'      => null,
			],
		];
		
		foreach($types as $type) {
			FileType::create($type);
		}
	}
}
