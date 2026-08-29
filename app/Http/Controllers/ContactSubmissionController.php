<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSubmissionRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ContactSubmissionController extends Controller
{
    public function store(ContactSubmissionRequest $request): RedirectResponse
    {
        ContactSubmission::query()->create([
            ...$request->validated(),
            'source_page' => 'home',
            'status' => ContactSubmission::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
        ]);

        return back()->with('success', 'Appointment request received.');
    }
}
