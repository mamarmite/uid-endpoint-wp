# UID for WordPress
Version 0.2.0 pour topo.art #wordpress.

Ajouter des identifiants uniques à des publications (post) wordpress selon un type prédéterminé grâce au CPT.

# Dépendances
- ACF (checkup d'installation à faire).

# Schemas
Les schémas sont le travail de [Capacoa](https://capacoa.ca/fr/). J'ai adapté et généré les outils grâce au travail sémantique de Capacoa.
Le répertoire contenant les [schémas de travail et générés par ce plugin](https://github.com/mamarmite/topo.art-schemas) a été ajouté en parallèle. Mais il y a présent dans l'historique de ce répertoire.

# À faire
- [ ] `UID` sous forme d'url.
- [ ] Ajout de la gestion des `id`, `uid` et `url` dans les données brute (BD).
- [ ] Ajout du endpoint `/jsonld` pour avoir un accès au jsonld brute avant l'`UID` via une redirection 303.
- [ ] Internationalisation des textes
- [ ] Centraliser l'utilisation des date pour gérer les timezones à un endroits (BD > Schema > User)

# Contributeur
- [Marc-André Martin - Mamarmite](https://github.com/mamarmite) (développement du plugin pour Wordpress.)
- [CAPACOA](https://capacoa.ca) (schemas et structure des données)

# Défis du mandat

- Ajouter les UID à des données déjà existante dans un système.
- Ajouter des ENDPOINT pour le json+ld pratique pour l'utilisateur, les robots et le RObot moisonneur d'Artsdata.
