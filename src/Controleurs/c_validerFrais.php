<?php

/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Martin CASTELBOU <#>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
switch ($action) {
    case 'selectionnerVisiteur':
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        break;
    case 'selectionnerMois':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($visiteurAModifier == "placeholder"){
            Utilitaires::ajouterErreur("Aucun visiteur séléctionné.");
            include PATH_VIEWS . 'v_erreurs.php';
            break;
        }
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        $moisASelectionner = getdate(time())['year'] . '' . getdate(time())['mon']-1;
        include PATH_VIEWS . 'v_selectionnerMois.php';
        break;
    case 'voirFicheAValider':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $moisASelectionner = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        include PATH_VIEWS . 'v_selectionnerMois.php';
        
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurAModifier, $moisASelectionner);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAModifier, $moisASelectionner);
        include PATH_VIEWS . 'v_listeFraisAValider.php';
        break;
    case 'majFraisForfait' :
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $moisASelectionner = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        include PATH_VIEWS . 'v_selectionnerMois.php';
        
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT , FILTER_FORCE_ARRAY);
        if (Utilitaires::lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($visiteurAModifier,$moisASelectionner,$lesFrais);
        } else {
            Utilitaires::ajouterErreur('Les valeurs des frais doivent être numériques');
            include PATH_VIEWS . 'v_erreurs.php';
        }
        
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAModifier, $moisASelectionner);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurAModifier, $moisASelectionner);
        include PATH_VIEWS . 'v_listeFraisAValider.php';
        break;
    case 'majFraisHorsForfait':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $moisASelectionner = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        include PATH_VIEWS . 'v_selectionnerMois.php';
        
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAModifier, $moisASelectionner);
        
        $lesFraisHF = filter_input(INPUT_POST, 'lesFraisHorsForfait', FILTER_DEFAULT , FILTER_FORCE_ARRAY);
        $leBouton = filter_input(INPUT_POST, 'envoyerFormulaire', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idFraisHF = substr(array_key_first($lesFraisHF),1);
        $uneDate = $lesFraisHF["D$idFraisHF"];
        $unLibelle = $lesFraisHF["L$idFraisHF"];
        $unMontant = $lesFraisHF["M$idFraisHF"];
        
        Utilitaires::valideInfosFrais(Utilitaires::dateAnglaisVersFrancais($uneDate), $unLibelle , $unMontant);
        if (Utilitaires::nbErreurs() == 0){
            if ($leBouton == "Corriger"){    
                $pdo->majFraisHorsForfait($idFraisHF, $uneDate, $unLibelle, $unMontant);              
            } else if ($leBouton == "Supprimer"){
                $pdo->refuserFraisHorsForfait($idFraisHF, $unLibelle);
            } else if ($leBouton == "Reporter"){
                $pdo->reporterFraisHorsForfait($visiteurAModifier, $idFraisHF, $uneDate, $unLibelle, $unMontant);
            }
        } else {
            include PATH_VIEWS . 'v_erreurs.php';
        }

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurAModifier, $moisASelectionner);
        include PATH_VIEWS . 'v_listeFraisAValider.php';
        break;
    case 'validerFicheFrais':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $moisASelectionner = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        include PATH_VIEWS . 'v_selectionnerMois.php';
        
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAModifier, $moisASelectionner);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurAModifier, $moisASelectionner);
        
        $nbJustificatifs = filter_input(INPUT_POST, 'nb-justificatifs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        include PATH_VIEWS . 'v_listeFraisAValider.php';
        
        $pdo->validerFicheFrais($visiteurAModifier, $moisASelectionner, $nbJustificatifs);
        
}
