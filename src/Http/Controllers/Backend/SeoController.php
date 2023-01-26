<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Inertia\Response;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;
use Wepa\Core\Models\Seo;

class SeoController extends InertiaController
{
    use SeoControllerTrait;

    public function setup()
    {
    }

    public function index(Request $request)
    {
        $items = Seo::when($request->search, function ($query, $search) {
            $query->where('slug', 'LIKE', "%$search%");
        })->where('id', '<>', 1)
            ->paginate();

        return $this->render('Blog/Backend/Seo/Index', 'seo', compact(['items']));
    }

    public function create()
    {
        return $this->render('Blog/Backend/Seo/Create', 'seo');
    }

    /**
     * @param  Seo  $seo
     * @return void
     */
    public function destroy(Seo $seo): void
    {
        $this->seoDestroy($seo->id);
    }

    /**
     * @param  Seo  $seo
     * @return Response
     */
    public function edit(Seo $seo): Response
    {
        return $this->render('Core/Backend/Seo/Edit', 'seo', compact(['seo']));
    }

    public function store(Request $request)
    {
        //		$this->seoStore();
    }

    public function update()
    {
    }
}
