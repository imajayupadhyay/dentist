<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use App\Models\Treatment;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $homePage = HomePage::current();

        return Inertia::render('Home/Index', [
            'homePage' => $homePage->toPageData(),
            'treatments' => Treatment::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn (Treatment $treatment): array => $treatment->toHomeCard())
                ->all(),
        ])->withViewData([
            'seo' => $homePage->toSeoMeta(),
        ]);
    }
}
