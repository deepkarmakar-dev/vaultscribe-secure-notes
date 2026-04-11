<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NotesChart extends ChartWidget
{
    protected ?string $heading = 'Notes Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
