<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
class FactureService{
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }
    public function facture($data,$total,$user){
        foreach($data as $produits){
            $facture = new Facture();
            $facture->setAchat($user)
                    ->setMatiere($produits["matiere"])
                    ->setPrixTo($total);
            $this->manager->persist($facture);
        }
        $this->manager->flush();

        return $facture;    
    }
}
?>