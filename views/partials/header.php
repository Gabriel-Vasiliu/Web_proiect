<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Collecting Bottles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Stoleriu Daniel, Vasiliu Gabriel">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon1.ico">
  </head>
  <body>
  <header>
    <nav>
    <div id="nav-menu-thumb"></div>
        <input id="nav-menu-toggle" type="checkbox">
            <ul>
                <li><a class="nav-link" href="/">Home</a></li>
                <li><a class="nav-link" href="/bottles/top">Top</a></li>
                <li><a class="nav-link" href="/bottles/search">Search</a></li>
                
                <?php if (App\Core\App::isGuest()): ?>
                <li><a class="nav-link" href="/login">Login</a></li>
                <li><a class="nav-link" href="/register">Register</a></li>
                <?php else: ?>
                <li><a class="nav-link" href="/bottles/manage">Manage</a></li>
                  <li><a href="/logout">Welcome, <?php echo App\Core\App::$user->getDisplayName(); ?>
                    (Logout)
                  </a><li>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
  </header>
  <main>

        