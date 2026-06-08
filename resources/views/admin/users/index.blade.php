{{-- resources/views/admin/users/index.blade.php --}}
{{-- Thème ART PRINT unifié --}}

@extends('layouts.admin-art-print')

@section('title', 'Gestion des Utilisateurs')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Nouvel Utilisateur</a>
@endsection

@section('content')
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th class="text-center">Rôle</th>
                    <th class="text-center">Créé le</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="font-semibold">{{ $user->name }}</td>
                        <td class="text-gray-500">{{ $user->email }}</td>
                        <td class="text-center">
                            <span class="badge
                                @if($user->role === 'admin') badge-danger
                                @elseif($user->role === 'employe') badge-warning
                                @else badge-ok
                                @endif"
                            >
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-center text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sm text-blue-600 hover:text-blue-800">Modifier</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
