<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 07/03/2019
 * Time: 22:33
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class GetAuthentificateUser extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function getLoggedUser()
    {
        if ($this->security->getUser() == null) {
            return null;
        }
        return $this->security->getUser();
    }
}