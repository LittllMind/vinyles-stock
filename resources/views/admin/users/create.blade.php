{{-- resources/views/admin/users/create.blade.php --}}
{{-- Thème ART PRINT unifié --}}

@extends('layouts.admin-art-print')

@section('title', 'Créer un Utilisateur')

@section('content')
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="max-w-lg space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" id="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
            <select name="role" id="role" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Annuler</a>
        </div>
    </form>
@endsection
