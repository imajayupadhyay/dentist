<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TreatmentRequest;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Treatments/Index', [
            'treatments' => Treatment::query()
                ->ordered()
                ->get()
                ->map(fn (Treatment $treatment): array => [
                    'id' => $treatment->id,
                    'sort_order' => $treatment->sort_order,
                    'is_active' => $treatment->is_active,
                    'slug' => $treatment->slug,
                    'tone' => $treatment->tone,
                    'home_title' => $treatment->home_title,
                    'home_subtitle' => $treatment->home_subtitle,
                    'home_image' => $treatment->home_image,
                    'updated_at' => $treatment->updated_at?->format('d M Y'),
                    'public_url' => "/treatments/{$treatment->slug}",
                ])
                ->all(),
        ]);
    }

    public function create(): Response
    {
        $treatment = Treatment::emptyAdminArray();
        $treatment['sort_order'] = ((int) Treatment::query()->max('sort_order')) + 1;

        return Inertia::render('Admin/Treatments/Form', [
            'mode' => 'create',
            'treatment' => $treatment,
            'seoOptions' => Treatment::seoOptions(),
            'tones' => Treatment::toneOptions(),
        ]);
    }

    public function store(TreatmentRequest $request): RedirectResponse
    {
        $treatment = Treatment::create($this->payload($request));

        return to_route('admin.treatments.edit', $treatment)
            ->with('success', 'Treatment created.');
    }

    public function edit(Treatment $treatment): Response
    {
        return Inertia::render('Admin/Treatments/Form', [
            'mode' => 'edit',
            'treatment' => $treatment->toAdminArray(),
            'seoOptions' => Treatment::seoOptions(),
            'tones' => Treatment::toneOptions(),
        ]);
    }

    public function update(TreatmentRequest $request, Treatment $treatment): RedirectResponse
    {
        $treatment->update($this->payload($request));

        return to_route('admin.treatments.edit', $treatment)
            ->with('success', 'Treatment updated.');
    }

    public function destroy(Treatment $treatment): RedirectResponse
    {
        $treatment->delete();

        return to_route('admin.treatments.index')
            ->with('success', 'Treatment deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(TreatmentRequest $request): array
    {
        $data = $request->validated();

        foreach ($this->imageFields() as $fileField => $pathField) {
            unset($data[$fileField]);

            if ($request->hasFile($fileField)) {
                $data[$pathField] = $this->storePublicAsset(
                    $request->file($fileField),
                    $data['slug'],
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
            'home_image_file' => 'home_image',
            'hero_image_file' => 'hero_image',
            'overview_image_file' => 'overview_image',
            'seo_og_image_file' => 'seo_og_image',
            'seo_twitter_image_file' => 'seo_twitter_image',
        ];
    }

    private function storePublicAsset(UploadedFile $file, string $slug, string $slot): string
    {
        $directory = public_path('assets/treatments');
        File::ensureDirectoryExists($directory);

        $base = Str::slug($slug.'-'.$slot.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = "{$base}-".Str::lower(Str::random(8)).".{$extension}";

        $file->move($directory, $filename);

        return "/assets/treatments/{$filename}";
    }
}
