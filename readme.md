# UID for WordPress

Ajout d'identifiants pérennes et uniques à des publications (post) wordpress selon un type du context `schema.org` prédéterminé grâce à leur CPT.

# Dépendances
- ACF

# Schemas
Les schémas sont le travail de [Capacoa](https://capacoa.ca/fr/). J'ai adapté et généré les outils grâce au travail sémantique de Capacoa.
Le répertoire contenant les [schémas de travail et générés par ce plugin](https://github.com/mamarmite/topo.art-schemas) a été ajouté en parallèle. Mais il y a présent dans l'historique de ce répertoire.

# Redirection 303
Il faut ajouter des configurations de rewrite pour le serveur afin d'assurer la redirection 303.

## Apache
```apacheconf
# BEGIN Mamarmite UID remplacer : [DOMAIN] par le domain du site web et [DOMAIN_UID] par le domaine de votre identifiant pérenne.
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^r/([^/.]+)/?$ https://[DOMAIN]/uid/ldjson?uid=http://[DOMAIN_UID]/r/$1 [R=303,NE,L]
</IfModule>
# END Mamarmite UID
```

La vérification pour la redirection `303` se fait seulement sur un `/r/` sur la base du domain sans la vérification du port de connexion : `80` ou `443`. Donc les deux fonctionnent.

# Terminaisons du plugiciel basées sur le `DOMAIN` de l'installation.
| Terminaison  | Description                                                                                                            | Paramètre d'url | Nécessaire? | Public cible |
|:-------------|:-----------------------------------------------------------------------------------------------------------------------|:---------------:|:-----------:|:------------:|
| `/uid`       | Base du plugiciel, une index actif seulement pour les humains.                                                         |        -        |      -      |    Humain    | 
| `/list`      | Toutes les entités en mode lecture pour les humains (avec des liens vers la page de prévisualisation et version `json` |        -        |      -      |    Humain    | 
| `/list.json` | Toutes les entités en mode `json`                                                                                      |        -        |      -      |   Machine    |
| `/preview`   | Prévisualisation d'un schemas                                                                                          |       uid       |     Oui     |    Humain    |
| `/ldjson`    | Prévisualisation d'un schemas en format `json`                                                                        |       uid       |     Oui     |   Machine    |

## `/list`
Principalement pour avoir prévisualiser les entités sur le site par un humain.

Contient :
- Une liste de toutes les entités
- Un raccourci vers la prévisualisation.
- Un raccourci vers l'entité en mode `JSON`
- Chaque entité contient une balise `<script type="application/ld+json" id="[UID]">` (the `[UID] c'est l'entité)
- Si on accède à la page avec un `#[UID]`, on peut cibler le code `<script>`.

## `/list.json`
Renvoie toutes les entités dans un format `JSON` avec le format :

```json
{
  "@context": "http://schema.org",
  "@graph": [
    {
      "@type": "Place",
      "@id": "http://topo.art/r/t8575",
      "name": "Etc."
    },
    {
      "@type": "Organization",
      "@id": "http://topo.art/r/t8486",
      "name": "TOPO",
      "description": "etc."
    }
  ]
}
```


Il n'y a pas de :
- Vérification si les schemas sont bien remplis

# Contributeurs
- [Marc-André Martin - Mamarmite](https://github.com/mamarmite) (développement du plugiciel pour Wordpress.)
- [CAPACOA](https://capacoa.ca) (schémas et structure des données)

# Défis du mandat

- Ajouter les UID à des données déjà existante dans un système.
- Ajouter des ENDPOINT pour le json+ld pratique pour l'utilisateur, les robots et le Robot moisonneur d'Artsdata.
- Encapsuler le plugiciel en mode prototype dans une version intelligible et assez versatile pour devenir un plugiciel complet.

# À faire
- [x] `UID` sous forme d'url (identifiant pérenne version 1)
- [x] Page de preview pour 
- [x] Ajouter la redirection 303 au niveau serveur web (apache pour la version 1) automatiquement (présentement en mode manuel).
  - [x] Protection des terminaisons du plugiciel, surtout pour la redirection 303.
- [x] Ajout de la terminaison `/jsonld` pour avoir un accès au jsonld brute avant l'`UID` via une redirection 303.
- [x] Ajout de la terminaison pour lecture humaine de toutes les entités.
- [x] Ajout de la terminaison pour toutes les entités en mode json.
- [ ] Confirmer le format `JSON` contenu dans la terminaison `/list.json`

# Améliorations
- [ ] Automatisation de l'installation avec un flush des rewrites des permalink, vérification qu'ACF est installé.
- [ ] Sauvegarder les UID complet dans un meta directement dans la BD.
- [ ] Internationalisation des textes, présentement en français seulement.
- [ ] Ajustement suite aux premiers moissonnages.

# Idées / Backlog
- [ ] Ajouter un pourcentage de complétion des schémas par entité dans la table wordpress directement (et dans la vue `/list`)
- [ ] Sélection des groupes de propriétés (ACF) compatibles via une section paramètres dans le plugiciel.
