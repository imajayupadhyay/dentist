<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentController extends Controller
{
    public function show(Treatment $treatment): Response
    {
        abort_unless($treatment->is_active, 404);

        return Inertia::render('Treatments/Show', [
            'treatment' => $treatment->toDetailPage(),
            'relatedTreatments' => Treatment::query()
                ->active()
                ->where('id', '!=', $treatment->id)
                ->ordered()
                ->limit(3)
                ->get()
                ->map(fn (Treatment $related): array => $related->toRelatedCard())
                ->all(),
        ])->withViewData([
            'seo' => $treatment->toSeoMeta(),
        ]);
    }
}
