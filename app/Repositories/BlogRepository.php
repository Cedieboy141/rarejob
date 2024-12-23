<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CommonInterface;
use App\Models\Blog;

class BlogRepository implements CommonInterface {

    protected $blogModel;

    public function __construct(Blog $blog) 
    {
        $this->blogModel = $blog;
    }

    public function all() {
        return $this->blogModel->all();
    }

    public function find($id) {
        return $this->blogModel->findOrFail($id);
    }

    public function create(array $data) {
        return $this->blogModel->create($data);
    }

    public function update($id, array $data){
        $blog = $this->blogModel->find($id);
        $blog->update($data);
        return $blog;
    }

    public function delete($id) {
        $blog = $this->blogModel->find($id);
        return $blog->delete();
    }
}