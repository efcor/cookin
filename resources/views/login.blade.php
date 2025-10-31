<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cookin' Login</title>
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-slate-50 to-white">

  <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 space-y-6 border border-slate-100">
    <div class="text-center">
      <h1 class="text-3xl font-semibold text-slate-700">Cookin'</h1>
      <p class="text-slate-300 mt-2">Enter your access code to continue</p>
    </div>

    <form method="POST" action="/login" class="space-y-4">
      @csrf
      <div>
        <label for="access_code" class="block text-slate-700 font-medium mb-2">Access Code</label>
        <input
          id="access_code"
          name="access_code"
          type="text"
          required
          class="w-full rounded-xl border border-slate-100 focus:ring-2 focus:ring-coral-300 focus:outline-none px-4 py-3 text-slate-700 text-lg placeholder-slate-300"
          placeholder="Enter your code"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-coral-500 hover:bg-coral-700 text-white font-semibold py-3 rounded-xl transition duration-200"
      >
        Continue
      </button>
    </form>

    <div class="text-center text-sm text-slate-300">
      <p>Don’t have a code? <a href="#" class="text-coral-500 hover:text-coral-700 font-medium">Request one</a></p>
    </div>
  </div>

</body>
</html>
