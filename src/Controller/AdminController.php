<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\TypeVehicule;
use App\Repository\AbonnementRepository;
use App\Repository\AbonnesRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\TypeVehiculeRepository;
use App\Repository\VehiculeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var
     */
    private $typeVehiculeRepository;

    private $vehiculeRepository;

    private $abonnementRepository;

    private $abonnesRepository;

    private $entrepriseRepository;

    public function __construct(ObjectManager $manager, TypeVehiculeRepository $typeVehiculeRepository, VehiculeRepository $vehiculeRepository,
AbonnementRepository $abonnementRepository,AbonnesRepository $abonnesRepository, EntrepriseRepository $entrepriseRepository)
    {
        $this->manager = $manager;
        $this->typeVehiculeRepository = $typeVehiculeRepository;
        $this->vehiculeRepository = $vehiculeRepository;
        $this->abonnementRepository = $abonnementRepository;
        $this->abonnesRepository = $abonnesRepository;
        $this->entrepriseRepository = $entrepriseRepository;
    }

    /**
     * @Route(path="/admin", name="admin.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $user = $this->getUser();
        $typeVehicule = $this->typeVehiculeRepository->findAll();
        $active = null;
        $active['typeVehicule'] = null;
        $active['typeAbonnement'] = null;
        if($user->getAdmin() == null || $user->getAdmin() == false)
            return $this->redirectToRoute('home');
        if (isset($_REQUEST['btn-typeVehicule'])){
            $active['typeVehicule'] = "active";
            if ($_POST['vehicule'] != null){
                $type = new TypeVehicule();
                $type->setLibelle($_POST['vehicule']);
                $this->manager->persist($type);
                $this->manager->flush();
                $this->addFlash('success','type de vehicule ajouté avec succes');
                return $this->redirectToRoute('admin.index');
            }
        }
        else if (isset($_REQUEST['btn-typeAbonnement'])){
            $active['typeAbonnement'] = "active";
            if ($_POST['abonnement'] != null && $_POST['montant'] != null){
                $type = new Abonnement();
                $type->setTypeAbonnement($_POST['abonnement']);
                $type->setMontant($_POST['montant']);
                $this->manager->persist($type);
                $this->manager->flush();
                $this->addFlash('success','type de abonnent ajouté avec succes');
                return $this->redirectToRoute('admin.index');
            }
        }
        else
            $active['typeVehicule'] = "active";
        return $this->render('admin/index.html.twig',[
            'user' => $user,
            'typeVehicule' => $typeVehicule,
            'typeAbonnement' => $this->abonnementRepository->findAll(),
            'active' => $active,
            'nbreAbonne' => count($this->abonnesRepository->findAll()),
            'nbreEntreprise' => count($this->entrepriseRepository->findAll()),
            'nbreVehicule' => count($this->vehiculeRepository->findAll())
        ]);
    }

    /**
     * @Route(path="/admin/vehicule",name="admin.vehicule")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vehicule()
    {
        $user = $this->getUser();
        if($user->getAdmin() == false)
            return $this->redirectToRoute('home');
        return $this->render('admin/vehicule.html.twig');
    }

    /**
     * @Route(path="/admin/abonnement", name="admin.abonnement")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function abonnement()
    {
        $user = $this->getUser();
        if($user->getAdmin() == false)
            return $this->redirectToRoute('home');
        return $this->render('admin/abonnement.html.twig');
    }

    /**
     * @Route(path="/admin/abonnes", name="admin.abonnes")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function abonnes()
    {
        $user = $this->getUser();
        if($user->getAdmin() == false)
            return $this->redirectToRoute('home');
        return $this->render('admin/abonnes.html.twig');
    }

    /**
     * @Route(path="/admin/account", name="admin.compte")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function account()
    {
        return $this->render('admin/account.html.twig');
    }

    /**
     * @Route(path="admin/edit/{id}",name="admin.edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit()
    {
        $sub = array();
        $sub['vehicule'] = "";

        return $this->redirectToRoute('admin.index');
    }

    /**
     * @Route(path="admin/delete/{id}",name="admin.delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        if (isset($_REQUEST['deleteVehicule'])){
            if ($this->vehiculeRepository->findBy(
                ['typeVehicule' => $this->typeVehiculeRepository->find($id)]) == null){
                $this->manager->remove($this->typeVehiculeRepository->find($id));
                $this->manager->flush();
                $this->addFlash('success','type de vehicule supprimé');
            }
            else{
                $this->addFlash('warning','type de vehicule non supprimé');
            }
        }
        if (isset($_REQUEST['deleteAbonnement'])){
            if ($this->abonnementRepository->find($id)->getVehicule()->count() != 0){
                    $this->addFlash('warning','type abonnent non supprimé');
                    return $this->redirectToRoute('admin.index');
            }
            $this->addFlash('success','type abonnement supprimé');
            $this->manager->remove($this->abonnementRepository->find($id));
            $this->manager->flush();
        }
        return $this->redirectToRoute('admin.index');
    }
}
