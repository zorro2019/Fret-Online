<?php

namespace App\Controller;

use App\Entity\Alerte;
use App\Entity\ALertSearch;
use App\Entity\Entreprise;
use App\Entity\SmsSender;
use App\Form\AlertSearchType;
use App\Repository\AbonnesRepository;
use App\Repository\AlerteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\MessagesRepository;
use App\Repository\TypeVehiculeRepository;
use App\Repository\VoyageRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ALertController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var EntrepriseRepository
     */
    private $entrepriseRepository;

    /**
     * @var VoyageRepository
     */
    private $voyageRepository;

    private $typeVehiculeRepository;

    private $alerteRepository;

    private $abonnesRepository;

    private $messagesRepository;

    /**
     * ALertController constructor.
     * @param ObjectManager $manager
     * @param EntrepriseRepository $entrepriseRepository
     * @param VoyageRepository $voyageRepository
     * @param TypeVehiculeRepository $typeVehiculeRepository
     * @param AlerteRepository $alerteRepository
     * @param AbonnesRepository $abonnesRepository
     * @param MessagesRepository $messagesRepository
     */
    public function __construct(ObjectManager $manager, EntrepriseRepository $entrepriseRepository,
                                VoyageRepository $voyageRepository, TypeVehiculeRepository $typeVehiculeRepository
            , AlerteRepository $alerteRepository, AbonnesRepository $abonnesRepository, MessagesRepository $messagesRepository
    )
    {
        $this->manager = $manager;
        $this->entrepriseRepository = $entrepriseRepository;
        $this->voyageRepository = $voyageRepository;
        $this->typeVehiculeRepository = $typeVehiculeRepository;
        $this->alerteRepository = $alerteRepository;
        $this->abonnesRepository = $abonnesRepository;
        $this->messagesRepository = $messagesRepository;
    }

    /**
     * @Route("/alerte", name="alerte")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        if ($this->getUser() == null)
            return $this->redirectToRoute('login');
        $search = new ALertSearch();
        $tab = array();
        $form = $this->createForm(AlertSearchType::class,$search);
        $form->handleRequest($request);
        $sesson = new Session();
        if ($sesson == null)
            $sesson->start();
        if ($form->isSubmitted() && $form->isValid()){
            if (isset($_POST['type'])){
                $search->setTypeVehicule($this->typeVehiculeRepository->find($_POST['type']));
                $sesson->set('alerte',$search);
            }

            $listeEntre = $this->entrepriseRepository->findAll();
            foreach ($listeEntre as $ent){
                $cpt = 0;
                foreach ($ent->getAbonnes() as $ab){
                    $cpt += count($liste = $ab->getVehiculeVide($this->voyageRepository,$search->getTypeVehicule()));
                }
                if ($cpt > 0)
                {
                    $ent->setNbreVehiculeVide($cpt);
                    $tab["$cpt"] = $ent;
                }
            }
            krsort($tab);
        }
        return $this->render('alerte/index.html.twig', [
            'types' => $this->typeVehiculeRepository->findAll(),
            'form' => $form->createView(),
            'liste' => $tab,
            'search' => $search,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route(path="/send-alerte/{id}",name="send.alerte")
     * @param Entreprise $entreprise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAlert(Entreprise $entreprise)
    {
        $user = $this->getUser();
        if ($user == null)
            return $this->redirectToRoute('login');
        $search = new Session();
        $search->get('alerte');
        if ($search->get('alerte') == null){
            return $this->redirectToRoute('user_espace');
        }
        if (isset($_REQUEST['btn_alerte'])){
            $alerte = new Alerte();
            $alerte->setFromId($user->getId());
            if ($search->get('alerte')->getDebutAt() != null){
                $alerte->setDebutAt($search->get('alerte')->getDebutAt());
            }
            if ($search->get('alerte')->getFinishAt() != null){
                $alerte->setFinishAt($search->get('alerte')->getFinishAt());
            }
            $alerte->setNbreVehicule($search->get('alerte')->getNbreVehicule());
            $alerte->setToId($entreprise->getId());
            $alerte->setVilleDepart($search->get('alerte')->getVilleSepart());
            $alerte->setVilleArrive($search->get('alerte')->getVilleArrive());
            if (!empty($_POST['description'])){
                $alerte->setContenuAlerte($_POST['description']);
            }
            $alerte->setCreatAt(new \DateTime('now',timezone_open('gmt')));
            $alerte->setReadAt(0);
            $alerte->setTypeVehicule($search->get('alerte')->getTypeVehicule()->getId());
            $this->manager->persist($alerte);
            $this->manager->flush();
            $this->addFlash('success','votre alerte a été envoyé avec succes');
            SmsSender::Send('Cher utilisateur, vous avez reçu une alerte de la part de '.$user->getNom().', .... voici le lien https://fret-online.sn/',$entreprise->getTel());
            $search->remove('alerte');
            $search->set('alert',null);
            $search->migrate(true);
            $search->clear();
            return $this->redirectToRoute('user_espace');
        }
        return $this->render('alerte/sendAlert.html.twig',[
            'search' => $search,
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @Route(path="mesAlerte",name="mes_alertes")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mesAlertes()
    {
        $abonnes = $this->getUser();
        dump($this->messagesRepository->findLastMessage($abonnes));
        $lastAlert = $this->alerteRepository->findLastByEntreprise($abonnes->getIdEntreprise()->getId());
        return $this->render('alerte/alerte.html.twig',[
            'lastAlerte' => $lastAlert,
            'entreprises' => $this->entrepriseRepository->findAll(),
            'users' => $this->abonnesRepository->findAll(),
            'messages' => $this->messagesRepository->findLastMessage($abonnes)
        ]);
    }

    /**
     * @Route(path="/alerte/show/{id}",name="alerte.show")
     * @param Alerte $alerte
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reatAlerte(Alerte $alerte)
    {

        return $this->render('alerte/show.html.twig',[
            'users' => $this->abonnesRepository->findAll(),
            'entreprises' => $this->entrepriseRepository->findAll(),
            'alerte' => $alerte,
            'type' => $this->typeVehiculeRepository->findAll()
        ]);
    }
}
