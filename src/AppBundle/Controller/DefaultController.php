<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\Housing;
use AppBundle\Entity\Receipt;
use AppBundle\Form\ReceiptsGeneratorType;
use Http\Discovery\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $jsonData = [];
        $companies = $this->getDoctrine()->getRepository('AppBundle:Company')->findBy([], ['name' => 'ASC']);
        foreach ($companies as $company) {
            $companyData = [
                'text' => $company->getName(),
                'icon' => 'fa fa-vcard',
                'state' => ['expanded' => true],
                'selectable' => false
            ];
            if (count($company->getBuildings()) > 0) {
                $companyData['nodes'] = [];

                /** @var Building $building */
                foreach ($company->getBuildings() as $building) {
                    $buildingData = [
                        'text' => $building->getAddress()->getAddress1(),
                        'icon' => 'fa fa-building',
                        'state' => ['expanded' => true],
                        'selectable' => false
                    ];
                    if (count($building->getHousings()) > 0) {
                        $buildingData['nodes'] = [];

                        /** @var Housing $housing */
                        foreach ($building->getHousings() as $housing) {
                            array_push($buildingData['nodes'], [
                                'text' => $housing->getName(),
                                'icon' => 'fa fa-home',
                                'id' => $housing->getId()
                            ]);
                        }
                    }

                    array_push($companyData['nodes'], $buildingData);
                }
            }

            array_push($jsonData, $companyData);
        }

        $form = $this->createReceiptsForm();

        return $this->render('home/home.html.twig', ['json' => json_encode($jsonData), 'form' => $form->createView()]);
    }

    /**
     * @Route("ajax/generate-receipts", name="dashboard_ajax_gen", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateReceiptsAction(Request $request) {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }

        $form = $this->createReceiptsForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $receipts = [];

            $year = $form->getData()["year"];
            $month = $form->getData()["month"];

            $housingsToReceipt = $this->getDoctrine()->getRepository('AppBundle:Housing')->findAllWaitingForReceipt();
            foreach ($housingsToReceipt as $housing) {
                $date = new \DateTime();
                $date->setDate($year, $month, date('d'));

                $tenant = null;
                $building = null;
                $company = null;

                $housing = $this->revert($housing, $year, $month);
                if ($housing !== null) {
                    $tenant = $this->revert($housing->getTenant(), $year, $month);

                    $building = $this->revert($housing->getBuilding(), $year, $month);
                    if ($building != null) {
                        $company = $this->revert($housing->getBuilding()->getCompany(), $year, $month);
                    }
                }

                if ($housing !== null && $tenant !== null && $building !== null && $company !== null) {
                    $receipt = $this->getDoctrine()->getRepository('AppBundle:Receipt')->findReceiptForDate(
                        $housing->getId(),
                        $month,
                        $year
                    );
                    if ($receipt === null) {
                        $receipt = new Receipt();
                    }

                    $receipt->setDate($date)
                        ->setHousing($housing)
                        ->setTenant($housing->getTenant());

                    $this->getDoctrine()->getManager()->persist($receipt);
                    $this->getDoctrine()->getManager()->flush();

                    $receipts[] = $this->generateUrl(
                        'receipt_print',
                        ['receipt' => $receipt->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                }
            }

            return new JsonResponse($receipts);
        }

        return new JsonResponse(["message" => "Erreur"], Response::HTTP_NOT_FOUND);
    }

    private function createReceiptsForm() {
        return $this->createForm(
            ReceiptsGeneratorType::class,
            ['month' => idate('m'), 'year' => idate('Y')],
            ['attr' => ['class' => 'form-inline text-center']]
        );
    }

    /**
     * @Route("ajax/{id}", name="dashboard_ajax", requirements={"id" = "\d+"}, methods={"POST"})
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Request $request, $id) {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }

        return $this->render(
            'home/housing_ajax.html.twig',
            ['receipts' => $this->getDoctrine()->getRepository('AppBundle:Housing')->find($id)->getReceipts()]
        );
    }

    /**
     * @Route("receipt/{receipt}", name="receipt_print")
     *
     * @param Receipt $receipt
     * @return Response
     */
    public function receiptAction(Receipt $receipt) {
        $year = $receipt->getDate()->format('Y');
        $month = $receipt->getDate()->format('m');

        $receipt->setTenant($this->revert($receipt->getTenant(), $year, $month));
        $receipt->setHousing($this->revert($receipt->getHousing(), $year, $month));
        $receipt->getHousing()->setBuilding($this->revert($receipt->getHousing()->getBuilding(), $year, $month));
        $receipt->getHousing()->getBuilding()->setCompany($this->revert(
            $receipt->getHousing()->getBuilding()->getCompany(),
            $year,
            $month
        ));

        return $this->render('receipt/A4-receipt.html.twig',
            ["receipt" => $receipt, "date" => \DateTime::createFromFormat('m/Y', $month . '/' . $year)]
        );
    }

    private function revert($entity, $year, $month) {
        $maxDate = \DateTime::createFromFormat('d-m-Y', '1-' . ($month + 1)  . '-' . $year);
        $maxDate->setTime(0, 0, 0);

        $logRepo = $this->getDoctrine()->getRepository('GedmoLoggable:LogEntry');

        $version = null;
        foreach ($logRepo->getLogEntries($entity) as $revision) {
            if ($revision->getLoggedAt() < $maxDate) {
                $version = $revision->getVersion();
                break;
            }
        }

        if ($version != null) {
            $logRepo->revert($entity, $version);
        } else {
            $entity = null;
        }

        return $entity;
    }
}
