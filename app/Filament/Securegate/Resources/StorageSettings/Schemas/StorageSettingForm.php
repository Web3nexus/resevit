<?php

namespace App\Filament\Securegate\Resources\StorageSettings\Schemas;

use Filament\Schemas\Schema;

class StorageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Active Storage Disk')
                    ->description('Choose where your application stores its media files.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('active_disk')
                            ->label('Active Storage Disk')
                            ->options([
                                'public' => 'Local Public Storage',
                                's3' => 'Amazon S3',
                                'r2' => 'Cloudflare R2',
                            ])
                            ->required()
                            ->live()
                            ->helperText('Warning: Switching disks will not automatically migrate existing files. Already uploaded media may become broken unless manually moved.'),
                        \Filament\Forms\Components\TextInput::make('cdn_url')
                            ->label('CDN Base URL')
                            ->placeholder('https://cdn.example.com')
                            ->helperText('Your custom CDN domain. If provided, all media URLs will be prefixed with this.'),
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Enable Cloud Storage Features')
                            ->default(true),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Cloudflare R2 / S3 Credentials')
                    ->description('Configure your cloud storage bucket details.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('s3_key')
                            ->label('Access Key ID')
                            ->password()
                            ->revealable(),
                        \Filament\Forms\Components\TextInput::make('s3_secret')
                            ->label('Secret Access Key')
                            ->password()
                            ->revealable(),
                        \Filament\Forms\Components\TextInput::make('s3_bucket')
                            ->label('Bucket Name'),
                        \Filament\Forms\Components\TextInput::make('s3_region')
                            ->label('Region')
                            ->placeholder('auto (for R2)')
                            ->default('auto'),
                        \Filament\Forms\Components\TextInput::make('s3_endpoint')
                            ->label('Endpoint URL')
                            ->placeholder('https://<account_id>.r2.cloudflarestorage.com')
                            ->columnSpanFull()
                            ->helperText('Required for Cloudflare R2 and other S3-compatible providers.'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Cloudflare API Integration')
                    ->description('Cloudflare specific features like automatic cache purging.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('cloudflare_api_token')
                            ->label('API Token')
                            ->password()
                            ->revealable(),
                        \Filament\Forms\Components\TextInput::make('cloudflare_zone_id')
                            ->label('Zone ID'),
                        \Filament\Forms\Components\TextInput::make('cloudflare_account_id')
                            ->label('Account ID'),
                    ])->columns(3),
            ]);
    }
}
