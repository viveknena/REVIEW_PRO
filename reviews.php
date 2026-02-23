<?php

// Include database connection
include('db_connection.php');

// Fetch reviews from the database
$reviews_query = "SELECT * FROM reviews";
$result = mysqli_query($conn, $reviews_query);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Stored Reviews</h1>";
    echo "<ul>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['review_text'] . "; <a href='download.php?id=" . $row['id'] . "'>Download</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews found.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
