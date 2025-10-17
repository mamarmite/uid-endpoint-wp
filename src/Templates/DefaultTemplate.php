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
    <title>/r/ <?php echo \get_the_title() . \get_bloginfo('name'); ?></title>
</head>
<body>
<main>
    <h1><?php echo \get_the_title(); ?> Unique identifiers</h1>
    <section>
    </section>
</main>
</body>
</html>
