<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <nav class="col-md-2 d-none d-md-block bg-white sidebar shadow-sm p-0">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.books.index') }}">Books</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.members.index') }}">Members</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.borrows.index') }}">Borrows</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.reports.borrowed') }}">Borrowed Books</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.reports.overdue') }}">Overdue Books</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('library.reports.most_borrowed') }}">Most Borrowed</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 