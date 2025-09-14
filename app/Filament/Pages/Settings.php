<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PatientTypeOverview;
use App\Filament\Clusters\SettingsCluster;
use Filament\Pages\Page;
use Filament\Actions\Action;

class Settings extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $title = 'App Settings'; // Same as heading

    protected ?string $subheading = 'Update application settings here';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dashboard')
                ->url(route('filament.admin.pages.dashboard')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PatientTypeOverview::make([
                'cats' => 1,
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
