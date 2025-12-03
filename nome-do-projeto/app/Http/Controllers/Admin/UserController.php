<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Insira um email válido',
            'email.unique' => 'Este email já está registado',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres',
            'password.confirmed' => 'As senhas não coincidem',
            'role.required' => 'O tipo de utilizador é obrigatório',
            'role.in' => 'Tipo de utilizador inválido',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador criado com sucesso!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Insira um email válido',
            'email.unique' => 'Este email já está registado',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres',
            'password.confirmed' => 'As senhas não coincidem',
            'role.required' => 'O tipo de utilizador é obrigatório',
            'role.in' => 'Tipo de utilizador inválido',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador atualizado com sucesso!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Impedir que o admin se delete a si próprio
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Não pode eliminar a sua própria conta!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador eliminado com sucesso!');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Impedir que o admin se desative a si próprio
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Não pode desativar a sua própria conta!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'ativado' : 'desativado';
        return redirect()->back()
            ->with('success', "Utilizador {$status} com sucesso!");
    }
}