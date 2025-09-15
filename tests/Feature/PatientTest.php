<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Filament\Resources\PatientResource;
use App\Filament\Resources\PatientResource\RelationManagers\TreatmentsRelationManager;
use App\Filament\Resources\PatientResource\Pages\EditPatient;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Owner;
use App\Models\User;
use App\Models\Patient;
use App\Models\Treatment;

uses(RefreshDatabase::class);

it('can view patient table records', function () {
    $owner = Owner::factory()->create();
    $patients = Patient::factory()->count(10)->for($owner)->create();
    Livewire::test(PatientResource\Pages\ListPatients::class)
        ->assertCanSeeTableRecords($patients);
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

it('can render and edit page', function () {
    $owner = Owner::factory()->create();
    $this->get(PatientResource::getUrl('edit', [
        'record' => Patient::factory()->for($owner)->create(),
    ]))->assertSuccessful();
});

it('can save an edit page', function () {
    $owner = Owner::factory()->create();
    $patient = Patient::factory()->for($owner)->create();
    $newData = Patient::factory()->for($owner)->make();

    Livewire::test(PatientResource\Pages\EditPatient::class, [
        'record' => $patient->getRouteKey(),
    ])
        ->fillForm([
            'owner_id' => $newData->owner->getKey(),
            'name' => $newData->name,
            'type' => $newData->type,
            'date_of_birth' => $newData->date_of_birth,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($patient->refresh())
        ->owner_id->toBe($newData->owner->getKey())
        ->name->toBe($newData->name)
        ->type->toBe($newData->type)
        ->date_of_birth->toBe($newData->date_of_birth);
});

it('can render relation manager', function () {
    $owner = Owner::factory()->create();
    $patient = Patient::factory()
        ->for($owner)
        ->has(Treatment::factory()->count(10))
        ->create();

    Livewire::test(PatientResource\RelationManagers\TreatmentsRelationManager::class, [
        'ownerRecord' => $patient,
        'pageClass' => EditPatient::class,
    ])
    ->assertSuccessful();
});

it('can list relation manager table', function () {
    $owner = Owner::factory()->create();
    $patient = Patient::factory()
        ->for($owner)
        ->has(Treatment::factory()->count(10))
        ->create();

    Livewire::test(PatientResource\RelationManagers\TreatmentsRelationManager::class, [
        'ownerRecord' => $patient,
        'pageClass' => EditPatient::class,
    ])
    ->assertCanSeeTableRecords($patient->treatments);
});
