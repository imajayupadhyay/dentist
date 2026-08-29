<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        $aboutPage = AboutPage::current();

        return Inertia::render('About/Index', [
            'aboutPage' => $aboutPage->toPageData(),
        ])->withViewData([
            'seo' => $aboutPage->toSeoMeta(),
        ]);
    }
}
