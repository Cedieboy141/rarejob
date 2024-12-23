<?php

namespace App\Services;

use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogServices {
    
    protected $blogRepository;

    public function __construct(BlogRepository $blogRepository) {
        $this->blogRepository  = $blogRepository;
    }

    public function create($data) {
        DB::beginTransaction();

        $responseData = [
            'success' => true,
            'data' => [],
            'message' => 'Created Successfully',
            'code' => 200
        ]; 

        try {
            $dataToPass = [
                'posted_by' => $data['user']->id,
                'title' => $data['title'],
                'description' => $data['description'], 
                'content' => $data['content']
            ];

            $validator = Validator::make($dataToPass, [
                'posted_by' => 'requireds',
                'title' => 'required|string',
                'description' => 'required|string', 
                'content' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('Validation failed: ' . $validator->errors()->first());
            }

            $user = $this->blogRepository->create($dataToPass);

            DB::commit();
            $responseData['data'] = $user;
        
        } catch (\Exception $err) {
            DB::rollBack();

            \Log::error('Error during blog posting: ' . $err->getMessage());
            $responseData['message'] =  $err->getMessage();
            $responseData['code'] = 500;
        }

        return $responseData;
    }
}