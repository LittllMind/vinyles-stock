{{-- resources/views/admin/users/edit.blade.php --}}
{{-- Thème ART PRINT unifié --}}

@extends('layouts.admin-art-print')

@section('title', 'Modifier ' . $user->name)

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

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="max-w-lg space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
            <select name="role" id="role" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200">
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Annuler</a>
        </div>
    </form>
@endsection
