@extends('layouts.account')

@section('page_title', 'Activity Log')

@section('account_content')

  <section class="card">
    <div class="card-header">
      <div class="card-title-wrap">
        <h2 class="card-title">Activity Log</h2>
        <div class="title-underline"></div>
      </div>
    </div>

    <div class="card-divider"></div>

    {{-- Filters row --}}
    <form method="GET" action="{{ route('customer.activity.index') }}" class="act-filter">
      <div class="act-filter-row">
        <input class="act-input" type="text" name="action" value="{{ $filters['action'] ?? '' }}" placeholder="Action">
        <input class="act-input" type="text" name="ip" value="{{ $filters['ip'] ?? '' }}" placeholder="IP address">
        <input class="act-input" type="text" name="date" value="{{ $filters['date'] ?? '' }}" placeholder="Choose a time to search">

        <button class="act-btn" type="submit">
          <span class="act-ico">🔍</span> Search
        </button>

        <a class="act-btn" href="{{ route('customer.activity.index') }}">
          <span class="act-ico">🗑️</span> Clear Filter
        </a>
      </div>

      <div class="act-filter-row act-filter-row-2">
        <div class="act-inline">
          <span class="act-label">SHOW:</span>
          <select class="act-select" name="show">
            @foreach([10,25,50,100] as $n)
              <option value="{{ $n }}" {{ (int)($filters['show'] ?? 10) === $n ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
          </select>
        </div>

        <div class="act-inline act-inline-right">
          <span class="act-label">SORT BY DATE:</span>
          <select class="act-select" name="sort">
            <option value="today" {{ ($filters['sort'] ?? 'today') === 'today' ? 'selected' : '' }}>Today</option>
            <option value="7days" {{ ($filters['sort'] ?? '') === '7days' ? 'selected' : '' }}>Last 7 days</option>
            <option value="30days" {{ ($filters['sort'] ?? '') === '30days' ? 'selected' : '' }}>Last 30 days</option>
            <option value="all" {{ ($filters['sort'] ?? '') === 'all' ? 'selected' : '' }}>All</option>
          </select>
        </div>
      </div>
    </form>

    {{-- Table --}}
    <div class="act-table-wrap">
      <table class="act-table">
        <thead>
          <tr>
            <th style="width:240px;">Date</th>
            <th>Action</th>
            <th style="width:220px;">IP</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs->take($filters['show'] ?? 10) as $row)
            <tr>
              <td>{{ $row->date }}</td>
              <td>{{ $row->action }}</td>
              <td>{{ $row->ip }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" style="text-align:center; padding:18px; color:#6b7280;">
                No activity found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="act-footer">
      Showing {{ min($logs->count(), (int)($filters['show'] ?? 10)) }} of {{ $logs->count() }} Results
    </div>

  </section>

@endsection
