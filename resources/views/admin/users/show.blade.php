{{-- resources/views/admin/users/show.blade.php --}}
{{-- Thème ART PRINT unifié --}}

@extends('layouts.admin-art-print')

@section('title', 'Profil de ' . $user->name)

@section('content')
    <div class="max-w-lg space-y-4">
        <p><span class="font-medium text-gray-700">Nom :</span> {{ $user->name }}</p>
        <p><span class="font-medium text-gray-700">Email :</span> {{ $user->email }}</p>
        <p>
            <span class="font-medium text-gray-700">Rôle :</span>
            <span class="badge
                @if($user->role === 'admin') badge-danger
                @elseif($user->role === 'employe') badge-warning
                @else badge-ok
                @endif"
            >
                {{ ucfirst($user->role) }}
            </span>
        </p>
        <p><span class="font-medium text-gray-700">Inscrit le :</span> {{ $user->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-900">← Retour liste</a>
    </div>
@endsection
