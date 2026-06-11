# UID for WordPress
Version 0.5.0 pour topo.art #wordpress.

Ajouter des identifiants uniques à des publications (post) wordpress selon un type prédéterminé grâce au CPT.

# Dépendances
- ACF (checkup d'installation à faire).

# Schemas
Les schémas sont le travail de [Capacoa](https://capacoa.ca/fr/). J'ai adapté et généré les outils grâce au travail sémantique de Capacoa.
Le répertoire contenant les [schémas de travail et générés par ce plugin](https://github.com/mamarmite/topo.art-schemas) a été ajouté en parallèle. Mais il y a présent dans l'historique de ce répertoire.

# Redirection 303
J'ai besoin d'ajouter un paramètre pour l'url de redirection et forme du uid selon la structure interne.
```apacheconf
# BEGIN Mamarmite UID
RewriteEngine On
RewriteRule ^r/([^/.]+)/?$ https://stg.topo.art/uid/ldjson?uid=http://topo.art/r/$1 [R=303,NE,L]
# END Mamarmite UID
```

# Contributeur
- [Marc-André Martin - Mamarmite](https://github.com/mamarmite) (développement du plugin pour Wordpress.)
- [CAPACOA](https://capacoa.ca) (schemas et structure des données)

# Défis du mandat

- Ajouter les UID à des données déjà existante dans un système.
- Ajouter des ENDPOINT pour le json+ld pratique pour l'utilisateur, les robots et le RObot moisonneur d'Artsdata.

# À faire
- [x] `UID` sous forme d'url (identifiant pérenne version 1)
- [x] Page de preview pour 
- [ ] Ajouter la redirection 303 au niveau serveur web (apache pour la version 1) automatiquement (présentement en mode manuel).
  - [ ] Protection des endpoints du plugins, surtout pour la redirection 303. 
- [ ] Sauvegarder les UID dans la BD.
- [x] Ajout du endpoint `/jsonld` pour avoir un accès au jsonld brute avant l'`UID` via une redirection 303.
- [ ] Internationalisation des textes, présentement en français seulement.
