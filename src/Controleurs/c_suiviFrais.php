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
    case 'selectionnerFiche':
        $lesVisiteursASuivre = $pdo->getLesInfosFichesSuivies();
        include PATH_VIEWS . 'v_selectionSuivi.php';
        break;
    case 'suiviPaiementFiche':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idVisiteur = substr($visiteurAModifier,0,5);
        $leMois = substr($visiteurAModifier,5,6);
        $lesVisiteursASuivre = $pdo->getLesInfosFichesSuivies();
        if ($visiteurAModifier == "placeholder"){
            Utilitaires::ajouterErreur("Aucun visiteur séléctionné.");
            include PATH_VIEWS . 'v_erreurs.php';
            break;
        }
        include PATH_VIEWS . 'v_selectionSuivi.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include PATH_VIEWS . 'v_suiviPaiementFrais.php';
        break;
    case 'miseEnPaiement':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idVisiteur = substr($visiteurAModifier,0,5);
        $leMois = substr($visiteurAModifier,5,6);
        
        $pdo->majEtatFicheFrais($idVisiteur,$leMois,"RB");
        
}
