# Post Status

Post Status: Just Share What's on your Mind to the Hello World.

> Best usage for Personal Updates and Teams usage.  

## Built USing

- HTML
- Bulma CSS
- Javascript
- Axios
- PHP
- PDO
- MYSQL

## Features

- Home page with Pagination
- Like Status and View Total Likes (per Post 100 likes limit if you want more check `src/models/Like.php`)
- Separate Status by Slug

## Setup

- Create database `query.sql` use this Queries to create database for Store Status data
- Update database details at `/src/config/database.php`
- Insert Status `KEY` Manually into database table `api_keys`
- Done

## Testing

- Start Development Server

```sh
php -S localhost:6052 -t public
```

- Create Status and View Status

```sh

## Access Home page URL to post status
http://localhost:6052

## View Status
http://localhost:6052/status_page.php?slug=status-66853b80a16b1

```

## LICENSE

MIT
