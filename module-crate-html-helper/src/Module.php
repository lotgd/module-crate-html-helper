<?php
declare(strict_types=1);

namespace LotGD\Module\CrateHtmlHelper;

use LotGD\Core\Game;
use LotGD\Core\Events\EventContext;
use LotGD\Core\Module as ModuleInterface;
use LotGD\Core\Models\Module as ModuleModel;
use LotGD\Crate\WWW\Model\AdminToolboxPage;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Module\CrateHtmlHelper\AdministrationToolboxes\CharacterToolbox;
use LotGD\Module\CrateHtmlHelper\AdministrationToolboxes\UserToolbox;

class Module implements ModuleInterface {
    public static function handleEvent(Game $g, EventContext $context): EventContext
    {
        return $context;
    }

    public static function onRegister(Game $g, ModuleModel $module)
    {
        $em = $g->getEntityManager();

        $roles = [
            "superuser" => new Role("ROLE_SUPERUSER", "lotgd/module-crate-html-helper"),
            "characterEditor" => new Role("ROLE_CHARACTER_EDIT", "lotgd/module-crate-html-helper"),
        ];

        $pages = [
            "users" => new AdminToolboxPage("users", UserToolbox::class),
            "characters" => new AdminToolboxPage("characters", CharacterToolbox::class),
        ];

        $pages["users"]->addRequiredRole($roles["superuser"]);
        $pages["users"]->setName("User");
        $pages["characters"]->addRequiredRole($roles["superuser"]);
        $pages["characters"]->addRequiredRole($roles["characterEditor"]);
        $pages["characters"]->setName("Character");

        foreach ($roles as $role) {
            $em->persist($role);
        }

        foreach ($pages as $page) {
            $em->persist($page);
        }
    }

    public static function onUnregister(Game $g, ModuleModel $module) { }
}
