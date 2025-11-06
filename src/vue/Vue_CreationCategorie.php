<?php
namespace App\Vue;
class Vue_CreationCategorie {

static function donneHTML():string
{ 
 $str = "<h1>Création d'une catégorie</h1>
   <form action='/categorie/creer' method='post'>
         
 <table>
 <tr><th>Libellé</th> </tr> 
            <tr><td>
                 <input type='text' value='' name='libelle'>
            
        </td></tr>";
    

    $str .= "</table>
<input type='submit' value='Créer'>
    </form>
     ";
    return $str;

}
}