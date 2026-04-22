# TaskIdPrefix — Plugin Kanboard

Remplace le caractère `#` devant les numéros de tâche dans toutes les notifications
Kanboard (corps des emails, cloche de notification web) par un préfixe configurable.

**Préfixe par défaut :** `n°`

Exemple : la tâche `#42` s'affiche `n°42` dans tous les emails de notification et dans
le panneau de notifications web.

---

## Installation

1. Copiez le dossier `TaskIdPrefix` dans le répertoire `plugins/` de votre instance
   Kanboard :
   ```
   plugins/
   └── TaskIdPrefix/
       ├── Plugin.php
       ├── Helper/
       ├── Controller/
       ├── Template/
       └── ...
   ```
2. Rechargez Kanboard (videz l'OPcache PHP si nécessaire).
3. Le plugin est automatiquement activé. Rendez-vous dans **Paramètres → Task ID Prefix**
   pour choisir votre préfixe.

Aucune dépendance Composer. Compatible PHP 7.4+.

---

## Configuration

Dans **Paramètres → Task ID Prefix**, saisissez le préfixe souhaité et cliquez sur
**Enregistrer**.

| Préfixe | Affichage |
|---------|-----------|
| `n°`    | `n°42`    |
| `#`     | `#42` (comportement Kanboard original) |
| `T-`    | `T-42`    |
| `REF-`  | `REF-42`  |

Si le champ est laissé vide, le préfixe par défaut `n°` est utilisé.

La valeur est stockée dans la table `settings` de la base de données Kanboard
(clé : `taskidprefix_prefix`).

---

## Périmètre d'action

Ce plugin surcharge les points de rendu suivants :

**Corps d'email (notifications) :**
- Création, modification, fermeture, réouverture de tâche
- Déplacement de colonne, changement d'assigné
- Création, modification, suppression de commentaire
- Création, modification, suppression de sous-tâche

**Notification web (cloche) :**
- Tous les événements listés dans le panneau `/notifications`

---

## Limitation connue : sujets d'emails

Les **sujets d'emails** ne sont pas modifiés dans cette version. Kanboard génère les
sujets depuis des chaînes de format codées en dur dans ses classes `EventBuilder` PHP
(ex. `"Task #%d closed"`), qui ne sont pas accessibles via le système de surcharge de
templates.

Le corps de l'email affiche bien le préfixe configuré. Seule la ligne `Objet :` du
message continue d'afficher `#42`.

Cette limitation sera adressée dans une version ultérieure (v2.0.0).

---

## Vérification de la dérive upstream

Lors d'une mise à jour majeure de Kanboard, les templates core peuvent changer. Exécutez
le script fourni pour détecter toute dérive :

```bash
# Comparer avec la branche main
./plugins/TaskIdPrefix/check_upstream.sh

# Comparer avec un tag spécifique
./plugins/TaskIdPrefix/check_upstream.sh v1.2.36
```

Le script télécharge chaque template upstream depuis GitHub et le compare au fichier
correspondant dans ce plugin. Toute différence structurelle est signalée avec un diff.

**Recommandation :** lancez ce script après chaque mise à jour de Kanboard et mettez à
jour les templates du plugin si nécessaire.

---

## Compatibilité

- Kanboard >= 1.2.20
- PHP 7.4+
- Aucune dépendance externe

---

## Licence

MIT — voir [LICENSE](LICENSE)
