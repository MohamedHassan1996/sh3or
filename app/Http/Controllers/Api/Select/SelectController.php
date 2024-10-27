<?php

namespace App\Http\Controllers\Api\Select;

use App\Http\Controllers\Controller;
use App\Services\Select\SelectService;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    private $selectService;

    public function __construct(SelectService $selectService)
    {
        //$this->middleware(middleware: 'auth:api');
        $this->selectService = $selectService;
    }

    public function getSelects(Request $request)
    {
        $selectData = $this->selectService->getSelects($request->allSelects);

        return response()->json([
            'data' => [
                'selects' => $selectData
            ]
        ]);
    }


}
