<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
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
<body class="bg-gradient-to-br from-orange-400 via-blue-800 to-orange-500 flex items-center justify-center min-h-screen">

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
        Create your account to get started with GSDC.
      </p>
    </div>

    <!-- Right Section with Form -->
    <div class="w-full md:w-1/2 p-10 bg-gradient-to-br from-white to-blue-50">
      <h2 class="text-2xl font-bold mb-2 text-orange-500">Create Account</h2>
      <p class="text-gray-500 mb-6">Fill in your details to register</p>
      
      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <!-- First Name -->
          <div>
            <label for="name" class="block text-gray-700 font-medium mb-1">First Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="given-name"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Last Name -->
          <div>
            <label for="lastname" class="block text-gray-700 font-medium mb-1">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required autocomplete="family-name"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('lastname') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Role -->
          <div>
            <label for="role" class="block text-gray-700 font-medium mb-1">Role</label>
            <select id="role" name="role" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select role</option>
              <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Department -->
          <div>
            <label for="department" class="block text-gray-700 font-medium mb-1">Department</label>
            <input type="text" id="department" name="department" value="{{ old('department') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('department') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Location -->
          <div>
            <label for="location" class="block text-gray-700 font-medium mb-1">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Position -->
          <div>
            <label for="position" class="block text-gray-700 font-medium mb-1">Position</label>
            <input type="text" id="position" name="position" value="{{ old('position') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('position') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Business Unit -->
          <div>
            <label for="bu" class="block text-gray-700 font-medium mb-1">Business Unit (BU)</label>
            <input type="text" id="bu" name="bu" value="{{ old('bu') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('bu') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password_confirmation') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-4 shadow-md transition duration-200 ease-in-out transform hover:scale-[1.02]">
          Register
        </button>

        <!-- Link to login -->
        <p class="text-center text-sm text-gray-600 mt-4">
          Already registered?
          <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
        </p>
      </form>

    </div>
  </div>

  <!-- Success Modal -->
  @if(session('success'))
  <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
      <h2 class="text-xl font-bold text-green-600">REGISTERED SUCCESSFULLY!</h2>
      <p class="text-gray-600 mt-2">You can now log in with your account.</p>
      <button onclick="document.getElementById('successModal').style.display='none'"
              class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        OK
      </button>
    </div>
  </div>
  @endif
</body>
</html>
