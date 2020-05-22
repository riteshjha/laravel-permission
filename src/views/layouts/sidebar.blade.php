
@php($currentRoute = \Route::current()->getName())

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center pt-0 {{ $currentRoute == 'permission.listRoles' ? 'active' :'' }}" href="{{ route('permission.listRoles') }}">
            <i class="fas fa-user-shield fa-fw icon" aria-hidden="true"></i>
            <span>Roles</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center {{ $currentRoute == 'permission.listAbilities' ? 'active' :'' }}" href="{{ route('permission.listAbilities') }}">
            <i class="fas fa-lock fa-fw icon" aria-hidden="true"></i>
            <span>Abilities</span>
        </a>
    </li>

    @if($currentRoute == 'permission.roleAbilities')
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center active" href="#">
                <i class="fas fa-lock-open fa-fw icon" aria-hidden="true"></i>
                <span>Permissions</span>
            </a>
        </li>
    @endif
</ul>