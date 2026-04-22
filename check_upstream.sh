#!/usr/bin/env bash
# check_upstream.sh — Détecte la dérive des templates par rapport à Kanboard upstream.
#
# Usage : ./check_upstream.sh [branche_ou_tag]
# Exemples :
#   ./check_upstream.sh          (utilise main)
#   ./check_upstream.sh v1.2.36

set -euo pipefail

BRANCH="${1:-main}"
BASE_URL="https://raw.githubusercontent.com/kanboard/kanboard/${BRANCH}/app/Template"
PLUGIN_DIR="$(cd "$(dirname "$0")" && pwd)/Template"
WORK_DIR="$(mktemp -d)"
trap 'rm -rf "$WORK_DIR"' EXIT

# Liste des templates overridés par ce plugin
TEMPLATES=(
    "notification/task_create"
    "notification/task_update"
    "notification/task_close"
    "notification/task_open"
    "notification/task_move_column"
    "notification/task_assignee_change"
    "notification/comment_create"
    "notification/comment_update"
    "notification/comment_delete"
    "notification/subtask_create"
    "notification/subtask_update"
    "notification/subtask_delete"
    "web_notification/show"
)

CHANGED=0
ERRORS=0

echo "Vérification des templates contre kanboard/kanboard@${BRANCH}"
echo "============================================================"

for TPL in "${TEMPLATES[@]}"; do
    URL="${BASE_URL}/${TPL}.php"
    SAFE_NAME="${TPL//\//_}"
    UPSTREAM="${WORK_DIR}/upstream_${SAFE_NAME}.php"
    PLUGIN_FILE="${PLUGIN_DIR}/${TPL}.php"

    printf "%-55s " "${TPL}.php ..."

    # Télécharge le template upstream
    HTTP_CODE=$(curl -sf -w "%{http_code}" -o "$UPSTREAM" "$URL" 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "000" ] || [ ! -s "$UPSTREAM" ]; then
        echo "ERREUR (impossible de télécharger)"
        ERRORS=$((ERRORS + 1))
        continue
    fi

    # Vérifie que le fichier local existe
    if [ ! -f "$PLUGIN_FILE" ]; then
        echo "MANQUANT dans le plugin !"
        CHANGED=$((CHANGED + 1))
        continue
    fi

    # Normalise le fichier du plugin : retire les substitutions intentionnelles
    # pour comparer uniquement la structure avec l'upstream.
    NORMALIZED="${WORK_DIR}/normalized_${SAFE_NAME}.php"

    sed \
        -e 's|(<?= $this->prefix->getPrefix() ?><?= $task\['"'"'id'"'"'\] ?>)|(#<?= $task['"'"'id'"'"'\] ?>)|g' \
        -e '/<?php $msg = t(/{N; s/<?php $msg = t(\(.*\), \$task\['"'"'id'"'"'\]);\n.*echo str_replace.*/<?= t(\1, $task['"'"'id'"'"'\]) ?>/; }' \
        -e 's|<?= $this->text->e($applyPrefix($notification\['"'"'title'"'"'\])) ?>|<?= $notification['"'"'title'"'"'\] ?>|g' \
        -e '/\$prefix = \$this->prefix->getPrefix();/d' \
        -e '/\$applyPrefix = function/,/};/d' \
        "$PLUGIN_FILE" > "$NORMALIZED"

    # Compare upstream vs normalisé
    if diff -q "$UPSTREAM" "$NORMALIZED" > /dev/null 2>&1; then
        echo "OK"
    else
        echo "MODIFIÉ — diff :"
        diff --unified "$UPSTREAM" "$NORMALIZED" || true
        echo ""
        CHANGED=$((CHANGED + 1))
    fi
done

echo ""
echo "============================================================"
if [ "$ERRORS" -gt 0 ]; then
    echo "Avertissement : ${ERRORS} template(s) n'ont pas pu être téléchargés."
fi

if [ "$CHANGED" -eq 0 ] && [ "$ERRORS" -eq 0 ]; then
    echo "Tous les templates sont synchronisés avec upstream ${BRANCH}."
    exit 0
else
    echo "Attention : ${CHANGED} template(s) diffèrent de l'upstream."
    echo "Vérifiez les diffs ci-dessus et mettez à jour le plugin si nécessaire."
    exit 1
fi
