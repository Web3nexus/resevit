<?php

namespace App\Filament\Invest\Resources\Tickets;


use BackedEnum;
use App\Filament\Invest\Resources\Tickets\Pages\ManageTickets;
use App\Models\Ticket;
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

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('New Support Ticket')
                    ->schema([
                        \Filament\Forms\Components\Select::make('tenant_id')
                            ->label('Business')
                            ->options(\App\Models\Tenant::query()->pluck('name', 'id'))
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search) => \App\Models\Tenant::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id'))
                            ->required(),
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
            ->modifyQueryUsing(fn($query) => $query->where('ticketable_id', \Filament\Facades\Filament::auth()->id())->where('ticketable_type', get_class(\Filament\Facades\Filament::auth()->user()))->with(['tenant', 'messages'])->latest())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),
                \Filament\Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Business')
                    ->searchable(),
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
            ])
            ->recordActions([
                \Filament\Actions\Action::make('reply')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->modalHeading(fn($record) => 'Ticket: ' . $record->subject)
                    ->modalContent(fn($record) => view('filament.invest.resources.tickets.chat-modal', ['ticket' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->slideOver(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Create Ticket')
                    ->modalHeading('Create Support Ticket')
                    ->form([
                        \Filament\Forms\Components\Select::make('tenant_id')
                            ->label('Business')
                            ->options(\App\Models\Tenant::query()->pluck('name', 'id'))
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search) => \App\Models\Tenant::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id'))
                            ->required(),
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
