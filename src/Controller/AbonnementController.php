<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 07/03/2019
 * Time: 14:29
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class AbonnementController extends AbstractController
{
    private $logged;
    public function __construct(GetAuthentificateUser $logged)
    {
        $this->logged = $logged;
    }
//    /**
//     * @Route(path="/abonnement",name="abonnement")
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function index()
//    {
//        if ($this->logged->getLoggedUser() == null)
//            return $this->redirectToRoute('login');
//        return $this->render('abonnement/index.html.twig');
//    }
}