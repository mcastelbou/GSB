<?php

$pdo = new PDO('mysql:host=localhost;dbname=gsb_b3','userGsb','secret');
$pdo->query('SET CHARACTER SET utf8');

$req = $pdo->prepare('use gsb_b3; ALTER TABLE visiteur MODIFY mdp CHAR(60)');
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
