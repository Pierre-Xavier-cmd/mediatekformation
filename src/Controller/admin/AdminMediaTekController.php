<?php



namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminMediaTekController
 *
 * @author pierr
 */
class AdminMediaTekController extends AbstractController {
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;






    private const PAGEADMIN = "admin/_admin.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $repository;

    /**
     * 
     * @param FormationRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }

    /**
     * @Route("/admin", name="admin.mediatek")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAll();
        return $this->render(self::PAGEADMIN, [
            'formations' => $formations
        ]);
    }
    
    /**
     * @Route("/admin/tri/{champ}/{ordre}", name="admin.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render(self::PAGEADMIN, [
           'formations' => $formations
        ]);
    }   
        
    /**
     * @Route("/admin/recherche/{champ}", name="admin.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            if($champ == "nom"){
                $formations = $this->repository->findByNiveau($champ, $valeur);
            }
            else{
                $formations = $this->repository->findByContainValue($champ, $valeur);                
            }

            return $this->render(self::PAGEADMIN, [
                'formations' => $formations
            ]);
        }
        return $this->redirectToRoute("formations");
    }  
    
    
    /**
     * @Route("/admin/recherche/{champ}", name="admin.findallniveau")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllNiveau($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByNiveau($champ, $valeur);
            return $this->render(self::PAGEADMIN, [
                'formations' => $formations
            ]);
        }
        return $this->redirectToRoute(self::PAGEADMIN);
    }     


    
    
    
    /**
     * @Route("/admin/formation/{id}", name="admin.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        return $this->render("admin/admin.formation.html.twig", [
            'formation' => $formation
        ]);        
    }


    /**
     * @Route("/admin/suppr/{id}", name="admin.mediatek.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.mediatek');
    }
    

    
    /**
     * @Route("/admin/edit/{id}", name="admin.mediatek.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */ 
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->flush();
            return $this->redirectToRoute('admin.mediatek');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    
    }
    
    
    
    
    /**
     * @Route("/admin/ajout", name="admin.mediatek.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.mediatek');
        }
        
        return $this->render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
} 
