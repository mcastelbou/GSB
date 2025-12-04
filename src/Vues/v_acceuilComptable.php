<?php
/**
 * Vue Accueil Comptable
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Martin CASTELBOU <fake@mail.com>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<div id="accueil">
    <h2>
        Gestion des frais<span class="fs-5 text-black text-opacity-50"> - Comptable : 
            <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></span>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header text-bg-warning">
                <h3 class="card-title">
                    <span class="bi bi-bookmark-fill"></span>
                    Navigation
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12 d-flex gap-3">
                        <a href="index.php?uc=validerFrais&action=selectionnerVisiteur"
                           class="btn btn-success btn-lg" role="button">
                            <span class="bi bi-check"></span>
                            <br>Valider les fiches de frais</a>
                        <a href="index.php?uc=suiviFrais&action=selectionnerFiche"
                           class="btn btn-warning btn-lg" role="button">
                            <span>€</span>
                            <br>Suivre le paiement des fiches de frais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

