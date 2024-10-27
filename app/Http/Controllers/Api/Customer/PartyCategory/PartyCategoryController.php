<?php

namespace App\Http\Controllers\Api\Customer\PartyCategory;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartyCategory\AllPartyCategoryCollection;
use App\Models\Party\PartyCategory;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class PartyCategoryController extends Controller
{


    public function index(Request $request)
    {
        $partyCategories = PartyCategory::all(['id', 'name', 'path']);

        return new AllPartyCategoryCollection(PaginateCollection::paginate($partyCategories, $request->pageSize?$request->pageSize:10));
    }

}
