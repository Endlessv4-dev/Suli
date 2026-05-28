<?php
session_start();
require "../Connection/config.php";

$keresett = isset($_GET['keresett']) ? $_GET['keresett'] : '';
$guesses = isset($_SESSION['guesses']) ? $_SESSION['guesses'] : [];

$query_str = "SELECT id, name, icon FROM items WHERE name LIKE '%$keresett%' ORDER BY name ASC";
$result = $conn->query($query_str);

if ($result && mysqli_num_rows($result) > 0) {
    while ($item = $result->fetch_assoc()) {
        if (in_array($item['id'], $guesses)) {
            continue;
        }
        ?>
        <form method="POST" action="">
            <input type="hidden" name="guessed_item_id" value="<?= $item['id']; ?>">
            <button type="submit" name="guess-btn" class="dropdown-item">
                <img src="../Items/<?= $item['name']; ?>/<?= $item['icon']; ?>" alt="Icon">
                <span><?= htmlspecialchars($item['name']); ?></span>
            </button>
        </form>
        <?php
    }
} else {
    echo "<div style='padding: 12px; color: #9ca3af;'>No items found...</div>";
}
?>