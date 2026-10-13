# Taxonomia oficial de eventos — GTM / dataLayer

Cada clique em um elemento com `data-gtm-event` envia ao `dataLayer`:

`{ event, link_location, link_text, link_url, [item_name], [lead_type] }`

| Evento | link_location | Parâmetros extras | Onde |
|---|---|---|---|
| `whatsapp_click` | `header`, `header_mobile`, `hero`, `contact_section`, `final_cta`, `floating_button`, `footer`, `groups_waitlist` | — | Header, Hero, Canais de Contato, Chamada Final, botão flutuante, Rodapé, aviso de Grupos |
| `instagram_click` | `contact_section`, `footer` | — | Canais de Contato, Rodapé |
| `form_lead_click` | `groups_projects`, `schools_companies`, `supervision` | `lead_type` = `project_signup` / `school` / `company` / `institution` / `supervision`; `item_name` = nome do projeto/card | Grupos e Vivências, Escolas e Empresas, Supervisão |
| `cta_click` | `hero`, `dual_entry`, `other_formats` | `item_name` | Hero (botão de link), Duas Portas, "Outros formatos" |
| `anchor_nav_click` | `anchor_nav` | — | Navegação por Âncoras |
| `nav_click` | `header_nav`, `footer_nav` | — | Menus do Header (desktop e mobile) e do Rodapé |
| `faq_toggle` | `faq` | `item_name` = pergunta | FAQ (só ao ABRIR) |
| `phone_click` | `footer` | — | Rodapé (telefone e CVV 188) |
| `cookie_consent_granted` | — | — | Banner de cookies (ao aceitar) |

Atributos HTML: `data-gtm-event`, `data-gtm-location`, `data-gtm-text` (opcional, substitui o texto visível), `data-gtm-item`, `data-gtm-lead-type`.

## Consent Mode v2
Padrão no `<head>`: `ad_storage`, `ad_user_data`, `ad_personalization` e `analytics_storage` = `denied`.
Aceitar -> `gtag('consent','update', {...granted})` + evento `cookie_consent_granted` + `localStorage.faruk_cookie_consent = 'granted'`.
Recusar -> mantém `denied` + `localStorage.faruk_cookie_consent = 'denied'`.
