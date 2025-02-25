<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build a menu for navigation.
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $browse = $menu->addChild('Browse', [
            'uri' => '#',
            'label' => 'Browse',
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('Archdeaconries & Courts', ['route' => 'archdeaconry_index']);
        $browse->addChild('Archives', ['route' => 'archive_index']);
        $browse->addChild('Books', ['route' => 'book_index']);
        $browse->addChild('Counties', ['route' => 'county_index']);
        $browse->addChild('Dioceses', ['route' => 'diocese_index']);
        $browse->addChild('Injunctions', ['route' => 'injunction_index']);
        $browse->addChild('Inventories', ['route' => 'inventory_index']);
        $browse->addChild('Monarchs', ['route' => 'monarch_index']);
        $browse->addChild('Nations', ['route' => 'nation_index']);
        $browse->addChild('Parishes', ['route' => 'parish_index']);
        $browse->addChild('Provinces', ['route' => 'province_index']);
        $browse->addChild('Sources', ['route' => 'source_index']);
        $browse->addChild('Towns', ['route' => 'town_index']);
        $browse->addChild('Transactions', ['route' => 'transaction_index']);
        $browse->addChild('Surviving Texts', ['route' => 'holding_index']);

        if ($this->hasRole('ROLE_CONTENT_ADMIN')) {
            $divider = $browse->addChild('divider_content', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Formats', ['route' => 'format_index']);
            $browse->addChild('Source Categories', ['route' => 'source_category_index']);
            $browse->addChild('Transaction Categories', ['route' => 'transaction_category_index']);
        }

        if ($this->hasRole('ROLE_ADMIN')) {
            $divider = $browse->addChild('divider_admin', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
        }

        return $menu;
    }

    /**
     * Build a menu for navigation.
     *
     * @return ItemInterface
     */
    public function footerMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $menu->addChild('Home', [
            'route' => 'homepage',
        ]);
        $menu->addChild('Privacy', [
            'route' => 'privacy',
        ]);
        $menu->addChild('GitHub', [
            'uri' => 'https://github.com/sfu-dhil/bep',
        ]);

        return $menu;
    }
}
