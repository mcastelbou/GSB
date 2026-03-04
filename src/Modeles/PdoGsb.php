<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $connexion de type PDO
 * $instance qui contiendra l'unique instance de la classe
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

namespace Modeles;

use PDO;
use Outils\Utilitaires;

require '../config/bdd.php';

class PdoGsb {

    protected $connexion;
    private static $instance = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        
        // Version Standard
        /**/
        $this->connexion = new PDO(DB_DSN, DB_USER, DB_PWD);
        $this->connexion->query('SET CHARACTER SET utf8');
        /**/
        
        // Version Master/Slave
        /*
        try {
            $this->connexion = new PDO(DB_DSN, DB_USER, DB_PWD);
        } catch (\PDOException $e) {
            try {
                $this->connexion = new PDO(DB_DSN_SECOURS, DB_USER_SECOURS, DB_PWD);
            } catch (\PDOException $e) {
                die();
            }
        }
        */
        
        // Version Master/Master 
        /*
        try {
            $serveurs = DB_URL;
            shuffle($serveurs);
            $this->connexion = new PDO("mysql:host=" . $serveurs[0] . ";dbname=" . DB_NAME . ";charset=UTF8", DB_USER, DB_PWD);
            $this->connexion->query('SET CHARACTER SET utf8');
            print_r("Test 1 : Connecté sur " . $serveurs[0]);
        } catch (\PDOException $e) {
            try {
                $this->connexion = new PDO("mysql:host=" . $serveurs[1] . ";dbname=" . DB_NAME . ";charset=UTF8", DB_USER, DB_PWD);
                $this->connexion->query('SET CHARACTER SET utf8');
                print_r("Test 2 : Connecté sur " . $serveurs[1]);
            } catch (\PDOException $e) {
                Utilitaires::ajouterErreur("Les bases de données ne sont actuellement pas disponibles.");
                Utilitaires::ajouterErreur("Veuillez réessayer dans quelques minutes.");
                include PATH_VIEWS . 'v_erreurs.php';
            }
        }
        */

    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct() {
        $this->connexion = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return PdoGsb  l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb(): PdoGsb {
        if (self::$instance == null) {
            self::$instance = new PdoGsb();
        }
        return self::$instance;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT * FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = Utilitaires::dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois): int {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne les informations d'un utilisateur
     *
     * @param String $login  Login de l'utilisateur
     * @param String $mdp  Mot de passe de l'utilisateur
     *
     * @return array  L'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosUtilisateur($login, $mdp): array {
        $role = $this->getRoleUtilisateur($login);
        if ($role == ""){
            return [];
        } else {    
            if ($role == "visiteur") {
                $infos = $this->getInfosVisiteur($login, $mdp);
                $infos['role'] = $role;
            } else if ($role == "comptable") {
                $infos = $this->getInfosComptable($login, $mdp);
                $infos['role'] = $role;
            } else {
                echo '<strong>Connexion impossible</strong>';
            }
            return $infos;
        }
    }

    /**
     * Retourne le role correspondant au login entré par l'utilisateur
     * 
     * @param String $login  Login de l'utilisateur 
     * 
     * @return String  Le role de correspondant au login entré
     */
    public function getRoleUtilisateur($login) : String {
        $requetePrepare = $this->connexion->prepare(
                'SELECT roleuser FROM role WHERE login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        $role = $requetePrepare->fetch();
        if (!is_array($role)){
            return "";
        } else {
            return $role['roleuser'];
        }    
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login  Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return array  L'id, le nom et le prénom sous la forme 
     *         d'un tableau associatif
     */
    public function getInfosVisiteur($login): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT visiteur.id AS id, visiteur.nom AS nom, '
                . 'visiteur.prenom AS prenom '
                . 'FROM visiteur '
                . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un comptable
     * 
     * @param String $login  Login du comptable
     * @param String $mdp  Mot de passe du comptable
     * 
     * @return array  L'id, le nom et le prénom sous la forme 
     *         d'un tableau associatif
     */
    public function getInfosComptable($login): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT comptable.id AS id, comptable.nom AS nom, '
                . 'comptable.prenom AS prenom '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne le mot de passe présent dans la BDD qui correspond au
     * visiteur donné
     * 
     * @param String $login  Le login du visiteur à tester
     * @return String  Le mot de passe correspondant
     */
    public function getMdpVisiteur($login) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT mdp '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }
    
    /**
     * Retourne le mot de passe présent dans la BDD qui correspond au
     * comptable donné
     * 
     * @param String $login  Le login du comptable à tester
     * @return String  Le mot de passe correspondant
     */
    public function getMdpComptable($login) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT mdp '
            . 'FROM comptable '
            . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }
    
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array  l'id, le libelle et la quantité sous la forme 
     *         d'un tableau associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fraisforfait.id as idfrais, '
                . 'fraisforfait.libelle as libelle, '
                . 'lignefraisforfait.quantite as quantite '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait '
                . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fraisforfait.id as idfrais '
                . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
    /**
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais): void {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            if ($unIdFrais != "CV"){
                $qte = $lesFrais[$unIdFrais];
                $requetePrepare = $this->connexion->prepare(
                        'UPDATE lignefraisforfait '
                        . 'SET lignefraisforfait.quantite = :uneQte '
                        . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                        . 'AND lignefraisforfait.mois = :unMois '
                        . 'AND lignefraisforfait.idfraisforfait = :idFrais'
                );
                $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
                $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
                $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
                $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
                $requetePrepare->execute();
            } else {
                if (empty($this->getTypeVehiculeFicheFrais($idVisiteur, $mois))){
                    $this->ajouterTypeVehicule($idVisiteur, $mois, $lesFrais[$unIdFrais]);
                } else {
                    $this->majTypeVehicule($idVisiteur, $mois, $lesFrais[$unIdFrais]);
                }
                
            }
        }
    }
    
    /**
     * Met à jour a table vehiculefraisforfait en enregistrant le type de 
     * véhicule choisi par un visiteur pour une fiche de frais donnée
     * 
     * @param String $idVisiteur  L'id d'un visiteur
     * @param String $mois  Le mois associé à la fiche de frais traitée
     * @param String $typeVehiculeFrais  Le code du type de véhicule choisi
     * 
     * @return void
     */
    public function majTypeVehicule($idVisiteur, $mois, $typeVehiculeFrais): void{
        $requetePrepare = $this->connexion->prepare(
                'UPDATE vehiculefraisforfait '
                . 'SET vehiculefraisforfait.codevehicule = :unTypeVehicule '
                . 'WHERE vehiculefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND vehiculefraisforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unTypeVehicule', $typeVehiculeFrais, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Créé dans la table vehiculefraisforfait un enregistrement du type
     * de vehicule choisi par un visiteur pour une fiche de frais donnée
     * 
     * @param String $idVisiteur  L'id du visiteur
     * @param String $mois  Le mois associé à la fiche de frais traitée
     * @param String $typeVehiculeFrais  Le code du type de véhicule choisi
     * 
     * @return void
     */
    public function ajouterTypeVehicule($idVisiteur, $mois, $typeVehiculeFrais): void{
        $requetePrepare = $this->connexion->prepare(
                'INSERT INTO vehiculefraisforfait (idvisiteur,mois,codevehicule) '
                . 'VALUES (:unIdVisiteur,:unMois,:unTypeVehicule)'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unTypeVehicule', $typeVehiculeFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Met à jour la table ligneFraisHorsForfait en enregistrant 
     * les nouvelles données d'un frais hors forfait donné
     * 
     * @param Integer $idFraisHF  L'identifiant unique d'un frais hors forfait
     * @param String $uneDate  La date associée à un frais hors forfait
     * @param String $unLibelle  Le libellé d'un frais hors forfait
     * @param Float $unMontant  Le montant du frais hors forfait
     * 
     * @return void
     */
    public function majFraisHorsForfait($idFraisHF, $uneDate, $unLibelle, $unMontant): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.date = :uneDate , '
                . 'lignefraishorsforfait.libelle = :unLibelle , '
                . 'lignefraishorsforfait.montant = :unMontant '
                . 'WHERE lignefraishorsforfait.id = :unIdFraisHF '
        );
        $requetePrepare->bindParam(':unIdFraisHF', $idFraisHF, PDO::PARAM_INT);
        $requetePrepare->bindParam(':uneDate', $uneDate, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $unLibelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $unMontant, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Met a jour la table ligneFraisHorsForfait en rajoutant 'REFUSE :'
     * au début du libellé pour un frais hors forfait donné
     * 
     * @param Integer $idFraisHF  L'identifiant unique d'un frais hors forfait
     * @param string $unLibelle  Le libellé d'un frais hors forfait
     * 
     * @return void
     */
    public function refuserFraisHorsForfait($idFraisHF, $unLibelle): void {
        $unLibelle = "REFUSE: " . $unLibelle;
        $requetePrepare = $this->connexion->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :unLibelle '
                . 'WHERE lignefraishorsforfait.id = :unIdFraisHF '
        );
        $requetePrepare->bindParam(':unIdFraisHF', $idFraisHF, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unLibelle', $unLibelle, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Reporte un frais hors forfait au mois suivant en prenant garde de créer la fiche de frais
     * du mois cible si elle n'existe pas encore
     * 
     * @param type $idVisiteur  L'identifiant d'un visiteur
     * @param type $idFraisHF  L'identifiant unique d'un frais hors forfait
     * @param type $uneDate  La date associée à un frais hors forfait
     * @param type $unLibelle  Le libellé d'un frais hors forfait
     * @param type $unMontant  Le montant du frais hors forfait
     * 
     * @return void
     */
    public function reporterFraisHorsForfait($idVisiteur, $mois/*, $idFraisHF, $uneDate, $unLibelle, $unMontant*/): void{
        $laNouvelleDate = $mois;
        $leMois = substr($laNouvelleDate, 4, 2);
        $LAnnee = substr($laNouvelleDate, 0, 4);
        if ($leMois == '12'){
            $laNouvelleDate = intval($LAnnee) + 1 . '01';
        } else {
            $laNouvelleDate += 1;
        }
        if($this->estPremierFraisMois($idVisiteur, $laNouvelleDate)){
            $this->creeNouvellesLignesFrais($idVisiteur, $laNouvelleDate);
        }
        
        // transmettre la ligne de fraisHF au mois suivant
        $this->creeNouveauFraisHorsForfait($idVisiteur, $laNouvelleDate, $unLibelle, $uneDate, $unMontant);
        // supprimer la ligne de fraisHF du mois actuel
        $this->supprimerFraisHorsForfait($idFraisHF);
    } 
    
    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE fichefrais '
                . 'SET nbjustificatifs = :unNbJustificatifs '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
                ':unNbJustificatifs',
                $nbJustificatifs,
                PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois): bool {
        $boolReturn = false;
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.mois FROM fichefrais '
                . 'WHERE fichefrais.mois = :unMois '
                . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur): string {
        $requetePrepare = $this->connexion->prepare(
                'SELECT MAX(mois) as dernierMois '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String  $idVisiteur ID du visiteur
     * @param String $mois  Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois): void {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = $this->connexion->prepare(
                'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
                . 'montantvalide,datemodif,idetat) '
                . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = $this->connexion->prepare(
                    'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                    . 'idfraisforfait,quantite) '
                    . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant): void {
        $dateFr = Utilitaires::dateFrancaisVersAnglais($date);
        $requetePrepare = $this->connexion->prepare(
                'INSERT INTO lignefraishorsforfait '
                . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
                . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais): void {
        $requetePrepare = $this->connexion->prepare(
                'DELETE FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    
    /**
     * Retourne les mois pour lesquels un visiteur donné à des fiche de frais 
     * en attente de validation
     * 
     * @param String $idVisiteur  L'id d'un visiteur
     * 
     * @return array  un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisAValider($idVisiteur): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.idetat = "CL" '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    
    /**
     * Retourne tout les visiteurs qui sont associés à des fiche de frais 
     * en attente de validation
     * 
     * @return array  un tableau associatif de clé un identifiant et de valeurs
     *         le nom et le prénom du visiteur correspondant
     */
    public function getLesVisiteursAValider(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT DISTINCT visiteur.id AS id, visiteur.nom AS nom, visiteur.prenom AS prenom '
                . 'FROM visiteur '
                . 'JOIN fichefrais ON visiteur.id = fichefrais.idvisiteur '
                . 'WHERE idetat = "CL"'
        );
        $requetePrepare->execute();
        $lesVisiteurs = array();
        while ($laLigne = $requetePrepare->fetch()){
            $id = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $lesVisiteurs[] = array(
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom
            );
        }
        return $lesVisiteurs;
    }

    /**
     * Retourne l'ensemble des différents types de véhicules indemnisés
     * ainsi que leur description plus détaillée (libelle)
     * 
     * @return array  un tableau associatif de clé un code detype de véhicule
     *         et de valeur la description plus détaillée de ce code
     */
    public function getLesTypesVehicules(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT typevehicule.codevehicule AS code, typevehicule.libelle AS libelle '
                . 'FROM typevehicule'
        );
        $requetePrepare->execute();
        $lesTypes = array();
        while ($laLigne = $requetePrepare->fetch()){
            $code = $laLigne['code'];
            $libelle = $laLigne['libelle'];
            $lesTypes[] = array(
                'code' => $code,
                'libelle' => $libelle
            );
        }
        return $lesTypes;
    }
    
    /**
     * Retourne le type de véhicule associé à une fiche de frais d'un visiteur
     * 
     * @param String $idVisiteur  L'id d'un visiteur
     * @param String $mois  Le mois de la fiche de frais en cours de traitement
     * @return array  un taleau associatif de clé code de catégorie de véhicule 
     *         et de valeur la description plus détaillée de ce code
     */
    public function getTypeVehiculeFicheFrais($idVisiteur, $mois): String {
        $requetePrepare = $this->connexion->prepare(
                'SELECT typevehicule.codevehicule AS code '
                . 'FROM vehiculefraisforfait '
                . 'JOIN typevehicule ON vehiculefraisforfait.codevehicule = typevehicule.codevehicule '
                . 'WHERE idvisiteur = :unIdVisiteur AND mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        if (!is_array($laLigne)){
            $laLigne = array( 'code' => "");
        }
        return $laLigne['code'];
    }
    
    /**
     * Retourne les infos des fiches de frais qui sont en état validé
     * 
     * @return array  un tableau associatif de clé un identifiant et de valeurs
     *         le nom et le prénom du visiteur et le mois de la fiche concernée
     */
    public function getLesInfosFichesSuivies(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT visiteur.id AS id, visiteur.nom AS nom, visiteur.prenom AS prenom, '
                . 'fichefrais.mois AS mois '
                . 'FROM visiteur JOIN fichefrais ON visiteur.id = fichefrais.idvisiteur '
                . 'WHERE idetat = "VA"'
        );
        $requetePrepare->execute();
        $lesVisiteurs = array();
        while ($laLigne = $requetePrepare->fetch()){
            $id = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $mois = $laLigne['mois'];
            $lesVisiteurs[] = array(
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'mois' => $mois
            );
        }
        return $lesVisiteurs;
    }
    
    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.idetat as idEtat, '
                . 'fichefrais.datemodif as dateModif, '
                . 'fichefrais.nbjustificatifs as nbJustificatifs, '
                . 'fichefrais.montantvalide as montantValide, '
                . 'etat.libelle as libEtat '
                . 'FROM fichefrais '
                . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE fichefrais '
                . 'SET idetat = :unEtat, datemodif = now() '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Appelle les fonctions nécessaires à la modification de la table
     * fichefrais pour passer une fiche de frais à l'état validée 
     * 
     * @param String  $idVisiteur  L'id d'un visiteur
     * @param String  $mois  Le mois correspondant à la fiche de frais à valider
     * @param int $nbJustificatifs  Le nombre de justificatifs 
     *        fournis pour la fiche de frais
     * 
     * @return void
     */
    public function validerFicheFrais($idVisiteur, $mois, $nbJustificatifs): void{
        //majNbJustificatifs
        $this->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs);
        //majMontantValide
        $this->majMontantValide($idVisiteur, $mois);
        //majEtatFicheFrais
        $this->majEtatFicheFrais($idVisiteur, $mois, "VA");
    }
    
    /**
     * Met a jour la table fiche frais pour actualiser le motant total valide
     * qui sera remboursé pour un fiche de frais donnée
     * 
     * @param String $idVisiteur  L'id du visiteur
     * @param String $mois  Le mois correspondant à la fiche de frais
     * 
     * @return void
     */
    public function majMontantValide($idVisiteur, $mois): void{
        $montantValideTotal = $this->calculerMontantValide($idVisiteur, $mois) ;
        $requetePrepare = $this->connexion->prepare(
                'UPDATE fichefrais '
                . 'SET montantvalide = :unMontant '
                . 'WHERE fichefrais.idvisiteur = :unVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unMontant', $montantValideTotal, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Calcule et retourne le montant total d'une fiche de frais en ignorant 
     * les frais hors forfait qui sont indiqués comme refusés
     * 
     * @param String $idVisiteur  L'id d'un visiteur
     * @param String $mois  Le mois correspondant à la fiche de frais
     * 
     * @return Float le montant total valide à rembourser pour la fiche du mois
     */
    public function calculerMontantValide($idVisiteur, $mois): float{
        $lesFraisHF = $this->getLesFraisHorsForfait($idVisiteur, $mois);
        $montantTotal = $this->getMontantFraisForfait($idVisiteur, $mois);
        foreach($lesFraisHF as $unFraisHF){
            if(!str_contains($unFraisHF['libelle'], 'REFUSE:')){
                $montantTotal += $unFraisHF['montant'];
            }
        }
        return $montantTotal;
    }
    
    /**
     * Calcule et retourne le montant des frais forfaitisés
     * 
     * @param String $idVisiteur  L'id d'un visiteur
     * @param String $mois  Le mois correspondant à la fiche de frais
     * 
     * @return Float  le montant des frais forfaitisés
     */
     public function getMontantFraisForfait($idVisiteur, $mois): float{
         $requetePrepare = $this->connexion->prepare(
                 'SELECT SUM(lignefraisforfait.quantite * fraisforfait.montant) AS total '
                 . 'FROM lignefraisforfait '
                 . 'JOIN fraisforfait '
                 . 'ON lignefraisforfait.idfraisforfait = fraisforfait.id '
                 . 'WHERE idvisiteur = :unVisiteur '
                 . 'AND mois = :unMois '
                 . 'AND idfraisforfait != "KM"'
         );
         $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
         $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
         $requetePrepare->execute();
         $tabMontant = $requetePrepare->fetch();
         return $tabMontant['total'] + $this->getMontantIndemniteKilometrique($idVisiteur, $mois);
     }
     
     /**
      * Retourne le montant de l'indemnisation des frais kilométrique 
      * en fonction du type de vehicule pour un visiteur et un mois donnés
      * 
      * @param String $idVisiteur  L'id d'un visiteur
      * @param Sting $mois  Le mois associé à la fiche de frais traitée
      * 
      * @return float  le montant de l'indemnisation kilométrique après calcul
      *         en fonction du type de vehicule entré en base de donnée
      */
     public function getMontantIndemniteKilometrique($idVisiteur, $mois): float{
         $requetePrepare = $this->connexion->prepare(
                 'SELECT (lignefraisforfait.quantite * typevehicule.coefindemnite) AS indemnite '
                 . 'FROM lignefraisforfait '
                 . 'JOIN vehiculefraisforfait '
                 . 'ON lignefraisforfait.idvisiteur = vehiculefraisforfait.idvisiteur '
                 . 'JOIN typevehicule '
                 . 'ON vehiculefraisforfait.codevehicule = typevehicule.codevehicule '
                 . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                 . 'AND vehiculefraisforfait.idvisiteur = :unIdVisiteur '
                 . 'AND lignefraisforfait.mois = :unMois '
                 . 'AND vehiculefraisforfait.mois = :unMois '
                 . 'AND idfraisforfait = "KM"'
         );
         $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
         $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
         $requetePrepare->execute();
         $tabMontant = $requetePrepare->fetch();
         return $tabMontant['indemnite'];
     }
}
