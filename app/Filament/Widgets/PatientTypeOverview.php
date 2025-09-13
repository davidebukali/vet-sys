<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Patient;

class PatientTypeOverview extends BaseWidget
{
    public int $cats;

    protected function getStats(): array
    {
        $local_cats;
        if (isset($this->cats)) {
            $local_cats = $this->cats;
        } else {
            $local_cats = Patient::query()->where('type', 'cat')->count();
        }
        return [
            Stat::make('Cats', $local_cats), // Coming from the Settings page - getHeaderWidgets()
            Stat::make('Dogs', Patient::query()->where('type', 'dog')->count()),
            Stat::make('Rabbits', Patient::query()->where('type', 'rabbit')->count()),
        ];
    }
}
