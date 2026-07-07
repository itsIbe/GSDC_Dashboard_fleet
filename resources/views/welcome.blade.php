<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Bounce animation for logo */
    @keyframes bounce-smooth {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }
    .bounce-logo { animation: bounce-smooth 2s infinite ease-in-out; }

    /* Fade-in animation for card */
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeInUp 1s ease-out forwards; }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-blue-400 to-blue-200 flex items-center justify-center min-h-screen">

  <!-- Card -->
  <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-full fade-in">
    
    <!-- Left Section with Logo -->
    <div class="flex flex-col items-center justify-center w-full md:w-1/2 p-10 bg-gradient-to-br from-blue-50 to-blue-100">
      <img src="{{ asset('images/GSDC-LOGO-NOBG.png') }}" 
           alt="Logo" 
           class="w-64 h-64 object-contain bounce-logo">
       <h2 class="text-1xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-blue-700 text-center">
         Great Sierra Development Corporation
       </h2>
      <p class="text-gray-600 mt-2 text-center">
        Welcome back to GSDC. Sign in to access your account.
      </p>
    </div>

    <!-- Right Section with Form -->
    <div class="w-full md:w-1/2 p-10 bg-gradient-to-br from-white to-blue-50">
      <h2 class="text-2xl font-bold mb-2 text-orange-700">Sign in</h2>
      <p class="text-gray-500 mb-6">Enter your credentials to access your account</p>
      
      <!-- ✅ Real Laravel Login Form -->
      <form method="POST" action="" class="space-y-4">
        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">E-mail</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                 placeholder="your.email@example.com" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 
                        focus:outline-none focus:ring-2 focus:ring-blue-700">
          @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
          <input type="password" id="password" name="password" required 
                 placeholder="********" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 
                        focus:outline-none focus:ring-2 focus:ring-blue-700">
          @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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

        <!-- Submit -->
        <button type="submit"
           class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold 
                  py-2 px-4 rounded-lg mt-4 shadow-md transition duration-200 ease-in-out transform hover:scale-[1.02]">
          Sign In
        </button>

      </form>
    </div>
  </div>

</body>
</html>
