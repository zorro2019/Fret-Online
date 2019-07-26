<?php

namespace App\Controller;

use App\Entity\Abonnes;
use App\Entity\Chauffeur;
use App\Entity\DetailsVoyage;
use App\Entity\Vehicule;
use App\Entity\Voyage;
use App\Form\ChauffeurType;
use App\Form\DetailsVoyageType;
use App\Form\VehiculeFormType;
use App\Form\VoyageType;
use App\Repository\AbonnementRepository;
use App\Repository\ChauffeurRepository;
use App\Repository\DetailsVoyageRepository;
use App\Repository\TypeVehiculeRepository;
use App\Repository\VehiculeRepository;
use App\Repository\VoyageRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var DetailsVoyageRepository
     */
    private $repo;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var VoyageRepository
     */
    private $voyageRepo;
    /**
     * @var GetAuthentificateUser
     */
    private $logged;
    /**
     * @var AbonnementRepository
     */

    private $chauffeurRepository;
    private $abonnementRepository;
    private $vehiculeRepository;
    private $typeVehiculeRepository;
    public function __construct(ObjectManager $manager, DetailsVoyageRepository $repo, PaginatorInterface $paginator,
                                GetAuthentificateUser $logged, VoyageRepository $voyageRepository,
                                AbonnementRepository $abonnementRepository, VehiculeRepository $vehiculeRepository,
                                ChauffeurRepository $chauffeurRepository, TypeVehiculeRepository $typeVehiculeRepository)
    {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->paginator = $paginator;
        $this->logged = $logged;
        $this->voyageRepo = $voyageRepository;
        $this->abonnementRepository = $abonnementRepository;
        $this->vehiculeRepository = $vehiculeRepository;
        $this->chauffeurRepository = $chauffeurRepository;
        $this->typeVehiculeRepository = $typeVehiculeRepository;
    }

    /**
     * @Route("/vehicule", name="create.vehicule")
     * @param Request $request
     * @param TypeVehiculeRepository $typeVehiculeRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, TypeVehiculeRepository $typeVehiculeRepository)
    {
        $active = array(
            'transport' => false,
            'detail' => false,
            'page' => false,
            'test' => false
        );
        if (isset($_GET['page']))
            $active['page'] = true;
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $totalAbonnement = $this->abonnementRepository->findAll();
        $vh = new Vehicule();
        $dtVg = new DetailsVoyage();
        $ab = $this->getUser();
        $abonne = new Abonnes();
        $nbre = count($ab->getListeVehicule());
        $form = $this->createForm(VehiculeFormType::class,$vh);
        $vg = new Voyage();
        $formVehicule = $this->createForm(VoyageType::class,$vg);
        $formDetail = $this->createForm(DetailsVoyageType::class,$dtVg);
        $form->handleRequest($request);
        $formVehicule->handleRequest($request);
        $chauffeur = new Chauffeur();
        $chauffeurForm = $this->createForm(ChauffeurType::class,$chauffeur);
        $chauffeurForm->handleRequest($request);
        $sub_error = array();
        $sub_error['quant'] = "";
        $sub_error['matricule'] = "";
        if ($form->isSubmitted()){
            if ($form->isValid()){
                $nbre = \count($ab->getListeVehicule());
                $vh->setIdAbonne($this->getUser());
                $vh->setCreatAt(new \DateTime('now',new \DateTimeZone('Africa/Dakar')));
                $vh->setTypeVehicule($typeVehiculeRepository->find($_POST['typeVehicule']));
                if ($nbre <= 3)
                {
                    if (isset($_POST['typeVehicule'])){
                        $type = $this->typeVehiculeRepository->find($_POST['typeVehicule']);
                        $vh->setTypeVehicule($type);
                    }
                    $this->manager->persist($vh);
                    $this->manager->flush();
                    $this->addFlash('success','Votre vehicule a été enregistré avec succes');
                    return $this->redirectToRoute('create.vehicule');
                }
                else{
                    if (isset($_POST['typeAb'])){
                        $abonnement = $this->abonnementRepository->findBy([
                            'montant' => $_POST['typeAb']
                        ]);
                        dump($abonnement);
                        $session = new Session();
                        $session->set('Vehicule',$vh);
                        $payement = new PayementController();
                        $payement->Payer([$abonnement]);
                        return $this->redirectToRoute('abonnement');
                    }
                }
            }
        }

        if ($formVehicule->isSubmitted() && $formVehicule->isValid()){
            $lastVoyage = $this->voyageRepo->findVoyageActif($this->vehiculeRepository->find($_POST['idVehicule']));
            $active['transport'] = true;
            foreach ($lastVoyage as $vyg){
                if ($vyg != null){
                    $vyg->setStatus(0);
                    $this->manager->persist($vyg);
                    $this->manager->flush();
                }
            }
            $vg->setIdVehicule($this->vehiculeRepository->find($_POST['idVehicule']));
            $vg->setDebutAt(new \DateTime($_POST['date']));
            if ($vg->getQuantite() <= $vg->getIdVehicule()->getTonnage()){
                $vg->setIdChauffeur($this->chauffeurRepository->find($_POST['chauffeur']));
                $this->manager->persist($vg);
                $this->manager->flush();
                $dt = new DetailsVoyage();
                $dt->setCharge(0);
                $dt->setDateDepart($vg->getDebutAt());
                $dt->setDecharge(0);
                $dt->setIdVoyage($vg);
                $dt->setPosition(true);
                $dt->setVille($vg->getVilledepart());
                $this->manager->persist($dt);
                $this->manager->flush();
                $this->addFlash('success','transport enregistré avec succes');
                return $this->redirectToRoute('create.vehicule');
            }
            else{
                    $sub_error['quant'] = "cette quantité est superieur au tonnage";
            }

        }
        $formDetail->handleRequest($request);
        $sub_error['total']= "";
        if ($formDetail->isSubmitted() && $formDetail->isValid()){
            $active['detail'] = true;
            if ($dtVg->getPosition() != null)
                $dtVg->setPosition(1);
            $lastDetail = $this->repo->findBy(['idVoyage' => $_POST['idvoyage']]);
            foreach ($lastDetail as $item){
                $item->setPosition(0);
            }

            if ($sub_error['total'] == null){
                $dtVg->setIdVoyage($this->voyageRepo->find($_POST['idvoyage']));
                $this->manager->persist($dtVg);
                $this->manager->flush();
                $this->addFlash('success','Detail ajouté au voyage avec succes');
                return $this->redirectToRoute('create.vehicule');
            }
        }
        if ($chauffeurForm->isSubmitted() && $chauffeurForm->isValid()){
            $chauffeur->setIdAbonnes($ab);
            $this->manager->persist($chauffeur);
            $this->manager->flush($chauffeur);
            $this->addFlash('success','Chauffeur ajouté avec succes');
            return $this->redirectToRoute('create.vehicule');
        }
        $listPagine = $this->paginator($request,$ab->getId());
        return $this->render('vehicule/create.html.twig',[
            'form' => $form->createView(),
            'user' => $ab,
            'sub_error' => $sub_error,
            'abonne' => $abonne,
            'chauffeurForm' => $chauffeurForm->createView(),
            'listePagine' => $listPagine,
            'formVehicule' =>$formVehicule->createView(),
            'formDetail' =>$formDetail->createView(),
            'nombre' => $nbre,
            'toatalAbonnement' => $totalAbonnement,
            'active' =>$active,
            'typeVehicule' => $typeVehiculeRepository->findAll()
        ]);
    }

    /**
     * @Route("/vehicule.edit/{id}", name="edit.vehicule")
     * @param Request $request
     * @param Vehicule $vehicule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request , Vehicule $vehicule)
    {
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $active = array(
            'transport' => false,
            'detail' => false,
            'page' => false,
            'test' => false
        );
        $sub_error = array();
        $sub_error['quant'] = "";
        $sub_error['total'] = "";
        $ab = $this->getUser();
        $form = $this->createForm(VehiculeFormType::class,$vehicule);
        $vg = new Voyage();
        $dtVg = new DetailsVoyage();
        $chauffeur = new Chauffeur();
        $chauffeurForm = $this->createForm(ChauffeurType::class,$chauffeur);
        $formVehicule = $this->createForm(VoyageType::class,$vg);
        $formDetail = $this->createForm(DetailsVoyageType::class,$dtVg);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if ($form->isValid()){
                $vehicule->setIdAbonne($this->getUser());
                $this->manager->persist($vehicule);
                $this->manager->flush();
                $this->addFlash('success','informations mises en jours');
                return $this->redirectToRoute('create.vehicule');
            }
        }
        $listPagine = $this->paginator($request,$ab->getId());
        $nbre = \count($ab->getListeVehicule());
        return $this->render('vehicule/create.html.twig',[
            'form' => $form->createView(),
            'user' => $ab,
            'listePagine'=> $listPagine,
            'formVehicule' => $formVehicule->createView(),
            'formDetail' => $formDetail->createView(),
            'nombre' => $nbre,
            'active' =>$active,
            'sub_error' =>$sub_error,
            'chauffeurForm' =>$chauffeurForm->createView(),
            'typeVehicule' => $this->typeVehiculeRepository->findAll()
        ]);
    }

    /**
     * @Route("/vehicule.delete/{id}", name="delete.vehicule")
     * @param Vehicule $vehicule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Vehicule $vehicule)
    {
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        foreach ($vehicule->getVoyage() as $vg){
            foreach ($vg->getListeDetailVoyage() as $dt){
                $this->manager->remove($dt);
                $this->manager->flush();
            }
            $this->manager->remove($vg);
            $this->manager->flush();
        }
        $this->manager->remove($vehicule);
        $this->manager->flush();
        $this->addFlash('success','vehicule suprimé avec succes');
        return $this->redirectToRoute('create.vehicule');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    private function paginator(Request $request,$id)
    {
        $pagination = $this->paginator->paginate(
            $this->vehiculeRepository->findAllVehiculeQuery($id),
            $request->query->getInt('page',1),
            5
            );
        return $pagination;
    }

}
