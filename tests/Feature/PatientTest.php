<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Filament\Resources\PatientResource;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Owner;
use App\Models\User;
use App\Models\Patient;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test patient list.
     */
    public function test_it_loads_patient_list(): void
    {;
        $owner = Owner::factory()->create();
        $patients = Patient::factory()->count(10)->for($owner)->create();
        Livewire::test(PatientResource\Pages\ListPatients::class)
            ->assertCanSeeTableRecords($patients);
    }
}
