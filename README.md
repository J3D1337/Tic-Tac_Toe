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

- Create a database: CREATE DATABASE tic_tac_toe;<br>
                    USE tic_tac_toe;<br>
                      CREATE TABLE game_board (<br>
                        id INT AUTO_INCREMENT PRIMARY KEY,<br>
                        position INT NOT NULL,<br>
                        taken BOOLEAN DEFAULT FALSE,<br>
                        player VARCHAR(1) DEFAULT NULL<br>
                    );<br>
                    INSERT INTO game_board (position) VALUES (1), (2), (3), (4), (5), (6), (7), (8), (9);


- Code comments explain the code arhitecture
