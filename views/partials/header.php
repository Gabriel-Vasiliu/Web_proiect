<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Collecting Bottles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" type="image/x-icon" href="/images/favicon1.ico">
  </head>
  <body>
    <nav>
      <input type="checkbox" id="check">
      <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
      </label>
      <label class="logo"></label>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/top">Top</a></li>
                <li><a href="/search">Search</a></li>
                
                <?php if (App\Core\App::isGuest()): ?>
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Register</a></li>
                <?php else: ?>
                <li>
                  <a href="/logout">Welcome, <?php echo App\Core\App::$user->getDisplayName(); ?>
                    (Logout)
                  </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        