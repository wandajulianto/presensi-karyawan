<div class="appBottomMenu">
    <!-- Home Menu -->
    <a href="{{ route('dashboard') }}" class="item {{ Route::is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>

    <!-- History Menu -->
    <a href="{{ route('presensi.history') }}" class="item {{ Route::is('presensi.history') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="timer-outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>

    <!-- Camera Action Button -->
    <a href="{{ route('presensi.create') }}" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a>

    <!-- Permission Menu -->
    <a href="{{ route('presensi.izin') }}" class="item {{ Route::is('presensi.izin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>

    <!-- Profile Menu -->
    <a href="{{ route('profile') }}" class="item {{ Route::is('profile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->