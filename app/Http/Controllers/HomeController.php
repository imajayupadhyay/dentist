<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home/Index', [
            'treatments' => Treatment::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn (Treatment $treatment): array => $treatment->toHomeCard())
                ->all(),
        ]);
    }
}
