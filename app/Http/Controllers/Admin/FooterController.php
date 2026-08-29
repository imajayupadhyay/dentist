<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FooterRequest;
use App\Models\SiteFooter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class FooterController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Footer/Form', [
            'siteFooter' => SiteFooter::current()->toAdminArray(),
            'footerOptions' => SiteFooter::options(),
        ]);
    }

    public function update(FooterRequest $request): RedirectResponse
    {
        SiteFooter::current()->update($this->payload($request));

        return to_route('admin.footer.edit')
            ->with('success', 'Footer updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(FooterRequest $request): array
    {
        $data = $request->validated();
        unset($data['logo_file']);

        if ($request->hasFile('logo_file')) {
            $data['logo_path'] = $this->storePublicAsset($request->file('logo_file'));
        }

        return $data;
    }

    private function storePublicAsset(UploadedFile $file): string
    {
        $directory = public_path('assets/footer');
        File::ensureDirectoryExists($directory);

        $base = Str::slug('logo-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = "{$base}-".Str::lower(Str::random(8)).".{$extension}";

        $file->move($directory, $filename);

        return "/assets/footer/{$filename}";
    }
}
