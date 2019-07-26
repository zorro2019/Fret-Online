<?php
namespace App\Controller;

use App\Entity\Abonnes;
use App\Entity\Commentaires;
use App\Entity\Messages;
use App\Entity\ReadingMessages;
use App\Entity\Rechercher;
use App\Entity\Vehicule;
use App\Form\RechercherType;
use App\Repository\AbonnesRepository;
use App\Repository\DetailsVoyageRepository;
use App\Repository\MessagesRepository;
use App\Repository\ReadingMessagesRepository;
use App\Repository\TypeVehiculeRepository;
use App\Repository\VoyageRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var MessagesRepository
     */
    private $messagesRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator ;
    /**
     * @var ReadingMessagesRepository
     */
    private $unreadRep;

    /**
     * @var
     */
    private $manager;

    /**
     * @var
     */
    private $abonnesRepository;

    /**
     * @var DetailsVoyageRepository
     */

    /**
     * @var DetailsVoyageRepository
     */
    private $detailRep;

    private $typeVehiculeRepository;

    /**
     * HomeController constructor.
     * @param Environment $twig
     * @param MessagesRepository $messagesRepository
     * @param PaginatorInterface $paginator
     * @param ReadingMessagesRepository $unreadRep
     * @param ObjectManager $manager
     * @param AbonnesRepository $abonnesRepository
     * @param DetailsVoyageRepository $detailRep
     */
    public function __construct(Environment $twig,MessagesRepository $messagesRepository, PaginatorInterface $paginator,
                                ReadingMessagesRepository $unreadRep, ObjectManager $manager,AbonnesRepository $abonnesRepository,
                DetailsVoyageRepository $detailRep, TypeVehiculeRepository $typeVehiculeRepository)
    {
        $this->twig = $twig;
        $this->messagesRepository = $messagesRepository;
        $this->paginator = $paginator;
        $this->unreadRep = $unreadRep;
        $this->manager = $manager;
        $this->abonnesRepository = $abonnesRepository;
        $this->detailRep = $detailRep;
        $this->typeVehiculeRepository = $typeVehiculeRepository;
    }

    /**
     * @Route(path="/",name="home")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index():Response
    {
        return new Response($this->twig->render('pages/index.html.twig'));
    }

    /**
     * @Route(path="/comment_ça_marche",name="faq")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function comment():Response
    {
        return new Response($this->twig->render('pages/faq.html.twig'));
    }

    /**
     * @Route(path="/fret-en-direct", name="fret.direct")
     * @param Request $request
     * @param VoyageRepository $repository
     * @return Response
     */
    public function fret(Request $request, VoyageRepository $repository)
    {
        $search = new  Rechercher();
        $listeTypeVehicule = $this->typeVehiculeRepository->findAll();
        $voyage = $repository->findListVoyageActif();
        $form = $this->createForm(RechercherType::class , $search);
        $recherche = array();
        $recherche['localite'] = "";
        $recherche['quant'] = 0;
        $recherche['type'] = "";
        $recherche['submit'] = 0;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recherche['localite'] = $search->getLocalite();
            $recherche['quant'] = $search->getTonnage();
            if (isset($_POST['type'])){
                $recherche['type'] = $_POST['type'];
                $search->setTypeVehicule($this->typeVehiculeRepository->find($_POST['type']));
                dump($search->getTypeVehicule());
            }
            if (empty($search->getLocalite()) && empty($search->getTypeVehicule()) && !empty($search->getTonnage())){
                $recherche['submit'] = 2;
            }
            if (empty($search->getLocalite()) && !empty($search->getTypeVehicule()) && empty($search->getTonnage())){
                $recherche['submit'] = 3;
            }
            if (empty($search->getLocalite()) && !empty($search->getTypeVehicule()) && !empty($search->getTonnage())){
                $recherche['submit'] = 4;
            }
            if (!empty($search->getLocalite()) && empty($search->getTypeVehicule()) && empty($search->getTonnage())){
                $recherche['submit'] = 5;
            }
            if (!empty($search->getLocalite()) && empty($search->getTypeVehicule()) && !empty($search->getTonnage())){
                $recherche['submit'] = 6;
            }
            if (!empty($search->getLocalite()) && !empty($search->getTypeVehicule()) && empty($search->getTonnage())){
                $recherche['submit'] = 7;
            }
            if (!empty($search->getLocalite()) && !empty($search->getTypeVehicule()) && !empty($search->getTonnage())){
                $recherche['submit'] = 8;
            }

        }
        return $this->render('pages/fret-en-direct.html.twig',[
            'form' => $form->createView(),
            'voyages' => $voyage,
            'search' => $recherche,
            'typeVheicule' => $listeTypeVehicule,
            'ValSearch' => $search
        ]);
    }

    /**
     * @Route(path="/alert-fret",name="alert.fret")
     * @param Request $request
     * @return Response
     */
    public function Alert(Request $request)
    {
        $user = $this->getUser();
        $listPagine = $this->paginatorMessage($request);
        if (isset($_REQUEST['valider'])){
           $message = new Messages();
           $message->setTitle($_POST['titre']);
           $message->setIdAbonne($user);
           $message->setCreateAt(new \DateTime('now'));
           $message->setContenu($_POST['message']);
           $this->manager->persist($message);
           $this->manager->flush();
           $users = $this->abonnesRepository->findAll();
           foreach ($users as $u){
               if ($u->getId() != $user->getid()){
                   $Inread = new ReadingMessages();
                   $Inread->setIdAbonne($u->getId());
                   $Inread->setIdMessage($message);
                   $Inread->setReaded(0);
                   $this->manager->persist($Inread);
                   $this->manager->flush();
               }
           }
        }
        return $this->render('pages/alert-fret.html.twig',[
            'listePagine' => $listPagine,
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    private function paginatorMessage(Request $request)
    {
        $pagination = $this->paginator->paginate(
            $this->messagesRepository->findLastMessageQuery(),
            $request->query->getInt('page',1),
            3
        );
        return $pagination;
    }

    /**
     * @Route(path="/espace/read/{id}",name="read.alert")
     * @param $id
     * @param Messages $messages
     * @return Response
     */
    public function read($id, Messages $messages)
    {
        $abonne = $this->getUser();
        if ($abonne != null){
            $messageUnread = $this->unreadRep->findMessagesInread($abonne->getid());
            foreach ($messageUnread as $item){
                if ($item->getIdMessage()->getId() == $id){
                    $item->setReaded(true);
                    $abonne->setNbreMessageInread($abonne->getNbreMessageInread() - 1);
                    $this->manager->persist($item);
                    $this->manager->flush();
                }
            }
        }
        if (isset($_REQUEST['btn_valider'])){
            if (!empty($_POST['comment'])){
                if ($this->getUser() == null){
                    return $this->redirectToRoute('create_compte');
                }
                else{
                    $comment = new Commentaires();
                    $comment->setCreatAt(new \DateTime('now'));
                    $comment->setIdMessages($messages);
                    $comment->setIdAbonne($this->getUser()->getId());
                    $comment->setContents($_POST['comment']);
                    $this->manager->persist($comment);
                    $this->manager->flush();
                }
            }
        }
        return $this->render('pages/read.html.twig',[
            'message' => $messages,
            'user' => $abonne
        ]);
    }


}