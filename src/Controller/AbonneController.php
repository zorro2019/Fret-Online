<?php

namespace App\Controller;

use App\Entity\Abonnes;
use App\Entity\Entreprise;
use App\Entity\SmsSender;
use App\Form\FormAbonneType;
use App\Form\FormEntrepriseType;
use App\Repository\AbonnesRepository;
use App\Repository\AlerteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\MessagesRepository;
use App\Repository\ReadingMessagesRepository;
use App\Repository\ZoneInterventionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Osms\Osms;

class AbonneController extends AbstractController
{
    private $logged;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var AbonnesRepository $repository
     */
    private $repository;
    /**
     * @var EntrepriseRepository
     */
    private $entrepriseRepo;
    /**
     * @var
     */
    private $messageRepo;

    private $readRepository;

    private $alerteRepository;

    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $encoder,
                                AbonnesRepository $repository, EntrepriseRepository $entrepriseRepo,
                                MessagesRepository $messageRepo, GetAuthentificateUser $logged,
                                ReadingMessagesRepository $readRepository, AlerteRepository $alerteRepository
    )
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->repository = $repository;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->messageRepo = $messageRepo;
        $this->logged = $logged;
        $this->readRepository = $readRepository;
        $this->alerteRepository = $alerteRepository;
    }

    /**
     * @Route(path="/mon_espace",name="user_espace")
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(\Swift_Mailer $mailer)
    {
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $abonnes = $this->logged->getLoggedUser();
        $lastAlert = null;
        $total = 0;
        if ($abonnes->getIdEntreprise() != null){
            $total = count($lastAlert = $this->alerteRepository->findLastByEntreprise($abonnes->getIdEntreprise()->getId()));
        }
        if ($abonnes->getAdmin() == true){
            return $this->redirectToRoute('admin.index');
        }
        $ab = new Abonnes();
        $abonnes->setNbreMessageInread(count($this->readRepository->findMessagesInread($abonnes->getId())));
        $lastMessage = $this->readRepository->findMessagesInread($abonnes->getId());
       
        return $this->render('abonne/index.html.twig', [
            'user' => $abonnes,
            'messages' => $lastMessage,
            'abonnes' => $ab,
            'total' => $total
        ]);
    }

    /**
     * @Route(path="/creer_compte",name="create_compte")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, \Swift_Mailer $mailer)
    {
        $ab = new Abonnes();
        $form = $this->createForm(FormAbonneType::class, $ab);
        $form->handleRequest($request);
        $sub_error = array();
        $sub_error['tel'] = "";
        $sub_error['password'] = "";
        if ($form->isSubmitted()) {
            if ($ab->getPassword() != $ab->getPwd()) {
                $sub_error['password'] = "erreur mot de passe";
            }
            if ($this->validateTel($ab->getTelephone()) == false) {
                $sub_error['tel'] = "Numero incorrect";
            }
            if ($sub_error['password'] == "" && $sub_error['tel'] == "") {
                if ($form->isValid()) {
                    $message = (new \Swift_Message("création de compte"))
                        ->setFrom('yaranagoreoumar@gmail.com')
                        ->setTo($ab->getEmail())
                        ->setBody($this->renderView(
                            'pages/register.html.twig',
                            ['name' => $ab->getNom(),
                                'login' => $ab->getEmail()]
                        ),
                            'text/html');
                    $mailer->send($message);
                    //SmsSender::Send("Bonjour " . $ab->getNom() . " Bienvenu(e) sur frêt Online , nous sommes heureux de vous accueillir parmi nos membres voici vos identifiants Login : " . $ab->getEmail() . " Password : " . $ab->getPassword() . " ", $ab->getTelephone());
                    $ab->setPassword($this->encoder->encodePassword($ab, $ab->getPassword()));
                    $ab->setCreatedAt(new \DateTime('now'));
                    $ab->setAdmin(false);
                    $this->manager->persist($ab);
                    $this->manager->flush();
                    $this->addFlash('success', 'votre compte a été créé avec succes');
                    $token = new UsernamePasswordToken($ab, null, 'main', ['ROLE_USER']);
                    $this->get('security.token_storage')->setToken($token);
                    if ($ab->getTypeAbonne() == 2)
                        return $this->redirectToRoute("abonne.create.entreprise");
                    else
                        return $this->redirectToRoute('user_espace');
                }
            }
        }
        return $this->render("abonne/create.html.twig", [
            'form' => $form->createView(),
            'sub_error' => $sub_error
        ]);
    }

    /**
     * @Route(path="/edit",name="abonnes.edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request)
    {
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $abonnes = $this->logged->getLoggedUser();
        $form = $this->createForm(FormAbonneType::class, $abonnes);
        $form->handleRequest($request);
        $sub_error = array();
        $sub_error['image'] = "";
        $sub_error['tel'] = "";
        $sub_error['email'] = "";
        $sub_error['password'] = "";
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($sub_error['tel']) && empty($sub_error['image'])) {
                $abonnes->setPassword($this->encoder->encodePassword($abonnes, $abonnes->getPassword()));
                $this->manager->persist($abonnes);
                $this->manager->flush();
                $this->addFlash('succes', 'Modification effectuée aves succès');
                return $this->redirectToRoute('user_espace');
            }
        }
        return $this->render("abonne/edit.html.twig", [
            'form' => $form->createView(),
            'sub_error' => $sub_error
        ]);
    }

    /**
     * @Route(path="/create_entreprise",name="abonne.create.entreprise")
     * @param ZoneInterventionRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create_entreprise(ZoneInterventionRepository $repository, Request $request)
    {
        if ($this->logged->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $ent = new Entreprise();
        $form = $this->createForm(FormEntrepriseType::class, $ent);
        $form->handleRequest($request);
        $errors = array();
        $errors['ninea'] = "";
        $errors['logo'] = "";
        $errors['tel'] = "";
        $errors['email'] = "";
        if ($form->isSubmitted()) {
            $last = $this->entrepriseRepo->findOneBy([
                'ninea' => $ent->getNinea()
            ]);
            if ($this->validateTel($ent->getTel()) == false) {
                $errors['tel'] = "Ce format est incorrect";
            }
            if ($last != null && 10 >= $last->getNbreAbonne()) {
                $errors['ninea'] = "Cette entreprise a plus de 10 utilisateurs";
            }

            if (empty($errors['tel'])) {
                if ($form->isValid()) {
                    /** @var TYPE_NAME $ninea */
                    if ($last == null) {
                        $this->manager->persist($ent);
                        $ent->setCreateAt(new \DateTime('now'));
                        $user = $this->getUser();
                        $user->setIdEntreprise($ent);
                        $this->manager->persist($user);
                        foreach ($_POST['zone'] as $value) {
                            $ent->addListeZone($repository->find(($value)));
                        }
                        $ent->setNbreAbonne(1);
                        $this->manager->persist($ent);
                        $this->manager->flush();
                        return $this->redirectToRoute("user_espace");
                    }
                    if ($last != null && $last->getNbreAbonne() < 10) {
                        $user = $this->getUser();
                        $user->setIdEntreprise($last);
                        $last->setNbreAbonne($last->getNbreAbonne() + 1);
                        $this->manager->persist($last);
                        $this->manager->persist($user);
                        $this->manager->flush();
                        return $this->redirectToRoute("user_espace");
                    }
                }
            }
        }
        return $this->render('abonne/create_entreprise.html.twig', [
            'form' => $form->createView(),
            'zones' => $repository->findAll(),
            'sub_error' => $errors
        ]);
    }

    /**
     * @param $tel
     * @return bool
     */
    private function validateTel($tel)
    {
        if ($tel[0] == '7') {
            if ($tel[1] == '7' || $tel[1] == '8' || $tel[1] == '0' || $tel[1] == '6') {
                return true;
            }
        } else
            return false;
        return false;
    }

    /**
     * @Route(path="/utilisateur/show/{id}",name="abonne.show")
     * @param Abonnes $abonnes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Abonnes $abonnes)
    {
        if ($this->logged->getLoggedUser() == null) {
            return $this->redirectToRoute('login');
        }
        $user = $this->getUser();
        if ($abonnes->getId() == $user->getid()) {
            return $this->redirectToRoute('user_espace');
        }
        return $this->render('abonne/show.html.twig', [
            'user' => $abonnes,
        ]);
    }

    public function sendSMS()
    {
        $config = array(
            'clientId' => 'ZUixAqbImGe0VJSPC0mvY2nkhlwgZABf',
            'clientSecret' => 'lJZKZAAXAmGvhqp4'
        );

        $osms = new Osms($config);

// retrieve an access token
        $response = $osms->getTokenFromConsumerKey();
        //$conf = array(
        //'token' => $response['access_token']
        //);

        if (!empty($response['access_token'])) {
            //$sms = new Osms($conf);
            $senderAddress = 'tel:+2210000';
            $receiverAddress = 'tel:+221778213119';
            $message = 'Frêt Online te salue';
            //$senderName = 'Optimus Prime';
            $osms->sendSMS($senderAddress, $receiverAddress, $message);
        } else {
            dump($response);
        }
    }
}