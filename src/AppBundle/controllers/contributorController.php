<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;

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
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
    }

    public function contributionAddAction() {
        $this->canContributor('contributions.add');
        $this->render('ContributionAdd');
    }

    public function contributionAddPostAction($request) {
        $this->canContributor('contributions.add');
        $contribution = $request['id'];
        $this->render('Contribution', [
            'contribution' => $contribution
        ]);
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
