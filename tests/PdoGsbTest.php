<?php

require_once __DIR__ . '/../vendor/autoload.php';
require '../config/testdata.php';

use PHPUnit\Framework\TestCase;
use Modeles\PdoGsb;

/*
 * Classe de test des fonctionnalité de la classe PdoGsb.php
 */

final class PdoGsbTest extends TestCase {

    private static $pdoGsb;

    /**
     * Méthode appelée avant chaque test pour initialiser l'instance de PdoGsb.
     */
    public static function setUpBeforeClass(): void {
        self::$pdoGsb = PdoGsb::getPdoGsbTest();
    }

    /**
     * Teste que getPdoGsb retourne bien une instance de PdoGsb.
     */
    public function testGetPdoGsbReturnsInstance(): void {
        $this->assertInstanceOf(PdoGsb::class, self::$pdoGsb);
    }

    /**
     * Fonction de test du constructeur de la classe PdoGsb.
     * @return void
     */
    public function testConstruct(): void {
        // TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test du constructeur de la classe PdoGsb.
     * @return void
     */
    public function testDestruct(): void {
        // TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la fonction de récupération des infos d'un visiteur.
     * @return void
     */
    public function testGetInfosVisiteur(): void {
        $fakeVisiteur = self::$pdoGsb->getInfosVisiteur(testFakeLogin);
        $this->assertFalse($fakeVisiteur);

        $visiteur = self::$pdoGsb->getInfosVisiteur(testLogin);
        $obtainedLogin = strtolower(substr($visiteur['prenom'], 0, 1))
                . '.'
                . strtolower($visiteur['nom']);

        $this->assertIsArray($visiteur);
        $this->assertArrayHasKey("id", $visiteur);
        $this->assertArrayHasKey(0, $visiteur);
        $this->assertArrayHasKey("nom", $visiteur);
        $this->assertArrayHasKey(1, $visiteur);
        $this->assertArrayHasKey("prenom", $visiteur);
        $this->assertArrayHasKey(2, $visiteur);
        $this->assertArrayHasKey("email", $visiteur);
        $this->assertArrayHasKey(3, $visiteur);
        $this->assertCount(8, $visiteur);

        $this->assertSame(testLogin, $obtainedLogin);
    }

    /**
     * Fonction de test de la fonction de récupération des infos de tout les visiteurs.
     * @return void
     */
    public function testGetInfosAllVisiteurs(): void {
        $tabVisiteur = self::$pdoGsb->getInfosAllVisiteurs();
        $firstVisiteur = $tabVisiteur[0];

        $this->assertIsArray($tabVisiteur);

        $this->assertIsArray($firstVisiteur);
        $this->assertArrayHasKey("nom", $firstVisiteur);
        $this->assertArrayHasKey(0, $firstVisiteur);
        $this->assertArrayHasKey("prenom", $firstVisiteur);
        $this->assertArrayHasKey(1, $firstVisiteur);
        $this->assertArrayHasKey("email", $firstVisiteur);
        $this->assertArrayHasKey(2, $firstVisiteur);
        $this->assertArrayHasKey("adresse", $firstVisiteur);
        $this->assertArrayHasKey(3, $firstVisiteur);
        $this->assertArrayHasKey("codepost", $firstVisiteur);
        $this->assertArrayHasKey(4, $firstVisiteur);
        $this->assertArrayHasKey("ville", $firstVisiteur);
        $this->assertArrayHasKey(5, $firstVisiteur);
        $this->assertArrayHasKey("dateembauche", $firstVisiteur);
        $this->assertArrayHasKey(6, $firstVisiteur);
        $this->assertCount(14, $firstVisiteur);
    }

    /**
     * Fonction de test de la fonction de récupération du mdp d'un visiteur.
     * @return void
     */
    public function testGetMdpVisiteur(): void {
        $fakePassword = self::$pdoGsb->getMdpVisiteur(testFakeLogin);
        $this->assertFalse($fakePassword);
        
        $password = self::$pdoGsb->getMdpVisiteur(testLogin);
        $this->assertSame($password, testPassword);
    }

    /**
     * Fonction de test de la fonctionnalité A2F (écrire/lire).
     * @return void
     */
    public function testCodeA2F(): void {
        $originalCode = self::$pdoGsb->getCodeVisiteur(testId);
        $this->assertNotNull($originalCode);

        self::$pdoGsb->setCodeA2f(testId, "test");

        $A2F = self::$pdoGsb->getCodeVisiteur(testId);
        $this->assertSame("test", $A2F);

        self::$pdoGsb->setCodeA2f(testId, $originalCode);
    }

    /**
     * Fonction de test de la création d'un frais hors forfait.
     * @return void
     */
    public function testCreerFraisHorsForfait(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la suppression d'un frais hors forfait.
     * @return void
     */
    public function testSupprimerFraisHorsForfait(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la mise à jour d'une fche de frais.
     * @return void
     */
    public function testFicheFrais(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la fonctionnalité des frais forfaitisés.
     * @return void
     */
    public function testFraisForfait(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la fonctionnalité de justificatifs.
     * @return void
     */
    public function testNbJustificatifs(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la récupération des frais d'un visiteur.
     * @return void
     */
    public function testGetLesIdFrais(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la récupération des identifiants des visiteurs.
     * @return void
     */
    public function testGetLesIdVisiteur(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la vérification du premier frais mensuel.
     * @return void
     */
    public function testEstPremierFraisMois(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la vérification du dernier frais saisi dans le mois courant.
     * @return void
     */
    public function testDernierMoisSaisi(): void {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * Fonction de test de la récupération de mois pour lesquels 
     * un visiteur a une fiche de frais renseignée.
     * @return void
     */
    public function testGetLesMoisDisponibles(): void {
        //TODO
        $this->assertTrue(true);
    }
}
