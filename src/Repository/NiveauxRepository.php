<?php

namespace App\Repository;

use App\Entity\Niveaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Niveaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveaux[]    findAll()
 * @method Niveaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveaux::class);
    }

    /**
     * Enregistrements dont un champ contientune valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Formation[]
     */
    public function findByContainValueNiv($champ, $valeur): array{
        if($valeur==""){
            return $this->createQueryBuilder('n')
                    ->orderBy('n.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('n')
                    ->where('n.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', $valeur)
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();            
        }
    }
}
