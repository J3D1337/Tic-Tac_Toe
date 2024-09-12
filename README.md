Features
-Two-player mode (X and O).
-Win condition checking (rows, columns, diagonals).
-Tracks player moves using MySQL database.
-Board reset upon win.
-Alternating player turns using PHP sessions.
-Simple HTML form for player interaction.


//Clone the repository:

git clone https://github.com/yourusername/tic-tac-toe-php.git
cd tic-tac-toe-php


//Set up the database:

Create a MySQL database named tic_tac_toe and run the following SQL queries:
---------------------------->
CREATE DATABASE tic_tac_toe;

USE tic_tac_toe;

CREATE TABLE game_board (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position INT NOT NULL,
    taken BOOLEAN DEFAULT FALSE,
    player VARCHAR(1) DEFAULT NULL
);

INSERT INTO game_board (position) VALUES (1), (2), (3), (4), (5), (6), (7), (8), (9);
---------------------------->

Code Architecture
Here is an explanation of the key components and the code architecture of the project:

//Database Connection

---------------------------->
$mysqli = new mysqli("localhost", "root", "", "tic_tac_toe");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
---------------------------->


//Reset the Board

---------------------------->
function reset_board($mysqli) {
    $mysqli->query("UPDATE game_board SET taken = FALSE, player = NULL");
    $_SESSION['turn'] = 'X'; // Reset turn to 'X'
}
---------------------------->


//Fetch the Current Board State


---------------------------->
$board = array();
$result = $mysqli->query("SELECT * FROM game_board ORDER BY position ASC");
while ($row = $result->fetch_assoc()) {
    $board[$row['position']] = $row;
}
---------------------------->


//Update the Board with Player Moves


---------------------------->
function update_board($mysqli, $position, $player) {
    $stmt = $mysqli->prepare("UPDATE game_board SET taken = TRUE, player = ? WHERE position = ? AND taken = FALSE");
    $stmt->bind_param("si", $player, $position);
    return $stmt->execute();
}
---------------------------->

//Check for a Winner


---------------------------->
function check_winner($board) {
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
---------------------------->


//Display the Game Board


---------------------------->
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
---------------------------->
