<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultScribe</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboardstyle.css') }}">
</head>

<body>

<div class="container">

    <aside class="sidebar">
        <div class="logo">
            <h2>VaultScribe</h2>
        </div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <i class="fa-solid fa-file-lines"></i> Notes
            </a>

            <a href="#" class="menu-item">
                <i class="fa-regular fa-star"></i> Important
            </a>

            <a href="{{route('notes.trash')}}" class="menu-item">
                <i class="fa-regular fa-trash-can"></i> Trash
            </a>

              {{-- 2FA Section --}}
    @if(auth()->user()->google2fa_enabled)

        <div class="menu-item" style="color: #22c55e;">
            <i class="fa-solid fa-shield-check"></i> 2FA Enabled
        </div>

        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            <button type="submit"
                    class="menu-item"
                    style="background:none;border:none;color:#ef4444;text-align:left;">
                <i class="fa-solid fa-shield-xmark"></i> Disable 2FA
            </button>
        </form>

    @else

        <a href="{{ route('2fa.setup') }}" class="menu-item">
            <i class="fa-solid fa-shield-halved"></i> Enable 2FA
        </a>

    @endif
        </nav>

        <div class="user-profile">
            <img src="https://i.pravatar.cc/150?img=3" class="avatar" alt="User Avatar">

            <div class="user-info">
                <h4>{{auth()->user()->name}}</h4>
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
        <section class="add-note-card">
            <h3>Add a Note</h3>
            <form id="noteForm" method="POST" action="{{route('notes.store')}}" >
                @csrf
                <input name="title"
                    type="text"
                    placeholder="Write the title"
                    class="note-title-input">
                <textarea 
                    name="description"
                    placeholder="Write something meaningful..."
                    class="note-body-input"
                ></textarea>

                <div class="card-footer">
                    <span class="shortcut-hint">[Ctrl + S to save]</span>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </section>


        <section class="notes-section">
            <div class="section-header">
                <h3>My Notes</h3>
                <span class="text-muted">Recently viewed</span>
            </div>
           
            
            <div class="notes-grid">
                @foreach($notes as $note)
                <div class="note-card card-blue">
                    <div class="note-header">
                        <h4>{{$note->title}}</h4>
                        <div class="actions">
                            <a href="{{ route('notes.edit', $note->id) }}">
                            <i class="fa-solid fa-pen"></i>
                            </a>

                              <form action="{{ route('notes.delete', $note) }}" method="POST" style="display:inline;">
                               @csrf
                               @method('DELETE')

                            <button type="submit"
                             onclick="return confirm('Delete this note?')"
                            style="background:none;border:none;cursor:pointer;">
                             <i class="fa-solid fa-trash"></i>
                           </button>
                           </form>
                            
                        </div>
                    </div>
                    <p>{{$note->description}}</p>
                    <br><br>
                    <span class="date">{{ $note->created_at->diffForHumans() }}</span>
                </div>
                @endforeach

            </div>
            
        </section>

    </main>


</div>
<script>
document.addEventListener('keydown', function(e) {

    if (e.ctrlKey && e.key === 's') {
        e.preventDefault(); 
        
        document.getElementById('noteForm').submit();
    }

});
</script>

</body>
</html>