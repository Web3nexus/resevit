<?php

namespace App\Filament\Influencer\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Illuminate\Support\Str;
use App\Traits\HandlesReferrals;

class Register extends BaseRegister
{
    use HandlesReferrals;
    protected ?string $redirectUrl = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Credentials')
                        ->icon('heroicon-m-lock-closed')
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                    Wizard\Step::make('Influencer Profile')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            Select::make('niche')
                                ->options([
                                    'food' => 'Food & Dining',
                                    'lifestyle' => 'Lifestyle',
                                    'travel' => 'Travel',
                                    'tech' => 'Technology',
                                    'fashion' => 'Fashion',
                                    'business' => 'Business',
                                    'other' => 'Other',
                                ])
                                ->required(),

                            Select::make('audience_size')
                                ->label('Primary Audience Size')
                                ->options([
                                    'under_1k' => 'Under 1,000',
                                    '1k_10k' => '1,000 - 10,000',
                                    '10k_50k' => '10,000 - 50,000',
                                    '50k_100k' => '50,000 - 100,000',
                                    'over_100k' => 'Over 100,000',
                                ])
                                ->required(),

                            TextInput::make('website')
                                ->url()
                                ->placeholder('https://yourwebsite.com'),
                        ]),
                    Wizard\Step::make('Social & Bio')
                        ->icon('heroicon-m-megaphone')
                        ->schema([
                            Repeater::make('social_links')
                                ->schema([
                                    TextInput::make('platform')
                                        ->required()
                                        ->placeholder('e.g. Instagram'),
                                    TextInput::make('url')
                                        ->url()
                                        ->required()
                                        ->placeholder('https://instagram.com/yourprofile'),
                                ])
                                ->columns(2)
                                ->columnSpanFull()
                                ->defaultItems(1),

                            Textarea::make('bio')
                                ->label('Tell us about yourself')
                                ->rows(5)
                                ->columnSpanFull()
                                ->required(),
                        ]),
                ])
                    ->submitAction(new \Illuminate\Support\HtmlString('<button type="submit" class="fi-btn fi-btn-size-md w-full rounded-lg bg-primary-600 text-white font-bold py-2">Complete Application</button>'))
            ]);
    }

    public function register(): ?\Filament\Auth\Http\Responses\Contracts\RegistrationResponse
    {
        $response = parent::register();

        if (auth('influencer')->check()) {
            $this->applyReferral(auth('influencer')->user());
        }

        return $response;
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['referral_code'] = 'REF-' . strtoupper(Str::random(8));
        $data['status'] = 'active';

        return $data;
    }
}
