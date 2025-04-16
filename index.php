<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Vynkly - a VIT-AP's Search Engine, open records of students projects" />
  <meta name="author" content="Ganesh M">
  <meta name="keywords" content="Vynkly, VIT-AP, vitap, search engine">
  <title>Vynkly - VIT-AP's Search Engine</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col">

  <main class="flex-grow flex flex-col items-center justify-center px-4">
    <div class="w-full max-w-xl text-center">
      <h1 class="text-4xl font-bold mb-4">Vynkly</h1>
      <p class="mb-8 text-gray-600 dark:text-gray-400">A VIT-AP's Search Engine</p>
      
      <form method="GET" action="search.php" class="flex flex-col sm:flex-row items-center gap-4 mb-6">
        <input
          name="search"
          autocomplete="off"
          type="text"
          placeholder="Search websites..."
          class="flex-1 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <button
          type="submit"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
        >
          Search
        </button>
      </form>
      <div class="flex flex-col gap-5">
        <a
          href="update.php"
          class="inline-block mt-2 text-blue-500 hover:underline"
        >
          ➕ Update the database
        </a>
        <a href="all.php" class="text-sm text-blue-500 hover:underline">All Websites</a>
      </div>
    </div>
  </main>

  <footer class="w-full border-t border-gray-300 dark:border-gray-700 pt-4 pb-6 text-center text-sm text-gray-600 dark:text-gray-400">
    <p>
      Made with ❤️ by 
      <a target="_blank" href="https://github.com/prodev717/" class="hover:underline font-semibold">Ganesh M</a>, 
      <a target="_blank" href="https://www.instagram.com/teamx.vit/" class="hover:underline font-semibold">TeamX</a>.
    </p>
  </footer>

</body>
</html>
