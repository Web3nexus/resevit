<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class Wallet extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wallet';

    protected string $view = 'filament.dashboard.pages.wallet';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static int|null $navigationSort = 101;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return has_feature('finance');
    }

    public $balance;
    public $transactions;

    public function mount()
    {
        $user = Auth::user();
        $this->balance = $user->wallet_balance ?? 0;
        $this->transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function deposit()
    {
        // This will be handled by a separate controller or action
        // For now, redirect to a checkout route
        return redirect()->route('wallet.deposit');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deposit')
                ->label('Deposit Funds')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->action('deposit'),
        ];
    }
}
