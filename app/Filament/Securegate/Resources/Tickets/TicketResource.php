<?php

namespace App\Filament\Securegate\Resources\Tickets;

use App\Filament\Securegate\Resources\Tickets\Pages\ManageTickets;
use App\Models\Ticket;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'External Users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Ticket Details')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->disabledOn('edit')
                            ->required()
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->visibleOn('create')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->required(),
                        \Filament\Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'closed' => 'Closed',
                            ])
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(['ticketable', 'messages'])->latest())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),
                \Filament\Tables\Columns\TextColumn::make('ticketable.name')
                    ->label('Submitted By')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('ticketable_type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'gray',
                        default => 'primary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('reply')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->modalHeading(fn($record) => 'Ticket: ' . $record->subject)
                    ->modalContent(fn($record) => view('filament.securegate.resources.tickets.chat-modal', ['ticket' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->slideOver(),
                EditAction::make()->slideOver(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTickets::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
