@extends('layouts.admin')

@section('title', 'Avis clients')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1>⭐ Avis clients</h1>
    <div style="display: flex; gap: 0.5rem;">
        @if($stats['pending'] > 0)
            <a href="{{ route('admin.reviews.pending') }}" style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
                {{ $stats['pending'] }} en attente
            </a>
        @endif
    </div>
</div>

{{-- STATS --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Total</p>
        <p style="margin: 0.25rem 0 0; font-size: 1.5rem; font-weight: 600;">{{ $stats['total'] }}</p>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
        <p style="margin: 0; color: #f59e0b; font-size: 0.875rem;">En attente</p>
        <p style="margin: 0.25rem 0 0; font-size: 1.5rem; font-weight: 600; color: #f59e0b;">{{ $stats['pending'] }}</p>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
        <p style="margin: 0; color: #10b981; font-size: 0.875rem;">Approuvés</p>
        <p style="margin: 0.25rem 0 0; font-size: 1.5rem; font-weight: 600; color: #10b981;">{{ $stats['approved'] }}</p>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
        <p style="margin: 0; color: #ef4444; font-size: 0.875rem;">Rejetés</p>
        <p style="margin: 0.25rem 0 0; font-size: 1.5rem; font-weight: 600; color: #ef4444;">{{ $stats['rejected'] }}</p>
    </div>
</div>

{{-- FILTRES --}}
<div style="background: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
        <div>
            <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Statut</label>
            <select name="status" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">Tous</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
        </div>
        <button type="submit" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">Filtrer</button>
        <a href="{{ route('admin.reviews.index') }}" style="color: #6b7280; padding: 0.5rem 1rem;">Réinitialiser</a>
    </form>
</div>

@if(session('success'))
    <div style="background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 1rem; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

{{-- TABLEAU --}}
<div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f3f4f6;">
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Vinyle</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Client</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Note</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Commentaire</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Statut</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Date</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $review->status == 'pending' ? 'background: #fffbeb;' : '' }}">
                    <td style="padding: 1rem;">
                        <small style="color: #6b7280;">#{{ $review->vinyle->id ?? 'N/A' }}</small>
                        <br>
                        <strong>{{ $review->vinyle->nom_complet ?? 'Vinyle supprimé' }}</strong>
                    </td>
                    <td style="padding: 1rem;">
                        {{ $review->user->name ?? 'Inconnu' }}
                        <br>
                        <small style="color: #6b7280;">{{ $review->user->email ?? '' }}</small>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="color: #fbbf24;">{{ str_repeat('★', $review->rating) }}</span>
                        <span style="color: #d1d5db;">{{ str_repeat('★', 5 - $review->rating) }}</span>
                    </td>
                    <td style="padding: 1rem; max-width: 300px;">
                        <p style="margin: 0; font-size: 0.875rem; line-height: 1.5;">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</p>
                        @if($review->admin_response)
                            <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #3b82f6;">📌 Réponse: {{ \Illuminate\Support\Str::limit($review->admin_response, 50) }}</p>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        {!! $review->statusBadge() !!}
                        @if($review->moderator && $review->status != 'pending')
                            <br>
                            <small style="color: #6b7280;">par {{ $review->moderator->name }}</small>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <small style="color: #6b7280;">{{ $review->created_at->diffForHumans() }}</small>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        @if($review->status == 'pending')
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;">Approuver</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;">Rejeter</button>
                                </form>
                            </div>
                        @else
                            <span style="color: #9ca3af; font-size: 0.75rem;">Traitée</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">
                        Aucun avis trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
<div style="margin-top: 1rem;">
    {{ $reviews->links() }}
</div>
@endsection