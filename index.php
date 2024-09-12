<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "tic_tac_toe");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to reset the board
function reset_board($mysqli) {
    $mysqli->query("UPDATE game_board SET taken = FALSE, player = NULL");
    $_SESSION['turn'] = 'X'; // Reset turn to 'X'
}

// Reset the board at the start of the session (optional)
if (!isset($_SESSION['game_started'])) {
    reset_board($mysqli);
    $_SESSION['game_started'] = true;
}

// Fetch the current board state
$board = array();
$result = $mysqli->query("SELECT * FROM game_board ORDER BY position ASC");
while ($row = $result->fetch_assoc()) {
    $board[$row['position']] = $row;
}

// Function to update the board after a player move
function update_board($mysqli, $position, $player) {
    $stmt = $mysqli->prepare("UPDATE game_board SET taken = TRUE, player = ? WHERE position = ? AND taken = FALSE");
    $stmt->bind_param("si", $player, $position);
    return $stmt->execute();
}

// Check if there's a winner
function check_winner($board) {
    // Winning combinations (rows, columns, diagonals)
    $winning_combos = [
        [1, 2, 3], [4, 5, 6], [7, 8, 9], // Rows
        [1, 4, 7], [2, 5, 8], [3, 6, 9], // Columns
        [1, 5, 9], [3, 5, 7]              // Diagonals
    ];

    foreach ($winning_combos as $combo) {
        if ($board[$combo[0]]['player'] &&
            $board[$combo[0]]['player'] === $board[$combo[1]]['player'] &&
            $board[$combo[0]]['player'] === $board[$combo[2]]['player']) {
            return $board[$combo[0]]['player'];
        }
    }

    return null;
}

// Handle form submission for a move
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $position = intval($_POST['position']);
    $player = $_SESSION['turn'];

    if (update_board($mysqli, $position, $player)) {
        // Reload the board after the move
        $result = $mysqli->query("SELECT * FROM game_board ORDER BY position ASC");
        $board = array();
        while ($row = $result->fetch_assoc()) {
            $board[$row['position']] = $row;
        }

        // Check for a winner after the move
        $winner = check_winner($board);
        if ($winner) {
            echo "<p>Player $winner wins!</p>";
            reset_board($mysqli); // Reset the board after a win
        } else {
            // Switch turns between players
            $_SESSION['turn'] = ($_SESSION['turn'] === 'X') ? 'O' : 'X';
        }
    }
}

// Display the game board
echo "<table border='1'>";
for ($i = 1; $i <= 9; $i++) {
    if ($i % 3 === 1) echo "<tr>";
    $cell = $board[$i];
    echo "<td style='width:50px;height:50px;text-align:center;font-size:24px;'>";
    if ($cell['taken']) {
        echo $cell['player'];
    } else {
        echo "<form method='POST' style='display:inline-block;'>";
        echo "<input type='hidden' name='position' value='$i'>";
        echo "<input type='submit' value='{$_SESSION['turn']}'>";
        echo "</form>";
    }
    echo "</td>";
    if ($i % 3 === 0) echo "</tr>";
}
echo "</table>";

// Close the database connection
$mysqli->close();
?>
