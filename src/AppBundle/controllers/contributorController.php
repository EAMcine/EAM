<?php

namespace AppBundle\Controllers;

use AppBundle\Controllers\DefaultController as Controller;
use StandardBundle\Models\Contribution as Contribution;
use StandardBundle\Models\ImageFormat;

final class ContributorController extends Controller {
    use \AppBundle\Traits\ContributorTrait;

    public function indexAction() {
        $this->canContributor();
        $this->render('Contributor');
    }

    public function contributionsAction() {
        $this->canContributor('contributions');
        $this->render('Contributions');
    }

    public function contributionAction($request) {
        $this->canContributor('contributions');
        // $contribution = Contribution::selectOneByPk($request['id']);
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
    }

    public function contributionAddAction() {
        $this->canContributor('contributions.add');
        $this->set('imagetypes', ImageFormat::selectAll());
        $this->render('ContributionAdd');
    }

    public function contributionAddPostAction($request) {
        $this->canContributor('contributions.add');
        if (!(isset($_POST['title']) && isset($_POST['sinopsis']) && isset($_POST['review']) && isset($_POST['note']) && isset($_FILES['image']) && isset($_SESSION['user']))) {
            $_SESSION['alert'] = 'Tous les champs sont obligatoires';
            $this->redirect('/contributions/add');
        }
        $title = $_POST['title'];
        $sinopsis = $_POST['sinopsis'];
        $review = $_POST['review'];
        $note = $_POST['note'];
        $image = $_FILES['image'];
        $user = $_SESSION['user'];
        $imageFormat = $image['type'];
        $contribution = Contribution::create($user, $title, $sinopsis, $review, $note, $imageFormat);
        if (!$contribution) {
            $_SESSION['alert'] = 'Une erreur est survenue';
            $this->redirect('/contributions/add');
        }    
    }

    public function contributionEditAction($request) {
        $this->canContributor('contributions.edit');
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
    }

    public function contributionEditPostAction($request) {
        $this->canContributor('contributions.edit');
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
    }

    public function contributionDeleteAction($request) {
        $this->canContributor('contributions.delete');
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
    }



}
