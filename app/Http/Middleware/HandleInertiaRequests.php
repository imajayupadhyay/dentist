<?php

namespace App\Http\Middleware;

use App\Models\SiteFooter;
use App\Models\SiteHeader;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'appName' => config('app.name'),

            'auth' => [
                'user' => $request->user()?->only('id', 'name', 'email'),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],

            'treatmentLinks' => fn () => $this->treatmentLinks(),
            'siteHeader' => fn () => $this->siteHeader(),
            'siteFooter' => fn () => $this->siteFooter(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function siteFooter(): array
    {
        if (! Schema::hasTable('site_footers')) {
            return SiteFooter::defaultPublicArray();
        }

        return SiteFooter::current()->toPublicArray();
    }

    /**
     * @return array<string, mixed>
     */
    private function siteHeader(): array
    {
        if (! Schema::hasTable('site_headers')) {
            return SiteHeader::defaultPublicArray();
        }

        return SiteHeader::current()->toPublicArray();
    }

    /**
     * @return list<array{label: string, href: string}>
     */
    private function treatmentLinks(): array
    {
        if (! Schema::hasTable('treatments')) {
            return [];
        }

        return Treatment::query()
            ->active()
            ->ordered()
            ->get()
            ->map(fn (Treatment $treatment): array => [
                'label' => $treatment->home_title,
                'href' => "/treatments/{$treatment->slug}",
            ])
            ->all();
    }
}
