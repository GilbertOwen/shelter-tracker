<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDogController extends Controller
{
    public function index(Request $request): View
    {
        $dogs = Dog::with(['activeAssignment.caretaker'])
            ->where('shelter_id', $request->user()->shelter_id)
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($inner) => $inner
                ->where('name', 'like', '%'.$request->input('q').'%')
                ->orWhere('breed', 'like', '%'.$request->input('q').'%')
                ->orWhere('kennel', 'like', '%'.$request->input('q').'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('adoption_status', $request->input('status')))
            ->when($request->filled('size'), fn ($query) => $query->where('size', $request->input('size')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.dogs.index', [
            'dogs' => $dogs,
            'caretakers' => $this->caretakers($request),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.dogs.form', [
            'dog' => new Dog(['intake_date' => today(), 'is_active' => true]),
            'caretakers' => $this->caretakers($request),
            'assignment' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'caretaker_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('shelter_id', $request->user()->shelter_id)
                    ->where('role', 'caretaker')
                    ->where('is_active', true)),
            ],
        ]);

        $data = $this->validated($request);
        $data['shelter_id'] = $request->user()->shelter_id;
        $data = $this->applyBooleans($request, $data);

        if ($request->hasFile('photo')) {
            $data['photo_url'] = $request->file('photo')->store('dogs', 'public');
        }

        $dog = Dog::create($data);

        if ($request->filled('caretaker_id')) {
            $dog->assignments()->create([
                'caretaker_id' => $request->integer('caretaker_id'),
                'assigned_by' => $request->user()->id,
                'assigned_at' => now(),
                'is_active' => true,
                'notes' => 'Initial admin assignment.',
            ]);
        }

        return redirect()->route('admin.dogs.index')->with('success', $dog->name.' added to shelter.');
    }

    public function edit(Request $request, Dog $dog): View
    {
        $this->ensureShelterDog($request, $dog);

        return view('admin.dogs.form', [
            'dog' => $dog->load('activeAssignment'),
            'caretakers' => $this->caretakers($request),
            'assignment' => $dog->activeAssignment,
        ]);
    }

    public function update(Request $request, Dog $dog): RedirectResponse
    {
        $this->ensureShelterDog($request, $dog);

        $data = $this->applyBooleans($request, $this->validated($request));

        if ($request->hasFile('photo')) {
            if ($dog->photo_url && ! str_starts_with($dog->photo_url, 'http')) {
                Storage::disk('public')->delete($dog->photo_url);
            }

            $data['photo_url'] = $request->file('photo')->store('dogs', 'public');
        }

        $dog->update($data);

        return redirect()->route('admin.dogs.index')->with('success', $dog->name.' updated.');
    }

    public function destroy(Request $request, Dog $dog): RedirectResponse
    {
        $this->ensureShelterDog($request, $dog);

        if ($dog->adoption_status === 'pending') {
            return back()->with('error', 'Dog with pending adoption cannot be archived yet.');
        }

        $dog->update(['is_active' => false]);

        return back()->with('success', $dog->name.' archived from active dog list.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'breed' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', Rule::in(['S', 'M', 'L', 'XL'])],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'color' => ['nullable', 'string', 'max:255'],
            'sex' => ['required', Rule::in(['male', 'female', 'unknown'])],
            'birth_date' => ['nullable', 'date'],
            'intake_date' => ['required', 'date'],
            'intake_source' => ['required', Rule::in(['rescue', 'surrender', 'other'])],
            'kennel' => ['nullable', 'string', 'max:255'],
            'quarantine_status' => ['required', Rule::in(['clear', 'quarantine'])],
            'adoption_status' => ['required', Rule::in(['available', 'pending', 'adopted', 'not_ready'])],
            'adoption_fee' => ['nullable', 'numeric', 'min:0'],
            'story' => ['nullable', 'string'],
            'temperament' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function applyBooleans(Request $request, array $data): array
    {
        $data['good_with_kids'] = $request->boolean('good_with_kids');
        $data['good_with_pets'] = $request->boolean('good_with_pets');
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    private function caretakers(Request $request)
    {
        return User::where('shelter_id', $request->user()->shelter_id)
            ->where('role', 'caretaker')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function ensureShelterDog(Request $request, Dog $dog): void
    {
        abort_unless($dog->shelter_id === $request->user()->shelter_id, 403);
    }
}
