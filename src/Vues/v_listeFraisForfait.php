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
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<div class="row">    
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
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
                    <?php
                    if ($idFrais == "KM"){?>
                    <select id="CV" name="lesFrais[CV]" class="form-control">
                        <option hidden value="placeholder">Choisir un type de véhicule</option>
                    <?php 
                    foreach ($lesTypesVehicule as $typeVehicule) {
                        $libelleType = $typeVehicule['libelle'];
                        $codeType = $typeVehicule['code'];
                        if ($codeType == $typeASelectionner) { ?>
                            <option selected value="<?php echo $codeType?>">
                                <?php echo $libelleType?></option>
                            <?php
                        } else { ?>
                            <option value="<?php echo $codeType?>">
                                <?php echo $libelleType?></option>
                        <?php
                        }
                    }?>
                    </select>
                    <?php
                    }?>
                </div>
                <?php
                }?>
                <div class="d-flex gap-3">
                    <button class="btn btn-success" type="submit">Ajouter</button>
                    <button class="btn btn-danger" type="reset">Effacer</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
