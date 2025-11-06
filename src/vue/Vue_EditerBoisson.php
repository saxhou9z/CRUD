<?php
namespace App\Vue;

use App\Entity\Boisson;
class Vue_EditerBoisson {

static function donneHTML(Boisson $boisson):string
{ 
 $str = "<h1>Editer une boisson</h1>
 <a href='/categorie'>Retour à la liste des catégories</a><br><br>
 <form action='/categorie/modifier/".$boisson->getId()."' method='post'>
 <label>Libellé :</label>
 <input type='text' value='".$boisson->getLibelle()."' name='libelle'>
 <input type='submit' value='Modifier'>
    </form>
     ";
 

    
    return $str;

}
}