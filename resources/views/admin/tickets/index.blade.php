@extends('admin.layouts.app')

@section('title', 'Tickets')

@section('content')
    <div class="page-header">
        <h1>Tickets</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.tickets.index') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ $filters['email'] ?? '' }}" placeholder="customer@email.com">
                    </div>

                    <div class="filter-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ $filters['phone'] ?? '' }}" placeholder="+79001234567">
                    </div>

                    <div class="filter-group">
                        <label for="from">From Date</label>
                        <input type="date" name="from" id="from" value="{{ $filters['from'] ?? '' }}">
                    </div>

                    <div class="filter-group">
                        <label for="to">To Date</label>
                        <input type="date" name="to" id="to" value="{{ $filters['to'] ?? '' }}">
                    </div>

                    <div class="filter-group" style="align-self: flex-end;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>

                    @if(!empty(array_filter($filters)))
                        <div class="filter-group" style="align-self: flex-end;">
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body" style="padding: 0;">
            @if($tickets->isEmpty())
                <p style="padding: 40px; text-align: center; color: #666;">No tickets found</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                <td>{{ $ticket->customer->name }}</td>
                                <td>
                                    @if($ticket->customer->email)
                                        {{ $ticket->customer->email }}<br>
                                    @endif
                                    @if($ticket->customer->phone)
                                        <small>{{ $ticket->customer->phone }}</small>
                                    @endif
                                </td>
                                <td><span class="badge badge-{{ $ticket->status->value }}">{{ $ticket->status->label() }}</span></td>
                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-secondary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @if($tickets->hasPages())
        <div class="pagination">
            {{ $tickets->appends($filters)->links() }}
        </div>
    @endif
@endsection
