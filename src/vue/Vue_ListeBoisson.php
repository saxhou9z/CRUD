<?php
namespace App\Vue;
class Vue_ListeBoisson {

static function donneHTML(array $tableauBoisson, string $msgErreur=""):string
{
    $str = "<h1>Liste des boissons</h1>\n";

    if ($msgErreur !== "") {
        $str .= "<p style='color:red'>" . htmlspecialchars($msgErreur, ENT_QUOTES, 'UTF-8') . "</p>\n";
    }

    $str .= "<table>\n<tr><th>Libellé</th><th>Supprimer</th></tr>\n";
    foreach($tableauBoisson as $boisson) // The error is here, the Boisson entity does not have a getLibelle() method. It should be getNom()
    {
        $str .= "<tr><td><a href='/boisson/editer/".$boisson->getId()."'>".$boisson->getNom()."</a></td>\n"
            . "<td>\n"
            . "<form action='/boisson/suppression/".$boisson->getId()."' method='post'>\n"
            . "<input type='submit' value='Supprimer'>\n"
            . "</form>\n"
            . "</td></tr>\n";
    }

    $str .= "</table>\n"
        . "Pour créer une nouvelle boisson, cliquez <a href='/boisson/creation'>ici</a>.<br>\n"
        . "Pour revenir sur la page des catégorie, cliquez <a href='/categorie'>ici</a>.<br>\n";

    return $str;
}
}