<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\RequestStatus;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function index()
    {
        return view('requests.index');
    }

    public function create()
    {
        return view('requests.form');
    }

    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pendente,em_andamento,concluido,cancelado',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:baixa,media,alta',
            'notes' => 'nullable|string'
        ]);

        $request = Request::create($validated);

        // Registra o status inicial
        RequestStatus::create([
            'request_id' => $request->id,
            'status' => $validated['status'],
            'comment' => 'Status inicial'
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Solicitação criada com sucesso!');
    }

    public function show(Request $request)
    {
        return view('requests.show', compact('request'));
    }

    public function edit(Request $request)
    {
        return view('requests.form', compact('request'));
    }

    public function update(HttpRequest $httpRequest, Request $request)
    {
        $validated = $httpRequest->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pendente,em_andamento,concluido,cancelado',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:baixa,media,alta',
            'notes' => 'nullable|string'
        ]);

        $request->update($validated);

        return redirect()->route('requests.index')
            ->with('success', 'Solicitação atualizada com sucesso!');
    }

    public function destroy(Request $request)
    {
        $request->delete();

        return redirect()->route('requests.index')
            ->with('success', 'Solicitação excluída com sucesso!');
    }

    public function updateStatus(HttpRequest $httpRequest, Request $request)
    {
        $validated = $httpRequest->validate([
            'status' => 'required|in:pendente,em_andamento,concluido,cancelado',
            'comment' => 'nullable|string'
        ]);

        $request->update(['status' => $validated['status']]);

        // Registra a mudança de status
        RequestStatus::create([
            'request_id' => $request->id,
            'status' => $validated['status'],
            'comment' => $validated['comment']
        ]);

        return redirect()->route('requests.show', $request)
            ->with('success', 'Status atualizado com sucesso!');
    }
} 