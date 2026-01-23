@php
    // Check if we're in a component context or regular include
    if (isset($this) && property_exists($this, 'document')) {
        // Component context
        $document = $this->document;
        $showDays = $this->showDays ?? true;
        $compact = $this->compact ?? false;
    } else {
        // Regular include context
        $document = $document ?? null;
        $showDays = $showDays ?? true;
        $compact = $compact ?? false;
    }
    
    // If no document, show nothing
    if (!$document) {
        return;
    }
    
    // Now do the calculations
    $isPermanent = $document->documentType && $document->documentType->retention_years == 0;
    $hasExpiry = !$isPermanent && $document->expiry_date;
    
    if ($hasExpiry) {
        $daysUntilExpiry = \Carbon\Carbon::now()->diffInDays($document->expiry_date, false);
        $isExpiringSoon = $daysUntilExpiry > 0 && $daysUntilExpiry <= 90;
        $isExpired = $daysUntilExpiry < 0;
    } else {
        $daysUntilExpiry = null;
        $isExpiringSoon = false;
        $isExpired = false;
    }
@endphp

@if($compact)
    {{-- Compact version for tables --}}
    <div class="d-inline-flex align-items-center">
        @if($isPermanent)
            <span class="badge bg-primary" title="Permanent - No expiry">
                <i class="fas fa-infinity"></i>
            </span>
        @elseif($hasExpiry && $isExpiringSoon)
            <span class="badge bg-warning text-dark" title="Expires in {{ (int) $daysUntilExpiry }} days">
                <i class="fas fa-clock"></i>
            </span>
        @elseif($document->status == 'archived')
            <span class="badge bg-secondary" title="Archived">
                <i class="fas fa-archive"></i>
            </span>
        @elseif(($hasExpiry && $isExpired) || $document->status == 'expired')
            <span class="badge bg-danger" title="Expired">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
        @else
            <span class="badge bg-success" title="Active">
                Active
            </span>
        @endif
        
        @if($showDays)
            @if($isPermanent)
                <small class="text-primary ms-1">Perm</small>
            @elseif($hasExpiry && !$isExpiringSoon)
                <small class="{{ $isExpired ? 'text-danger' : 'text-muted' }} ms-1">
                    @if($isExpired)
                        {{ (int) abs($daysUntilExpiry) }}d
                    @elseif($daysUntilExpiry > 90)
                        {{ (int) $daysUntilExpiry }}d
                    @endif
                </small>
            @endif
        @endif
    </div>
@else
    {{-- Full version for detail views --}}
    <div>
        <div class="d-flex flex-wrap gap-1 align-items-center">
            @if($isPermanent)
                <span class="badge bg-primary">
                    <i class="fas fa-infinity me-1"></i> Permanent
                </span>
            @elseif($hasExpiry && $isExpiringSoon)
                <span class="badge bg-warning text-dark">
                    <i class="fas fa-clock me-1"></i> Expiring Soon
                </span>
            @elseif($document->status == 'archived')
                <span class="badge bg-secondary">
                    <i class="fas fa-archive me-1"></i> Archived
                </span>
            @elseif($hasExpiry && $isExpired)
                <span class="badge bg-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Expired
                </span>
            @elseif($document->status == 'expired')
                <span class="badge bg-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Expired
                </span>
            @else
                <span class="badge bg-success">Active</span>
            @endif
        </div>

        @if($showDays)
            @if($isPermanent)
                <div class="mt-1">
                    <small class="text-primary">
                        <i class="fas fa-infinity me-1"></i> Permanent document - No expiry
                    </small>
                </div>
            @elseif($hasExpiry)
                <div class="mt-1">
                    @if($isExpiringSoon)
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>
                            {{ (int) $daysUntilExpiry }} days until expiry
                        </small>
                    @elseif($isExpired)
                        <small class="text-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Expired {{ (int) abs($daysUntilExpiry) }} days ago
                        </small>
                    @elseif($daysUntilExpiry == 0)
                        <small class="text-danger">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Expires today
                        </small>
                    @elseif($daysUntilExpiry > 90)
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ (int) $daysUntilExpiry }} days until expiry
                        </small>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endif