<?php

/**
 * Vue Liste des frais hors forfait
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
<div class="row">
    <div class="card text-bg-info bg-opacity-25 p-0 border-info border-opacity-25 mb-5">
        <div class="card-header">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive m-0">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; ?>           
                <tr>
                    <td> <?php echo $date ?></td>
                    <td> <?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td>
                        <a href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=<?php echo $id ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">
                            Supprimer ce frais
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>
<div class="row">
    <h3>Nouvel élément hors forfait</h3>
    <div class="col-md-4">
        <form action="index.php?uc=gererFrais&action=validerCreationFrais" 
              method="post" role="form" class="ms-4">
            <div class="form-group">
                <label for="txtDateHF" class="fw-semibold my-2">Date (jj/mm/aaaa): </label>
                <input type="date" id="txtDateHF" name="dateFrais" 
                       class="form-control" id="text">
            </div>
            <div class="form-group">
                <label for="txtLibelleHF" class="fw-semibold my-2">Libellé</label>             
                <input type="text" id="txtLibelleHF" name="libelle" class="form-control" id="text">
            </div> 
            <div class="form-group">
                <label for="txtMontantHF" class="fw-semibold my-2">Montant : </label>
                <div class="input-group">
                    <span class="input-group-text">€</span>
                    <input type="text" id="txtMontantHF" name="montant" class="form-control" value="">
                </div>
            </div>
            <div class="d-flex gap-3 mt-3">
                <button class="btn btn-success" type="submit">Ajouter</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </div>
        </form>
    </div>
</div>