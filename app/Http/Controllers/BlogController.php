<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogServices;

class BlogController extends Controller
{
    private $blogService;

    public function __construct(BlogServices $blogService) {
        $this->blogService = $blogService;
    }
   
    public function create(Request $request)
    {
        $blogServiceResponse = $this->blogService->create($request);

        return response()->json(
            $blogServiceResponse,
            $blogServiceResponse['code']
        );
    }
}
