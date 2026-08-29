<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ContactSubmissionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Contacts/Index', [
            'submissions' => ContactSubmission::query()
                ->latest()
                ->take(100)
                ->get()
                ->map(fn (ContactSubmission $submission): array => $submission->toAdminArray())
                ->all(),
            'stats' => [
                'total' => ContactSubmission::query()->count(),
                'new' => ContactSubmission::query()->where('status', ContactSubmission::STATUS_NEW)->count(),
                'contacted' => ContactSubmission::query()->where('status', ContactSubmission::STATUS_CONTACTED)->count(),
                'closed' => ContactSubmission::query()->where('status', ContactSubmission::STATUS_CLOSED)->count(),
                'today' => ContactSubmission::query()->whereDate('created_at', now()->toDateString())->count(),
            ],
            'statuses' => ContactSubmission::statusOptions(),
        ]);
    }

    public function update(Request $request, ContactSubmission $contactSubmission): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(ContactSubmission::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:4000'],
        ]);

        $contactSubmission->update($data);

        return to_route('admin.contacts.index')
            ->with('success', 'Contact request updated.');
    }
}
