#!/usr/bin/env bash
# Puja /public al hosting d'IONOS per SSH (rsync).
#
# Preparació (un sol cop), a ~/.ssh/config:
#   Host agnesaran
#     HostName <servidor SSH d'IONOS>
#     User <usuari SSH>
#     IdentityFile ~/.ssh/id_ed25519
# i després:  ssh-copy-id agnesaran
#
# Ús:
#   ./deploy.sh            → simulació (no puja res, mostra què canviaria)
#   ./deploy.sh --go       → puja de veritat
set -euo pipefail

HOST="${DEPLOY_HOST:-agnesaran}"
REMOTE_DIR="${DEPLOY_DIR:-agnesaran}"   # carpeta creada al hosting d'IONOS (relativa a l'arrel SSH)

cd "$(dirname "$0")"

DRY="--dry-run"
[[ "${1:-}" == "--go" ]] && DRY=""

rsync -avz --delete $DRY \
  --exclude ".DS_Store" \
  --exclude "cache/*.csv" \
  public/ "$HOST:$REMOTE_DIR/"

[[ -n "$DRY" ]] && echo -e "\n(Simulació. Per pujar de veritat: ./deploy.sh --go)"
