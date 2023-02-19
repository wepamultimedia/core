<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Wepa\Core\Http\Helpers\InterventionImageHelper;
use Wepa\Core\Http\Requests\Backend\SeoFormRequest;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;
use Wepa\Core\Http\Traits\StorageControllerTrait;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\Site;

class SiteController extends InertiaController
{
    use StorageControllerTrait;
    use SeoControllerTrait;

    public string $packageName = 'core';

    /**
     * @param  Seo  $seo
     */
    public function destroy(Site $site): void
    {
    }

    public function edit(): Response
    {
        $seo = Seo::where(['alias' => 'home'])->first();
        $site = Site::find(1)->attrsToArray(['seo']);

        return $this->render('Core/Backend/Site/Edit', ['seo', 'backend/site'], compact(['site', 'seo']));
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

    public function index(Request $request): Response
    {
        $routes = Seo::when($request->search, function ($query, $search) {
            $query->where('alias', 'LIKE', '%'.$search.'%')
                ->orWhereTranslationLike('slug', '%'.$search.'%');
        })
            ->where('alias', '<>', 'home')
            ->orWhereNull('alias')
            ->paginate();

        return $this->render('Core/Backend/Seo/Index', 'seo', compact(['routes']));
    }

    public function store(SeoFormRequest $request): Redirector|Application|RedirectResponse
    {
        $excludeFilter = ['alias', 'canonical'];

        $params = collect($request->all())
            ->filter(function ($value, $key) use ($excludeFilter) {
                if ($value !== null or in_array($key, $excludeFilter)) {
                    return true;
                }

                return false;
            })
            ->merge($request['translations'])
            ->except(['translations'])
            ->toArray();

        Seo::create($params);

        return redirect(route('admin.seo.index'));
    }

    public function create(): Response
    {
        return $this->render('Core/Backend/Seo/Create', 'seo');
    }

    /**
     * @throws BindingResolutionException
     */
    public function update(Request $request): void
    {
        $site = Site::find(1);
        $site->update($request->all());
        $this->seoUpdate($site->seo_id);
    }
}
