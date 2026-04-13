@extends('layouts.admin')

@section('title', 'Conversations')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>💬 Conversations clients</h1>
        <div style="display: flex; gap: 0.5rem;">
            @php
                $nonLuesCount = \App\Models\Conversation::whereHas('messages', function($q) {
                    $q->whereNull('lu_at')->where('type', 'client');
                })->count();
            @endphp
            @if($nonLuesCount > 0)
                <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                    {{ $nonLuesCount }} non lue{{ $nonLuesCount > 1 ? 's' : '' }}
                </span>
            @endif
        </div>
    </div>

    {{-- FILTRES --}}
    <div style="background: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Statut</label>
                <select name="statut" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Tous</option>
                    <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
                    <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>Archivée</option>
                </select>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="non_lues" id="non_lues" value="1" {{ request('non_lues') ? 'checked' : '' }}>
                <label for="non_lues" style="font-size: 0.875rem;">Non lues seulement</label>
            </div>
            <button type="submit" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">Filtrer</button>
            <a href="{{ route('admin.conversations.index') }}" style="color: #6b7280; padding: 0.5rem 1rem;">Réinitialiser</a>
        </form>
    </div>

    {{-- TABLEAU --}}
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f3f4f6;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Client</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Sujet</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Statut</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Non lus</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Dernier message</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conversation)
                    @php
                        $messagesNonLus = $conversation->messages_count;
                        $hasUnread = $messagesNonLus > 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #e5e7eb; {{ $hasUnread ? 'background: #fef2f2;' : '' }}">
                        <td style="padding: 1rem;">
                            <strong>{{ $conversation->client->name ?? 'Inconnu' }}</strong>
                            <br>
                            <small style="color: #6b7280;">{{ $conversation->client->email ?? '-' }}</small>
                        </td>
                        <td style="padding: 1rem;">
                            @if($conversation->order_id)
                                <small style="color: #3b82f6;">📦 Commande #{{ $conversation->order->numero_commande ?? $conversation->order_id }}</small>
                                <br>
                            @endif
                            {{ $conversation->sujet ?: '(Pas de sujet)' }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            {!! $conversation->statutBadge() !!}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($messagesNonLus > 0)
                                <span style="background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    {{ $messagesNonLus }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <small style="color: #6b7280;">
                                {{ $conversation->dernier_message_at ? $conversation->dernier_message_at->diffForHumans() : 'Jamais' }}
                            </small>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.conversations.show', $conversation) }}" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem;">
                                {{ $hasUnread ? 'Lire' : 'Voir' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">
                            Aucune conversation trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top: 1rem;">
        {{ $conversations->links() }}
    </div>
@endsection