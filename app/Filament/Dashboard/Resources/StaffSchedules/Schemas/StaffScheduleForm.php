<?php

namespace App\Filament\Dashboard\Resources\StaffSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StaffScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Shift Details')
                    ->schema([
                        Select::make('staff_id')
                            ->relationship('staff', 'user.name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('day_of_week')
                            ->options([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ])
                            ->required(),
                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->required(),
                        Select::make('shift_type')
                            ->options([
                                'regular' => 'Regular',
                                'split' => 'Split',
                                'overtime' => 'Overtime',
                                'holiday' => 'Holiday',
                            ])
                            ->default('regular')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
