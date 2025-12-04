<?php

/**
 * Vue État de Frais
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<hr>
<div class="card text-bg-warning bg-opacity-75 p-0 border-warning border-opacity-50 mb-5">
    <div class="card-header">Fiche de frais du mois <?php echo $numMois . '-' . $numAnnee ?> : </div>
    <div class="card-body text-bg-light">
        <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
        depuis le <?php echo $dateModif ?> <br> 
        <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
    </div>
</div>
<div class="card text-bg-warning bg-opacity-75 p-0 border-warning border-opacity-50 mb-5">
    <div class="card-header">Eléments forfaitisés</div>
    <table class="table table-bordered table-responsive m-0">
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle']; ?>
                <th> <?php echo htmlspecialchars($libelle) ?></th>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite']; ?>
                <td class="qteForfait"><?php echo $quantite ?> </td>
                <?php
            }
            ?>
        </tr>
    </table>
</div>
<div class="card text-bg-warning bg-opacity-75 p-0 border-warning border-opacity-50 mb-5">
    <div class="card-header">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered table-responsive m-0">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<form method="post" role="form" 
        action="index.php?uc=suiviFrais&action=miseEnPaiement">
        <div class="d-flex gap-2">
            <button class="btn btn-success" type="submit">Rembourser fiche</button>
        </div>
        <select hidden id="visiteur" name="visiteur">
            <option selected value="<?php echo $visiteurAModifier?>"></option>
        </select>
</form>
