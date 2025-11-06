<?php

/**
 * Vue Liste des frais au forfait
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<?php 
if($action == "majFraisForfait"){
    echo "<script type='text/javascript'>confirm('Modification prise en compte.')</script>";
} 
?>
<hr class="my-5">
<div class="row">    
    <h2 class="text-warning mb-3">Valider la fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" role="form" action="index.php?uc=validerFrais&action=majFraisForfait">
            <fieldset class="d-grid row-gap-3 ms-4">
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="<?php echo $idFrais ?>" class="fw-semibold"><?php echo $libelle ?></label>
                        <input type="text" id="<?php echo $idFrais ?>" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" type="submit">Corriger</button>
                    <button class="btn btn-danger" type="reset">Réinitialiser</button>
                </div>
            </fieldset>
            <select hidden id="visiteur" name="visiteur">
                    <option selected value="<?php echo $visiteurAModifier?>"></option>
            </select>
            <select hidden id="mois" name="mois">
                    <option selected value="<?php echo $moisASelectionner?>"></option>
            </select>
        </form>
    </div>
</div>
<hr>
<div class="row">
    <div class="card text-bg-warning bg-opacity-75 p-0 border-warning border-opacity-50 mb-5">
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
            if (empty($lesFraisHorsForfait)) {
                echo "<tr>"
                . "<td>"
                  . "<span class='fw-bold text-danger'> "
                  . "Aucun frais hors forfait</span>"
                . "</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td></td>"
            . "</tr>";
            } else {
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; ?>           
                <tr>
                    <form method="post" role="form" 
                        action="index.php?uc=validerFrais&action=majFraisHorsForfait">
                        <td> 
                            <input type="date" id="Date<?php echo $id?>" 
                                   name="lesFraisHorsForfait[D<?php echo $id?>]" 
                                   size="10" maxlength="10"
                                   value="<?php echo Outils\Utilitaires::dateFrancaisVersAnglais($date) ?>"
                                   class="form-control">
                        </td>
                        <td>
                            <input type="text" id="Libelle<?php echo $id?>" 
                                   name="lesFraisHorsForfait[L<?php echo $id?>]" 
                                   size="10" maxlength="45"
                                   value="<?php echo $libelle?>"
                                   class="form-control">
                        </td>
                        <td>
                            <input type="text" id="Montant<?php echo $id?>" 
                                   name="lesFraisHorsForfait[M<?php echo $id?>]" 
                                   size="6" maxlength="8"
                                   value="<?php echo $montant ?>"
                                   class="form-control">
                        </td>
                        <td class="d-flex gap-1 flex-wrap">
                            <input class="btn btn-success"
                                    type="submit"
                                    value="Corriger"
                                    name="envoyerFormulaire"/>
                            <input class="btn btn-danger"
                                    type="reset"
                                    value="Réinitialiser"
                                    name="RéinitialiserFraisHF"/>
                            <input class="btn btn-danger"
                                    type="submit"
                                    value="Supprimer"
                                    name="envoyerFormulaire"/> 
                            <input class="btn btn-danger"
                                    type="submit"
                                    value="Repporter"
                                    name="envoyerFormulaire"/> 
                        </td>
                        <select hidden id="visiteur" name="visiteur">
                            <option selected value="<?php echo $visiteurAModifier?>"></option>
                        </select>
                        <select hidden id="mois" name="mois">
                                <option selected value="<?php echo $moisASelectionner?>"></option>
                        </select>
                    </form>    
                </tr>
                <?php
                }
            }
            ?>
            </tbody>  
        </table>
    </div>
    <div class="mb-3">
        <label for="nb-justificatifs">Nombre de justificatifs : </label>
        <input type="number" name="nb-justificatifs"
               id="nb-justificatifs"
               size="2" min="0" max="25"
               class="rounded-2"/>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success" type="submit">[Valider]</button>
        <button class="btn btn-danger" type="reset">[Effacer]</button>
    </div>
</div>