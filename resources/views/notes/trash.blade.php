<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash - Notes App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboardstyle.css') }}">
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="logo">
            <h2>Notes App</h2>
        </div>
        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="menu-item">
                <i class="fa-solid fa-file-lines"></i> Notes
            </a>
            <a href="#" class="menu-item">
                <i class="fa-regular fa-star"></i> Important
            </a>
            <a href="{{ route('notes.trash') }}" class="menu-item active">
                <i class="fa-regular fa-trash-can"></i> Trash
            </a>
        </nav>

        <div class="user-profile">
            <img src="https://i.pravatar.cc/150?img=3" class="avatar" alt="User Avatar">
            <div class="user-info">
                <h4>{{ auth()->user()->name }}</h4>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-link">Logout</button>
                </form>
            </div>
            <i class="fa-solid fa-gear settings-icon"></i>
        </div>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <section class="notes-section">
            <div class="section-header">
                <h3>Deleted Notes</h3>
                <span class="text-muted">Trash will be kept until permanently deleted</span>
            </div>

            <div class="notes-grid">
                @forelse($notes as $note)
                <div class="note-card card-blue" style="opacity: 0.8;"> <div class="note-header">
                        <h4>{{ $note->title }}</h4>
                        <div class="actions">
                            <form action="{{ route('notes.restore', $note->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Restore Note" style="background:none; border:none; color:#4a90e2; cursor:pointer;">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                            </form>

                            <form action="{{ route('notes.forceDelete', $note->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete Permanently" 
                                    onclick="return confirm('Note will be deleted forever. Proceed?')"
                                    style="background:none; border:none; color:#e74c3c; cursor:pointer; margin-left: 10px;">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p>{{ $note->description }}</p>
                    <br><br>
                    <span class="date">Deleted {{ $note->deleted_at->diffForHumans() }}</span>
                </div>
                @empty
                <div style="text-align: center; padding: 50px; color: #888; grid-column: 1 / -1;">
                    <i class="fa-regular fa-trash-can" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Trash is empty!</p>
                </div>
                @endforelse
            </div>


@if($notes->count() > 0)

<div style="margin-top:20px; display:flex; gap:10px;">

    <!-- Restore All Button -->
    <form method="POST" action="{{ route('notes.restoreAll') }}">
        @csrf
        @method('PATCH')

        <button type="submit"
            onclick="return confirm('Restore all deleted notes?')"
            style="
                background-color: #4CAF50;
                color: white;
                padding: 8px 14px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            ">
            Restore All
        </button>
    </form>

    <!-- Delete All Button -->
    <form method="POST" action="{{ route('notes.deleteAll') }}">
        @csrf
        @method('DELETE')

        <button type="submit"
            onclick="return confirm('Are you sure? This will permanently delete all notes.')"
            style="
                background-color: red;
                color: white;
                padding: 8px 14px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            ">
            Delete All Permanently
        </button>
    </form>

</div>

@endif
        </section>
    </main>
</div>

</body>
</html>