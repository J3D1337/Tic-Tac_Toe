## FEATURES

- Two-player mode (X and O)
- Win condition checking (rows, columns, diagonals)
- Tracks player moves using MySQL database.
- Board reset upon win.
- Alternating player turns using PHP sessions.
- Simple HTML form for player interaction.

## How To Install

- Clone the repository: git clone https://github.com/yourusername/tic-tac-toe-php.git
cd tic-tac-toe-php

- Create a database: CREATE DATABASE tic_tac_toe;

USE tic_tac_toe;

CREATE TABLE game_board (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position INT NOT NULL,
    taken BOOLEAN DEFAULT FALSE,
    player VARCHAR(1) DEFAULT NULL
);

INSERT INTO game_board (position) VALUES (1), (2), (3), (4), (5), (6), (7), (8), (9);

