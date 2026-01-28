@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Ticket #{{ $ticket->id }}</h1>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; font-weight: 600; margin: 0;">{{ $ticket->subject }}</h3>
            </div>
            <div class="card-body">
                <div style="white-space: pre-wrap; line-height: 1.6; color: #333;">{{ $ticket->text }}</div>

                @if($ticket->media->isNotEmpty())
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #eee;">
                        <h4 style="font-size: 14px; font-weight: 600; color: #666; margin-bottom: 12px;">Attachments</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($ticket->getMedia('attachments') as $media)
                                <a href="{{ route('admin.tickets.download', [$ticket->id, $media->id]) }}"
                                   class="btn btn-secondary btn-sm"
                                   style="display: inline-flex; align-items: center; gap: 6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    {{ $media->file_name }}
                                    <small style="color: #888;">({{ number_format($media->size / 1024, 1) }} KB)</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Customer</h3>
                </div>
                <div class="card-body">
                    <dl style="display: grid; gap: 12px;">
                        <div>
                            <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Name</dt>
                            <dd style="font-size: 14px; font-weight: 500;">{{ $ticket->customer->name }}</dd>
                        </div>
                        @if($ticket->customer->email)
                            <div>
                                <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Email</dt>
                                <dd style="font-size: 14px;">
                                    <a href="mailto:{{ $ticket->customer->email }}" style="color: #4a90d9; text-decoration: none;">
                                        {{ $ticket->customer->email }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($ticket->customer->phone)
                            <div>
                                <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Phone</dt>
                                <dd style="font-size: 14px;">
                                    <a href="tel:{{ $ticket->customer->phone }}" style="color: #4a90d9; text-decoration: none;">
                                        {{ $ticket->customer->phone }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Status</h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 16px;">
                        <span class="badge badge-{{ $ticket->status->value }}" style="font-size: 14px; padding: 6px 14px;">
                            {{ $ticket->status->label() }}
                        </span>
                    </div>

                    <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div style="display: flex; gap: 8px;">
                            <select name="status" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Details</h3>
                </div>
                <div class="card-body">
                    <dl style="display: grid; gap: 12px;">
                        <div>
                            <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Created</dt>
                            <dd style="font-size: 14px;">{{ $ticket->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Updated</dt>
                            <dd style="font-size: 14px;">{{ $ticket->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @if($ticket->answered_at)
                            <div>
                                <dt style="font-size: 12px; color: #666; margin-bottom: 2px;">Answered</dt>
                                <dd style="font-size: 14px;">{{ $ticket->answered_at->format('M d, Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
