<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FilmRepository extends EntityRepository
{
    public function findAllUserByFilm($search)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u INNER JOIN AppBundle:Film f WITH f.user = u.id WHERE f.title LIKE :search'
            )
            ->setParameter('search', $search)
            ->getResult();
    }

    public function findMostPopularFilm()
    {
      return $this->getEntityManager()
          ->createQuery(
              'SELECT f.title, f.poster, COUNT(f.title) AS nb_voted FROM AppBundle:Film f GROUP BY f.title ORDER BY nb_voted DESC'
          )
          ->setMaxResults(1)
          ->getResult();
    }
}
