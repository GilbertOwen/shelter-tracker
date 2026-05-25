<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\CaretakerAssignment;
use App\Models\ContactLog;
use App\Models\Dog;
use App\Models\HealthRecord;
use App\Models\Schedule;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'caretaker', 'adopter'] as $roleName) {
            Role::findOrCreate($roleName);
        }

        $shelter = Shelter::updateOrCreate(
            ['email' => 'info@sahabatanabul.id'],
            [
                'name' => 'Sahabat Anabul Shelter',
                'address' => 'Jl. Mawar No. 12, Jakarta Selatan',
                'city' => 'Jakarta',
                'phone' => '08123456789',
                'capacity' => 50,
                'description' => 'Shelter anjing yang fokus pada rescue, rehabilitasi, dan adopsi bertanggung jawab.',
                'contact_for_adoption' => 'https://wa.me/6281234567890',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@shelter.com'],
            [
                'shelter_id' => $shelter->id,
                'name' => 'Admin Shelter',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081200000001',
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        $caretakers = collect(['Budi', 'Siti', 'Andi', 'Maya'])->map(function (string $name, int $index) use ($shelter) {
            $caretaker = User::updateOrCreate(
                ['email' => strtolower($name).'@shelter.com'],
                [
                    'shelter_id' => $shelter->id,
                    'name' => $name.' Caretaker',
                    'password' => Hash::make('password'),
                    'role' => 'caretaker',
                    'phone' => '08120000000'.($index + 2),
                    'is_active' => true,
                ]
            );
            $caretaker->syncRoles(['caretaker']);

            return $caretaker;
        });

        $adopter = User::updateOrCreate(
            ['email' => 'adopter@example.com'],
            [
                'shelter_id' => null,
                'name' => 'Demo Adopter',
                'password' => Hash::make('password'),
                'role' => 'adopter',
                'phone' => '081299999999',
                'is_active' => true,
            ]
        );
        $adopter->syncRoles(['adopter']);

        $dogRows = [
            ['Luna', 'Kintamani Mix', 'M', 'female', 'Cream', 'A-01', 'available', true, true, 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=900&q=80'],
            ['Bima', 'Local Mix', 'L', 'male', 'Brown', 'A-02', 'available', false, true, 'https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?auto=format&fit=crop&w=900&q=80'],
            ['Milo', 'Golden Retriever', 'L', 'male', 'Gold', 'B-01', 'pending', true, true, 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=900&q=80'],
            ['Nala', 'Beagle Mix', 'S', 'female', 'Tricolor', 'B-02', 'available', true, false, 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=900&q=80'],
            ['Rocky', 'German Shepherd Mix', 'XL', 'male', 'Black Tan', 'C-01', 'not_ready', false, false, 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?auto=format&fit=crop&w=900&q=80'],
            ['Coco', 'Poodle Mix', 'S', 'female', 'White', 'C-02', 'available', true, true, 'https://images.unsplash.com/photo-1583511655826-05700442b31b?auto=format&fit=crop&w=900&q=80'],
            ['Taro', 'Shiba Mix', 'M', 'male', 'Red', 'D-01', 'adopted', true, false, 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?auto=format&fit=crop&w=900&q=80'],
            ['Kopi', 'Domestic Mix', 'M', 'male', 'Black', 'D-02', 'available', false, true, 'https://images.unsplash.com/photo-1517849845537-4d257902454a?auto=format&fit=crop&w=900&q=80'],
            ['Sora', 'Husky Mix', 'L', 'female', 'Gray', 'E-01', 'available', false, false, 'https://images.unsplash.com/photo-1605568427561-40dd23c2acea?auto=format&fit=crop&w=900&q=80'],
            ['Poppy', 'Terrier Mix', 'S', 'female', 'Tan', 'E-02', 'available', true, true, 'https://images.unsplash.com/photo-1598133894008-61f7fdb8cc3a?auto=format&fit=crop&w=900&q=80'],
            ['Max', 'Labrador Mix', 'L', 'male', 'Black', 'F-01', 'not_ready', true, true, 'https://images.unsplash.com/photo-1517423440428-a5a00ad493e8?auto=format&fit=crop&w=900&q=80'],
            ['Daisy', 'Local Mix', 'M', 'female', 'White Brown', 'F-02', 'available', true, false, 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=900&q=80'],
        ];

        $dogs = collect($dogRows)->map(function (array $row, int $index) use ($shelter) {
            return Dog::updateOrCreate(
                ['shelter_id' => $shelter->id, 'name' => $row[0]],
                [
                    'breed' => $row[1],
                    'size' => $row[2],
                    'sex' => $row[3],
                    'color' => $row[4],
                    'kennel' => $row[5],
                    'adoption_status' => $row[6],
                    'good_with_kids' => $row[7],
                    'good_with_pets' => $row[8],
                    'photo_url' => $row[9],
                    'weight_kg' => 8 + ($index * 2.4),
                    'birth_date' => now()->subMonths(10 + ($index * 4))->toDateString(),
                    'intake_date' => now()->subDays(8 + ($index * 3))->toDateString(),
                    'intake_source' => $index % 3 === 0 ? 'rescue' : ($index % 3 === 1 ? 'surrender' : 'other'),
                    'quarantine_status' => $index === 4 ? 'quarantine' : 'clear',
                    'adoption_fee' => $row[6] === 'available' ? 250000 : 0,
                    'story' => $row[0].' datang ke shelter dengan karakter yang mulai percaya diri. Ia cocok untuk keluarga yang sabar dan mau membangun rutinitas positif.',
                    'temperament' => $index % 2 === 0 ? 'Calm, loyal, food motivated' : 'Playful, curious, people-friendly',
                    'is_active' => true,
                ]
            );
        });

        $dogs->each(function (Dog $dog, int $index) use ($admin, $caretakers) {
            $caretaker = $caretakers[$index % $caretakers->count()];

            CaretakerAssignment::updateOrCreate(
                ['dog_id' => $dog->id, 'is_active' => true],
                [
                    'caretaker_id' => $caretaker->id,
                    'assigned_by' => $admin->id,
                    'assigned_at' => now()->subDays(7 - min($index, 6)),
                    'ended_at' => null,
                    'notes' => 'Primary daily care assignment.',
                ]
            );

            $feeding = Schedule::updateOrCreate(
                ['dog_id' => $dog->id, 'title' => 'Morning feeding for '.$dog->name],
                [
                    'assigned_to' => $caretaker->id,
                    'type' => 'feeding',
                    'description' => 'Serve measured meal and refill water.',
                    'start_time' => now()->setTime(8, 0)->addDays($index % 3 === 0 ? 0 : 1),
                    'duration_minutes' => 30,
                    'priority' => 'medium',
                    'status' => $index % 5 === 0 ? 'completed' : 'pending',
                ]
            );

            Schedule::updateOrCreate(
                ['dog_id' => $dog->id, 'title' => 'Exercise walk for '.$dog->name],
                [
                    'assigned_to' => $caretaker->id,
                    'type' => 'exercise',
                    'description' => 'Short leash walk and enrichment session.',
                    'start_time' => now()->setTime(16, 0)->addDays($index % 2),
                    'duration_minutes' => 45,
                    'priority' => $index % 4 === 0 ? 'high' : 'medium',
                    'status' => 'pending',
                ]
            );

            HealthRecord::updateOrCreate(
                [
                    'dog_id' => $dog->id,
                    'recorded_at' => now()->subDays($index % 5)->setTime(10, 30)->toDateTimeString(),
                ],
                [
                    'recorded_by' => $caretaker->id,
                    'observation' => $index === 4 ? 'Low appetite, skin irritation, needs isolation follow-up.' : 'Alert, responsive, appetite normal.',
                    'severity' => $index === 4 ? 'urgent' : ($index % 4 === 0 ? 'watch' : 'normal'),
                    'symptoms' => $index === 4 ? 'Skin lesion, scratching, lethargy' : null,
                    'zoonosis_flag' => $index === 4,
                    'notes' => $index === 4 ? 'Use gloves and minimize cross-contact until vet check.' : null,
                ]
            );

            ContactLog::updateOrCreate(
                [
                    'dog_id' => $dog->id,
                    'caretaker_id' => $caretaker->id,
                    'logged_at' => now()->subDays($index % 6)->setTime(9, 15)->toDateTimeString(),
                ],
                [
                    'contact_type' => $index % 2 === 0 ? 'feeding' : 'walking',
                    'duration_minutes' => 20 + (($index % 4) * 10),
                    'ppe_used' => $index === 4 ? 'gloves' : 'none',
                    'notes' => 'Routine handling recorded for traceability.',
                ]
            );

            if ($feeding->status === 'completed') {
                ActivityLog::updateOrCreate(
                    ['schedule_id' => $feeding->id],
                    [
                        'dog_id' => $dog->id,
                        'user_id' => $caretaker->id,
                        'performed_at' => $feeding->start_time->copy()->addMinutes(25),
                        'notes' => 'Completed feeding task from demo seed.',
                    ]
                );
            }
        });
    }
}
