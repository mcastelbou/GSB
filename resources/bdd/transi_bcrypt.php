<?php

$pdo = new PDO('mysql:host=10.20.3.47;dbname=gsb_frais','adminmariadb','P@ssw0rdMARIADB');
$pdo->query('SET CHARACTER SET utf8');

$req = $pdo->prepare('use gsb_frais; ALTER TABLE visiteur MODIFY mdp CHAR(60)');
$req->execute();

$req = $pdo->prepare('use gsb_frais; ALTER TABLE comptable MODIFY mdp CHAR(60)');
$req->execute();

$getMdp = $pdo->prepare('SELECT id, mdp FROM visiteur');
$getMdp->closeCursor();
$getMdp->execute();
$rs = $getMdp->fetchAll(PDO::FETCH_OBJ);

foreach ($rs as $uneLigne){
    $unMdp = password_hash($uneLigne->mdp, PASSWORD_DEFAULT);
    $requetePrepare = $pdo->prepare('UPDATE visiteur '
            . 'SET visiteur.mdp = :unMdp '
            . 'WHERE visiteur.id = :unId ');
    $requetePrepare->bindParam(':unMdp', $unMdp);
    $requetePrepare->bindParam(':unId', $uneLigne->id);
    $requetePrepare->execute();
}

$getMdpComptable = $pdo->prepare('SELECT id, mdp FROM comptable');
$getMdpComptable->closeCursor();
$getMdpComptable->execute();
$rsComptable = $getMdpComptable->fetchAll(PDO::FETCH_OBJ);

foreach ($rsComptable as $uneLigne){
    $unMdp = password_hash($uneLigne->mdp, PASSWORD_DEFAULT);
    $requetePrepare = $pdo->prepare('UPDATE comptable '
            . 'SET comptable.mdp = :unMdp '
            . 'WHERE comptable.id = :unId ');
    $requetePrepare->bindParam(':unMdp', $unMdp);
    $requetePrepare->bindParam(':unId', $uneLigne->id);
    $requetePrepare->execute();
}
