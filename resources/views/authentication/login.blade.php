<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8" />
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!-- Scrollbar -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />

  <!-- Custom Style -->
  <style>
    body.login-page {
      background-image: url({{ asset('assets/image/coal.jpg') }});
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      position: relative;
      margin: 0;
    }
    .footer-copyright {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      padding: 0.75rem 0;
      background-color: rgba(26, 26, 26, 0.1);
      color: #facc15;
      font-size: 0.9rem;
      z-index: 10;
      user-select: none;
    }

    body.login-page::before {
      content: '';
      background-color: rgba(0, 0, 0, 0.3);
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      z-index: 0;
    }

    .login-box {
      z-index: 1;
      position: relative;
    }

    .card.card-outline.card-primary {
      background-color: #1a1a1a;
      border-radius: 30px;
      color: #facc15;
      border-top: 4px solid #facc15;
      box-shadow: 0 0 30px rgba(255, 255, 0, 0.15);
    }

    .card-body {
      padding: 1.75rem;
    }

    .logo-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.25rem 0.5rem;
      margin-bottom: 1rem;
      border-bottom: 1px solid #333;
    }

    .logo-header img {
      height: 45px;
      object-fit: contain;
    }

    .form-floating > .form-control {
      background-color: #111;
      border: 1px solid #444;
      color: #fff;
      border-radius: 12px;
    }

    .form-floating > label {
      color: #facc15;
    }

    .input-group-text {
      background-color: #333;
      color: #facc15;
      border: none;
      border-radius: 0 12px 12px 0;
    }

    .form-check-label {
      color: #ccc;
    }

    .btn-primary {
      background-color: #facc15;
      color: #1a1a1a;
      font-weight: bold;
      border: none;
      border-radius: 12px;
    }

    .btn-primary:hover {
      background-color: #eab308;
      color: #000;
    }

    a {
      color: #facc15;
    }

    a:hover {
      color: #eab308;
    }
  </style>
</head>

<body class="login-page">
  <div class="login-box mx-auto" style="max-width: 450px;">
    <div class="card card-outline card-primary">
      
      <!-- LOGO HEADER INSIDE CARD -->
      <div class="logo-header">
        <img src="{{ asset('assets/image/resindo-logo.jpg') }}" alt="Resindo Logo">
        <img src="{{ asset('assets/image/benchmark-logo.png') }}" alt="Benchmark Logo">
      </div>

      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Email -->
          <div class="mb-3">
            <div class="input-group">
              <div class="form-floating flex-grow-1">
                <input id="loginEmail" name="email" type="email" class="form-control" placeholder="Email" required />
                <label for="loginEmail">Email</label>
              </div>
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            </div>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <div class="input-group">
              <div class="form-floating flex-grow-1">
                <input id="loginPassword" name="password" type="password" class="form-control" placeholder="Password" required />
                <label for="loginPassword">Password</label>
              </div>
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            </div>
          </div>

          <!-- Remember + Submit -->
          <div class="row mb-3">
            <div class="col-8 d-flex align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                <label class="form-check-label" for="remember"> Remember Me </label>
              </div>
            </div>
            <div class="col-4">
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Sign In</button>
              </div>
            </div>
          </div>
        </form>

        <p class="mb-1 text-center">
          <a href="#">I forgot my password</a>
        </p>
      </div>
    </div>
  </div>
  
   <div class="footer-copyright">
      &copy; {{ date('Y') }} Isolutions Indonesia. All rights reserved.
    </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('js/adminlte.js') }}"></script>
</body>
</html>