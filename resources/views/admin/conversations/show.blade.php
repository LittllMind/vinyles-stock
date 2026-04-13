@extends('layouts.admin')

@section('title', 'Conversation #' . $conversation->id)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h1>💬 {{ $conversation->sujet }}</h1>
        @if($conversation->order_id)
            <p style="color: #6b7280; margin: 0;">
                📦 Commande liée: 
                <a href="{{ route('admin.orders.show', $conversation->order) }}" style="color: #3b82f6;">
                    #{{ $conversation->order->numero_commande ?? $conversation->order_id }}
                </a>
            </p>
        @endif
    </div>
    <div style="display: flex; gap: 0.5rem;">
        {!! $conversation->statutBadge() !!}
        @if($conversation->statut === 'active')
            <form method="POST" action="{{ route('admin.conversations.close', $conversation) }}" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="" onclick="return confirm('Fermer cette conversation ?')" style="background: #6b7280; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                    Fermer
                </button>
            </form>
        @endif
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 1rem; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem;">
    {{-- INFO CLIENT --}}
    <div>
        <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0;">👤 Client</h3>
            <p><strong>{{ $conversation->client->name ?? 'Inconnu' }}</strong></p>
            <p style="color: #6b7280;">{{ $conversation->client->email ?? '-' }}</p>
            <p style="margin-top: 1rem;">
                <small>Créée le {{ $conversation->created_at->format('d/m/Y H:i') }}</small>
            </p>
            @if($conversation->fermee_at)
                <p>
                    <small>Fermée le {{ $conversation->fermee_at->format('d/m/Y H:i') }}</small><br>
                    <small>par {{ $conversation->fermeePar->name ?? 'Inconnu' }}</small>
                </p>
            @endif
        </div>
    </div>

    {{-- THREAD --}}
    <div>
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            {{-- Messages --}}
            <div style="margin-bottom: 2rem;">
                @foreach($conversation->messages as $message)
                    @if($message->type === 'systeme')
                        <div style="text-align: center; margin: 1rem 0;">
                            <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem;">
                                {{ $message->contenu }}
                            </span>
                        </div>
                    @else
                        <div style="display: flex; margin-bottom: 1rem; {{ $message->type === 'admin' ? 'flex-direction: row-reverse;' : '' }}">
                            <div style="max-width: 70%; background: {{ $message->type === 'admin' ? '#3b82f6' : '#f3f4f6' }}; color: {{ $message->type === 'admin' ? 'white' : '#1f2937' }}; padding: 1rem; border-radius: 0.5rem;">
                                <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.5rem;">
                                    {{ $message->user->name ?? ($message->type === 'admin' ? 'Admin' : 'Client') }} • {{ $message->created_at->format('d/m/Y H:i') }}
                                    @if(!$message->estLu() && $message->estDeLAdmin())
                                        • <span style="color: #fbbf24;">Non lu</span>
                                    @endif
                                </div>
                                <p style="margin: 0; white-space: pre-wrap;">{{ $message->contenu }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Reply form --}}
            @if($conversation->statut === 'active')
                <div style="border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                    <h3 style="margin-top: 0;">Répondre</h3>
                    <form method="POST" action="{{ route('admin.conversations.reply', $conversation) }}">
                        @csrf
                        <textarea name="contenu" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-bottom: 1rem; font-family: inherit;" placeholder="Écrivez votre réponse..." required></textarea>
                        @error('contenu')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                            Envoyer la réponse
                        </button>
                    </form>
                </div>
            @else
                <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; text-align: center; color: #6b7280;">
                    Cette conversation est fermée.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
