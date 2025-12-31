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
                            ->label('Staff Member')
                            ->options(function () {
                                return \App\Models\Staff::query()
                                    ->join('users', 'staff.user_id', '=', 'users.id')
                                    ->where('staff.branch_id', \Illuminate\Support\Facades\Session::get('current_branch_id'))
                                    ->orderBy('users.name')
                                    ->pluck('users.name', 'staff.id');
                            })
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
