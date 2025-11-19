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
    case 'selectionnerMois':
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        include PATH_VIEWS . 'v_selectionSuivi.php';
        break;
    case 'selectionnerMois':
        $visiteurAModifier = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($visiteurAModifier == "placeholder"){
            Utilitaires::ajouterErreur("Aucun visiteur séléctionné.");
            include PATH_VIEWS . 'v_erreurs.php';
            break;
        }
        $lesVisiteursAValider = $pdo->getLesVisiteursAValider();
        $lesMois = $pdo->getLesMoisAValider($visiteurAModifier);
        $moisASelectionner = getdate(time())['year'] . '' . getdate(time())['mon']-1;
        include PATH_VIEWS . 'v_selectionSuivi.php';
        break;
}
