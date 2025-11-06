<?php
namespace App\Vue;

use App\Entity\Categorie;
class Vue_EditerCategorie {

static function donneHTML(Categorie $categorie):string
{ 
 $str = "<h1>Editer une catégorie</h1>
 <a href='/categorie'>Retour à la liste des catégories</a><br><br>
 <form action='/categorie/modifier/".$categorie->getId()."' method='post'>
 <label>Libellé :</label>
 <input type='text' value='".$categorie->getLibelle()."' name='libelle'>
 <input type='submit' value='Modifier'>
    </form>
     ";
 

    
    return $str;

}
}