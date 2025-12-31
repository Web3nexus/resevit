<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Branch;
use Illuminate\Support\Facades\Session;

class BranchSwitcher extends Component
{
    public $branches;
    public $currentBranchId;

    public function mount()
    {
        if (!tenant()) {
            return;
        }

        $this->branches = Branch::where('is_active', true)->get();
        $this->currentBranchId = Session::get('current_branch_id');

        // Automatically select first branch if none selected and branches exist
        if (!Session::exists('current_branch_id') && $this->branches->count() > 0) {
            Session::put('current_branch_id', $this->branches->first()->id);
            $this->currentBranchId = $this->branches->first()->id;
        }
    }

    public function switchBranch($branchId)
    {
        if ($branchId === 'all') {
            Session::put('current_branch_id', null);
        } else {
            Session::put('current_branch_id', $branchId);
        }

        $this->currentBranchId = $branchId;

        // Use Livewire's redirect method
        $this->redirect(request()->header('Referer') ?: url()->current(), navigate: true);
    }

    public function render()
    {
        if (!tenant()) {
            return <<<'HTML'
            <div></div>
            HTML;
        }

        return view('livewire.branch-switcher');
    }
}
