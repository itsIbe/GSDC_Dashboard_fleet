{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes bounce-smooth {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }
    .bounce-logo { animation: bounce-smooth 2s infinite ease-in-out; }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeInUp 1s ease-out forwards; }
  </style>
</head>
<body class="bg-gradient-to-br from-orange-400 via-blue-800 to-orange-500 flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">

  <!-- Card -->
  <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-w-5xl w-full fade-in">

    <!-- Left Section with Logo -->
    <div class="flex flex-col items-center justify-center w-full md:w-1/2 p-6 sm:p-10 bg-gradient-to-br from-blue-50 to-blue-100 text-center">
      <img src="{{ asset('images/GSDC-LOGO-NOBG.png') }}" 
           alt="Logo" 
           class="w-40 h-40 sm:w-56 sm:h-56 lg:w-64 lg:h-64 object-contain bounce-logo">
      <h2 class="text-lg sm:text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-blue-700 mt-4">
        Great Sierra Development Corporation
      </h2>
      <p class="text-gray-600 mt-2 text-sm sm:text-base">
        Welcome back to GSDC. Sign in to access your account.
      </p>
    </div>

    <!-- Right Section with Form -->
    <div class="w-full md:w-1/2 p-6 sm:p-10 bg-gradient-to-br from-white to-blue-50">
      <h2 class="text-xl sm:text-2xl font-bold mb-2 text-orange-500">Sign in</h2>
      <p class="text-gray-500 mb-6 text-sm sm:text-base">Enter your credentials to access your account</p>
      
      <!-- ✅ Laravel Login Form -->
      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1 text-sm sm:text-base">E-mail</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                 placeholder="your.email@example.com" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 
                        focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm sm:text-base">
          @error('email')
            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

      <!-- Password -->
<div>
  <label for="password" class="block text-gray-700 font-medium mb-1 text-sm sm:text-base">Password</label>
  <div class="relative">
    <input type="password" id="password" name="password" required 
           placeholder="********" 
           class="w-full border border-gray-300 rounded-lg px-4 py-2 
                  focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm sm:text-base pr-10">
    
    <!-- 👁️ Toggle Button -->
    <span onclick="togglePassword()" 
          class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500 hover:text-gray-700">
      <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" 
           class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 
              4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
    </span>
  </div>
  @error('password')
    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
  @enderror
</div>


        <!-- Remember Me -->
        <div class="flex items-center">
          <input id="remember_me" type="checkbox" name="remember" 
                 class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
          <label for="remember_me" class="ml-2 block text-sm text-orange-600">
            Remember me
          </label>
        </div>

        <!-- Error Message -->
        @if(session('error'))
          <p class="text-red-600 text-sm mt-2">{{ session('error') }}</p>
        @endif

        <!-- Submit -->
        <button type="submit"
           class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold 
                  py-2 px-4 rounded-lg mt-4 shadow-md transition duration-200 ease-in-out transform hover:scale-[1.02] text-sm sm:text-base">
          Sign In
        </button>

      </form>
    </div>
  </div>
<script>
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      // Switch icon to eye-off
      eyeIcon.outerHTML = `
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7
                   a9.956 9.956 0 012.55-4.364m2.025-1.68A9.956 9.956 0 0112 5c4.478 0
                   8.269 2.943 9.543 7a9.956 9.956 0 01-4.568 5.818M15 12a3 3 0 11-6 0
                   3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M3 3l18 18" />
        </svg>`;
    } else {
      passwordInput.type = "password";
      // Switch back to normal eye
      eyeIcon.outerHTML = `
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
                   9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>`;
    }
  }
</script>

</body>
</html>
{{-- End --}}
