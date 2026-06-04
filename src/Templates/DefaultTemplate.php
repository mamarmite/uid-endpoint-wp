<?php

namespace Mamarmite\UIDEndpoint\Templates;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class DefaultTemplate extends AbstractTemplate
{
    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function render_head():void {
        ?>
        <script type="application/ld+json" class="unique-id-endpoint">
            <?php echo \wp_json_encode($this->entity->transform(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
        </script>
        <?php
    }

    public function render_content():void {
        ?>
        <h3><span class="schema-type"><?php echo $this->entity->post_type->labels->singular_name; ?></span></h3>
        <h2><?php echo $this->entity->uid->full(); ?></h2>
        <h1><?php echo \get_the_title(); ?></h1>
        <section>
            <div class="schema-container">
                <div><button onclick="copyCodeHandler(this)">🗐</button></div>
                <pre><code id="schemaJsonLd"><?php echo json_encode($this->entity->transform(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></code></pre>
            </div>
        </section>
        <script>
            async function copyCodeHandler(button) {
                const codeElement = document.getElementById('schemaJsonLd');
                const text = codeElement.textContent;
                const originalText = button.textContent;

                try {
                    await navigator.clipboard.writeText(text);

                    button.textContent = 'Copié!';
                    button.classList.add('copied');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('copied');
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy text: ', err);
                    button.textContent = 'Erreur';
                    setTimeout(() => {
                        button.textContent = originalText;
                    }, 2000);
                }
            }
        </script>
        <?php
    }

    public function render_footer():void {
        ?><?php
    }
}
?>
