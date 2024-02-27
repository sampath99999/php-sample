<?php

namespace App\Controllers;

use App\Services\PostService;

class PostController
{
    public function create()
    {
        return PostService::createPost();
    }
}
