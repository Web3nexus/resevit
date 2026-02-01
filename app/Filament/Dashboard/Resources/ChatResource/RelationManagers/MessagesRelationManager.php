<?php

namespace App\Filament\Dashboard\Resources\ChatResource\RelationManagers;

use App\Services\Social\SocialMessageRouterService;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $recordTitleAttribute = 'content';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inbound' => 'info',
                        'outbound' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\Action::make('send_message')
                    ->label('Send Message')
                    ->form([
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->label('Reply Content'),
                    ])
                    ->action(function (array $data) {
                        $chat = $this->getOwnerRecord();
                        $router = app(SocialMessageRouterService::class);

                        $account = \App\Models\SocialAccount::where('platform', $chat->source)
                            ->where('is_active', true)
                            ->first();

                        if ($account) {
                            $service = $router->getService($chat->source, $account);
                            if ($service && method_exists($service, 'send')) {
                                $service->send($chat, $data['content']);
                                \Filament\Notifications\Notification::make()
                                    ->title('Message sent successfully')
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Failed to send message: Service not available')
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('No active social account found for '.$chat->source)
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                // No individual actions for messages
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
