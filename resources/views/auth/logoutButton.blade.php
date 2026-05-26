<form action="{{ route('logout') }}" method="POST" class="form-logout m-0">
    @csrf

    <button type="submit" class="logout-link logout-button">
        <i class="bi bi-box-arrow-right"></i>
        <span>Sign Out</span>
    </button>
</form>