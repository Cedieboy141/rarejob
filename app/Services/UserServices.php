<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserServices {
    
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository  = $userRepository;
    }

    public function login($data) {

        $responseData = [
            'success' => true,
            'data' => [],
            'message' => 'Login Successfully',
            'code' => 200
        ]; 

        try {
             $validator = Validator::make($data->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => 'Validation Error', 'message' => $validator->errors()], 400);
            }
    
            $token = $this->userRepository->login($data->only('email', 'password'));

            $responseData['data'] = $token;
        } catch (\Exception $err) {
            DB::rollBack();

            \Log::error('Error during logging in: ' . $err->getMessage());
            $responseData['message'] =  $err->getMessage();
            $responseData['code'] = 401;
        }

        return $responseData;
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
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => $data['password'], 
                'birthdate' => $data['birthdate']
            ];

            // Validate the incoming data
            $validator = Validator::make($dataToPass, [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8', 
                'birthdate' => 'required|date'
            ]);

            // If validation fails, throw an exception to rollback
            if ($validator->fails()) {
                throw new \Exception('Validation failed: ' . $validator->errors()->first());
            }

            // Encrypt the password
            $dataToPass['password'] = Hash::make($dataToPass['password']);

            // Create the user record
            $user = $this->userRepository->create($dataToPass);

            // Commit the transaction if everything is successful
            DB::commit();
            $responseData['data'] = $user;
        
        } catch (\Exception $err) {
            // Rollback the transaction in case of any errors
            DB::rollBack();

            \Log::error('Error during user creation: ' . $err->getMessage());
            $responseData['message'] =  $err->getMessage();
            $responseData['code'] = 500;
        }

        return $responseData;
    }
}