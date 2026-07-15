@extends('layouts.user')

@section('title', 'Team Report - Daleel AI')

@section('content')
<div class="container" style="max-width: 920px; margin-top: 20px;">
    <div style="padding: 16px; border-radius: 12px; background: #fff; border: 1px solid #e5e7eb;">
        <h2 style="margin:0 0 8px; font-weight: 900;">DALEEL Training Intelligence</h2>
        <p style="margin:0 0 16px; color:#6b7280;">
            DALEEL Training Intelligence — static sample design. Real report data will be wired in later.
        </p>
        <div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 13px; color:#374151;">
            <div><b>type</b>: {{ $type ?? 'n/a' }}</div>
            <div><b>dept_id</b>: {{ $dept_id ?? 'n/a' }}</div>
            <div><b>employee_id</b>: {{ $employee_id ?? 'n/a' }}</div>
            <div><b>range</b>: {{ $range ?? 'today' }}</div>
        </div>
        <div style="margin-top:16px;">
            <a href="{{ route('team.index') }}" style="display:inline-block; padding:10px 14px; border-radius:10px; background:#111827; color:#fff; text-decoration:none; font-weight:800;">Back to Team</a>
        </div>
    </div>
</div>
@endsection

