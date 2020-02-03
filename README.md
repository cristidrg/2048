<p align="center">
  <a href="https://github.com/cristidrg/2048">
    <img src="public/images/2048.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">2048 Live</h3>

  <p align="center">
    A multiplayer version of 2048, inspired by Twitch Plays Pokemon.
    <br />
    <a href="https://live-2048.herokuapp.com/"><strong>View demo »</strong></a>
    <br />
    <br />
  </p>
</p>

## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [How to play](#how-to-play)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Development Notes](#development-notes-and-challenges)
  * [Database Design](#database-design)
  * [Back-end Design](#back-end-design)
  * [Front-end Design](#front-end-design)
* [My Notes](#my-notes)
* [Contact](#contact)

## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

This project is an variant of the 2048 game which introduces a multiplayer feature, in which players move blocks on the board via chat messages.

It served as a self-development tool to learn how to implement web sockets on back-end.

### Built With

* [Laravel](https://laravel.com)
* [Pusher](https://pusher.com/)
* [TailWind CSS](https://tailwindcss.com/)
* [Vue.js](https://vuejs.org/)
* [Anime.js](https://animejs.com/)

## How To Play

The rules are almost identical to the original 2048 game, create a 2048 block in order to win the game.
The game starts by default with one obstacle, a randomly placed block which acts as a wall. 
Players can modify how many obstacles are on the board in the settings.

To move the blocks, send movement commands: 'left', 'right', 'bottom' or 'top' -- In lower case format

To facilitate testing and debugging, arrow keys and touch gestures inside the grid are available.

[Click to play!](https://live-2048.herokuapp.com/)

## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* [Composer](https://getcomposer.org/doc/00-intro.md)
* [Laravel 6+](https://laravel.com/docs/6.x)
* [Node.js](https://nodejs.org/en/download/)
* A MySQL database
* [Pusher](https://dashboard.pusher.com/accounts/sign_up) - free version is ok
* Optional - [Sentry](http://sentry.io/)

### Installation
 
1. Clone the repository
```sh
git clone https://github.com/cristidrg/2048.git
```
2. Create a .env file from the .env.example template and fill in the blanks

3. Install Laravel Dependencies
```sh
composer install
```

4. Install NPM dependencies
```sh
npm install
```

4. Create Database Schema
```sh
php artisan migrate
```

5. Populate Database with sample data
```sh
php artisan db:seed
```

6. Serve the project locally
``` sh
php artisan serve
```

## Development Notes And Challenges

### Database Design
* The first step I took was to create the shape of the database, which looks like so:
- Game has:
    - obstacleCount (int)
    - Blocks (one-to-many)
        - row (int)
        - column (int)
        - value (int)
- Messages

This database schema fulfills all the needs for a single game session. Figuring out if a game
is done can be checked via the blocks values.

If we would want to have multiple game rooms at the same time which also support
turn-based turns, we could use the following structure:

- GameRoom has:
    - democracyActive (bool)
    - ActiveUserId (string)
    - Game (one-to-one)
        - obstacleCount (int)
        - Blocks (one-to-many)
            - row (int)
            - column (int)
            - value (int)
    - Messages (one-to-many)
    - ListOfUsers (one-to-many)

### Back-end Design
Laravel is a MVC framework. In the game I used a single controller for both messages and the game actions to get it done faster.
All of the game logic is done on the back-end, while the front-end only displays the data its being given and sends commands to the back-end
to generate a new state.

The merging algorithm can be found in the GridHelper class. I went and implemented a brute-force to cut down development time.
Besides sliding and merging, there were 2 important constraints to keep in mind:
* Obstacles should be ignored
* A block can only merge once during a move

The biggest challenge I had to face was to implement the multiplayer aspect. It had to listen to events made by other players, thus
using web sockets was mandatory. I haven't implemented web sockets before, but I watched some tutorials and then I got the job done
using Laravel Events and Pusher.io. There are two public channels used:

- BroadcastMessageCreation - for messages
- GameUpdated - for game state

The API routes are:
```
+--------+---------------+----------------------------+------+----------------------------------------------------------+--------------+
| Domain | Method        | URI                        | Name | Action                                                   | Middleware   |
+--------+---------------+----------------------------+------+----------------------------------------------------------+--------------+
|        | GET|HEAD      | /                          |      | Closure                                                  | web          |
|        | GET|HEAD      | api/game/{id}              |      | Closure                                                  | web          |
|        | POST          | api/game/{id}/commands     |      | App\Http\Controllers\GameController@handleCommand        | web          |
|        | POST          | api/game/{id}/message      |      | App\Http\Controllers\GameController@receiveMessage       | web          |
|        | POST          | api/game/{id}/setObstacles |      | App\Http\Controllers\GameController@setObstacles         | web          |
+--------+---------------+----------------------------+------+----------------------------------------------------------+--------------+
```
The /commands route accepts the playing commands "left","right","top","bottom" as well as "restart".

### Front-end Design
The front-end was built using Vue.js to ease data manipulation. The game grid is built using div elements positioned absolutely inside the grid container. Anime.js was leveraged to create smooth animations.

Theming is done via TailWind CSS.

## Contact
Cristian Dragomir - [@linkedin](https://www.linkedin.com/in/cristidrg/) - cristiandrg96@gmail.com

<!-- IMAGES -->
[product-screenshot]: public/images/product.png