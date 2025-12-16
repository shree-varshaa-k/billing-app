<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display all clients.
     */
    public function index()
    {
        $clients = Client::all();
        return view('client', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('add-client');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:clients,name',
            'email'   => 'nullable|email|max:255|unique:clients,email',
            'address' => 'nullable|string|max:255',
        ], [
            'name.unique'  => 'This client name already exists.',
            'email.unique' => 'This email address is already registered.',
        ]);

        Client::create($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('clients.index')->with('success', 'Client added successfully!');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);

        // Prevent editing if client has invoices
        if (Invoice::where('client_id', $client->id)->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Cannot edit client with existing invoices.');
        }

        return view('edit-client', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255|unique:clients,name,' . $client->id,
            'email'   => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'phone'   => 'nullable|digits_between:7,15' . $client->id,
            'address' => 'nullable|string|max:255',
        ], [
            'name.unique'  => 'This client name already exists.',
            'email.unique' => 'This email address is already in use.',
        ]);

        $client->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        // Prevent deletion if client has invoices
        if (Invoice::where('client_id', $client->id)->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Cannot delete client with existing invoices.');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }
    public function checkDuplicate(Request $request)
    {
        $field = $request->field;
        $value = $request->value;

        $exists = Client::where($field, $value)->exists();

        return response()->json(['exists' => $exists]);
    }

}
