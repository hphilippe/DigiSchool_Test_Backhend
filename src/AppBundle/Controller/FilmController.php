<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\FilmType;
use AppBundle\Entity\Film;

class FilmController extends Controller
{

  /**
   * @Rest\View(serializerGroups={"film"})
   * @Rest\Get("films/search")
   * @Rest\QueryParam(
   *    name="search",
   *    default=null,
   *    nullable=false,
   *    description="search film"
   * )
   */
  public function getFilmAction(Request $request)
  {
      $film = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Film')
              ->findAllUserByFilm($request->get('search'));

      if (empty($film)) {
          return $this->filmNotFound();
      }

      return $film;
  }

  /**
   * @Rest\View(serializerGroups={"film"})
   * @Rest\Get("films/popular")
   */
  public function getFilmPopularAction(Request $request)
  {
      $film = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Film')
              ->findMostPopularFilm();

      if (empty($film)) {
          return $this->filmNotFound();
      }

      return $film;
  }

  /**
   * @Rest\View(serializerGroups={"film"})
   * @Rest\Get("/users/{id}/films")
   */
  public function getFilmsUserAction(Request $request)
  {
      $user = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:User')
              ->find($request->get('id'));

      if (empty($user)) {
          return $this->filmNotFound();
      }

      return $user->getFilms();
  }

   /**
   * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"film"})
   * @Rest\Post("/users/{id}/films")
   */
  public function postFilmsAction(Request $request)
  {
      $user = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:User')
              ->find($request->get('id'));

      if (empty($user)) {
          return $this->filmNotFound();
      }

      $film = new Film();
      $film->setUser($user);
      $form = $this->createForm(FilmType::class, $film);
      
      $form->submit($request->request->all());

      if ($form->isValid()) {
          $em = $this->get('doctrine.orm.entity_manager');
          $em->persist($film);
          $em->flush();
          return $film;
      } else {
          return $form;
      }
  }

  /**
  * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"film"})
  * @Rest\Delete("films/{id}")
  */
  public function removeFilmAction(Request $request)
  {
     $em = $this->get('doctrine.orm.entity_manager');
     $film = $em->getRepository('AppBundle:Film')
                 ->find($request->get('id'));

     if ($film) {
       $em->remove($film);
       $em->flush();
       return new JsonResponse(['message' => 'Film successfully deleted'], Response::HTTP_OK);
     } else {
       return $this->filmNotFound();
     }
  }

  private function filmNotFound()
  {
      return \FOS\RestBundle\View\View::create(['message' => 'Film not found'], Response::HTTP_NOT_FOUND);
  }

}
