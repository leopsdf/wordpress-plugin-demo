# INCLUA – Demo de Integração (WordPress Plugin)

Plugin de demonstração para consumir uma API externa (FastAPI/IA) a partir do WordPress, via shortcode `[inclua_quote]`.

## Requisitos
- WordPress 6+
- PHP 8.1+

## Instalação
1. Faça o download da pasta do plugin e compacte (zip).
2. Em **Plugins > Adicionar novo > Enviar plugin**, carregue o `.zip` e ative.
3. No menu **INCLUA Demo**, defina a **URL base da API** (ex.: `http://localhost:8000/`).
4. Em uma página/post, insira: `[inclua_quote text="políticas públicas e equidade"]`.

## Segurança
- Uso de `sanitize_text_field`, `esc_url_raw` e checagens de erro em `wp_remote_get`.

## Licença
MIT
