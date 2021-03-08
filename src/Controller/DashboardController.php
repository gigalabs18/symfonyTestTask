<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request): Response
    {
    	$data = null;
        $form = $this->createFormBuilder()
            ->add('date', TextType::class)
            ->add('timezone', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $form->getData();
            $data['nameOfMonth'] = date('F', strtotime($formData['date']));
            $data['numOfDaysInMonth'] = date('t', strtotime($formData['date']));
            $data['febDays']=cal_days_in_month(CAL_GREGORIAN,2, date("Y", strtotime($formData['date'])));
			$data['timezoneOffset'] = $this->calTimezoneOffset($formData['timezone']);
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'pageTitle' => 'Test Task',
            'my_form'=>$form->createView(),
            'data'=>$data
        ]);
    }

    public function calTimezoneOffset($timezone) {
    	$dtz = new \DateTimeZone($timezone);
		$convert = new \DateTime('now', $dtz);
		return $dtz->getOffset( $convert ) / 3600;
    }
}
