<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class BusinessReviewForm extends Component
{
    public $tenantId;
    public $rating = 5;
    public $comment;
    public $successMessage;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    public function mount($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        Review::create([
            'user_id' => Auth::id(),
            'tenant_id' => $this->tenantId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_published' => true, // Auto-publish for now, can be changed to false if moderation is strictly required
        ]);

        $this->reset(['comment', 'rating']);
        $this->successMessage = 'Thank you! Your review has been submitted successfully.';

        $this->dispatch('review-submitted');
    }

    public function setRating($val)
    {
        $this->rating = $val;
    }

    public function render()
    {
        return view('livewire.business-review-form');
    }
}
