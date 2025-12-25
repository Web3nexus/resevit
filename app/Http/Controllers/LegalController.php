<?php

namespace App\Http\Controllers;

use App\Models\LegalDocument;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function show($slug)
    {
        $document = LegalDocument::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('legal.show', compact('document'));
    }
}
