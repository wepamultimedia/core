<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Wepa\Core\Http\Helpers\InterventionImageHelper;
use Wepa\Core\Http\Traits\StorageControllerTrait;
use Wepa\Core\Models\Site;

class SiteController extends InertiaController
{
    use StorageControllerTrait;

    public string $packageName = 'core';

    public function edit(): Response
    {
        $site = Site::whereId(1)->with('seo')->first();

        return $this->render('Vendor/Core/Backend/Site/Edit', ['seo', 'backend/site'], compact(['site']));
    }

    public function generateIcons(Request $request): void
    {
        $file = $request->file('file');
        foreach ($request->sizes as $sizes) {
            foreach ($sizes as $size) {
                $name = $size['name'].'.'.$file->extension();
                $image = new InterventionImageHelper($file);
                $image->resize($size['size'])->fit($size['size']);
                $fileToStorage = $image->toStorage();

                Storage::disk($this->fileSystemDisk())->putFileAs('icons', $fileToStorage, $name, 'public');
                $image->destroy();
            }
        }
        $this->generateBrowserConfigFile($request);
        $this->generateManifest($request);
    }

    public function generateBrowserConfigFile(Request $request)
    {
        $microsoft = $request->sizes['microsoft'];

        $text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $text .= "<browserconfig>\n";
        $text .= "    <msapplication>\n";
        $text .= "        <tile>\n";
        foreach ($microsoft as $icon) {
            $url = Storage::url("/icons/{$icon['name']}.png");
            $text .= "           <square{$icon['size']}x{$icon['size']}logo src=\"{$url}\"/>\n";
        }
        $text .= "           <TileColor>#000000</TileColor> \n";
        $text .= "       </tile>\n";
        $text .= "   </msapplication>\n";
        $text .= "</browserconfig>\n";

        Storage::disk($this->fileSystemDisk())->put('icons/browserconfig.xml', $text, 'public');
    }

    public function generateManifest(Request $request): void
    {
        $sourceIcons = collect($request['sizes']['favicon'])
            ->merge($request['sizes']['android'])
            ->merge($request['sizes']['apple'])
            ->toArray();

        $icons = [];
        foreach ($sourceIcons as $sourceIcon) {
            $icons[] = [
                'src' => Storage::url("/icons/{$sourceIcon['name']}.png"),
                'sizes' => "{$sourceIcon['size']}x{$sourceIcon['size']}",
                'type' => 'image/png',
            ];
        }

        $manifest = [
            'name' => config('app.name'),
            'short_name' => preg_replace('/ /', '', config('app.name')),
            'icons' => $icons,
            'theme_color' => '#000000',
            'background_color' => '#000000',
            'display' => 'standalone',
            'start_url' => request()->root(),
        ];

        Storage::disk($this->fileSystemDisk())->put('icons/manifest.json', json_encode($manifest), 'public');
    }

    public function update(Request $request): void
    {
        $site = Site::find(1);
        $site->update($request->all());
    }
}
