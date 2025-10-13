<?php

/**
 * Vue Liste des mois
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
    <div class="col-md-4 sm:col-md-1">
        <form action="index.php?uc=validerFrais&action=voirFicheAValider" 
              method="post" role="form">
            <div class="form-group">
                <label for="visiteur" accesskey="n" class="fw-semibold mb-2">Mois :</label>
                <select id="mois" name="mois" class="form-control ms-3">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
                <select hidden id="visiteur" name="visiteur" class="form-control">
                    <option selected value="<?php echo $visiteurAModifier?>"></option>
                </select>
            </div>
            <div class="d-flex gap-2 mt-3 ms-3">
                <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                       role="button">
                <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                       role="button">
            </div>
        </form>
    </div>
</div>



