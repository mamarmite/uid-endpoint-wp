<?php

namespace Mamarmite\UIDEndpoint\Templates;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>/r/<?php echo \get_bloginfo('name'); ?></title>
    </head>
    <body>
        <main>
            <h1><?php echo \get_bloginfo('name'); ?> Unique identifiers base endpoint</h1>
            <section>
                <h2>Entities</h2>
                <ul>
                    <li>Event</li>
                    <li>Artist / agent</li>
                    <li>Organisation</li>
                    <li>Place</li>
                    <li>CreativeWork</li>
                </ul>
            </section>
        </main>
    </body>
</html>
