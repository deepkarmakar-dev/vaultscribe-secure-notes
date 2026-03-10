<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Poppins, sans-serif;
            background: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #555;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Edit Note ✏</h2>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div style="color:red;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('notes.update', $note->id) }}">
        @csrf
        @method('PUT')

        <label>Title</label>
        <input type="text" name="title" value="{{ $note->title }}" required>

        <label>Description</label>
        <textarea name="description" rows="5">{{ $note->description }}</textarea>

        <button type="submit">Update Note</button>
    </form>

    <a href="{{ route('dashboard') }}" class="back-link">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>
