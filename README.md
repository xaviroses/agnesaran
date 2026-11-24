# agnesaran.com

Web de l'Agnès Aran: portada, `/lactancia` (CA/ES/EN), `/veu` i `/cuina`.

Les plantilles són PHP, però la web que es publica és **estàtica**: un GitHub Action executa les plantilles, llegeix el contingut del Google Sheet i publica l'HTML a **GitHub Pages**, amb SSL gratuït.

## Com s'actualitza

| Què canvia | Qui | Com |
|---|---|---|
| Tallers, preus, formació, vídeos | l'Agnès | Edita el Google Sheet. La web s'actualitza sola en uns minuts. |
| Disseny, textos fixos, codi | en Xavi | `git push` a `main` |

El workflow [`.github/workflows/publish.yml`](.github/workflows/publish.yml) s'executa:
- en cada push;
- quan el full avisa que hi ha canvis, amb l'Apps Script de [`google-apps-script/Publicar.gs`](google-apps-script/Publicar.gs);
- cada hora, com a xarxa de seguretat;
- a mà, des de la pestanya Actions.

Si Google no respon durant el build, el build falla i la web publicada **no es toca**.

## Treballar en local

```bash
php -S localhost:8080 -t public
```

Per generar la versió estàtica a `dist/`:

```bash
php build.php
```

Variables d'entorn del build:
- `BUILD_STRICT=1`: si el full no respon, s'atura amb error.
- `BASE_PATH=/agnesaran`: per publicar a `xaviroses.github.io/agnesaran` sense domini propi.
- `CNAME=agnesaran.com`: escriu el fitxer CNAME.

## Estructura

```
public/                  plantilles i recursos (també funciona tal qual en un hosting amb PHP)
  index.php              portada (NO PUC ESTAR QUIETA)
  lactancia/ (es/ en/)   lactància en CA / ES / EN
  veu/  cuina/
  inc/
    config.php           ID del Google Sheet i de cada pestanya, WhatsApp, URL del lloc
    sheet.php            lectura del full, memòria cau i utilitats
    lactancia.php        plantilla de lactància (comuna als 3 idiomes)
    lactancia-i18n.php   textos fixos de lactància
  data/*.csv             contingut inicial / còpia de seguretat
  assets/                CSS, JS, imatges
build.php                genera dist/ (HTML estàtic + sitemap + 404)
google-apps-script/      script del full que avisa GitHub dels canvis
deploy.sh                pujada per SSH a un hosting amb PHP (staging a IONOS)
```

## Contingut editable (Google Sheet)

Un sol full de càlcul amb 4 pestanyes, més una pestanya d'instruccions. El nom de les columnes ha de ser exactament aquest (l'ordre és lliure). La fila 1 és la capçalera.

| Pestanya | Columnes | On surt |
|---|---|---|
| `tallers` | visible, data, data_text_ca/es/en, titol_ca/es/en, lloc, adreca, preu, enllac | Lactància → Propers tallers |
| `preus` | visible, grup_ca/es/en, concepte_ca/es/en, preu | Lactància → Preus |
| `formacio` | visible, grup_ca/es/en, anys, text_ca/es/en | Lactància → Experiència i formació |
| `videos` | visible, categoria, titol, url | Veu |

Normes per omplir-lo:
- **visible:** si poses `no`, la fila no surt. Buit vol dir que sí.
- **Grups** (preus i formació): la fila que té el `grup_ca` omplert obre un bloc nou. Les files de sota, amb el grup buit, hi pertanyen.
- **Text:** `**negreta**`, `[text](https://enllaç)`, i salts de línia dins la cel·la.
- **Traduccions:** si una columna `_es` o `_en` és buida, es fa servir el català.
- **Tallers:**
  - `data` (dd/mm/aaaa) és opcional. Si hi és, el taller deixa de sortir l'endemà, al següent build (com a mínim n'hi ha un cada hora).
  - `enllac` buit vol dir que el botó obre WhatsApp amb el nom del taller ja escrit.
  - `adreca` genera l'enllaç a Google Maps.
- **preu:** si només hi ha un número, s'hi afegeix €.
- **anys:** `2021-actualitat` es tradueix sol.
- **videos:** `categoria` ha de ser locucions, jingles o musica.

El full ha d'estar compartit com "Qualsevol persona amb l'enllaç: lector".

## Posada en marxa de GitHub Pages

1. Al repositori, a **Settings → Pages → Source**, triar **GitHub Actions**.
2. Mentre no hi hagi domini: a **Settings → Secrets and variables → Actions → Variables**, crear `BASE_PATH` = `/agnesaran`.
3. Amb el domini:
   - Verificar `agnesaran.com` a GitHub (**Settings del compte → Pages → Add a domain**), amb el TXT a cdmon.
   - DNS a cdmon: quatre registres A per a `@` → `185.199.108.153`, `185.199.109.153`, `185.199.110.153`, `185.199.111.153`, i un CNAME `www` → `xaviroses.github.io`.
   - Variables: `CNAME` = `agnesaran.com`; esborrar `BASE_PATH`.
   - A **Settings → Pages**, el domini personalitzat ha de ser `agnesaran.com`, amb **Enforce HTTPS**.
4. Apps Script del full: les instruccions són a la capçalera de `Publicar.gs`. Cal un token *fine-grained* només per a aquest repositori, amb **Contents: Read and write**.

## Pendent

- [ ] Activar GitHub Pages i fer el primer build
- [ ] Instal·lar l'Apps Script al full, amb el token
- [ ] DNS a cdmon cap a GitHub i domini verificat; HTTPS
- [ ] Transferir la propietat del Google Sheet a l'Agnès
- [ ] Allotjar les fonts a la web (ara venen de Google Fonts)
- [ ] Textos legals (avís legal, privacitat, cookies, en CA/ES/EN) i bàner de cookies
- [ ] Favicon i imatge per compartir (OG) definitiva
- [ ] Optimitzar les imatges (WebP / mides)
- [ ] Contingut real: títols dels vídeos, fotos originals, revisió ES/EN, gif de COCU en més resolució
- [ ] Guia d'una pàgina per a l'Agnès
