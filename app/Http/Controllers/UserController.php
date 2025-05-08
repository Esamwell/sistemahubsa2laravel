<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function index()
    {
        $data = $this->getData();
        return response()->json($data['users'] ?? []);
    }

    public function store(Request $request)
    {
        $data = $this->getData();
        $users = $data['users'] ?? [];
        
        $newUser = $request->all();
        $newUser['id'] = uniqid();
        
        $users[] = $newUser;
        $data['users'] = $users;
        
        $this->saveData($data);
        
        return response()->json($newUser, 201);
    }

    public function show($id)
    {
        $data = $this->getData();
        $users = $data['users'] ?? [];
        
        $user = collect($users)->firstWhere('id', $id);
        
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getData();
        $users = $data['users'] ?? [];
        
        $index = collect($users)->search(function($user) use ($id) {
            return $user['id'] === $id;
        });
        
        if ($index === false) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        
        $users[$index] = array_merge($users[$index], $request->all());
        $data['users'] = $users;
        
        $this->saveData($data);
        
        return response()->json($users[$index]);
    }

    public function destroy($id)
    {
        $data = $this->getData();
        $users = $data['users'] ?? [];
        
        $users = collect($users)->filter(function($user) use ($id) {
            return $user['id'] !== $id;
        })->values()->all();
        
        $data['users'] = $users;
        
        $this->saveData($data);
        
        return response()->json(null, 204);
    }
} 