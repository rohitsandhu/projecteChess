<?php

namespace App\Controller;

use App\Repository\ArbitreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function main(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }


    /**
     * @Route("/comprovarLogin", name="comprovarLogin")
     */
    public function ferLogin(Request $request, ArbitreRepository $arbitreRepository, SessionInterface $session): Response
    {
        $error = [];
        $usuari = $request->get('usuari');
        $contrasenya = $request->get('contra');

        if ($usuari != "" && $usuari != null) {
        } else {
            array_push($error, "El format del usuari és incorrecte.");
        }

        if ($contrasenya != "" && $contrasenya != null) {
        } else {
            array_push($error, "El format de la contrasenya és incorrecte.");
        }

        if (count($error) > 0) {
            return $this->render("login.html.twig", [
                "arrayErrors" => $error,
            ]);
        } else {
            $arbitre = $arbitreRepository->findOneBy(['usuari' => $usuari]);

            if (!is_null($arbitre)) {

                if (strtoupper($arbitre->getUsuari()) == strtoupper($usuari) && $arbitre->getContrasenya() == $contrasenya) {

                    $session->set("arbitreLogged", $arbitre);

                    return $this->render("index.html.twig", [
                        "xd" => "xddddd",
                    ]);
                }

            }
            array_push($error, "L'usuari o la contrasenya son incorrectes");
            return $this->render("login.html.twig", [
                "arrayErrors" => $error,
            ]);
        }
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(SessionInterface $session): Response
    {
        $session->remove("arbitreLogged");
        return $this->render('index.html.twig');
    }

}
