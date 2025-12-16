<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="turbo-prefetch" content="false">
   <title>Login - MemberSidoel - Trusted Hosting Service</title>
   <link rel="shortcut icon" href="{{ asset('images/logo.webp') }}" type="image/x-icon">
   @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50">
<div class="min-h-screen flex items-center justify-center p-4">
   <div class="max-w-5xl w-full bg-white rounded-3xl shadow overflow-hidden">
      @yield('content')
   </div>
</div>
</body>
</html>
