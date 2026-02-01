<?php

namespace App\Filament\Dashboard\Resources\Tickets;

use App\Filament\Dashboard\Resources\Tickets\Pages\ManageTickets;
use App\Models\Ticket;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Communication';

    public static function canViewAny(): bool
    {
        return has_feature('support_tickets');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('New Support Ticket')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['ticketable', 'messages'])->latest())
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
                \Filament\Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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
            ])
            ->recordActions([
                \Filament\Actions\Action::make('reply')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->modalHeading(fn ($record) => 'Ticket: '.$record->subject)
                    ->modalContent(fn ($record) => view('filament.dashboard.resources.tickets.chat-modal', ['ticket' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->slideOver(),
                EditAction::make()->slideOver()->visible(fn ($record) => $record->ticketable_id === \Filament\Facades\Filament::auth()->id() && $record->ticketable_type === get_class(\Filament\Facades\Filament::auth()->user())), // Only allow edit if own ticket
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Contact Support')
                    ->modalHeading('Contact Support')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium')
                            ->required(),
                    ])
                    ->using(function (array $data, string $model): Ticket {
                        $description = $data['description'];
                        unset($data['description']);

                        $data['ticketable_id'] = \Filament\Facades\Filament::auth()->id();
                        $data['ticketable_type'] = get_class(\Filament\Facades\Filament::auth()->user());
                        $data['tenant_id'] = tenant('id') ?? null;
                        $data['status'] = 'open';

                        $ticket = $model::create($data);

                        $ticket->messages()->create([
                            'user_id' => \Filament\Facades\Filament::auth()->id(),
                            'user_type' => get_class(\Filament\Facades\Filament::auth()->user()),
                            'message' => $description,
                        ]);

                        return $ticket;
                    }),
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
