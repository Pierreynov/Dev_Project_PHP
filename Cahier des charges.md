Site Web Foot 

Accueil / Equipes (Classements et Résultats)  / Boutique / Profil 
PhP / SQL
Base de données pour la connexion / Classements et Résultats

Cahiers des charges 

Conception d'un site web (php , html/css)
Creation d'une base de données (SQL) pour la connexion 

Page Accueil	
	Actualités du club, Photos, Derniers résultats des equipes et matchs a venir 

Page Equipes 
	Affichage des classements, resultats, calendriers et prochains matchs 
	Possibilité de trier par Equipes 
	Saisons précédentes 

Page Boutique 
	Possibilité d'acheter des objets, vetements, avec gestion de stock via BDD
	

Profil 
	Role au sein du club, Infos personelles 

Mise en place BDD complète,
Création du site (accueil) -> Autres pages avec redirection 
Fonctionnalités -> Boutique 

        BASE DE DONNEES
    
    Table User 
        - User_Id
        - Nom
        - MDP
        - Date
        - Solde
        - Rôle
        - Admin
    
    Table Equipes
        - Id
        - Nom
        - Points
        - Matchs joués
        - Victoires
        - Egalités
        - Défaites
        - Forfait
        - Buts pour
        - Buts contre
        - Diff

    Table Matchs
        - Date
        - Equipe Domicile
        - Score Equipe Domicile
        - Equipe Exterieure
        - Score Equipe Exterieure
        - Lieu 
    
    Table Article 
        - Id
        - Nom
        - Prix 
        - Stock 
        - Image


