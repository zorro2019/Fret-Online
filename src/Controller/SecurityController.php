<?php
namespace App\Controller;
use App\Entity\Reinitialisation;
use App\Repository\AbonnesRepository;
use App\Repository\ReinitialisationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @var AbonnesRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ReinitialisationRepository
     */
    private $reinitialisationRepository;

    private $encoder;

    /**
     * SecurityController constructor.
     * @param AbonnesRepository $repository
     * @param ObjectManager $manager
     * @param ReinitialisationRepository $reinitialisationRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(AbonnesRepository $repository, ObjectManager $manager,ReinitialisationRepository $reinitialisationRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->reinitialisationRepository = $reinitialisationRepository;
        $this->encoder = $encoder;
    }
    /**
     * @Route(path="/login",name="login")
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $lastEmail = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();
        return $this->render("security/login.html.twig", [
            'email' => $lastEmail,
            'error' => $error
        ]);
    }

    /**
     * @Route(path="/logout",name="logout")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout()
    {
        return $this->render('pages/index.html.twig');
    }

    /**
     * @Route(path="/reset_password",name="changePassword")
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function resetPassword(\Swift_Mailer $mailer)
    {
        if ($this->getUser() != null)
            return $this->redirectToRoute('home');
        $error = "";
        $submit = "";
        if (isset($_REQUEST['reset'])){
            $last = $this->repository->findOneBy(['email' =>$_POST['username']]);
            if ($last == null)
                $error = "Cet émail n'est associé à aucun compte";
            else{
                $randomStr = '';
                $allowedCharacters='-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                for (
                    $i = 0, $allowedMaxIdx = mb_strlen($allowedCharacters) - 1;
                    $i < 60;
                    $i ++
                ) {
                    $randomStr .= $allowedCharacters[random_int(0, $allowedMaxIdx)];
                }
                $reset = new Reinitialisation();
                $reset->setNom($last->getNom());
                $reset->setEmail($last->getEmail());
                $reset->setToken($randomStr);
                $this->manager->persist($reset);
                $this->manager->flush();
                $submit = "émail envoyé avec succes";
                $message = (new \Swift_Message("création de compte"))
                    ->setFrom('yaranagoreoumar@gmail.com')
                    ->setTo($last->getEmail())
                    ->setBody($this->renderView(
                        'pages/reset.html.twig',
                        ['name' => $last->getNom(),
                            'token' => $reset->getToken() ]
                    ),
                        'text/html')
                ;
                $mailer->send($message);
            }
        }
        return $this->render('security/changePassword.html.twig',['error' => $error,'submit' => $submit]);
   }

    /**
     * @Route(path="/change_password/token={token}",name="new.password")
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validateTokenReset($token)
    {
        $error = "";
        $email = "";
        $last = $this->reinitialisationRepository->findOneBy(['token' => $token]);
        if ($last != null){
            $email = $user = $this->repository->findOneBy(['email' => $last->getEmail()])->getEmail();
        }
        if ($last == null)
            $error = "ce lien n'est pas valide";
        return $this->render('pages/newPassword.html.twig',[
            'error' => $error,
            'email' => $email
        ]);
   }

    /**
     * @Route(path="validePassword",name="valideNewPassword")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function valideNewPassword()
    {
        $error = "1";
        $email = "";
        if (isset($_REQUEST['reset'])){
            $email = $this->repository->findOneBy(['email' => $_POST['email']])->getEmail();
            $last = $this->repository->findOneBy(['email' => $_POST['email']]);
            if ($last != null){
                if ($_POST['pwd1'] == $_POST['pwd2']){
                    $last->setPassword($this->encoder->encodePassword($last, $_POST['pwd1']));
                    $this->manager->persist($last);
                    $this->manager->flush();
                    $error = "2";
                }
                else
                    $error = "3";
            }
            else
                $error = "4";
        }
        return $this->render('pages/newPassword.html.twig',[
            'error' => $error,
            'email' => $email
        ]);
   }
}
