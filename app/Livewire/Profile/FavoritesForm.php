<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoritesForm extends Component
{
    public $favorites;

    public function mount()
    {
        $this->favorites = Auth::user()->favorites;
    }

    public function updateFavorites()
    {
        $this->dispatch('saved');
    }

    public function removeFavorite($bookId)
    {
        $user = Auth::user();

        $user->favorites()->detach($bookId);

        $this->favorites = $user->favorites()->get();

        $this->dispatch('favoriteRemoved');
    }

    public function render()
    {
        return view('livewire.profile.favorites-form', [
            'favorites' => $this->favorites,
        ]);
    }
}
