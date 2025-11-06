<?php
namespace App\Vue;

class Vue_EditerBoisson {
    public static function donneHTML(\App\Entity\Boisson $boisson): string
    {
        return "<h2>Modifier la boisson : " . htmlspecialchars($boisson->getNom()) . "</h2>
                <form action='/boisson/modifier/{$boisson->getId()}' method='POST'>
                    <input type='text' name='nom' value='" . htmlspecialchars($boisson->getNom()) . "' required>
                    <input type='submit' value='Modifier'>
                </form>
                <br><a href='/boisson/selection?categorie=" . $boisson->getCategorie()->getId() . "'>⬅ Retour aux boissons</a>";
    }
}
