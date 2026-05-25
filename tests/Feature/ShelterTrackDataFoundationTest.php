<?php

namespace Tests\Feature;

use App\Models\ContactLog;
use App\Models\Dog;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShelterTrackDataFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_one_seeder_creates_demo_dataset(): void
    {
        $this->seed();

        $this->assertDatabaseCount('roles', 3);
        $this->assertDatabaseCount('shelters', 1);
        $this->assertDatabaseCount('users', 6);
        $this->assertDatabaseCount('dogs', 12);
        $this->assertDatabaseCount('caretaker_assignments', 12);
        $this->assertDatabaseCount('schedules', 24);
        $this->assertDatabaseCount('health_records', 12);
        $this->assertDatabaseCount('contact_logs', 12);
        $this->assertDatabaseCount('activity_logs', 3);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@shelter.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('health_records', [
            'severity' => 'urgent',
            'zoonosis_flag' => true,
        ]);
    }

    public function test_core_relationships_resolve_expected_records(): void
    {
        $this->seed();

        $rocky = Dog::where('name', 'Rocky')->firstOrFail();
        $budi = User::where('email', 'budi@shelter.com')->firstOrFail();
        $schedule = Schedule::with(['dog', 'assignee'])->firstOrFail();
        $contactLog = ContactLog::with(['dog', 'caretaker'])->firstOrFail();

        $this->assertTrue($rocky->hasUrgentHealthRecord());
        $this->assertSame('Sahabat Anabul Shelter', $rocky->shelter->name);
        $this->assertNotNull($rocky->activeAssignment);
        $this->assertTrue($rocky->activeAssignment->caretaker->isCaretaker());
        $this->assertTrue($budi->assignedDogs()->exists());
        $this->assertNotNull($schedule->dog);
        $this->assertTrue($schedule->assignee->isCaretaker());
        $this->assertNotNull($contactLog->dog);
        $this->assertTrue($contactLog->caretaker->isCaretaker());
    }
}
