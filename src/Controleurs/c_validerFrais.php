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
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionnerVisiteur.php';
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        $moisASelectionner = getdate(time())['year'] . '' . getdate(time())['mon'];
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
        require PATH_VIEWS . 'v_listeFraisForfait.php';
        require PATH_VIEWS . 'v_listeFraisHorsForfait.php';
}
