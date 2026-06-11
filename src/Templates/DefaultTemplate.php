<?php

namespace Mamarmite\UIDEndpoint\Templates;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class DefaultTemplate extends AbstractTemplate
{

    public $json_endpoint_url;
    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        parent::__construct($post);
        $this->json_endpoint_url = \get_home_url().'/'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_LDJSON_ENDPOINT.'?uid='.$this->entity->uid->full();
    }

    public function render_head():void {
        ?>
        <link rel="canonical-jsonld" href="<?php echo $this->json_endpoint_url; ?>" type="json" />
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
                <div>
                    <button onclick="copyCodeHandler(this)">Copier</button>
                    <a href="<?php echo $this->json_endpoint_url; ?>" class="btn" title="json">Format JSON</a>
                </div>
                <pre><code id="schemaJsonLd"><?php echo json_encode($this->entity->transform(true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></code></pre>
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
