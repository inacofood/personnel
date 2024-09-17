<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with Rubic landing page.">
    <meta name="author" content="Devcrud">
    <title>Personnel | INACO</title>
	<link rel="stylesheet" href="{{ asset('css/rubic.css') }}">
	<link rel="icon" type="image/x-icon" href="{{ asset('imgs/favicon.ico') }}">
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">
    <header class="header d-flex justify-content-center">
        <div class="container text-center">
            <div class="row h-100 align-items-center">
                <div class="col-md-7">
                    <div class="header-content">
                        <h3 class="header-title"><strong class="text-primary">PERSONNEL </strong><span class="text-light">HCM</span></h3>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block mx-auto">
                    <form class="header-form" action="/login" method="post">
                        @csrf
                        <div class="head">Login</div>
                        <div class="body">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email*" autofocus required value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password*" required>
                            </div>
                        </div>
                        <div class="footer">
                            <button class="btn btn-primary btn-block">Login</button>
                        </div>
                    </form>
                    @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                </div>
            </div>
        </div>

    </header>

	<!-- core  -->
    <script src="{{ asset('vendors/jquery/jquery-3.4.1.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.bundle.js') }}"></script>
    <!-- bootstrap 3 affix -->
	<script src="{{ asset('vendors/bootstrap/bootstrap.affix.js') }}"></script>

    <!-- Rubic js -->
    <script src="{{ asset('js/rubic.js') }}"></script>
</body>
</html>

