<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $adminCount = $this->adminCount();

        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()
                ->where('is_admin', true)
                ->latest()
                ->get()
                ->map(fn (User $user): array => $this->toAdminArray($user, $request, $adminCount))
                ->all(),
            'stats' => [
                'admins' => $adminCount,
            ],
        ]);
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        return to_route('admin.users.index')
            ->with('success', 'Admin user created.');
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        abort_unless($user->is_admin, 404);

        $data = $request->validated();

        if (! filled($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update([
            ...$data,
            'is_admin' => true,
        ]);

        return to_route('admin.users.index')
            ->with('success', 'Admin user updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->is_admin, 404);

        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        if ($this->adminCount() <= 1) {
            return back()->with('error', 'At least one admin account must remain.');
        }

        $user->delete();

        return to_route('admin.users.index')
            ->with('success', 'Admin user deleted.');
    }

    private function adminCount(): int
    {
        return User::query()->where('is_admin', true)->count();
    }

    /**
     * @return array<string, mixed>
     */
    private function toAdminArray(User $user, Request $request, int $adminCount): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_current' => $user->is($request->user()),
            'can_delete' => ! $user->is($request->user()) && $adminCount > 1,
            'created_at' => $user->created_at?->format('M j, Y, g:i A'),
            'updated_at' => $user->updated_at?->format('M j, Y, g:i A'),
        ];
    }
}
