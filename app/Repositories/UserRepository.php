<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CommonInterface;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository implements CommonInterface {

    protected $userModel;

    public function __construct(User $user) 
    {
        $this->userModel = $user;
    }

    public function all() {
        return $this->userModel->all();
    }

    public function find($id) {
        return $this->userModel->findOrFail($id);
    }

    public function create(array $data) {
        return $this->userModel->create($data);
    }

    public function login(array $data) {
        $token = JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']]);

        return [
            'access_token' => $token
        ];
    }

    public function update($id, array $data){
        $user = $this->userModel->find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id) {
        $user = $this->userModel->find($id);
        return $user->delete();
    }
}