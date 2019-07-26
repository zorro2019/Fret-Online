<?php

namespace App\Controller;


use App\Entity\Abonnement;
use App\Entity\Paypal;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PayementController extends AbstractController
{

    public function __construct()
    {

    }

    /**
     * @Route(path="/abonnement",name="abonnement")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $vide = null;
        if (isset($_REQUEST['valider'])){
            $abonnemet = array();
            $list = new ItemList();
            $total = 0;
            if (!empty($_POST['mois']) && $_POST['mois'] > 0 && is_numeric($_POST['mois'])){
                $abonnemet['mois'] = array(
                    'prix' => 5000,
                    'nom' => 'Abonnement mensuel',
                    'quantite' => $_POST['mois']
                );
            }
            else{
                $vide['mois'] = "choix mois vide";
            }
            if (!empty($_POST['semestre']) && $_POST['semestre'] > 0 && is_numeric($_POST['semestre'])){
                $abonnemet['semestre'] = array(
                    'prix' => 20000,
                    'nom' => 'Abonnement semestruel',
                    'quantite' => $_POST['semestre']
                );
            }
            else{
                $vide['semestre'] = "choix mois vide";
            }
            if (!empty($_POST['an']) && $_POST['an'] > 0 && is_numeric($_POST['an'])){
                $abonnemet['an'] = array(
                    'prix' => 50000,
                    'nom' => 'Abonnement annuel',
                    'quantite' => $_POST['an']
                );
            }
            else{
                $vide['an'] = "choix an vide";
            }
            if (!empty($abonnemet )){
                foreach ($abonnemet as $tab){
                    $total = $total+($tab['prix']*$tab['quantite']);
                    $item = new Item();
                    $item->setPrice($tab['prix']);
                    $item->setName($tab['nom']);
                    $item->setCurrency('EUR');
                    $item->setQuantity($tab['quantite']);
                    $list->addItem($item);
                }
                $detail = new Details();
                $detail->setSubtotal($total);
                $amount = new Amount();
                $amount->setTotal($total);
                $amount->setCurrency('EUR');
                $amount->setDetails($detail);
                $transaction = (new Transaction())
                    ->setItemList($list)
                    ->setDescription('Payement abonnement')
                    ->setAmount($amount)
                    ->setCustom('demo');
                $payment = new Payment();
                $apiContext = new ApiContext(
                    new OAuthTokenCredential(Paypal::getPaypal()['id'], Paypal::getPaypal()['secret'])
                );
                $payment->setIntent('sale');
                $redirectUrls = (new RedirectUrls())
                    ->setReturnUrl('http://localhost:8000/validation')
                    ->setCancelUrl('http://localhost:8000');
                $payment->setRedirectUrls($redirectUrls);
                $payment->setPayer((new Payer())->setPaymentMethod('paypal'));
                $payment->setTransactions([$transaction]);
                $payment->create($apiContext);
                //echo json_encode([])
                return $this->redirect($payment->getApprovalLink());
            }
        }
        return $this->render('abonnement/index.html.twig',[
            'errors' => $vide
        ]);
    }

    /**
     * @Route(path="/validation",name="payement.validator")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validation()
    {
        if (!empty($_GET['paymentId']) && !empty($_GET['PayerID']) && !empty($_GET['token'])){
            $apiContext = new ApiContext(
                new OAuthTokenCredential(Paypal::getPaypal()['id'], Paypal::getPaypal()['secret'])
            );
            $payement = Payment::get($_GET['paymentId'],$apiContext);
            $execution = (new PaymentExecution())
                ->setPayerId($_GET['PayerID'])
                ->setTransactions($payement->getTransactions());
            $payement->execute($execution,$apiContext);
            $this->addFlash('success','Votre abonnement a été activé avec succes');
        }
        else{
            return $this->redirectToRoute('abonnement');
        }
        return $this->render('abonnement/validation.html.twig');
    }


    /**
     * @param Abonnement $abonnement
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function Payer(Abonnement $abonnement)
    {
        $list = new ItemList();
            $item = new Item();
            $item->setPrice($abonnement->getMontant());
            $item->setName($abonnement->getTypeAbonnement());
            $item->setCurrency('EUR');
            $item->setQuantity(1);
            $list->addItem($item);
        $detail = new Details();
        $detail->setSubtotal($abonnement->getMontant());
        $amount = new Amount();
        $amount->setTotal($abonnement->getMontant());
        $amount->setCurrency('EUR');
        $amount->setDetails($detail);
        $transaction = (new Transaction())
            ->setItemList($list)
            ->setDescription('Payement abonnement')
            ->setAmount($amount)
            ->setCustom('demo');
        $payment = new Payment();
        $apiContext = new ApiContext(
            new OAuthTokenCredential(Paypal::getPaypal()['id'], Paypal::getPaypal()['secret'])
        );
        $payment->setIntent('sale');
        $redirectUrls = (new RedirectUrls())
            ->setReturnUrl('http://localhost:8000/validation_ajoutVehicule')
            ->setCancelUrl('http://localhost:8000/cancel_ajoutVehicule');
        $payment->setRedirectUrls($redirectUrls);
        $payment->setPayer((new Payer())->setPaymentMethod('paypal'));
        $payment->setTransactions([$transaction]);
        $payment->create($apiContext);
        //echo json_encode([])
        return $this->redirect($payment->getApprovalLink());
    }
}