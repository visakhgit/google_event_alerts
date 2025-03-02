<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Calendar Alerts</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">
    <h3>Google Event Call Alert!</h3>

    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-error"><?= session('error') ?></div>
    <?php endif; ?>

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-warning">
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('save-phone') ?>">
        <?= csrf_field() ?>
        <input type="text" placeholder="Enter phone number" name="phone" value="<?= old('phone') ?>" />
        <button type="submit">Update Phone</button>
    </form>

    <?php if (session()->has('user_phone')) : ?>
        <p><strong>Current Phone:</strong> <?= session()->get('user_phone'); ?></p>
    <?php endif; ?>

    <?php if (!empty($events)) : ?>
        <div class="event-list">
            <h4>Upcoming Events</h4>
            <ul>
                <?php foreach ($events as $event) : ?>
                    <li>
                        <strong><?= esc($event->getSummary()) ?></strong> <br>
                        <!-- <small><?= esc($event->getStart()->dateTime ?? 'No time available') ?></small>  -->
                        <?php 
                            $dateTime = $event->getStart()->dateTime ?? null;
                            if($dateTime){
                                $date = new DateTime($dateTime);
                                echo $date->format('d-m-y h:i A');
                            }else{
                                echo 'No time available';
                            }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <p>No upcoming events.</p>
    <?php endif; ?>

    <footer>
        <p><a href="<?= site_url('/auth/logout') ?>" class="logout">Sign Out</a></p>
    </footer>
</div>

</body>
</html>

