<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Performer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PerformerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Performer::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $performers = $query->paginate(15)->withQueryString();

        return view('admin.performers.index', compact('performers'));
    }

    public function create(): View
    {
        return view('admin.performers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:performers,slug'],
            'bio' => ['nullable', 'string'],
            'avatar_url' => ['nullable', 'url', 'max:2048'],
            'birth_date' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        Performer::create($data);

        return redirect()->route('admin.performers.index')->with('status', 'Performer created.');
    }

    public function edit(Performer $performer): View
    {
        return view('admin.performers.edit', compact('performer'));
    }

    public function update(Request $request, Performer $performer): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:performers,slug,' . $performer->id],
            'bio' => ['nullable', 'string'],
            'avatar_url' => ['nullable', 'url', 'max:2048'],
            'birth_date' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $performer->update($data);

        return redirect()->route('admin.performers.index')->with('status', 'Performer updated.');
    }

    public function destroy(Performer $performer): RedirectResponse
    {
        $performer->delete();

        return back()->with('status', 'Performer deleted.');
    }
}

