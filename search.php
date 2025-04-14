<?php
require_once "utils.php";

$search = $_GET["search"] ?? '';
$page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vynkly - Search Results</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-6">
  <div class="max-w-4xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
      <a href="index.php" class="text-3xl font-bold text-blue-600 hover:underline">Vynkly</a>
      <a href="all.php" class="text-sm text-blue-500 hover:underline">All Websites</a>
    </div>

    <form method="GET" action="search.php" class="mb-6">
      <input
        name="search"
        value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Search websites..."
        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
      />
    </form>

    <?php if (!empty($search)): ?>
      <?php
        $db = new Websites();
        $results = $db->find($search, $page);
        $data = $results["data"];
        $totalPages = $results["total_pages"];
        $currentPage = $results["current_page"];
      ?>

      <?php if (empty($data)): ?>
        <p class="text-red-500">No results found for "<?php echo htmlspecialchars($search); ?>"</p>
      <?php else: ?>

        <p class="mb-4 text-gray-600 dark:text-gray-400">About <?php echo $results["total_results"]; ?> results found</p>
        
        <?php foreach ($data as $entry): ?>
          <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <a href="<?php echo htmlspecialchars($entry['url']); ?>" target="_blank" class="text-sm text-green-600 hover:underline">
              <?php echo htmlspecialchars($entry['url']); ?>
            </a>
            <h2 class="text-xl font-semibold text-blue-600 hover:underline">
              <a href="<?php echo htmlspecialchars($entry['url']); ?>" target="_blank">
                <?php echo htmlspecialchars($entry['title'] ?? 'Untitled'); ?>
              </a>
            </h2>
            <p class="mt-1 text-gray-700 dark:text-gray-300 text-sm">
              <?php echo htmlspecialchars($entry['description'] ?? ''); ?>
            </p>
          </div>
        <?php endforeach; ?>

        <div class="flex flex-wrap justify-center items-center gap-2 mt-8">
          <?php if ($currentPage > 1): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $currentPage - 1; ?>"
              class="px-3 py-2 border rounded bg-white dark:bg-gray-800 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400">
              ← Prev
            </a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"
              class="px-3 py-2 border rounded <?php echo $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>

          <?php if ($currentPage < $totalPages): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $currentPage + 1; ?>"
              class="px-3 py-2 border rounded bg-white dark:bg-gray-800 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400">
              Next →
            </a>
          <?php endif; ?>
        </div>

      <?php endif; ?>
    <?php else: ?>
      <p class="text-gray-500">No input given. Go back and enter a search query.</p>
    <?php endif; ?>
  </div>
</body>
</html>
