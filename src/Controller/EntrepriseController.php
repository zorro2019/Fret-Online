<?php
namespace App\Controller;
use App\Entity\Entreprise;
use App\Form\FormEntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Repository\ZoneInterventionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController {

    /**
     * @var ZoneInterventionRepository
     */
    private $repository;

    /**
     * @var GetAuthentificateUser
     */
    private $authentificateUser;

    /**
     * @var EntrepriseRepository
     */
    private $entrepriseRepository;

    private $manager;
    public function __construct(ZoneInterventionRepository $repository,GetAuthentificateUser $authentificateUser,
                                EntrepriseRepository $entrepriseRepository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->authentificateUser = $authentificateUser;
        $this->entrepriseRepository = $entrepriseRepository;
        $this->manager = $manager;
    }

    /**
     * @Route(path="/entreprise/{id}",name="entreprise.show")
     * @param Entreprise $entreprise
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Entreprise $entreprise, Request $request)
    {
        if ($this->authentificateUser->getLoggedUser() == null)
            return $this->redirectToRoute('login');
        $user = $this->getUser();
        $total = 0;
        $zones = $this->repository->findAll();
        $form = $this->createForm(FormEntrepriseType::class,$entreprise);
        $form->handleRequest($request);
        foreach ($entreprise->getAbonnes() as $ab){
            $total = $total + count($ab->getListeVehicule());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($entreprise);
            foreach ($_POST['zone'] as $value)
            {
                $entreprise->addListeZone($this->repository->find(($value)));
            }
            $this->manager->persist($entreprise);
            $this->manager->flush();
        }
        return $this->render('entreprise/show.html.twig',[
            'entreprise' => $entreprise,
            'user' => $user,
            'total' => $total,
            'zones' => $zones,
            'form' => $form->createView()
        ]);
    }
}