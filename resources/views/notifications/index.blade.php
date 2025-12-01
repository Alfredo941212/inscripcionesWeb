@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Notificaciones</h1>
        <form action="{{ route('notifications.markRead') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-check2-circle me-1"></i> Marcar todas como leidas
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="fw-semibold mb-1">{{ $notification->data['title'] ?? 'Notificacion' }}</p>
                                <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                @if(!empty($notification->data['notes']))
                                    <p class="small text-muted mb-1">Notas: {{ $notification->data['notes'] }}</p>
                                @endif
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            @if(!empty($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted py-4">
                        No tienes notificaciones todavia.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
