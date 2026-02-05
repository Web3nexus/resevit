<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\PlatformMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentMessagesWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Messages';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PlatformMessage::query()
                    ->where('sender_id', '!=', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('From')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('body')
                    ->label('Message')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->body),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recieved')
                    ->since()
                    ->color('gray'),
            ])
            ->paginated(false);
    }
}
