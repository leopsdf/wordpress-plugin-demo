<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrap">
  <h1>INCLUA – Demo de Integração</h1>
  <form method="post" action="options.php">
    <?php settings_fields('inclua_demo_group'); ?>
    <?php do_settings_sections('inclua_demo_group'); ?>
    <table class="form-table" role="presentation">
      <tr>
        <th scope="row"><label for="inclua-demo-api">URL base da API</label></th>
        <td>
          <input type="url" name="<?php echo esc_attr(INCLUA_Demo_Plugin::OPTION_API_BASE); ?>" id="inclua-demo-api" class="regular-text"
                 value="<?php echo esc_attr(get_option(INCLUA_Demo_Plugin::OPTION_API_BASE, 'http://localhost:8000/')); ?>" />
          <p class="description">Ex.: http://localhost:8000/</p>
        </td>
      </tr>
    </table>
    <?php submit_button('Salvar'); ?>
  </form>
  <hr />
  <h2>Como usar</h2>
  <p>Insira o shortcode no conteúdo de uma página: <code>[inclua_quote text="exemplo de texto"]</code></p>
</div>
