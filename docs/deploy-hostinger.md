# Deploy em produção — Hostinger

O WordPress precisa de um servidor com PHP e banco de dados; por isso o site é hospedado na **Hostinger**. O fluxo abaixo leva o tema do seu computador ao servidor.

## Princípio (Opção A)

A Hostinger só **puxa o repositório**: ela não roda `npm` nem `composer`. Por isso os arquivos gerados vão **dentro do Git**:

| Vai para o Git | Fica de fora |
|---|---|
| `assets/css/main.css` (gerado) | `node_modules/` |
| `assets/vendor/swiper/` (gerado) | arquivos temporários/locais (`.gitignore`) |
| `assets/fonts/*.woff2` | `*.zip`, `.env`, `.vscode/` |
| `vendor/` (Carbon Fields) | |
| `package-lock.json` e `composer.lock` | |

O `.gitignore` do tema já está assim.

## 1. Preparar o repositório (uma vez)

1. Crie um repositório **privado** no GitHub (ou outro serviço Git).
2. Na pasta do tema:

```powershell
git init
git branch -M main
git remote add origin <URL-DO-REPOSITORIO>
```

## 2. Compilar para produção (a cada publicação)

```powershell
npm install
npm run build
composer install --no-dev --optimize-autoloader
```

Nota: hoje o tema só tem dependência de produção (Carbon Fields), então o `--no-dev` não remove nada; ele fica como boa prática.

Confira que existem `assets/css/main.css`, `assets/vendor/swiper/`, `assets/fonts/*.woff2` e `vendor/`.

## 3. Versionar e enviar

```powershell
git add .
git status          # confirme: main.css, assets/vendor e vendor/ aparecem; node_modules NÃO
git commit -m "Publicação: descreva a mudança"
git push -u origin main
```

## 4. Preparar o WordPress na Hostinger

1. No hPanel, instale o WordPress no domínio (instalador automático) e conclua o assistente.
2. Ative o **SSL** (HTTPS) do domínio.
3. Anote o caminho do tema: `public_html/wp-content/themes/faruk-arteterapia/`.

## 5. Publicar — Método 1: Git no hPanel (recomendado)

Os nomes dos menus podem variar um pouco; procure por **Avançado → Git**.

1. Em **Git**, crie um novo repositório informando:
   - endereço do repositório (URL SSH ou HTTPS);
   - branch: `main`;
   - diretório de instalação: `public_html/wp-content/themes/faruk-arteterapia` (a pasta deve estar vazia/inexistente na primeira vez).
2. Repositório **privado**: a Hostinger mostra uma **chave SSH pública**. Cadastre-a no GitHub em *Settings → Deploy keys* do repositório (somente leitura basta).
3. Clique em **Deploy** (ou *Pull*). Pronto: os arquivos chegam ao servidor.
4. **Atualizações automáticas (opcional):** copie a URL de *webhook / auto deployment* e cadastre no GitHub em *Settings → Webhooks* (evento *push*). A cada `git push`, o site atualiza.

## 6. Publicar — Método 2: SSH

Em **Avançado → Acesso SSH**, veja usuário, IP e porta (na Hostinger costuma ser a **65002**):

```bash
ssh -p 65002 USUARIO@IP-DO-SERVIDOR
cd domains/SEU-DOMINIO/public_html/wp-content/themes
git clone <URL-DO-REPOSITORIO> faruk-arteterapia      # primeira vez
cd faruk-arteterapia && git pull                      # próximas vezes
```

(Confira o caminho exato do site no hPanel.)

## 7. Publicar — Método 3: FTP

1. **Arquivos → Contas FTP**: copie servidor, usuário e senha.
2. No FileZilla, envie a pasta `faruk-arteterapia` (**sem** `node_modules`) para `public_html/wp-content/themes/`.
3. Substitua tudo a cada atualização, mantendo `vendor/` e `assets/` completos.

## 8. Depois de publicar

1. **Aparência → Temas →** ativar **Faruk Arteterapia**.
2. Criar páginas, menus e preencher **Configurações do site** (`docs/instalacao-local-windows.md`, passos 7 e 8).
3. **Configurações → Links permanentes →** *Salvar* (corrige erros 404).
4. Seguir `docs/checklist-pos-lancamento.md`.

## 9. Segurança e rotina

- Mantenha WordPress e plugins atualizados; faça backup (hPanel → Backups) antes de cada publicação.
- Não versione senhas nem `wp-config.php`.
- Teste no LocalWP antes de publicar (`docs/roteiro-de-testes.md`).
