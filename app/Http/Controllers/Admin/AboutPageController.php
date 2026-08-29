<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutPageRequest;
use App\Models\AboutPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AboutPageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/About/Form', [
            'aboutPage' => AboutPage::current()->toAdminArray(),
            'seoOptions' => AboutPage::seoOptions(),
        ]);
    }

    public function update(AboutPageRequest $request): RedirectResponse
    {
        AboutPage::current()->update($this->payload($request));

        return to_route('admin.about.edit')
            ->with('success', 'About page updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(AboutPageRequest $request): array
    {
        $data = $request->validated();

        foreach ($this->imageFields() as $fileField => $pathField) {
            unset($data[$fileField]);

            if ($request->hasFile($fileField)) {
                $data[$pathField] = $this->storePublicAsset(
                    $request->file($fileField),
                    $pathField,
                );
            }
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    private function imageFields(): array
    {
        return [
            'masthead_lead_image_file' => 'masthead_lead_image',
            'masthead_inset_image_file' => 'masthead_inset_image',
            'note_image_file' => 'note_image',
            'team_image_file' => 'team_image',
            'seo_og_image_file' => 'seo_og_image',
            'seo_twitter_image_file' => 'seo_twitter_image',
        ];
    }

    private function storePublicAsset(UploadedFile $file, string $slot): string
    {
        $directory = public_path('assets/about');
        File::ensureDirectoryExists($directory);

        $base = Str::slug($slot.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = "{$base}-".Str::lower(Str::random(8)).".{$extension}";

        $file->move($directory, $filename);

        return "/assets/about/{$filename}";
    }
}
