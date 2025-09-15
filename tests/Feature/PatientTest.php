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

uses(RefreshDatabase::class);

it('can render patient list page', function () {
    $this->get(PatientResource::getUrl('index'))->assertSuccessful();
});

it('can view patient table records', function () {
    $owner = Owner::factory()->create();
    $patients = Patient::factory()->count(10)->for($owner)->create();
    Livewire::test(PatientResource\Pages\ListPatients::class)
        ->assertCanSeeTableRecords($patients);
});

it('can view create page for patients', function () {
    $this->get(PatientResource::getUrl('create'))->assertSuccessful();
});

it('can create a patient', function () {
    $owner = Owner::factory()->create();
    $newData = Patient::factory()->for($owner)->create();

    Livewire::test(PatientResource\Pages\CreatePatient::class)
        ->fillForm([
            'owner_id' => $newData->owner->getKey(),
            'name' => $newData->name,
            'type' => $newData->type,
            'date_of_birth' => $newData->date_of_birth,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Patient::class, [
        'owner_id' => $newData->owner->getKey(),
        'name' => $newData->name,
        'type' => $newData->type,
        'date_of_birth' => $newData->date_of_birth,
    ]);
});

it('can validate patient input', function () {
    Livewire::test(PatientResource\Pages\CreatePatient::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});
