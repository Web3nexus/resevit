<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\SocialAccountResource\Pages;
use App\Models\SocialAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SocialAccountResource extends Resource
{
    protected static ?string $model = SocialAccount::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static bool $shouldRegisterNavigation = false;

    public static function canViewAny(): bool
    {
        return has_feature('messaging');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Account Details')
                    ->schema([
                        \Filament\Forms\Components\Select::make('platform')
                            ->options([
                                'whatsapp' => 'WhatsApp Business',
                                'facebook' => 'Facebook Messenger',
                                'instagram' => 'Instagram DM',
                            ])
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('external_account_id')
                            ->label('External Account ID')
                            ->helperText('Phone Number ID or Page ID')
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Friendly Name')
                            ->placeholder('My Business WhatsApp')
                            ->required(),

                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),

                \Filament\Schemas\Components\Section::make('Credentials')
                    ->description('Enter the API keys provided by the platform.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('credentials.access_token')
                            ->label('Access Token')
                            ->password() // Hide token
                            ->revealable()
                            ->required()
                            ->helperText('The long-lived access token from the developer portal.'),

                        \Filament\Forms\Components\TextInput::make('credentials.verify_token')
                            ->label('Webhook Verify Token')
                            ->helperText('Your efficient custom string to verify webhook connectivity (optional).'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'whatsapp' => 'success',
                        'facebook' => 'info',
                        'instagram' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('external_account_id')
                    ->label('ID')
                    ->copyable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialAccounts::route('/'),
        ];
    }
}
