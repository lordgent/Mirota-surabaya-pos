<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | POS</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">

  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Register Akun</h2>

    <div id="error" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>
    <div id="success" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"></div>

    <div class="space-y-4">
      <input
        type="text"
        id="name"
        placeholder="Nama Lengkap"
        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <input
        type="email"
        id="email"
        placeholder="Email"
        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <input
        type="password"
        id="password"
        placeholder="Password"
        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <input
        type="password"
        id="password_confirmation"
        placeholder="Konfirmasi Password"
        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        onclick="register()"
        class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition"
      >
        Daftar
      </button>
    </div>
  </div>

  <script>
    async function register() {
      const name = document.getElementById('name').value
      const email = document.getElementById('email').value
      const password = document.getElementById('password').value
      const password_confirmation = document.getElementById('password_confirmation').value
      const errorDiv = document.getElementById('error')
      const successDiv = document.getElementById('success')

      errorDiv.classList.add('hidden')
      successDiv.classList.add('hidden')

      try {
        await axios.get('/sanctum/csrf-cookie')

        await axios.post('/api/signup', {
              name,
              email,
              password,
              password_confirmation
            })

        successDiv.textContent = 'Pendaftaran berhasil! Silakan login.'
        successDiv.classList.remove('hidden')

        // Opsional redirect otomatis
        setTimeout(() => {
          window.location.href = '/login'
        }, 1500)
      } catch (error) {
        console.error(error)
        const message = error.response?.data?.message || 'Gagal daftar. Coba lagi.'
        errorDiv.textContent = message
        errorDiv.classList.remove('hidden')
      }
    }
  </script>
</body>
</html>
