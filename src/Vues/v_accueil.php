<?php

/**
 * Vue Accueil
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

?>
<div class="alert alert-warning" role="alert">
    <strong>Rappel : </strong>Vos frais sont à déclarer au plus tard le dernier jour du mois 
    et vos factures acquittées doivent être arrivées aux services comptables 
    au plus tard le 10 du mois suivant la saisie.
    Les éléments reçus après le 10 seront reportés sur le mois suivant.
</div>
<div id="accueil">
    <h2>
        Gestion des frais<span class="fs-5 text-black text-opacity-50"> - Visiteur : 
            <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></span>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card border-primary">
            <div class="card-header text-bg-primary">
                <h3 class="card-title">
                    <span class="bi bi-bookmark-fill"></span>
                    Navigation
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12 d-flex gap-3">
                        <a href="index.php?uc=gererFrais&action=saisirFrais"
                           class="btn btn-success btn-lg" role="button">
                            <span class="bi bi-pencil-fill"></span>
                            <br>Renseigner la fiche de frais</a>
                        <a href="index.php?uc=etatFrais&action=selectionnerMois"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="bi bi-list-task"></span>
                            <br>Afficher mes fiches de frais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>