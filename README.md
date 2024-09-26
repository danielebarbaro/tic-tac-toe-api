# Tic Tac Toe Game

## Description
This is a simple Tic Tac Toe game that can be played by two players. 
The game is played on a 3x3 grid. 
The players take turns to place their mark (either 'X' or 'O') on the grid. 
The first player to get three of their marks in a row (horizontally, vertically, or diagonally) wins the game. If all the cells on the grid are filled and no player has won, the game ends in a draw.

## Specifications


The backend system will serve a frontend application developed by a different team, but they have shared a set of product-level requirements that we need to implement through an API. These key requirements are as follows:

1. There should be an endpoint to initiate a new game. The response must return a unique identifier for that game, which will be used in subsequent requests to reference it.

2. Another endpoint is required for making a move within the game. This endpoint should accept the Game ID (from the first endpoint), a player identifier (either player 1 or 2), and the move's position. The response should provide a full representation of the current game board so the UI can reflect the latest server state. Additionally, it should include a status indicating if there is a winner, and if so, which player won.

3. The endpoint handling moves should incorporate basic validation to ensure moves are legitimate, such as confirming it’s the correct player’s turn and that the move is within the allowed parameters (e.g., no playing two consecutive turns or placing pieces on top of existing ones).

## Installation
```bash
  git clone 
  cd tic-tac-toe-api
  composer install
  symfony server:ca:install
```

## Usage
```bash
  symfony serve
  docker-compose up -d
```

## API
Visit http://localhost:8000/api/doc to view the API documentation.

![](swagger.png)


## Test workflow
 - call POST /api/game to start a new game
 - call POST /api/game/{id}/moves to play a move in the game

or use the API documentation to test the endpoints.  
or run the tests using the command below.  

```bash
  php bin/phpunit
```
![](tests/results.png)

Coverage:
```bash
  php bin/phpunit --coverage-html build/coverage
```

## Scratchpad Notes
The board is an array of 9 cells from 1 to 9. 

Board [0, 0, 0, 0, 0, 0, 0, 0, 0]:  
    - 3x3 grid  
    - 9 cells  
    - 3 rows  
    - 3 columns  
    - 2 diagonals  

Board Position mapping:   
```
1 | 2 | 3
---------
4 | 5 | 6
---------
7 | 8 | 9
```


### Routes:
 - POST /api/games | payload: empty
 - PATCH /api/games/{id} | payload: {players: 2}
 - GET /api/games/{id}

 - POST /api/games/{id}/moves | payload: {player: 1, position: 1}
 - GET /api/games/{id}/moves
 - GET /api/moves/{Id}
 
