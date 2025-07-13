<?php
session_start();
include 'database.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search ? "WHERE title LIKE '%$search%' OR content LIKE '%$search%'" : '';

// Count total
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM posts $search_sql");
$total = mysqli_fetch_assoc($count_query)['total'];
$pages = ceil($total / $limit);

// Fetch posts
$query = "SELECT * FROM posts $search_sql ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?= $_SESSION['username']; ?></h2>
    <a href="create.php" class="btn">+ Add Post</a>
    <a href="logout.php" class="btn logout-btn">Logout</a>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['content']); ?></td>
            <td><?= $row['created_at']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn">Edit</a>
                <a href="delete.php?id=<?= $row['id']; ?>" class="btn logout-btn" onclick="return confirm('Are you sure to delete?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++) { ?>
            <a href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>" class="btn"><?= $i; ?></a>
        <?php } ?>
    </div>
</div>
</body>
</html>
