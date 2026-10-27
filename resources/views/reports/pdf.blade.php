<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ public_path('images/logo-full.png') }}" alt="ISMS Audit" style="height: 60px; margin-bottom: 20px;">
    <h2 style="margin: 8px 0;">{{ auth()->user()->currentTenant->name ?? 'ISO 27001 Report' }}</h2>
</div>