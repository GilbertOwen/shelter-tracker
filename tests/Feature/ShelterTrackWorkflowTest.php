<?php

namespace Tests\Feature;

use App\Models\CaretakerAssignment;
use App\Models\Dog;
use App\Models\Schedule;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShelterTrackWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_registration_creates_shelter_workspace(): void
    {
        Role::findOrCreate('admin');

        $this->post('/register', [
            'role' => 'admin',
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'phone' => '081111111111',
            'shelter_name' => 'Happy Paws Shelter',
            'shelter_city' => 'Jakarta',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $admin = User::where('email', 'new-admin@example.com')->first();

        $this->assertAuthenticatedAs($admin);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertSame('admin', $admin->role);
        $this->assertSame('Happy Paws Shelter', $admin->shelter->name);
    }

    public function test_adopter_registration_does_not_create_shelter(): void
    {
        Role::findOrCreate('adopter');

        $this->post('/register', [
            'role' => 'adopter',
            'name' => 'New Adopter',
            'email' => 'new-adopter@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $adopter = User::where('email', 'new-adopter@example.com')->first();

        $this->assertAuthenticatedAs($adopter);
        $this->assertTrue($adopter->hasRole('adopter'));
        $this->assertSame('adopter', $adopter->role);
        $this->assertNull($adopter->shelter_id);
    }

    public function test_public_adoption_lists_only_available_active_dogs(): void
    {
        $shelter = $this->shelter();

        Dog::create($this->dogData($shelter, ['name' => 'Visible Dog', 'adoption_status' => 'available', 'is_active' => true]));
        Dog::create($this->dogData($shelter, ['name' => 'Pending Dog', 'adoption_status' => 'pending', 'is_active' => true]));
        Dog::create($this->dogData($shelter, ['name' => 'Archived Dog', 'adoption_status' => 'available', 'is_active' => false]));

        $this->get('/adopt')
            ->assertOk()
            ->assertSee('Visible Dog')
            ->assertDontSee('Pending Dog')
            ->assertDontSee('Archived Dog');
    }

    public function test_caretaker_cannot_view_unassigned_dog(): void
    {
        [$shelter, $admin, $caretaker, $otherCaretaker] = $this->usersForShelter();
        $assignedDog = Dog::create($this->dogData($shelter, ['name' => 'Assigned Dog']));
        $unassignedDog = Dog::create($this->dogData($shelter, ['name' => 'Unassigned Dog']));

        CaretakerAssignment::create([
            'dog_id' => $assignedDog->id,
            'caretaker_id' => $caretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        CaretakerAssignment::create([
            'dog_id' => $unassignedDog->id,
            'caretaker_id' => $otherCaretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $this->actingAs($caretaker)
            ->get(route('caretaker.dogs.show', $unassignedDog))
            ->assertForbidden();
    }

    public function test_caretaker_dashboard_loads_assigned_dogs_without_ambiguous_columns(): void
    {
        [$shelter, $admin, $caretaker] = $this->usersForShelter();
        $dog = Dog::create($this->dogData($shelter, ['name' => 'Dashboard Dog']));

        CaretakerAssignment::create([
            'dog_id' => $dog->id,
            'caretaker_id' => $caretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $this->actingAs($caretaker)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Dog');
    }

    public function test_reassigning_caretaker_closes_previous_assignment(): void
    {
        [$shelter, $admin, $caretaker, $otherCaretaker] = $this->usersForShelter();
        $dog = Dog::create($this->dogData($shelter, ['name' => 'Reassign Dog']));

        $oldAssignment = CaretakerAssignment::create([
            'dog_id' => $dog->id,
            'caretaker_id' => $caretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.dogs.assign', $dog), ['caretaker_id' => $otherCaretaker->id])
            ->assertRedirect();

        $this->assertFalse($oldAssignment->fresh()->is_active);
        $this->assertNotNull($oldAssignment->fresh()->ended_at);
        $this->assertDatabaseHas('caretaker_assignments', [
            'dog_id' => $dog->id,
            'caretaker_id' => $otherCaretaker->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_dog_with_photo_and_initial_assignment(): void
    {
        Storage::fake('public');

        [$shelter, $admin, $caretaker] = $this->usersForShelter();

        $this->actingAs($admin)
            ->post(route('admin.dogs.store'), [
                'name' => 'Photo Dog',
                'breed' => 'Kintamani Mix',
                'size' => 'M',
                'sex' => 'female',
                'weight_kg' => 12,
                'color' => 'Cream',
                'birth_date' => now()->subYears(2)->format('Y-m-d'),
                'intake_date' => today()->format('Y-m-d'),
                'intake_source' => 'rescue',
                'kennel' => 'T-01',
                'quarantine_status' => 'clear',
                'adoption_status' => 'available',
                'adoption_fee' => 250000,
                'story' => 'A calm dog ready for adoption.',
                'temperament' => 'Calm, friendly',
                'good_with_kids' => '1',
                'good_with_pets' => '1',
                'is_active' => '1',
                'caretaker_id' => $caretaker->id,
                'photo' => UploadedFile::fake()->image('photo-dog.jpg'),
            ])
            ->assertRedirect(route('admin.dogs.index'));

        $dog = Dog::where('name', 'Photo Dog')->firstOrFail();

        Storage::disk('public')->assertExists($dog->photo_url);
        $this->assertSame($shelter->id, $dog->shelter_id);
        $this->assertDatabaseHas('caretaker_assignments', [
            'dog_id' => $dog->id,
            'caretaker_id' => $caretaker->id,
            'is_active' => true,
        ]);
    }

    public function test_caretaker_complete_schedule_creates_activity_log_with_photo(): void
    {
        Storage::fake('public');

        [$shelter, $admin, $caretaker] = $this->usersForShelter();
        $dog = Dog::create($this->dogData($shelter, ['name' => 'Scheduled Dog']));
        CaretakerAssignment::create([
            'dog_id' => $dog->id,
            'caretaker_id' => $caretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $schedule = Schedule::create([
            'dog_id' => $dog->id,
            'assigned_to' => $caretaker->id,
            'type' => 'feeding',
            'title' => 'Dinner feeding',
            'start_time' => now()->addHour(),
            'duration_minutes' => 30,
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        $this->actingAs($caretaker)
            ->patch(route('caretaker.schedules.complete', $schedule), [
                'notes' => 'Finished and water refilled.',
                'photo' => UploadedFile::fake()->image('activity.jpg'),
            ])
            ->assertRedirect();

        $this->assertSame('completed', $schedule->fresh()->status);

        $activity = $schedule->activityLogs()->first();
        $this->assertNotNull($activity);
        $this->assertSame('Finished and water refilled.', $activity->notes);
        Storage::disk('public')->assertExists($activity->photo_url);
    }

    public function test_health_record_contact_log_and_admin_trace_are_connected(): void
    {
        [$shelter, $admin, $caretaker] = $this->usersForShelter();
        $dog = Dog::create($this->dogData($shelter, ['name' => 'Trace Dog']));

        CaretakerAssignment::create([
            'dog_id' => $dog->id,
            'caretaker_id' => $caretaker->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $this->actingAs($caretaker)
            ->post(route('caretaker.health-records.store'), [
                'dog_id' => $dog->id,
                'observation' => 'Skin lesion found during grooming.',
                'severity' => 'urgent',
                'symptoms' => 'Skin lesion',
                'zoonosis_flag' => '1',
                'notes' => 'Use gloves until vet review.',
                'recorded_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertRedirect(route('caretaker.dogs.show', $dog->id));

        $this->assertDatabaseHas('health_records', [
            'dog_id' => $dog->id,
            'recorded_by' => $caretaker->id,
            'severity' => 'urgent',
            'zoonosis_flag' => true,
        ]);

        $this->actingAs($caretaker)
            ->post(route('caretaker.contact-log.store'), [
                'dog_id' => $dog->id,
                'contact_type' => 'handling',
                'duration_minutes' => 25,
                'ppe_used' => 'gloves',
                'notes' => 'Handled with gloves.',
                'logged_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertRedirect(route('caretaker.contact-log.index'));

        $this->actingAs($admin)
            ->get(route('admin.contact-trace', [
                'dog_id' => $dog->id,
                'caretaker_id' => $caretaker->id,
                'from' => now()->subDay()->format('Y-m-d'),
                'to' => now()->addDay()->format('Y-m-d'),
            ]))
            ->assertOk()
            ->assertSee('Trace Dog')
            ->assertSee($caretaker->name)
            ->assertSee('Handled with gloves.');
    }

    private function usersForShelter(): array
    {
        foreach (['admin', 'caretaker'] as $role) {
            Role::findOrCreate($role);
        }

        $shelter = $this->shelter();
        $admin = $this->user($shelter, 'admin', 'admin@example.com');
        $caretaker = $this->user($shelter, 'caretaker', 'caretaker@example.com');
        $otherCaretaker = $this->user($shelter, 'caretaker', 'other@example.com');

        return [$shelter, $admin, $caretaker, $otherCaretaker];
    }

    private function user(Shelter $shelter, string $role, string $email): User
    {
        $user = User::create([
            'shelter_id' => $shelter->id,
            'name' => ucfirst($role).' User',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function shelter(): Shelter
    {
        return Shelter::create([
            'name' => 'Demo Shelter',
            'address' => 'Jl. Demo No. 1',
            'city' => 'Jakarta',
            'email' => 'demo@shelter.test',
        ]);
    }

    private function dogData(Shelter $shelter, array $overrides = []): array
    {
        return array_merge([
            'shelter_id' => $shelter->id,
            'name' => 'Demo Dog',
            'breed' => 'Local Mix',
            'sex' => 'female',
            'intake_date' => today(),
            'intake_source' => 'rescue',
            'quarantine_status' => 'clear',
            'adoption_status' => 'available',
            'is_active' => true,
        ], $overrides);
    }
}
