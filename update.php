<?php
require_once "utils.php";

if (!empty($_GET["url"]) && !empty($_GET["title"])) {
    $db = new Websites();
    $db->create($_GET["url"], $_GET["title"], $_GET["description"] ?? '', $_GET["author"] ?? '', $_GET["keywords"] ?? '');
    echo "<p class='text-green-600'>Website updated successfully.</p>";
}

$metadata = null;
if (!empty($_POST["fetch"])) {
    $metadata = pagedata($_POST["fetch"]);
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Vynkly - Update Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-6">
    <div class="max-w-3xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <a href="index.php" class="text-3xl font-bold text-blue-600 hover:underline">Vynkly</a>
            <a href="all.php" class="text-sm text-blue-500 hover:underline">View All</a>
        </div>

        <h1 class="text-2xl font-semibold mb-4">Update or Add Website</h1>

        <?php if (!$metadata): ?>
            <form method="POST" action="update.php" class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
                <label class="block font-medium">Enter Website URL:</label>
                <input name="fetch" type="text" autocomplete="off" placeholder="https://example.com"
                    class="w-full px-4 py-2 rounded border bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"/>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Fetch Metadata
                </button>
            </form>
        <?php else: ?>

            <form method="GET" action="update.php" class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4 mt-6">
                <?php foreach ($metadata as $key => $value): ?>
                    <div>
                        <label class="block font-medium capitalize"><?php echo htmlspecialchars($key); ?>:</label>
                        <input type="text" name="<?php echo htmlspecialchars($key); ?>"
                            value="<?php echo htmlspecialchars($value); ?>"
                            class="w-full px-4 py-2 rounded border bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
                    </div>
                <?php endforeach; ?>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Update Database
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
