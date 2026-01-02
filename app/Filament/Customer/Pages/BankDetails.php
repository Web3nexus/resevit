<?php

namespace App\Filament\Customer\Pages;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema as Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use BackedEnum;

class BankDetails extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected string $view = 'filament.pages.bank-details';

    protected static ?string $navigationLabel = 'Bank Details';

    protected static ?string $title = 'Bank Details';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth('customer')->user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Banking Information')
                    ->description('Provide your bank account details securely to receive referral rewards.')
                    ->schema([
                        TextInput::make('bank_name')
                            ->required()
                            ->label('Bank Name'),
                        TextInput::make('account_name')
                            ->required()
                            ->label('Account Holder Name'),
                        TextInput::make('account_number')
                            ->required()
                            ->label('Account Number'),
                        TextInput::make('iban')
                            ->label('IBAN (International Bank Account Number)'),
                        TextInput::make('swift_code')
                            ->label('SWIFT/BIC Code'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            auth('customer')->user()->update($data);

            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }
}
