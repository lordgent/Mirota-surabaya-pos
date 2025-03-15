<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | POS</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">

  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login ke POS</h2>

    <div id="error" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>

    <div class="space-y-4">
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

      <button
        onclick="login()"
        class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition"
      >
        Login
      </button>
    </div>
  </div>

  <script>
  async function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('error');

    try {
      await axios.get('/sanctum/csrf-cookie');

      const response = await axios.post('/api/signin', {
        email,
        password
      });

      if (response.data.status === 'success') {
        localStorage.setItem('auth_token', response.data.data.token);

        localStorage.setItem('user', JSON.stringify(response.data.data.user));

        window.location.href = '/staff/dashboard';
      } else {
        throw new Error('Login gagal');
      }
    } catch (error) {
      console.error(error);
      errorDiv.textContent = 'Login gagal. Periksa email & password.';
      errorDiv.classList.remove('hidden');
    }
  }
</script>

</body>
</html>
