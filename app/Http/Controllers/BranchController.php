<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $cia_id = $request->query('cia_id');

        $branches = Branch::when($cia_id, function ($query) use ($cia_id) {
            $query->where('cia_id', $cia_id);
        })->get();

        return BranchResource::collection($branches)->resolve();
    }
}
