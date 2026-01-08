<?php
require_once 'includes/config.php';

header('Content-Type: text/html; charset=utf-8');

echo '<h2>Categories Debug</h2>';
$result = mysqli_query($conn, "SELECT id, name, slug, status FROM categories ORDER BY id");
if (!$result) {
    echo '<p style="color:red;">Query failed: ' . mysqli_error($conn) . '</p>';
    exit;
}

echo '<table border="1" cellpadding="6" cellspacing="0">';
echo '<tr><th>id</th><th>name</th><th>slug</th><th>status</th></tr>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['slug']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '</tr>';
}

echo '</table>';

?>