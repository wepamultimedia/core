<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;
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
                'name' => 'folder',
                'extension' => '.',
                'icon' => 'folder',
                'mime' => null,
            ],
            [
                'name' => 'Word',
                'extension' => 'doc',
                'icon' => 'doc',
                'mime' => 'application/msword',
            ],
            [
                'name' => 'Word',
                'extension' => 'docx',
                'icon' => 'docx',
                'mime' => 'application/msword',
            ],
            [
                'name' => 'Excel',
                'extension' => 'xls',
                'icon' => 'xls',
                'mime' => 'application/vnd.ms-excel',
            ],
            [
                'name' => 'Excel',
                'extension' => 'xlsx',
                'icon' => 'xlsx',
                'mime' => 'application/vnd.ms-excel',
            ],
            [
                'name' => 'Powerpoint',
                'extension' => 'ppt',
                'icon' => 'ppt',
                'mime' => 'application/vnd.ms-powerpoint',
            ],
            [
                'name' => 'Powerpoint',
                'extension' => 'pptx',
                'icon' => 'pptx',
                'mime' => 'application/vnd.ms-powerpoint',
            ],
            [
                'name' => 'Text',
                'extension' => 'txt',
                'icon' => 'txt',
                'mime' => 'text/plain',
            ],
            [
                'name' => 'Icon',
                'extension' => 'ico',
                'icon' => 'ico',
                'mime' => 'image/x-icon',
            ],
            [
                'name' => 'CSV',
                'extension' => 'csv',
                'icon' => 'csv',
                'mime' => 'text/csv',
            ],
            [
                'name' => 'PDF',
                'extension' => 'pdf',
                'icon' => 'pdf',
                'mime' => 'application/pdf',
            ],
            [
                'name' => 'JPG',
                'extension' => 'jpg',
                'icon' => 'image',
                'mime' => 'image/jpeg',
            ],
            [
                'name' => 'JPEG',
                'extension' => 'jpeg',
                'icon' => 'image',
                'mime' => 'image/jpeg',
            ],
            [
                'name' => 'PNG',
                'extension' => 'png',
                'icon' => 'image',
                'mime' => 'image/png',
            ],
            [
                'name' => 'WEBP',
                'extension' => 'webp',
                'icon' => 'image',
                'mime' => 'image/webp',
            ],
            [
                'name' => 'SVG',
                'extension' => 'svg',
                'icon' => 'svg',
                'mime' => 'image/svg+xml',
            ],
            [
                'name' => 'ZIP',
                'extension' => 'zip',
                'icon' => 'zip',
                'mime' => 'application/zip',
            ],
            [
                'name' => 'RAR',
                'extension' => 'rar',
                'icon' => 'rar',
                'mime' => 'application/x-rar-compressed',
            ],
            [
                'name' => 'Video MP4',
                'extension' => 'mp4',
                'icon' => 'mp4',
                'mime' => 'video/mp4',
            ],
            [
                'name' => 'Video WEBM',
                'extension' => 'webm',
                'icon' => 'webm',
                'mime' => 'video/webm',
            ],
            [
                'name' => 'Video WEBM',
                'extension' => 'weba',
                'icon' => 'webm',
                'mime' => 'video/webm',
            ],
            [
                'name' => 'Audio MP3',
                'extension' => 'mp3',
                'icon' => 'mp3',
                'mime' => 'audio/mpeg',
            ],
            [
                'name' => 'True Type Font',
                'extension' => 'ttf',
                'icon' => 'ttf',
                'mime' => 'font/ttf',
            ],
            [
                'name' => 'Adobe Ilustrator',
                'extension' => 'ai',
                'icon' => 'ai',
                'mime' => null,
            ],
        ];

        foreach ($types as $type) {
            FileType::create($type);
        }
    }
}
