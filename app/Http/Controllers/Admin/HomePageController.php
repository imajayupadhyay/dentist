<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomePageRequest;
use App\Models\HomePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomePageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Home/Form', [
            'homePage' => HomePage::current()->toAdminArray(),
            'seoOptions' => HomePage::seoOptions(),
        ]);
    }

    public function update(HomePageRequest $request): RedirectResponse
    {
        HomePage::current()->update($this->payload($request));

        return to_route('admin.home.edit')
            ->with('success', 'Home page updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(HomePageRequest $request): array
    {
        $data = $request->validated();

        foreach ([
            'seo_og_image_file' => 'seo_og_image',
            'seo_twitter_image_file' => 'seo_twitter_image',
        ] as $fileField => $pathField) {
            unset($data[$fileField]);

            if ($request->hasFile($fileField)) {
                $data[$pathField] = $this->storePublicAsset(
                    $request->file($fileField),
                    $pathField,
                );
            }
        }

        $data['hero_slides'] = collect($data['hero_slides'] ?? [])
            ->map(function (array $slide, int $index) use ($request): array {
                unset($slide['image_file']);

                if ($request->hasFile("hero_slides.{$index}.image_file")) {
                    $slide['image'] = $this->storePublicAsset(
                        $request->file("hero_slides.{$index}.image_file"),
                        'hero-slide-'.($index + 1),
                    );
                }

                return $slide;
            })
            ->values()
            ->all();

        $data['stories_items'] = collect($data['stories_items'] ?? [])
            ->map(function (array $story, int $index) use ($request): array {
                unset($story['video_file'], $story['poster_file']);

                if ($request->hasFile("stories_items.{$index}.video_file")) {
                    $story['src'] = $this->storePublicAsset(
                        $request->file("stories_items.{$index}.video_file"),
                        'story-'.($index + 1).'-video',
                    );
                }

                if ($request->hasFile("stories_items.{$index}.poster_file")) {
                    $story['poster'] = $this->storePublicAsset(
                        $request->file("stories_items.{$index}.poster_file"),
                        'story-'.($index + 1).'-poster',
                    );
                }

                return $story;
            })
            ->values()
            ->all();

        return $data;
    }

    private function storePublicAsset(UploadedFile $file, string $slot): string
    {
        $directory = public_path('assets/home');
        File::ensureDirectoryExists($directory);

        $base = Str::slug($slot.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = "{$base}-".Str::lower(Str::random(8)).".{$extension}";

        $file->move($directory, $filename);

        return "/assets/home/{$filename}";
    }
}
