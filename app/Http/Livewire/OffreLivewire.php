<?php

namespace App\Http\Livewire;

use App\Models\Offre;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;


class OffreLivewire extends Component
{
     use WithPagination;
     protected $paginationTheme = 'tailwind';
public $searchTerm = '';
    public $viewMode = 'list';
    public $selectedOffer = null;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
    ];

    public function mount()
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
    }

    public function selectOffer($id)
    {
        $this->selectedOffer = Offre::with('questionFormulaire', 'postuleOffre')->findOrFail($id);
    }

    public function render()
    {
        $query = Offre::where('status_offre', 'active')
            ->with('questionFormulaire', 'postuleOffre');

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nom_enquete', 'like', "%{$this->searchTerm}%")
                  ->orWhere('details_enquete', 'like', "%{$this->searchTerm}%");
            });
        }

        $offres = $query->paginate(10);

        return view('frontOffice.pages.offre', compact('offres'))
            ->layout('frontOffice.layouts.app', ['title' => "Offres d'enquête"]);
    }
}
